<?php
date_default_timezone_set('Europe/Bratislava');
require_once "../../config.php";
global $dbconfig;
$db = new mysqli();
$db->connect($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password']);
$db->select_db($dbconfig['database']);

if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];

    $query = "SELECT f.id FROM files f WHERE filename = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $result = $stmt->get_result();
    $res = $result->fetch_assoc();
    $file_id = $res['id'];

    // Prepare the query using a parameterized statement
    $query = "SELECT * FROM tasks WHERE file_id = ?";
    $stmt = $db->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $file_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch tasks and add them to the $tasks array
        $tasks = array();
        while ($row = $result->fetch_assoc()) {
            $task = array(
                'taskID' => $row['task_id'],
                'section' => $row['section'],
                'taskDescription' => $row['task_description'],
                'solution' => $row['solution'],
                'imagePath' => $row['image_path']
            );
            $tasks[] = $task;
        }

        // Close the statement
        $stmt->close();

        // Return the tasks as JSON
        echo json_encode($tasks);
    } else {
        echo "Failed to prepare the statement.";
    }
} else {
    echo "Error occurred, file ID not set.";
}
