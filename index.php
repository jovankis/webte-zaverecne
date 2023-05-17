<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zav_zad', 'xkis', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Validation
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Meno je povinné.';
        }
        if (empty($surname)) {
            $errors[] = 'Priezvisko je povinné.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Prosím, zadajte platnú emailovú adresu.';
        }
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Heslo musí obsahovať aspoň 6 znakov.';
        }
        if ($role !== 'Študent' && $role !== 'Učiteľ') {
            $errors[] = 'Prosím, vyberte platnú rolu.';
        }

        // Check if email already exists in the database
        $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errors[] = 'Email už existuje v databáze.';
        }

        // If there are no errors, insert the user into the database
        if (empty($errors)) {
            // Password hashing for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // SQL query
            $sql = "INSERT INTO users (name, surname, email, password, role) VALUES (?, ?, ?, ?, ?)";

            // Prepared statement
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $surname, $email, $hashedPassword, $role]);
        } else {
            // If there are errors, display them
            foreach ($errors as $error) {
                echo '<script>alert("' . $error . '");</script>';
            }
        }
    }


} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$pdo = null;
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Záverečné zadanie</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Registrácia</h2>
            <form autocomplete="off" method="post" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="name">Meno</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Zadajte meno"
                           onblur="validateName(this); checkFormValidity()">
                    <div id="nameError" class="error-message"></div>

                </div>
                <div class="form-group">
                    <label for="surname">Priezvisko</label>
                    <input type="text" class="form-control" id="surname" name="surname"
                           placeholder="Zadajte priezvisko" onblur="validateSurname(this); checkFormValidity()">
                    <div id="surnameError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="email">Emailová adresa</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Zadajte email"
                           onblur="validateEmail(this); checkFormValidity()">
                    <div id="emailError" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="password">Heslo</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Zadajte heslo" onblur="validatePassword(this); checkFormValidity()">
                    <div id="passwordError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="role">Chcem sa registrovať ako</label>
                    <select class="form-control" id="role" name="role">
                        <option>Študent</option>
                        <option>Učiteľ</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" id="registerButton" disabled>Registrovať</button>
            </form>
            <br>
            <br>
            <div class="row justify-content-center">
                <div>
                    <text>Už máte účet?</text>
                    <a href="login.php">prihláste sa</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="script.js"></script>
</body>
</html>