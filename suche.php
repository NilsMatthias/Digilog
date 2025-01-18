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

    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" :"%";
    $sql_taetigkeit = "SELECT * FROM `Userdaten_Hash` WHERE nachname LIKE ? OR vorname LIKE ? ORDER BY nachname ASC, vorname ASC";
    $stmt = $mysqli->prepare($sql_taetigkeit);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result(); // Ergebnis für den gesamten Datensatz
    $stmt->close();

    $noResults = ($result->num_rows == 0);
    $taetigkeitenResult = $result;

    $rolle = "";


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
                <span class="layout-title">Personen-Suche</span>

                <div class="header-right">
                    <span class="username-text userTextMobil">Hallo, <?= htmlspecialchars($user["username"]) ?> </span>
                    <img src="images/icon_user_white.png" alt="Logo" class="icon-user" id="icon_user">

                </div>
            </div>
        </header>
        <div class="dropdown-menu" id="userDropdown">
            <a href="lehrer_startseite.php">Zurück zur Startseite</a>
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
                <div class="information">
                    <h2>Hier kannst du Studierende und Lehrende suchen </h2>
                    <hr /></br>
                    <form action="" method="get">
                        <div class="search-bar">

                            <input type="text" id="searchInput" class="styled-input input-search" name="search"
                                placeholder="Name eingeben..."
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            <input type="submit" value="suchen">
                        </div>


                    </form><br>
                    <!--?php echo "Suchbegriff: " . $search; 
                    if (!isset($_GET['search']) || empty($_GET['search'])) {
                        echo "Keine Suchanfrage eingegeben.";
                    }?-->

                    <?php if (!$noResults): ?>
                        <div class="results">
                            <h3>Suchergebnisse:</h3>
                            <div class="search-rand">
                                <?php while ($row = $result->fetch_assoc()):
                                    if ($row['rolle'] == 2) {
                                        $rolle = "Lehrende";
                                    } elseif ($row['rolle'] == 3) {
                                        $rolle = "Studierende";
                                    } ?>

                                    <div class="search-results-names">
                                        <?php echo "<p><a class='information-link informationHoover' href='profil.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['vorname'])
                                            . " " . htmlspecialchars($row['nachname']) . " (" . (htmlspecialchars($rolle)) . ") " . "</a></p>";
                                        ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php if ($noResults): ?>
                        <p class="error-message">...</p>
                        <div class="error-message">
                            <p>Keine Personen gefunden. Bitte versuchen Sie es erneut.</p>
                        </div>
                    <?php endif; ?>

                </div></br>

            </div>
        </main>
    </div>

    <script src="script.js">
    </script>

</body>

</html>