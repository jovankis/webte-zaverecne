<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=zav_zad', 'xkis', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'Učiteľ') {
                header('Location: /zaverecnezadanie/teacher/teacher.php');
            } else if ($user['role'] === 'Študent') {
                header('Location: /zaverecnezadanie/student/student.php');
            } else {
                echo "Nesprávna rola";
            }
            exit;
        } else {
            echo "Nespravný email alebo heslo";
        }

    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Záverečné zadanie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Prihlásenie</h2>
            <form method="post">
                <div class="form-group">
                    <label for="email">Emailová adresa</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Zadajte email">
                </div>
                <div class="form-group">
                    <label for="password">Heslo</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Zadajte heslo">
                </div>
                <button type="submit" class="btn btn-primary">Prihlásiť</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>