<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = [];
$vorname = $nachname = $name = $email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $vorname = htmlspecialchars(string: $_POST["vorname"]);
    $nachname = htmlspecialchars(string: $_POST["nachname"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];
    $rolle = 3;


    // Validierung
    if (empty($name)) {
        $errors['name'] = "First name is required";
    }
    if (empty($vorname)) {
        $errors['name'] = "Last name is required";
    }
    if (empty($nachname)) {
        $errors['name'] = "Name is required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Valid email is required";
    }
    if (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    }
    if (!preg_match("/[a-z]/i", $password)) {
        $errors['password'] = "Password must contain at least one letter";
    }
    if (!preg_match("/[0-9]/", $password)) {
        $errors['password'] = "Password must contain at least one number";
    }
    if ($password !== $password_confirmation) {
        $errors['password_confirmation'] = "Passwords must match";
    }

    // Nur wenn keine Fehler vorliegen, Daten speichern
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $mysqli = require __DIR__ . "/connection.php";

        $sql = "INSERT INTO Userdaten_Hash (rolle, username, nachname, vorname, email, password_hash) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->stmt_init();


        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $mysqli->error);
        }

        $stmt->bind_param("isssss", $rolle, $name, $nachname, $vorname, $email, $password_hash);

        if ($stmt->execute()) {
            header("Location: signup-success.html");
            exit;

        } else {
            if ($mysqli->errno === 1062) {
                $errors['email'] = "Email already taken";
            } else {
                die($mysqli->error . " " . $mysqli->errno);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Das digitale Logbuch - Registrierung</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="login.css" rel="stylesheet" type="text/css">
</head>

<body class="pagelayout-login">
    <div id="page" class="container-fluid">
        <div id="page-content" class="row">
            <div class="login-wrapper">
                <div class="login-container">
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="loginform">

                            <h1 class="login-heading sr-only">Registrierung</h1>
                            
                            <form action="" method="post" novalidate>
                                <div class="form-group">
                                    <i class="fa-solid fa-user"></i>
                                    <input class="form-control" type="text" id="vorname" name="vorname" placeholder="Vorname" value="<?= htmlspecialchars($vorname) ?>" required>
                                    <?php if (!empty($errors['vorname'])): ?>
                                        <p class="error-message"><?= $errors['vorname'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <br>
                                <div class="form-group">
                                    <i class="fa-solid fa-user"></i>
                                    <input class="form-control" type="text" id="nachname" name="nachname" placeholder="Nachname" value="<?= htmlspecialchars($nachname) ?>" required>
                                    <?php if (!empty($errors['nachname'])): ?>
                                        <p class="error-message"><?= $errors['nachname'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <br>
                                <div class="form-group">
                                    <i class="fa-solid fa-user"></i>
                                    <input class="form-control" type="text" id="name" name="name" placeholder="Benutzername" value="<?= htmlspecialchars($name) ?>" required>
                                    <?php if (!empty($errors['name'])): ?>
                                        <p class="error-message"><?= $errors['name'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <br>
                                <div>
                                    <i class="fa-solid fa-envelope"></i>
                                    <input class="form-control" type="email" id="email" name="email" placeholder="E-mail" value="<?= htmlspecialchars($email) ?>" required>
                                    <?php if (!empty($errors['email'])): ?>
                                        <p class="error-message"><?= $errors['email'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <br>
                                <div>
                                    <i class="fa-solid fa-lock"></i>
                                    <input class="form-control" type="password" id="password" name="password" placeholder="Passwort" required>
                                    <?php if (!empty($errors['password'])): ?>
                                        <p class="error-message"><?= $errors['password'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <br>
                                <div>
                                    <i class="fa-solid fa-lock"></i>
                                    <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="Passwort wiederholen" required>
                                    <?php if (!empty($errors['password_confirmation'])): ?>
                                        <p class="error-message"><?= $errors['password_confirmation'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <br>
                                <div class="signup-form-submit form-group">
                                    <button class="button-login" type="submit" id="sgnbtn">Registrieren</button>
                                </div>
                                <div class="backToLogin login-form-forgotpassword">
                                    <a class="navigation-link " href="login.php">Zurück zur Anmeldung</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
