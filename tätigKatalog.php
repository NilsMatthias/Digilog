<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/connection.php";

    $sql = "SELECT * FROM Userdaten_Hash WHERE id = {$_SESSION['user_id']}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if (isset($_GET['sortieren'])) {
        switch ($_GET['sortieren']) {
            case "Name_ASC":
                $sortierung = "Name ASC";
                break;
            case "Name_DESC":
                $sortierung = "Name DESC";
                break;
            case "Kategorie_ASC":
                $sortierung = "Kategorie ASC";
                break;
            default:
                $sortierung = "Name ASC";
        }
    }
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
    $sql_taetigkeit = "SELECT * FROM Taetigkeiten WHERE `name` LIKE ? ORDER BY $sortierung";
    $stmt = $mysqli->prepare($sql_taetigkeit);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result(); // Ergebnis für den gesamten Datensatz
    $stmt->close();

    /* if ($result->num_rows == 0) {
         die("Keine Tätigkeiten gefunden.");
     }
         */
    $noResults = ($result->num_rows == 0);

    // Die Ergebnisse in einer Variablen speichern, um sie in der HTML-Ausgabe zu verwenden
    $taetigkeitenResult = $result;

    $sortierung = "Kategorie ASC";
    $suchbegriff = "";
    $suchklausel = "";

    $sortierung = "Name ASC";  // Standardwert


    $sql_taetigkeit = "SELECT * FROM Taetigkeiten WHERE `name` LIKE ? ORDER BY $sortierung";
    $stmt = $mysqli->prepare($sql_taetigkeit);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
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
    <title>Das digitale Logbuch - <?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : 'Tätigkeiten' ?>
    </title>
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
                <span class="layout-title">Tätigkeitskatalog</span>

                <div class="header-right">
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
                <div class="tätigkeiten">
                    <h2>Tätigkeiten</h2>
                    <hr /><br>
                    <form action="" method="get">
                        <div class="search-bar">
                            <input type="text" id="searchInput" class="styled-input styled-input-tät" name="search"
                                placeholder="Tätigkeiten suchen..."
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            <input type="submit" value="suchen">
                        </div>

                        <!-- Hidden input für die Sortierung, um den Wert zu übergeben -->
                        <input type="hidden" name="sortieren"
                            value="<?= isset($_GET['sortieren']) ? htmlspecialchars($_GET['sortieren']) : 'Name_ASC' ?>">

                    </form>
                    <div class="sort-bar">
                        <form method="get" action="">
                            <label for="sortSelect">Sortieren nach:</label>
                            <select name="sortieren" id="sortieren" class="styled-select-search"
                                onchange="this.form.submit()">
                                <option value="Name_ASC" <?= isset($_GET['sortieren']) && $_GET['sortieren'] == 'Name_ASC' ? 'selected' : '' ?>>Name (aufsteigend)</option>
                                <option value="Name_DESC" <?= isset($_GET['sortieren']) && $_GET['sortieren'] == 'Name_DESC' ? 'selected' : '' ?>>Name (absteigend)</option>
                                <option value="Kategorie_ASC" <?= isset($_GET['sortieren']) && $_GET['sortieren'] == 'Kategorie_ASC' ? 'selected' : '' ?>>Kategorie</option>
                            </select>
                        </form>
                    </div>
                    <br>
                    <div class="information-link">
                        <?php
                        if ($_GET['sortieren'] == "Name_ASC" || $_GET['sortieren'] == "Name_DESC") {
                            echo "<h3>Bezeichnung der Tätigkeiten</h3>";
                        }
                        ?>
                    </div>
                    <?php if ($noResults): ?>
                        <p class="error-message">...</p>
                        <div class="error-message">
                            <p>Keine Tätigkeiten gefunden. Bitte versuchen Sie es erneut.</p>
                        </div>
                    <?php endif; ?>

                    <?php
                    $currentCategory = null;

                    // Die Tätigkeiten durchgehen und ausgeben
                    while ($taetigkeit = $taetigkeitenResult->fetch_assoc()):
                        // Bei Wechsel der Kategorie eine neue Überschrift ausgeben
                        if ($_GET['sortieren'] === 'Kategorie_ASC' && $currentCategory !== $taetigkeit['Kategorie']) {
                            $currentCategory = $taetigkeit['Kategorie'];
                            echo "<h3>" . htmlspecialchars($currentCategory) . "</h3>";
                        }
                        // Tätigkeit mit Link zur Detailseite ausgeben
                        echo "<p><a class='information-link informationHoover' href='tätigSubpage.php?id=" . $taetigkeit['ID'] . "'>" . htmlspecialchars($taetigkeit['Name']) . "</a></p>";
                    endwhile;
                    ?>

                </div>
            </div>
        </main>
    </div>

    <script src="script.js">
    </script>
</body>

</html>