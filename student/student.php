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

$_SESSION['time'] = time();
$objects = array();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zav_zad', 'xkis', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT t.section, t.task_description, t.solution, t.image_path, f.points, f.generating_enabled, f.starting_date, f.ending_date, t.file_id 
                       FROM tasks t 
                       INNER JOIN files f ON t.file_id = f.id');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $objects_id = array();
    foreach ($rows as $row) {
        $section = $row['section'];
        $task = $row['task_description'];
        $solution = $row['solution'];
        $imagePath = $row['image_path'];
        $points = $row['points'];
        $generating_enabled = $row['generating_enabled'];
        $starting_date = $row['starting_date'];
        $ending_date = $row['ending_date'];

        $object = array(
            'section' => $section,
            'task' => $task,
            'solution' => $solution,
            'imagePath' => $imagePath,
            'points' => $points,
        );

        $current_date = time();
        $starting_date = $row['starting_date'] ? strtotime($row['starting_date']) : null;
        $ending_date = $row['ending_date'] ? strtotime($row['ending_date']) : null;

        // Add tasks that meet the conditions to an array under their file id
        if ($generating_enabled == 1 &&
            (($starting_date === null || $current_date >= $starting_date) && ($ending_date === null || $current_date <= $ending_date))) {
            $file_id = $row['file_id'];
            if (!isset($objects_id[$file_id])) {
                $objects_id[$file_id] = array();
            }
            $objects_id[$file_id][] = $object;
        }
    }

    // Shuffle and select a random item for each file id after adding all the tasks
    foreach ($objects_id as $file_id => $tasks) {
        shuffle($tasks);
        $objects[$file_id] = $tasks[0];
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}


function displayObjects($objects): void
{
    echo '<div id="tasks" class="container" style="display: none;">';
    foreach ($objects as $object) {
        echo '<div class="row justify-content-center">';
        echo '<div class="col-md-6">';
        echo '<div class="card mb-4">';
        echo '<div class="card-body text-center">';
        echo '<h5 class="card-title">' . $object['section'] . '</h5>';
        $task = $object['task'];
        $taskParts = explode('$', $task);
        for ($i = 1; $i < count($taskParts); $i += 2) {
            $taskParts[$i] = '\\begin{equation*}' . $taskParts[$i] . '\\end{equation*}';
        }
        $task = implode('', $taskParts);
        echo '<p class="card-text math-tex">' . htmlspecialchars($task) . '</p>';
        if (!empty($object['imagePath'])) {
            echo '<img src="../' . $object['imagePath'] . '" class="img-fluid">';
        }
        echo '<div id="' . $object['section'] . '" class="editorContainer mt-4" style="width: 500px; height: 150px;"></div>';

        // Submit button
        echo '<button type="submit" id="submit_' . $object['section'] . '" class="btn btn-primary mt-3">Odoslať</button>';

        echo '<div id="answer_' . $object['section'] . '" class="answer mt-3"></div>';
        echo '<p id="points_' . $object['section'] . '">0 / ' . $object['points'] . ' body</p>';
        echo '<input type="hidden" id="solution_' . $object['section'] . '" value="' . htmlspecialchars($object['solution']) . '">';

        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
}


?>

<!doctype html>
<html lang=sk>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://www.wiris.net/demo/editor/editor"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Registračný formulár</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="./student.php"><i class="fa fa-home"></i>Študent<span
                            class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <span class="navbar-text text-light"><?php echo($_SESSION['user']['name'] . " " . $_SESSION['user']['surname']); ?></span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?logout=true">Odhlásiť</a>
            </li>
        </ul>
    </div>
</nav>
<h1 class="h1 text-center">Test</h1>
<br>
<?php
displayObjects($objects);
?>

<div class="d-flex justify-content-center">
    <button id="displayButton" class="btn btn-primary">Generuj príklady</button>
</div>
<div id="displayArea"></div>

<script>
    document.getElementById('displayButton').addEventListener('click', function () {
        document.getElementById('tasks').style.display = "block";
        this.style.display = 'none';

        var sections = [];
        <?php foreach ($objects as $object) { ?>
        sections.push('<?php echo $object['section']; ?>');
        <?php } ?>

        $.ajax({
            url: 'save_generating.php',
            method: 'POST',
            data: {
                time: <?php echo $_SESSION['time'];?>,
                sections: sections,
                student_id: <?php echo $_SESSION['user']['id'];?>,
                body: 0
            },
            success: function(response) {
                // Close the editing dialog
                $('#edit-modal').modal('hide');
                alert("Úprava bola úspešne vykonaná. Obnovte stránku.")
            },
            error: function(xhr, status, error) {
                // Handle the AJAX error here
                alert("Vyskytla sa chyba. Prosím skúste znova.")
            }
        });
    });
</script>
<script type="text/javascript" src="student.js"></script>
</body>
</html>