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

    $letzteBewertungSql = "
    SELECT u.vorname, u.nachname, t.ID as TID, t.Name, t.Kategorie, d.Datum, u.id as UID, DATE_FORMAT(d.Datum, '%d.%m.%Y') AS datum_formatiert 
    FROM Durchführung d
    JOIN Taetigkeiten t ON d.`Tätigkeit-ID` = t.ID
    JOIN `Userdaten_Hash`u ON d.`User-ID` = u.id
    WHERE d.`Lehrer-ID` = ?
    ORDER BY d.Datum DESC
    LIMIT 6";

    $stmt = $mysqli->prepare($letzteBewertungSql);
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $letzteBewertungSql = $stmt->get_result();


    
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
              <div class="tätigkeiten letzte-tätigkeiten">
                    <h2>Bewertungstool</h2>
                    <hr />
                    <p><a class="bewertungen-link bewertungen-link-bold" href="bewertung.php">Vergabe einer Note an den
                            Studenten</a></p>
                </div></br>
                <div class="tätigkeiten letzte-tätigkeiten">
                        <h2>Bereits bewertete Studierende</h2>
                        <hr /><br>
                        <?php if ($letzteBewertungSql->num_rows > 0): ?>
                            <?php $counter = 1;
                            while ($bewertung = $letzteBewertungSql->fetch_assoc()): ?>
                                <h3>Tätigkeit <?= $counter ?></h3>
                                <p><a class="tätigkeiten-link tätigkeiten-link-bold"
                                        href="bewertung.php?user_id=<?= $bewertung['UID'] ?>&taetigkeit_id=<?= $bewertung['TID'] ?>"><?= htmlspecialchars($bewertung['Name']) ?></a>
                                </p>
                                <p><?= htmlspecialchars($bewertung['Kategorie']) ?></p>
                                <p><?= htmlspecialchars($bewertung['vorname']) ?> <?= htmlspecialchars($bewertung['nachname']) ?></p>
                                <?php $counter++;
                            endwhile; ?>
                        <?php else: ?>
                            <p>Keine bearbeiteten Tätigkeiten gefunden</p>
                        <?php endif; ?>

                    </div>
            </div>
        </main>
    </div>

    <script src="script.js">
    </script>

</body>

</html>