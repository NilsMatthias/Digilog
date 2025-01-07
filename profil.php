<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/connection.php";

    $sql = "SELECT * FROM Userdaten_Hash
            WHERE id = {$_SESSION["user_id"]}";


    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
    $user_id = $user["id"];

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT rolle, id, vorname, nachname, email, DATE_FORMAT(geburtsdatum, '%d.%m.%Y') AS geburtsdatum_formatiert  FROM `Userdaten_Hash` WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();


        if ($result && $result->num_rows > 0) {
            // Hole das Ergebnis als Array
            $taetigkeit = $result->fetch_assoc();
        } else {
            // Keine Tätigkeit gefunden
            die("Profil kann nicht abgerufen werden.");
        }



    }
    $rolle = "";
    if ($row['rolle'] == 2) {
        $rolle = "Lehrende";
    } elseif ($row['rolle'] == 3) {
        $rolle = "Studierende";
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
                <span class="layout-title">Personen-Suche</span>

                <div class="header-right">
                    <span class="username-text">Hallo, <?= htmlspecialchars($user["username"]) ?> </span>
                    <img src="images/icon_user_white.png" alt="Logo" class="icon-user" id="icon_user">

                </div>
            </div>
        </header>
        <div class="dropdown-menu" id="userDropdown">
            <a href="startseite.php">Zurück zur Startseite</a>
            <a href="einstellungen.php">Einstellungen</a>
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
                <div class="tätigkeiten letzte-tätigkeiten">
                    <div class="heading-profil">
                        <h2 class="profil-name">Profil von <?= htmlspecialchars($row['vorname']) ?>
                            <?= htmlspecialchars($row['nachname']) ?></h2>
                        <h2 class="profil-role"><?= htmlspecialchars($rolle) ?></h2>
                    </div>
                    <hr /></br>
                    <div class="details">
                        <p><strong>Vorname:</strong> <?= htmlspecialchars($row["vorname"]) ?></p>
                        <p><strong>Nachname:</strong> <?= htmlspecialchars($row["nachname"]) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($row["email"]) ?></p>
                        <p><strong>Geburtsdatum:</strong>
                            <?= htmlspecialchars($row["geburtsdatum_formatiert"] ?? 'Nicht angegeben') ?></p>

                    </div>


                </div></br>

            </div>
        </main>
    </div>

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