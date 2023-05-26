<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Math website</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

    <!--    <script src="js/script.js"></script>-->
    <script src="js/download.js"></script>

    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script type="text/javascript" id="MathJax-script" async
            src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js">
    </script>

    <script>
        MathJax = {
            loader: {load: ['input/asciimath', 'output/chtml']}
        }
    </script>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo "<p class='not-logged-in-text'>Nie ste prihlasený. Prihlaste sa <a href='../login.php'>tu</a></p>";
    exit;
}

if ($_SESSION['role'] !== 'Učiteľ') {
    echo "Nemáte povolenia";
    exit;
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./teacher.php"><i class="fa fa-home"></i>Domovská stránka - Súbory s úlohami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./result_table.php"><i class="fa fa-table"></i>Tabuľka s výsledkami</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="./gen_history.php"><i class="fa fa-history"></i>História generovania<span
                        class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./tutorial.php"><i class="fa fa-file"></i>Návod</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <span class="navbar-text text-light"><?php echo($_SESSION['user']['name'] . " " . $_SESSION['user']['surname']); ?></span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?logout=true"><i class="fa fa-sign-out"></i>Odhlásiť</a>
            </li>
        </ul>
    </div>
</nav>
<div class="main-div">
    <?php
    require_once "../config.php";
    date_default_timezone_set('Europe/Bratislava');
    global $dbconfig;
    $db = new mysqli();
    $db->connect($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password']);
    $db->select_db($dbconfig['database']);

    $query = "SELECT gen_history.*, users.name, users.surname FROM gen_history JOIN users ON gen_history.student_id = users.id;";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo '
            <table class="table table-hover table-auto table-striped" id="results-table">
                <thead class="thead-dark">
                    <tr>
                        <th>Študent</th>
                        <th>Úloha</th>
                        <th>Dátum a čas generovania</th>
                        <th>Odovzdané</th>
                        <th>Body</th>
                        <th>Úspešné</th>
                    </tr>
                </thead>';
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']} {$row['surname']}</td>";
            echo "<td>{$row['section']}</td>";
            $readableDateTime = date('Y-m-d H:i:s', $row['time']);
            echo "<td>$readableDateTime</td>";
            if($row['submitted']){
                echo "<td>Áno</td>";
                echo "<td>{$row['body']}</td>";
                echo "<td>{$row['successful']}</td>";
            } else {
                echo "<td>Nie</td>";
                echo "<td>0 (neodovzdané)</td>";
                echo "<td>Nie (neodovzdané)</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "No results found";
    }
    ?>
</div>
</body>
</html>