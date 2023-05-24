<?php
$directory = '../../files'; // Replace with the path to the directory you want to list
$files = [];

if (is_dir($directory)) {
    $files = scandir($directory);

    // Remove the "." and ".." entries from the list
    $files = array_diff($files, array('.', '..'));
    $files = array_diff($files, ['images']);
    if (count($files) > 0) {
        echo '
        <table class="table table-hover table-auto" id="files-table">
            <tr>
                <th>Nazov suboru</th>
            </tr>';

        foreach ($files as $file) {
//                echo $directory . "/" . $file;
            echo "<tr><td>$file</td></tr>";
        }

        echo '
        </table>';

    } else {
        echo "Directory is empty. No files found";
    }
} else {
    echo "Invalid directory.";
}

if (count($files) > 0) {
    date_default_timezone_set('Europe/Bratislava');
    require_once "../../config.php";
    global $dbconfig;
    $db = new mysqli();
    $db->connect($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password']);
    $db->select_db($dbconfig['database']);

    // TODO: REMOVE ECHOS
    // Add refreshed files
    foreach ($files as $file) {
        // Escape the location value to prevent SQL injection
        $file = $db->real_escape_string($file);

        // Check if the file already exists in the database
        $query = "SELECT COUNT(*) AS count FROM files WHERE filename = '$file'";
        $result = $db->query($query);
        $row = $result->fetch_assoc();
        $fileExists = $row['count'];

        if (!$fileExists) {
            // File doesn't exist, insert it into the database
            $insertQuery = "INSERT INTO files (filename) VALUES ('$file')";
            echo $insertQuery;
            $insertResult = $db->query($insertQuery);
            echo $insertResult;
            $file_id = $db->insert_id;
            echo $file_id;

            // Parse files and insert tasks into TASKS table
            $latexFile = "../../files/".$file;
            echo $latexFile;
            // Read the contents of the LaTeX file
            $latexContent = file_get_contents($latexFile);

            // Parse the content into objects
            $objects = array();
            preg_match_all('/\\\\section\*{(.+?)}\s*\\\\begin{task}(.*?)\\\\end{task}\s*\\\\begin{solution}(.*?)\\\\end{solution}/s', $latexContent, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $section = $match[1];
                $task = trim($match[2]);
                $solution = trim($match[3]);
                $solutionEquation = '';
                $imagePath = '';

                // Extract solution equation
                preg_match('/\\\\begin{equation\*}(.*?)\\\\end{equation\*}/s', $solution, $solutionMatch);
                if (!empty($solutionEquationMatch[1])) {
                    $solutionEquation = trim($solutionEquationMatch[1]);
                }

                // Extract image path
                preg_match('/\\\\includegraphics(?:\[.*?\])?\{(.+?)\}/', $task, $imagePathMatch);
                if (!empty($imagePathMatch[1])) {
                    $imagePath = $imagePathMatch[1];
                    $task = preg_replace('/\\\\includegraphics(?:\[.*?\])?\{(.+?)\}/', '', $task);
                    $task = trim($task);
                }

                // Create object
                $object = array(
                    'section' => $section,
                    'task' => $task,
                    'solutionEquation' => $solutionEquation,
                    'imagePath' => $imagePath
                );

                var_dump($object);

                $insertTaskQuery = "INSERT INTO tasks (file_id, section, task_description, solution, image_path) VALUES (?, ?, ?, ?, ?)";
                echo $insertTaskQuery;
                $stmt = $db->prepare($insertTaskQuery);
                $stmt->bind_param("issss", $file_id, $section, $task, $solution, $imagePath);
                $stmt->execute();

                // Check for successful execution
                if ($stmt->affected_rows > 0) {
                    echo "Task inserted successfully.\n";
                } else {
                    echo "Failed to insert task.\n";
                }

                // Close the statement
                $stmt->close();
            }

            // Output the objects
            foreach ($objects as $object) {
                echo "Section: " . $object['section'] . "<br>";
                echo "Task: " . $object['task'] . "<br>";
                echo "Solution Equation: " . $object['solution'] . "<br>";
                echo "Image Path: " . $object['imagePath'] . "<br>";
                echo "<br>";
            }

            if ($insertResult) {
                echo "File inserted successfully: $file\n";
            } else {
                echo "Failed to insert file: $file\n";
            }
        } else {
            echo "file exists";
        }
    }
}

