<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Redirect, wenn der Benutzer nicht angemeldet ist
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$mysqli = require __DIR__ . "/connection.php";
$feedback = ""; // Feedback-Variable initialisieren

// Begrüßung: Aktuellen Benutzernamen abrufen
$sql_user = "SELECT username FROM Userdaten_Hash WHERE id = ?";
$stmt = $mysqli->prepare($sql_user);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();
$stmt->close();

// Liste aller Benutzer abrufen
$sql_users = "SELECT id, vorname, nachname FROM Userdaten_Hash";
$users_result = $mysqli->query($sql_users);

// Benutzer-ID aus POST abrufen (Sicherheitsprüfung)
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

// Tätigkeitsbewertung absenden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taetigkeit_select'], $_POST['bewertung'])) {
    $taetigkeit_id = intval($_POST['taetigkeit_select']);
    $bewertung = trim($_POST['bewertung']);

    // Überprüfen, ob der Eintrag existiert
    $sqlCheck = "SELECT * FROM Durchführung WHERE `user-id` = ? AND `tätigkeit-id` = ?";
    $stmtCheck = $mysqli->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $user_id, $taetigkeit_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $sqlUpdate = "UPDATE Durchführung SET Bewertung = ? WHERE `user-id` = ? AND `tätigkeit-id` = ?";
        $stmtUpdate = $mysqli->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sii", $bewertung, $user_id, $taetigkeit_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
        $feedback = "Eintrag erfolgreich aktualisiert.";
    }
    $stmtCheck->close();
}

// Tätigkeiten für Dropdown abrufen, wenn eine Benutzer-ID ausgewählt wurde
if ($user_id !== null) {
    $sql_taetigkeiten = "SELECT `Tätigkeit-id`, Name FROM `Durchführung` JOIN Taetigkeiten ON Durchführung.`Tätigkeit-id` = Taetigkeiten.ID WHERE `User-ID` = ?";
    $stmt = $mysqli->prepare($sql_taetigkeiten);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $taetigkeiten_result = $stmt->get_result();
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewertung</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="fixed-header-drawer">
        <header class="layout-header">
            <div class="header-row">
                <div class="button-icon-menu" id="menuButton">
                    <i class="material-icons">menu</i>
                </div>
                <span class="layout-title">Bewertung</span>

                <div class="header-right">
                    <span class="username-text">Hallo, Prof. <?= htmlspecialchars($current_user["username"]) ?> </span>
                    <img src="images/icon_user_white.png" alt="Logo" class="icon-user" id="icon_user">
                </div>
            </div>
            <div class="layout-drawer" id="drawer">
                <nav class="navigation">
                    <a class="navigation-link" href="lehrer_startseite.php">Startseite</a>
                    <a class="navigation-link" href="">Suche</a>
                    <a class="navigation-link" href="tätigKatalog.php">Tätigkeitenkatalog</a>
                    <a class="navigation-link" href="">Profil</a>
                    <a class="navigation-link" href="">Einstellungen</a>
                    <a class="navigation-link" href="">Hilfe</a>
                    <hr class="navigation-divider">
                    <a class="navigation-link" href="logout.php">Log out</a>
                </nav>
            </div>
        </header>

        <main class="layout-content">
            <div class="Page-content">
                <div class="bewertungen">
                    <h2>Tätigkeitsbewertung</h2>
                </div></br>
                <div class="dokumentation">
                    <h3>Bewertung der Tätigkeit</h3></br>
                    <form method="POST" action="">
                        <!-- Auswahl Benutzer -->
                        <select name="user_id" id="user_id" class="styled-select" required onchange="this.form.submit()">
                            <option value="" disabled selected>Student:in wählen</option>
                            <?php while ($user = $users_result->fetch_assoc()): ?>
                                <option value="<?= $user['id'] ?>" <?= ($user['id'] == $user_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($user['nachname'] . " " . $user['vorname']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <!-- Auswahl Tätigkeit -->
                        <select name="taetigkeit_select" id="taetigkeit_select" class="styled-select" required>
                            <option value="" disabled selected>Wählen Sie eine Tätigkeit</option>
                            <?php if (isset($taetigkeiten_result)): ?>
                                <?php while ($taetigkeit = $taetigkeiten_result->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($taetigkeit['Tätigkeit-id']) ?>">
                                        <?= htmlspecialchars($taetigkeit['Name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select></br></br>

                        <textarea id="bewertung" name="bewertung" style="width: 100%; height: 200px;"
                            placeholder="Schreiben Sie hier den Text der Bewertung"></textarea><br>

                        <input type="submit" value="Abschicken">
                    </form>
                </div>

            </div>
        </main>
    </div>

    <script>
       document.getElementById('menuButton').addEventListener('click', function() {
            document.body.classList.toggle('drawer-open');
        });
        document.addEventListener('click', function(event) {
            var drawer = document.getElementById('drawer');
            var menuButton = document.getElementById('menuButton');

            if(!drawer.contains(event.target) && !menuButton.contains(event.target)) {
                document.body.classList.remove('drawer-open');
            }
        });
        document.getElementById('icon_user').addEventListener('click', function() {
             // Debugging output funktioniert
            const dropdown = document.getElementById('userDropdown');
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        });

        // Close dropdown if click outside of it
        document.addEventListener('click', function(event) {
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