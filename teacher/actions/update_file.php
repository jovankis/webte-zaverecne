<?php
date_default_timezone_set('Europe/Bratislava');
require_once "../../config.php";
global $dbconfig;
$db = new mysqli();
$db->connect($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password']);
$db->select_db($dbconfig['database']);

try {
    $file_id = $_POST["id"];
    $generatingEnabled = $_POST["generatingEnabled"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $points = $_POST['points'];

    // Handle points bigger than 0
    if ($points <= 0) {
        throw new Exception("Zle nastavene body");
    }

    // Handle empty date values
    if (empty($startDate)) {
        $startDate = null;
    }
    if (empty($endDate)) {
        $endDate = null;
    }

    // Prepare the SQL statement
    $st = $db->prepare("UPDATE files SET `generating_enabled` = ?, `starting_date` = ?, `ending_date` = ?, `points` = ? WHERE id = ?");
    $st->bind_param("sssii", $generatingEnabled, $startDate, $endDate, $points, $file_id);

    // Execute the prepared statement
    $result = $st->execute();
    if ($result) {
        // Update successful
    } else {
        http_response_code(500);
        throw new Exception('Internal Server Error');
    }

} catch (Exception $e) {
    echo $e->getMessage();
}