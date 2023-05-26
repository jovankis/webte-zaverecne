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

    <script src="js/script.js"></script>

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
                <a class="nav-link active" href="./teacher.php"><i class="fa fa-home"></i>Domovská stránka - Súbory s úlohami<span
                        class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./result_table.php"><i class="fa fa-table"></i>Tabuľka s výsledkami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./gen_history.php"><i class="fa fa-history"></i>História generovania</a>
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


    echo "
    <button class='btn btn-light' id='refresh-button'>
        <i class='fa fa-refresh' style='margin-right: 8px;'></i> Obnoviť súbory
    </button>";

    require_once "../config.php";
    global $dbconfig;
    $db = new mysqli();
    $db->connect($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password']);
    $db->select_db($dbconfig['database']);

    $query = "SELECT * FROM files";
    $result = $db->query($query);

    echo '
            <table class="table table-hover table-auto table-striped" id="files-table">
                <thead class="thead-dark">
                    <tr>
                        <th style="display: none"></th>
                        <th>Názov súboru</th>
                        <th>Možné generovanie</th>
                        <th>Otvorené</th>
                        <th>Body</th>
                        <th></th>
                    </tr>
                </thead>';

    while ($row = $result->fetch_assoc()) {
        echo "<tr data-id='{$row['id']}' data-filename='{$row['filename']}' data-generating-enabled='{$row['generating_enabled']}' data-start-date='{$row['starting_date']}' data-end-date='{$row['ending_date']}' data-points='{$row['points']}'>";
        echo "<td>{$row['filename']}</td>";
        $generating_enabled = $row['generating_enabled'] ? "Áno" : "Nie";
        echo "<td>$generating_enabled</td>";
        $dates = $row['starting_date'] != null && $row['ending_date'] != null ? $row['starting_date'] . "/" . $row['ending_date'] : "Nedefinovane";
        echo "<td>$dates</td>";
        echo "<td>{$row['points']}</td>";
        echo "<td><button id='edit-btn' class='btn btn-success'><i class=\"fa fa-pencil\"></i>Upraviť</button></td>";
        echo "</tr>";
    }

    echo '
        </table>';
    ?>
</div>


<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">Edit Placement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="edit-modal-id">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="edit-modal-generating-enabled">
                            <label class="form-check-label" for="edit-modal-generating-enabled">Je možné generovat
                                príklady</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-modal-start-date">Dátum začatia:</label>
                        <input type="date" class="form-control" id="edit-modal-start-date">
                    </div>
                    <div class="form-group">
                        <label for="edit-modal-end-date">Dátum ukončenia:</label>
                        <input type="date" class="form-control" id="edit-modal-end-date">
                    </div>
                    <div class="form-group">
                        <label for="edit-modal-points">Počet bodov za každú úlohu:</label>
                        <input type="number" class="form-control" id="edit-modal-points" min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušiť</button>
                <button type="button" class="btn btn-primary" id="edit-modal-save">Uložiť</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>