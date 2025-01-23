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
    $taetigkeiten = "SELECT * FROM Taetigkeiten, `Durchführung` LIMIT 3";
    $taetigkeitenResult = $mysqli->query($taetigkeiten);

    //Bewertungen fetchen
    $bewertung = "SELECT * FROM `Durchführung` d JOIN Taetigkeiten t on d.`Tätigkeit-ID` = t.ID WHERE `User-ID` = ? AND `Lehrer-ID` != ''";
    $stmt = $mysqli->prepare($bewertung);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $bewertungResult = $stmt->get_result();
    $bewertungen = "";

    $uhrzeitBearbeitungTätigkeit = "SELECT Datum FROM `Durchführung`";
    $uhrzeitResult = $mysqli->query($uhrzeitBearbeitungTätigkeit);
    $uhrzeit = $uhrzeitResult->fetch_assoc();

    if (!$taetigkeitenResult) {
        die("Fehler beim Abrufen der Tätigkeiten: " . $mysqli->error);
    }
    if (!$uhrzeitResult) {
        die("Fehler beim Abrufen der Uhrzeit: " . $mysqli->error);
    }

    //letzten 3 bearbeiteten Tätigkeiten
    $letzteTaetigkeitenSql = "
    SELECT t.ID, t.Name, t.Kategorie, d.Datum, DATE_FORMAT(d.Datum, '%d.%m.%Y') AS geburtsdatum_formatiert 
    FROM Durchführung d
    JOIN Taetigkeiten t ON d.`Tätigkeit-ID` = t.ID
    WHERE d.`User-ID` = ?
    ORDER BY d.Datum DESC
    LIMIT 6
    ";

    $stmt = $mysqli->prepare($letzteTaetigkeitenSql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $letzteTaetigkeitenResult = $stmt->get_result();

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
                    <span class="username-text userTextMobil">Hallo, <?= htmlspecialchars($user["username"]) ?> </span>
                    <img src="images/icon_user_white.png" alt="Logo" class="icon-user" id="icon_user">

                </div>
            </div>
        </header>
        <div class="dropdown-menu" id="userDropdown">
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
                    <h2>Zuletzt bearbeitete Tätigkeiten</h2>
                    <hr /><br>
                    <?php if ($letzteTaetigkeitenResult->num_rows > 0): ?>
                        <?php $counter = 1;
                        while ($taetigkeit = $letzteTaetigkeitenResult->fetch_assoc()): ?>
                            <h3>Tätigkeit <?= $counter ?></h3>
                            <p><a class="tätigkeiten-link tätigkeiten-link-bold"
                                    href="tätigSubpage.php?id=<?= $taetigkeit['ID'] ?>"><?= htmlspecialchars($taetigkeit['Name']) ?></a>
                            </p>
                            <p><?= htmlspecialchars($taetigkeit['Kategorie']) ?></p>
                            <p>Zuletzt bearbeitet am: <?= htmlspecialchars($taetigkeit["geburtsdatum_formatiert"]) ?></p>
                            <?php $counter++;
                        endwhile; ?>
                    <?php else: ?>
                        <p>Keine bearbeiteten Tätigkeiten gefunden</p>
                    <?php endif; ?>





                </div></br>
                <div class="bewertungen">
                    <h2>Bewertungen</h2>
                    <hr />
                    <?php if ($bewertungResult->num_rows > 0): ?>
                    <?php while ($bewertungen = $bewertungResult->fetch_assoc()): ?>
                        <p><a class="tätigkeiten-link tätigkeiten-link-bold"
                                href="bewertungssicht.php?id=<?= $bewertungen['ID'] ?>"><?= htmlspecialchars($bewertungen['Name']) ?></a>
                        </p>
                    <?php endwhile; ?>
                <?php else: ?>
                    <br><p>Sie haben noch keine Bewertungen erhalten</p>
                <?php endif; ?>

                </div>
            </div>
        </main>
    </div>

    <script src="script.js">
    </script>

</body>

</html>