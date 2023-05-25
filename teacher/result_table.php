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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Welcome page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./teacher.php"><i class="fa fa-home"></i>Domovská stránka<span
                        class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="task_files.php"><i class="fa fa-list"></i>Súbory s úlohami</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="./result_table.php"><i class="fa fa-table"></i>Tabuľka s výsledkami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./gen_history.php"><i class="fa fa-history"></i>História generovania</a>
            </li>
        </ul>
    </div>
</nav>
<div class="main-div">
    <?php
    require_once "../config.php";
    global $dbconfig;
    $db = new mysqli();
    $db->connect($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password']);
    $db->select_db($dbconfig['database']);

    $query = "SELECT * FROM student_answer";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo '
            <table class="table table-hover table-auto table-striped" id="results-table">
                <thead class="thead-dark">
                    <tr>
                        <th>Uloha</th>
                        <th>ID studenta</th>
                        <th>Meno</th>
                        <th>Priezvisko</th>
                        <th>Odpoved</th>
                        <th>Ziskane body</th>
                    </tr>
                </thead>';
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['section']}</td>";
            echo "<td>{$row['student_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['surname']}</td>";
            echo "<td>{$row['answer']}</td>";
            echo "<td>0</td>";
            echo "</tr>";
        }

        echo '<button class="btn btn-light" id="download-csv-button">
                    <i class="fa fa-download" style="margin-right: 8px;"></i> Stiahnut ako CSV subor
                </button>';
    } else {
        echo "No results found";
    }
    ?>

</div>
</body>
</html>
