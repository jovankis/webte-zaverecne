<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Math website</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Welcome page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./teacher.php"><i class="fa fa-home"></i>Home<span
                        class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="task_files.php"><i class="fa fa-list"></i>Files with tasks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./result_table.php"><i class="fa fa-table"></i>Table with results</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./gen_history.php"><i class="fa fa-history"></i>Generating history</a>
            </li>
        </ul>
    </div>
</nav>
<div id="container">
    <?php
    //session_start();
    //
    //if (!isset($_SESSION['user'])) {
    //    echo "Nie ste prihlasený";
    //    exit;
    //}
    //
    //if ($_SESSION['role'] !== 'Učiteľ') {
    //    echo "Nemáte povolenia";
    //    exit;
    //}

    // TODO: edit to get file paths from db
    $directory = '../files'; // Replace with the path to the directory you want to list

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
                echo $directory . "/" . $file;
                echo "<tr><td>{$file}</td></tr>";
            }

            echo '
        </table>';

        } else {
            echo "Directory is empty. No files found";
        }
    } else {
        echo "Invalid directory.";
    }


    ?>
</div>
<?php
$latexFile = "../files/odozva01pr.tex";
// Read the contents of the LaTeX file
$latexContent = file_get_contents($latexFile);

// Parse the content into objects
$objects = array();
preg_match_all('/\\\\section\*{(.+?)}\s*\\\\begin{task}(.*?)\\\\end{task}\s*\\\\begin{solution}(.*?)\\\\end{solution}/s', $latexContent, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    $section = $match[1];
    $task = trim($match[2]);
    $solution = trim($match[3]);

    $taskEquation = '';
    $solutionQuestion = '';
    $imagePath = '';

    // Extract task equation
    preg_match('/\\\\begin{equation\*}(.*?)\\\\end{equation\*}/s', $task, $taskEquationMatch);
    if (!empty($taskEquationMatch[1])) {
        $taskEquation = trim($taskEquationMatch[1]);
    }

    // Extract solution question
    preg_match('/\\\\begin{equation\*}(.*?)\\\\end{equation\*}/s', $solution, $solutionQuestionMatch);
    if (!empty($solutionQuestionMatch[1])) {
        $solutionQuestion = trim($solutionQuestionMatch[1]);
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
        'taskEquation' => $taskEquation,
        'solution' => $solution,
        'solutionQuestion' => $solutionQuestion,
        'imagePath' => $imagePath
    );

    // Add object to the list
    $objects[] = $object;
}

// Output the objects
foreach ($objects as $object) {
    echo "Section: " . $object['section'] . "<br>";
    echo "Task: " . $object['task'] . "<br>";
    echo "Task Equation: " . $object['taskEquation'] . "<br>";
    echo "Solution: " . $object['solution'] . "<br>";
    echo "Solution Question: " . $object['solutionQuestion'] . "<br>";
    echo "Image Path: " . $object['imagePath'] . "<br>";
    echo "<br>";
}
?>

</body>
</html>