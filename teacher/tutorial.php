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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./teacher.php"><i class="fa fa-home"></i>Domovská stránka - Súbory s
                    úlohami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./result_table.php"><i class="fa fa-table"></i>Tabuľka s výsledkami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./gen_history.php"><i class="fa fa-history"></i>História generovania</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link active" href="./tutorial.php"><i class="fa fa-file"></i>Navod<span
                        class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <span
                    class="navbar-text text-light"><?php echo($_SESSION['user']['name'] . " " . $_SESSION['user']['surname']); ?></span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?logout=true"><i class="fa fa-sign-out"></i>Odhlásiť</a>
            </li>
        </ul>
    </div>
</nav>
<div class="main-div" id="tutorial">
    <h2>Navod</h2>

    <div class="tutorial-div">
        <h3>1. Domovska stranka</h3>
        <p>Na domovskej stranke najdete tabulku so vsetkymi dostupnymi subormi.</p>
        <img src="tutorial_images/1.png" alt="Screenshot domovskej stranky" width="100%">

        <p>Kliknutim na tlacidlo "Obnovit subory", aplikacia preveri<br> ci na serveri nie su nejake nove subory a
            vyparsuje tasky zo suborov do databazy.</p>
        <p>Po uspesnom obnoveni sa zobrazi takyto alert.</p>
        <img src="tutorial_images/2.png" alt="Screenshot alertu" width="40%">
        <p>Kliknutim na tlacidlo "Upravit", zobrazi sa okno v ktorom je mozne upravovat
            atributy suborov.</p>
        <img src="tutorial_images/2a.png" alt="Screenshot okna pre modifikaciu" width="40%">
    </div>

    <div class="tutorial-div">
        <h3>2. Tabulka s vysledkami</h3>
        <p>Kliknutim na tlacidlo "Stiahnut ako CSV subor", pouzivatelovi sa zacne stahovat subor s udajmi z tabulky.</p>
        <img src="tutorial_images/3.png" alt="Screenshot tabulky s vysledkami" width="100%">
    </div>

    <div class="tutorial-div">
        <h3>3. Historia generovania</h3>
        <p>Na stranke historia generovania mozete pozriet ktory student kedy generoval ulohy,<br>
            ci ich odovzdal alebo nie a kolko bodov dostal(ak ich odovzdal).</p>
        <img src="tutorial_images/4.png" alt="Screenshot tabulky s historiou generovania" width="100%">
    </div>

    <div class="tutorial-div">
        <h3>4. Odhlasenie</h3>
        <p>Kliknutim na tlacidlo "Odhlasit" ucitel bude odhlaseny a presmerovany na uvodnu stranku.</p>
        <img src="tutorial_images/5.png" alt="Screenshot so sipkou na tlacidlo" width="50%">
    </div>

    <h3>5: Navod</h3>
    <p>Ak chcete stiahnut tento navod ako PDF subor, kliknite na tlacidlo "Stiahnut PDF".</p>
    <a id="download-tutorial-button" class="btn btn-primary" href="actions/generate_pdf.php" download>Stiahnut PDF</a>

</div>
</body>
</html>