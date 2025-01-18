<?php
session_start();

if (isset($_SESSION["user_id"])) {
    if ($_SESSION["rolle"] == 3) {
        header("Location: startseite.php");
        exit;
    }
    
    $mysqli = require __DIR__ . "/connection.php";

    $sql = "SELECT * FROM Userdaten_Hash
            WHERE id = {$_SESSION["user_id"]}";


    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    $taetigkeiten = "SELECT * FROM Taetigkeiten LIMIT 3";
    $taetigkeitenResult = $mysqli->query($taetigkeiten);

    $uhrzeitBearbeitungTätigkeit = "SELECT Datum FROM `Durchführung`";
    $uhrzeitResult = $mysqli->query($uhrzeitBearbeitungTätigkeit);
    $uhrzeit = $uhrzeitResult->fetch_assoc();

    if (!$taetigkeitenResult) {
        die("Fehler beim Abrufen der Tätigkeiten: " . $mysqli->error);
    }
    if (!$uhrzeitResult) {
        die("Fehler beim Abrufen der Uhrzeit: " . $mysqli->error);
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
                <span class="layout-title">Startseite</span>

                <div class="header-right">
                    <span class="username-text userTextMobil">Hallo, Prof. <?= htmlspecialchars($user["username"]) ?> </span>
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
                <a class="navigation-link" href="lehrer_startseite.php">Startseite</a>
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
                <div class="tätigkeiten">
                    <h2>Tätigkeiten</h2>
                    <hr />

                    <?php while ($taetigkeit = $taetigkeitenResult->fetch_assoc()): ?>
                        <h4>Tätigkeit <?= htmlspecialchars($taetigkeit['ID']) ?></h4>
                        <p><a class="tätigkeiten-link tätigkeiten-link-bold"
                                href="tätigSubpage.php?id=<?= $taetigkeit['ID'] ?>"><?= htmlspecialchars($taetigkeit['Name']) ?></a>
                        </p>
                        <p><?= htmlspecialchars($taetigkeit['Kategorie']) ?></p>


                    <?php endwhile; ?>



                </div></br>
                <div class="tätigkeiten">
                    <h2>Bewertungen</h2>
                    <hr />
                    <p><a class="bewertungen-link bewertungen-link-bold" href="bewertung.php">Vergabe einer Note an den
                            Studenten</a></p>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js">
    </script>

</body>

</html>