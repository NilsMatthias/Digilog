<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$mysqli = require __DIR__ . "/connection.php";
$buttonBewertung = "Speichern";
$bewertung_text = "Schreiben Sie hier den Text der Bewertung.";

// Получаем имя текущего пользователя для приветствия
$sql_user = "SELECT username FROM Userdaten_Hash WHERE id = ?";
$stmt = $mysqli->prepare($sql_user);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();
$stmt->close();

// Получаем список всех пользователей
$sql_users = "SELECT id, vorname, nachname FROM Userdaten_Hash";
$users_result = $mysqli->query($sql_users);

// Обработка выбора пользователя
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$durchfuehrung_result = null;
if ($user_id) {
    // Получение записей из таблицы Durchführung для выбранного пользователя
    $sql_durchfuehrung = "SELECT t.Name AS taetigkeit_name, d.dokumentation_text, d.created_at 
                          FROM Durchführung d
                          JOIN Taetigkeiten t ON d.taetigkeit_id = t.ID
                          WHERE d.user_id = ?";
    $stmt = $mysqli->prepare($sql_durchfuehrung);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $durchfuehrung_result = $stmt->get_result();
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

        </header>

        <main class="layout-content">
            <div class="Page-content">
                <div class="bewertungen">
                    <h2>Tätigkeitsbewertung</h2>

                </div></br>
                <div class="dokumentation">
                    <h3>Bewertung der Tätigkeit</h3></br>
                    <form method="POST" action="bewertung_speichern.php">
                        <!-- Выбор Benutzer -->
                        <select name="user_id" id="user_id" class="styled-select" required>
                            <option value="" disabled selected>Student:in wählen</option>
                            <?php while ($user = $users_result->fetch_assoc()): ?>
                                <option value="<?= $user['id'] ?>" <?= ($user['id'] == $user_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($user['nachname'] )," ",($user['vorname']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <!-- Выбор Tätigkeit -->
                        <select name="taetigkeit" id="taetigkeit" class="styled-select" required>
                            <option value="" disabled selected>Wählen Sie eine Tätigkeit</option>
                            <?php
                            $sql_taetigkeiten = "SELECT ID, Name FROM Taetigkeiten";
                            $taetigkeiten_result = $mysqli->query($sql_taetigkeiten);
                            while ($taetigkeit = $taetigkeiten_result->fetch_assoc()): ?>
                                <option value="<?= $taetigkeit['ID'] ?>">
                                    <?= htmlspecialchars($taetigkeit['Name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select></br></br>

                         <form action="" method="POST">
                            <textarea id="dokumentation" name="dokumentation"  style="width: 100%; height: 200px;" rows="5" cols="44" placeholder="<?= htmlspecialchars($bewertung_text ?? '') ?>"></textarea>
                            <input type="hidden" name="taetigkeit_id" value="<?= $taetigkeit['ID'] ?>">
                            <input type="submit" value="<?=htmlspecialchars(string: $buttonBewertung) ?>">
                        </form>
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