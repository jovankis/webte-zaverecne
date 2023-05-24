<?php
session_start();

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
    //unset and destroy session on logout
    session_unset();
    session_destroy();
    header('Location: ../index.php'); //redirect to your desired link
    exit;
}

$latexFile = "../zaverecne_zadanie/blokovka01pr.tex";
//$latexFile = "../zaverecne_zadanie/blokovka02pr.tex";
//$latexFile = "../zaverecne_zadanie/odozva01pr.tex";
//$latexFile = "../zaverecne_zadanie/odozva02pr.tex";
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
        $imagePath = basename($imagePath);
    }

// If the task contains an equation wrapped with $
    if (preg_match('/\$(.*?)\$/', $task, $dollarWrapEquation)) {
        $dollarWrappedEquation = $dollarWrapEquation[0];
        $unwrappedEquation = $dollarWrapEquation[1];

        $equationWithTags = '\\begin{equation*}' . $unwrappedEquation . '\\end{equation*}';

        $task = str_replace($dollarWrappedEquation, $equationWithTags, $task);
    }

    $task = str_replace(['\begin{equation*}', '\end{equation*}'], ['\\(', '\\)'], $task);


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


function displayObjects($objects)
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
            echo '<img src="../zaverecne_zadanie/images/' . $object['imagePath'] . '" class="img-fluid">';
        }
        echo '<div id="' . $object['section'] . '" class="editorContainer mt-4" style="width: 500px; height: 150px;"></div>';

        // Submit button
        echo '<button type="submit" id="submit_' . $object['section'] . '" class="btn btn-primary mt-3">Submit</button>';

        echo '<div id="answer_' . $object['section'] . '" class="answer mt-3"></div>';
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
                <a class="nav-link" href="../index.php">Welcome page</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="./student.php"><i class="fa fa-home"></i>Home<span
                            class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <span class="navbar-text text-light"><?php echo($_SESSION['user']['name'] . " " . $_SESSION['user']['surname']); ?></span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?logout=true">Logout</a>
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
        this.style.display = 'none';  // Hide the button
    });
</script>
<script type="text/javascript" src="wiris.js"></script>
</body>
</html>