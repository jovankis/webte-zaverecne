<?php
session_start();
date_default_timezone_set('Europe/Bratislava');

if (!isset($_SESSION['user'])) {
    echo "<h1>Nie ste prihlasený</h1>";
    echo "<div class='row justify - content - center'>";
    echo "<div>";
    echo "<a href = '../login.php' > prihláste sa </a >";
    echo "</div>";
    echo "</div>";
    exit;
}

if ($_SESSION['role'] !== 'Študent') {
    echo "Nemáte povolenia";
    exit;
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=zav_zad', 'xkis', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$student_id = $_SESSION['user']['id'];
$name = $_SESSION['user']['name'];
$surname = $_SESSION['user']['surname'];
$task_id = $_POST['task_id'];
$answer = $_POST['answer'];

$stmt = $pdo->prepare("INSERT INTO student_answer (student_id, name, surname, section, answer) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$student_id, $name, $surname, $task_id, $answer]);
$stmt = $pdo->prepare("UPDATE gen_history SET submitted = 1 WHERE student_id = ? AND time = ? AND section = ?");
$stmt->execute([$_SESSION['user']['id'], $_SESSION['time'], $task_id]);