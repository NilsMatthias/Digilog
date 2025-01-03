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

// Benutzer-ID und Tätigkeit-ID aus GET abrufen
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$taetigkeit_id = isset($_GET['taetigkeit_id']) ? intval($_GET['taetigkeit_id']) : null;
$bewertung = "";
$bewertung_baek = "";
$bewertung_epa = "";

// Bewertung laden, wenn Benutzer und Tätigkeit ausgewählt sind
if ($user_id && $taetigkeit_id) {
    $sql_bewertung = "SELECT Bewertung, `BÄK-Bewertung` AS baek, `EPA-Bewertung` AS epa FROM Durchführung 
                      WHERE `user-id` = ? AND `tätigkeit-id` = ?";
    $stmt = $mysqli->prepare($sql_bewertung);
    $stmt->bind_param("ii", $user_id, $taetigkeit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $bewertung = $row['Bewertung'] ?? '';
        $bewertung_baek = $row['baek'] ?? '';
        $bewertung_epa = $row['epa'] ?? '';
    }
    $stmt->close();
}

// Bewertung speichern (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bewertung'], $_POST['baek'], $_POST['epa'], $_POST['taetigkeit_select'], $_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $taetigkeit_id = intval($_POST['taetigkeit_select']);
    $bewertung = trim($_POST['bewertung']);
    $baek = intval($_POST['baek']);
    $epa = $_POST['epa'];
    $lehrer_id = $_SESSION["user_id"];

    $sqlUpdate = "UPDATE Durchführung 
                  SET Bewertung = ?, `BÄK-Bewertung` = ?, `EPA-Bewertung` = ?, `Lehrer-ID` = ? 
                  WHERE `user-id` = ? AND `tätigkeit-id` = ?";
    $stmtUpdate = $mysqli->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sisiii", $bewertung, $baek, $epa, $lehrer_id, $user_id, $taetigkeit_id);
    $stmtUpdate->execute();
    $stmtUpdate->close();
    $feedback = "Bewertung erfolgreich gespeichert.";
}

// Liste aller Benutzer abrufen
$sql_users = "SELECT id, vorname, nachname FROM Userdaten_Hash WHERE rolle=3";
$users_result = $mysqli->query($sql_users);

// Tätigkeiten für Dropdown abrufen, wenn eine Benutzer-ID ausgewählt wurde
if ($user_id !== null) {
    $sql_taetigkeiten = "SELECT `Tätigkeit-id`, Name 
                         FROM `Durchführung` 
                         JOIN Taetigkeiten ON Durchführung.`Tätigkeit-id` = Taetigkeiten.ID 
                         WHERE `User-ID` = ?";
    $stmt = $mysqli->prepare($sql_taetigkeiten);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $taetigkeiten_result = $stmt->get_result();
    $stmt->close();
}

// Begrüßung: Aktuellen Benutzernamen abrufen
$sql_user = "SELECT username FROM Userdaten_Hash WHERE id = ?";
$stmt = $mysqli->prepare($sql_user);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();
$stmt->close();
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
                    <a class="navigation-link" href="suche.php">Suche</a>
                    <a class="navigation-link" href="tätigKatalog.php?sortieren=Name_ASC">Tätigkeitenkatalog</a>
                    <a class="navigation-link" href="einstellungen.php">Einstellungen</a>
                    <a class="navigation-link" href="">Hilfe</a>
                    <hr class="navigation-divider">
                    <a class="navigation-link" href="logout.php">Log out</a>
                </nav>
            </div>
        </header>
        <div class="dropdown-menu" id="userDropdown">
            <a href="startseite.php">Zurück zur Startseite</a>
            <a href="einstellungen.php">Einstellungen</a>
            <a class="navigation-link" href="logout.php">Log out</a>
        </div>

        <main class="layout-content">
            <div class="Page-content">
                <div class="bewertungen">
                    <h2>Tätigkeitsbewertung</h2>
                </div></br>
                <div class="dokumentation">
                    <h3>Bewertung der Tätigkeit</h3></br>
                    <form method="POST" action="">
                        <!-- Auswahl Benutzer -->
                        <select name="user_id" id="user_id" class="styled-select" required onchange="window.location.href='?user_id=' + this.value">
                            <option value="" disabled selected>Student:in wählen</option>
                            <?php while ($user = $users_result->fetch_assoc()): ?>
                                <option value="<?= $user['id'] ?>" <?= ($user['id'] == $user_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($user['nachname'] . " " . $user['vorname']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <!-- Auswahl Tätigkeit -->
                        <?php if($user_id!== null): ?>
                            <?php if (isset($taetigkeiten_result) && $taetigkeiten_result->num_rows > 0): ?>
                                <select name="taetigkeit_select" id="taetigkeit_select" class="styled-select styled-select-taetigkeit" required onchange="window.location.href='?user_id=<?= $user_id ?>&taetigkeit_id=' + this.value">
                                    <option value="" disabled selected>Wählen Sie eine Tätigkeit</option>
                                    <?php while ($taetigkeit = $taetigkeiten_result->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($taetigkeit['Tätigkeit-id']) ?>" <?= ($taetigkeit['Tätigkeit-id'] == $taetigkeit_id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($taetigkeit['Name']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                
                            <?php else: ?>
                                <p>Der ausgewählte Student hat noch keine Tätigkeiten.</p>
                            <?php endif; ?>
                            <?php else: ?>
                                <p>Bitte wählen Sie einen Studenten aus, um die Tätigkeiten anzuzeigen.</p>
                        <?php endif; ?>

                        <!-- Bewertung Felder -->
                        <?php if ($user_id && $taetigkeit_id): ?>
                            <label for="baek">BÄK:</label>
                            <select name="baek" id="baek" class="styled-select styled-select-taetigkeit" required>
                                <option value="" disabled selected>Wählen</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($bewertung_baek == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <label for="epa">EPA:</label>
                            <select name="epa" id="epa" class="styled-select styled-select-taetigkeit"required>
                                <option value="" disabled selected>Wählen</option>
                                <?php foreach (['1', '2', '3a', '3b'] as $epa): ?>
                                    <option value="<?= $epa ?>" <?= ($bewertung_epa == $epa) ? 'selected' : '' ?>><?= $epa ?></option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <br>
                                <br>

                            <textarea id="bewertung" name="bewertung" style="width: 100%; height: 200px;"
                                placeholder="Schreiben Sie hier den Text der Bewertung"><?= htmlspecialchars($bewertung) ?></textarea>
                            <br>
                        <?php endif; ?>
                        <br>

                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                        <input type="submit" value="Speichern">
                    </form>
                </div>
                <?php if (!empty($feedback)): ?>
                    <p><?= htmlspecialchars($feedback) ?></p>
                <?php endif; ?>
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