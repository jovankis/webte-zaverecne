<?php
//session_start();
//
//if (!isset($_SESSION['user'])) {
//    echo "Nie ste prihlasený";
//    exit;
//}
//
//if ($_SESSION['role'] !== 'Študent') {
//    echo "Nemáte povolenia";
//    exit;
//}


//$latexFile = "../zaverecne_zadanie/blokovka01pr.tex";
$latexFile = "../zaverecne_zadanie/odozva01pr.tex";
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

function formatObjects($objects)
{
    $output = "";
    foreach ($objects as $object) {
        $output .= "Section: " . $object['section'] . "<br>";
        $output .= "Task: " . $object['task'] . "<br>";
        $output .= "Task Equation: <span class='math-tex'>\\(" . $object['taskEquation'] . "\\)</span><br>";
        $output .= "Solution: " . $object['solution'] . "<br>";
        $output .= "Solution Question: <span class='math-tex'>\\(" . $object['solutionQuestion'] . "\\)</span><br>";
        $output .= "Image Path: " . $object['imagePath'] . "<br>";
        $output .= "<br>";
        $output .= "<div id='1' class='editorContainer' style='width: 500px; height: 500px;'></div>";
    }
    return $output;
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
        echo '<p class="card-text">' . $object['task'] . '</p>';
//        echo '<p class="card-text">' . $object['solution'] . '</p>';
//        echo '<p class="card-text"><span class="math-tex">\\(' . $object['solutionQuestion'] . '\\)</span></p>';
        if (!empty($object['imagePath'])) {
            echo '<img src="../zaverecne_zadanie/images/' . $object['imagePath'] . '" class="img-fluid">';
        }
        echo '<div id="' . $object['section'] . '" class="editorContainer mt-4" style="width: 500px; height: 200px;"></div>';
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
    <script src="https://www.wiris.net/demo/editor/editor"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>
<body>
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