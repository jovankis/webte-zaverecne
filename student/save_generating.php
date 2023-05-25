<?php
date_default_timezone_set('Europe/Bratislava');
require_once "../config.php";
global $dbconfig;
$db = new mysqli();
$db->connect($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password']);
$db->select_db($dbconfig['database']);

try {
    $sections = $_POST['sections'];
    $student_id = $_POST['student_id'];
    $body = 0;
    $successful = 0;
    $time = $_POST['time'];
    $submitted = 0;

    foreach ($sections as $section) {
        $insertQuery = $db->prepare("INSERT INTO gen_history (student_id, section, body, successful, time, submitted) VALUES (?, ?, ?, ?, ?, ?)");
        $insertQuery->bind_param("isiisi", $student_id, $section, $body, $successful, $time, $submitted);
        $insertQuery->execute();
    }
    echo "Uspesne";
} catch (Exception $e) {
    echo $e->getMessage();
}
