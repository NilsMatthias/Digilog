<?php
session_start();

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/connection.php";

    $sql = "SELECT * FROM Userdaten_Hash
            WHERE id = {$_SESSION["user_id"]}";


    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();


    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $mysqli->real_escape_string($_POST["email"]);
        $vorname = $mysqli->real_escape_string($_POST["vorname"]);
        $nachname = $mysqli->real_escape_string($_POST["nachname"]);
        $birthdate = $mysqli->real_escape_string($_POST["birthdate"]);

        $sql = "UPDATE Userdaten_Hash 
                SET email = '$email', 
                    vorname = '$vorname', 
                    nachname = '$nachname', 
                    geburtsdatum = '$birthdate' 
                WHERE id = {$_SESSION["user_id"]}";

        if ($mysqli->query($sql)) {
            header("Location: einstellungen.php?status=success");
        } else {
            die("Fehler beim Speichern: " . $mysqli->error);
        }
    }
}


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
                <a class="navigation-link" href="">Suche</a>
                <a class="navigation-link" href="tätigKatalog.php">Tätigkeitenkatalog</a>
                <a class="navigation-link" href="">Profil</a>
                <a class="navigation-link" href="einstellungen.php">Einstellungen</a>
                <a class="navigation-link" href="">Hilfe</a>
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
                        <p><strong>Vorname:</strong> <?= htmlspecialchars($user["vorname"]) ?></p>
                        <p><strong>Nachname:</strong> <?= htmlspecialchars($user["nachname"]) ?></p>
                        <p><strong>Geburtsdatum:</strong>
                            <?= htmlspecialchars($user["geburtsdatum"] ?? 'Nicht angegeben') ?></p>
                        <form method="get">
                            <button type="submit" name="edit" value="1">Bearbeiten</button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="">
                            <p><strong>Email:</strong>
                                <input type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required>
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
                            <a href="einstellungen.php">Abbrechen</a>
                        </form>
                    <?php endif; ?>
                </div></br>
            </div>
        </main>
    </div>

    <?php if (isset($_GET["status"]) && $_GET["status"] === "success"): ?>
        <p style="color: green;">Profil wurde erfolgreich aktualisiert!</p>
    <?php endif; ?>


    <script>
        document.getElementById('menuButton').addEventListener('click', function () {
            document.body.classList.toggle('drawer-open');
        });
        document.addEventListener('click', function (event) {
            var drawer = document.getElementById('drawer');
            var menuButton = document.getElementById('menuButton');

            if (!drawer.contains(event.target) && !menuButton.contains(event.target)) {
                document.body.classList.remove('drawer-open');
            }
        });
        document.getElementById('icon_user').addEventListener('click', function () {
            // Debugging output funktioniert
            const dropdown = document.getElementById('userDropdown');
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        });

        // Close dropdown if click outside of it
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('userDropdown');
            const iconUser = document.getElementById('icon_user');

            // Close dropdown if click is outside the dropdown or icon
            if (!iconUser.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>

</body>

</html>