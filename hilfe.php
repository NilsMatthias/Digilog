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
                <span class="layout-title">Hilfe</span>

                <div class="header-right">
                    <!--span class="username-text userTextMobil">Hallo, <?= htmlspecialchars($user["username"]) ?> </span-->
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
                    <h2>Häufig gestellte Fragen (FAQ)</h2>
                    <hr /><br />
                    <h3>Für Studierende</h3>
                    <ul>
                        <li><b>Wie registriere ich mich?</b><br><br> Klicken Sie auf "Registrieren" und füllen Sie das
                            Anmeldeformular aus.</li><br>
                        <li><b>Wie starte ich eine Tätigkeit?</b><br><br> Navigieren Sie zur <a
                                class="information-link-bold" href="startseite.php">Startseite</a> und
                            wählen Sie eine Tätigkeit aus. Geben Sie Ihre Dokumentation und Reflexion direkt in die
                            vorgesehenen Felder ein.</li><br>
                        <li><b>Wie sehe ich meine Bewertungen?</b><br><br> Besuchen Sie den Bereich <a
                                class="information-link-bold" href="startseite.php">Startseite</a> in
                            Ihrem Profil. Klicken Sie dann im Feld „Bewertungen“ auf die Tätigkeit, die Sie
                            interessiert.
                        </li><br>
                        <li><b>Wie kann ich mein Profil ändern?</b><br><br> Gehen Sie zu <a
                                class="information-link-bold" href="einstellungen.php">Einstellungen</a> und bearbeiten
                            Sie
                            Ihre Daten.</li><br>
                        <li><b>Wie finde ich einen Dozenten oder Kommilitonen?</b><br><br> Nutzen Sie <a
                                class="information-link-bold" href="suche.php"> die Suchfunktion</a>
                            auf
                            der Plattform.</li><br>
                        <li><b>Wie kann ich einen Dozenten kontaktieren?</b><br><br> Sie können Ihren Dozenten über die
                            in
                            seinem Profil angegebene E-Mail-Adresse finden. Sie finden alle Studenten und Dozenten inkl.
                            ihrer Kontaktdaten in der <a class="information-link-bold"
                                href="suche.php">Suchfunktion</a>.
                        </li><br>
                    </ul>

                    <h3>Für Lehrende</h3>
                    <ul>
                        <li><b>Wie korrigiere ich Tätigkeiten?</b><br><br> Öffnen Sie den Bereich <a
                                class="information-link-bold" href="startseite.php">Startseite</a>, um die
                            eingereichten Dokumentationen und Reflexionen der Studierenden zu
                            bewerten.</li><br>
                        <li><b>Wie kontaktiere ich Studierende?</b><br><br> Nutzen Sie die
                            Kontaktinformationen, die in den Profilen der Studierenden angegeben sind. Sie können
                            Studierende über die <a class="information-link-bold" href="suche.php">Suche</a> aufrufen.
                        </li><br>
                    </ul>
                    </li><br>
                    </ul>

                    <h3>Technische Probleme</h3>
                    <ul>
                        <li><b>Ich habe mein Passwort vergessen. Was soll ich tun?</b><br><br> Nutzen Sie die Funktion
                            "Passwort vergessen" auf der Login-Seite.</li><br>
                        <li><b>Die Plattform lädt nicht. Was kann ich tun?</b><br><br> Überprüfen Sie Ihre
                            Internetverbindung und versuchen Sie, die Seite neu zu laden.</li><br>
                        <li><b>Wie kontaktiere ich den Support?</b><br><br> Schreiben Sie uns eine E-Mail an
                            <b>support@digilog.de</b>.
                        </li><br>
                    </ul>

                    <!--h3>Ungewöhnliche Probleme</h3><br>
                    <span class="taetigkeiten"><b>Profil:</b> <?= htmlspecialchars($user["username"]) ?>
                    </span><br><br>
                    <span class="taetigkeiten"><b>Email:</b> <?= htmlspecialchars($user["email"]) ?> </span><br><br>
                    <form method="post" action="submit_problem.php">
                        <label for="problem"><b>Problembeschreibung:</b></label><br><br>
                        <textarea id="problem" name="problem" class="full-width" rows="4"
                            placeholder="Beschreiben Sie Ihr Problem..." required></textarea><br><br>
                        <button type="submit">Absenden</button>
                    </form-->

                </div>
        </main>
    </div>

    <script src="script.js">
    </script>

</body>

</html>