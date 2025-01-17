<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/connection.php";

    $sql = "SELECT id, username, email, vorname, nachname, geburtsdatum, DATE_FORMAT(geburtsdatum, '%d.%m.%Y') AS geburtsdatum_formatiert 
        FROM Userdaten_Hash 
        WHERE id = {$_SESSION["user_id"]}";


    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();


    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $mysqli->real_escape_string($_POST["username"]);
        $vorname = $mysqli->real_escape_string($_POST["vorname"]);
        $nachname = $mysqli->real_escape_string($_POST["nachname"]);
        $birthdate = $mysqli->real_escape_string($_POST["birthdate"]);

        $sql = "UPDATE Userdaten_Hash 
                SET username = '$username', 
                    vorname = '$vorname', 
                    nachname = '$nachname', 
                    geburtsdatum = " . ($birthdate ? "'$birthdate'" : "NULL") . "                
                    WHERE id = {$_SESSION["user_id"]}";

        if ($mysqli->query($sql)) {
            header("Location: einstellungen.php?status=success");
        } else {
            die("Fehler beim Speichern: " . $mysqli->error);
        }
    }
}
//Automatisches Log
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 7200) {
    session_unset();
    session_destroy();
    header("Location: login.php");
   }
   $_SESSION['last_activity'] = time();


?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Das digitale Logbuch</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="fixed-header-drawer">
        <header class="layout-header fixed-top">
            <div class="header-row">
                <div class="button-icon-menu" id="menuButton">
                    <i class="material-icons">menu</i>

                </div>
                <span class="layout-title">Einstellungen</span>

                <div class="header-right">
                    <span class="username-text">Hallo, <?= htmlspecialchars($user["username"]) ?> </span>
                    <img src="images/icon_user_white.png" alt="Logo" class="icon-user" id="icon_user">

                </div>
            </div>
        </header>
        <div class="dropdown-menu" id="userDropdown">
            <a href="startseite.php">Zurück zur Startseite</a>
            <a class="navigation-link" href="logout.php">Log out</a>

        </div>

        <div class="layout-drawer" id="drawer">
            <nav class="navigation">
                <a class="navigation-link" href="startseite.php">Startseite</a>
                <a class="navigation-link" href="suche.php">Suche</a>
                <a class="navigation-link" href="tätigKatalog.php?sortieren=Name_ASC">Tätigkeitenkatalog</a>
                <a class="navigation-link" href="einstellungen.php">Einstellungen</a>
                <a class="navigation-link" href="hilfe.php">Hilfe</a>
                <hr class="navigation-divider">
                <a class="navigation-link" href="logout.php">Log out</a>

            </nav>

        </div>
        <main class="layout-content">
            <div class="Page-content">
                <div class="details">
                    <h2><span class="username-text">Hallo, <?= htmlspecialchars($user["username"]) ?> </span></h2>
                    <hr /><br />
                    <h3>Details zum Profil</h3><br />
                    <img src="images/icon_user.png" alt="Logo" class="icon-user" id="icon_user">
                    <?php if (!isset($_GET["edit"])): ?>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>
                        <p><strong>Username:</strong> <?= htmlspecialchars($user["username"]) ?></p>
                        <p><strong>Vorname:</strong> <?= htmlspecialchars($user["vorname"]) ?></p>
                        <p><strong>Nachname:</strong> <?= htmlspecialchars($user["nachname"]) ?></p>
                        <p><strong>Geburtsdatum:</strong>
                            <?= htmlspecialchars($user["geburtsdatum_formatiert"] ?? 'Nicht angegeben') ?></p>
                        <form method="get">
                            <button type="submit" name="edit" value="1">Bearbeiten</button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="">
                            <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>

                            <p><strong>Username:</strong>
                                <input type="text" name="username" value="<?= htmlspecialchars($user["username"]) ?>"
                                    required>
                            </p>
                            <p><strong>Vorname:</strong>
                                <input type="text" name="vorname" value="<?= htmlspecialchars($user["vorname"]) ?>"
                                    required>
                            </p>
                            <p><strong>Nachname:</strong>
                                <input type="text" name="nachname" value="<?= htmlspecialchars($user["nachname"]) ?>"
                                    required>
                            </p>
                            <p><strong>Geburtsdatum:</strong>
                                <input type="date" name="birthdate"
                                    value="<?= htmlspecialchars($user["geburtsdatum"] ?? '') ?>">
                            </p>
                            <input type="submit"></input>
                            <a href="einstellungen.php" class="button">Abbrechen</a>
                        </form>
                    <?php endif; ?>
                </div></br>
            </div>
        </main>
    </div>

    <?php if (isset($_GET["status"]) && $_GET["status"] === "success"): ?>
        <p style="color: green;">Profil wurde erfolgreich aktualisiert!</p>
    <?php endif; ?>


    <script src="script.js">
    </script>
</body>

</html>