<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Nutzer eingeloggt prüfen 
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirect if not logged in
    exit;
}

$mysqli = require __DIR__ . "/connection.php";

$user_id = $_SESSION['user_id'];
$feedback = "";
$placeHolderText = "Schreiben Sie hier ihre Dokumentation. Diese wird hier angezeigt, sobald Sie den Button `Speichern` getätigt haben.";
$placeHolderTextRef = "Schreiben Sie hier Ihre Selbsteinschätzung zur Dokumentation. Diese wird hier angezeigt, sobald Sie den Button `Speichern` getätigt haben.";
$dokumentation_text = "";
$documentationChance = ""; 
$self_reflection_text = "";
$buttonText = "Speichern";
$buttonSelfRefText = "Speichern";
$lehrer_id = 0;

// ID aus URL verarbeiten
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Tätigkeit abrufen
    $sql = "SELECT * FROM Taetigkeiten WHERE ID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $taetigkeit = $result->fetch_assoc();
    } else {
        die("Tätigkeit nicht gefunden.");
    }

    // Bewertung abrufen
    $sqlGetValues = "SELECT * FROM `Durchführung` d join Userdaten_Hash u on d.`Lehrer-ID` = u.id where `User-ID` = ? AND `Tätigkeit-ID` = ?";
    $stmt = $mysqli->prepare($sqlGetValues);
    $stmt->bind_param("ii", $user_id, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $bewertung = $result->fetch_assoc();
    } else {
        $bewertung = null;
    }

    $stmt->close();
} else {
    die("Ungültige ID.");
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Das digitale Logbuch</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="fixed-header-drawer">
        <header class="layout-header fixed-top">
            <div class="header-row">
                <div class="button-icon-menu" id="menuButton">
                    <i class="material-icons">menu</i>
                </div>
                <span class="layout-title">Hier kannst du deine Bewertung einsehen</span>
                <div class="header-right">
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
                <a class="navigation-link" href="">Hilfe</a>
                <hr class="navigation-divider">
                <a class="navigation-link" href="logout.php">Log out</a>
            </nav>
        </div>
        <main class="layout-content">
            <div class="Page-content">
                <div class="tätigkeiten">
                    <?php if (isset($taetigkeit)): ?>
                        <h2><?= htmlspecialchars($taetigkeit['Name']) ?></h2>
                    <?php else: ?>
                        <p>Keine Tätigkeit gefunden oder ungültige ID übergeben.</p>
                    <?php endif; ?>
                    <p class="lehrer">von Prof. <?= htmlspecialchars($bewertung['nachname'])?>, <?=htmlspecialchars($bewertung['vorname']) ?></p> 
                </div>
                <br>
                <div class="beschreibung">
                    <h3>Bewertung</h3>
                    <?php if ($bewertung): ?>
                        <p>EPA: <strong><?= htmlspecialchars($bewertung['EPA-Bewertung'])?></strong>    BÄK: <strong><?= htmlspecialchars($bewertung['BÄK-Bewertung'])?></strong></p>
                        <br>
                        <p class="kat"><?= htmlspecialchars($bewertung['Bewertung']) ?></p>
                    <?php else: ?>
                        <p>Sie haben noch keine Bewertung erhalten.</p>
                    <?php endif; ?>
                </div>
                <br>
            </div>
        </main>
    </div>
    <script src="script.js">
    </script>
</body>
</html>