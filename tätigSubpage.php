<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
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


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM Taetigkeiten WHERE ID = $id";
  $result = $mysqli->query($sql);

  if ($result && $result->num_rows > 0) {
    $taetigkeit = $result->fetch_assoc();
  } else {
    die("Tätigkeit nicht gefunden.");
  }

  $sqlGetValues = "SELECT Beschreibung, Selbstreflexion, Bewertung, `EPA-Bewertung`, `BÄK-Bewertung`, `Lehrer-ID`, u.vorname, u.nachname, Datum, DATE_FORMAT(Datum, '%d.%m.%Y') AS datum_formatiert
FROM Durchführung d 
LEFT JOIN `Userdaten_Hash` u ON u.id = d.`Lehrer-ID` 
WHERE `User-ID` = ? AND `Tätigkeit-ID` = ?";
  $stmt = $mysqli->prepare($sqlGetValues);
  $stmt->bind_param("ii", $user_id, $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($row) {
    $datum = $row['datum_formatiert'];
    $epa_bewertung = $row['EPA-Bewertung'] ?? "Noch keine Bewertung";
    $baek_bewertung = $row['BÄK-Bewertung'] ?? "Noch keine Bewertung";
    $generelle_bewertung = $row['Bewertung'] ?? "Noch keine Bewertung";
    if (!empty($row['Beschreibung'])) {
      $dokumentation_text = $row['Beschreibung'];
      $buttonText = "Aktualisieren";
    }
    if (!empty($row['Selbstreflexion'])) {
      $self_reflection_text = $row['Selbstreflexion'];
      $buttonSelfRefText = "Aktualisieren";
    }
  } else {
    $epa_bewertung = "Noch keine Bewertung";
    $baek_bewertung = "Noch keine Bewertung";
    $generelle_bewertung = "Noch keine Bewertung";
  }
  $stmt->close();
} else {
  die("Ungültige ID.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taetigkeit_id'])) {
  $taetigkeit_id = $_POST['taetigkeit_id'];
  $beschreibung = isset($_POST['dokumentation']) ? trim($_POST['dokumentation']) : null;
  $selbstreflexion = isset($_POST['selbstreflexion']) ? trim($_POST['selbstreflexion']) : null;
  $date = date("Y-m-d H:i:s");

  if (!is_numeric($taetigkeit_id)) {
    $feedback = "Ungültige Tätikgeits-ID";
  }
  if (empty($beschreibung) && empty($selbstreflexion)) {
    $feedback = "Die Dokumentation und/oder Selbstreflexion darf nicht leer sein.";
  } else {
    $sqlCheck = "SELECT * FROM Durchführung WHERE `User-ID` = ? AND `Tätigkeit-ID` = ?";
    $stmtCheck = $mysqli->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $user_id, $taetigkeit_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
      $sqlUpdate = "UPDATE Durchführung SET 
        Beschreibung = IF(? IS NOT NULL, ?, Beschreibung), 
        Selbstreflexion = IF(? IS NOT NULL, ?, Selbstreflexion), 
        Datum = ? 
        WHERE `User-ID` = ? AND `Tätigkeit-ID` = ?";
      $stmtUpdate = $mysqli->prepare($sqlUpdate);
      $stmtUpdate->bind_param(
        "sssssii",
        $beschreibung,
        $beschreibung,
        $selbstreflexion,
        $selbstreflexion,
        $date,
        $user_id,
        $taetigkeit_id
      );
      $stmtUpdate->execute();
      $stmtUpdate->close();

      $feedback = "Eintrag erfolgreich aktualisiert.";
    } else {
      $sqlInsert = "INSERT INTO Durchführung (`Lehrer-ID`, `User-ID`, `Tätigkeit-ID`, Beschreibung, Selbstreflexion, Datum) 
                  VALUES (?, ?, ?, ?, ?, ?)";
      $stmtInsert = $mysqli->prepare($sqlInsert);
      $stmtInsert->bind_param(
        "iiisss",
        $lehrer_id,
        $user_id,
        $taetigkeit_id,
        $beschreibung,
        $selbstreflexion,
        $date
      );
      $stmtInsert->execute();
      $stmtInsert->close();

      $feedback = "Eintrag erfolgreich gespeichert.";
    }
    $stmtCheck->close();

    $sqlGetValues = "SELECT Beschreibung, Selbstreflexion, Datum FROM Durchführung WHERE `User-ID` = ? AND `Tätigkeit-ID` = ?";
    $stmtFetch = $mysqli->prepare($sqlGetValues);
    $stmtFetch->bind_param("ii", $user_id, $taetigkeit_id);
    $stmtFetch->execute();
    $resultFetch = $stmtFetch->get_result();
    $rowFetch = $resultFetch->fetch_assoc();

    if ($rowFetch) {
      $dokumentation_text = $rowFetch['Beschreibung'] ?? $dokumentation_text;
      $self_reflection_text = $rowFetch['Selbstreflexion'] ?? $self_reflection_text;
      $datum = $rowFetch['Datum'] ?? $datum;

      if (!empty($beschreibung)) {
        $buttonText = "Aktualisieren";
      }

      if (!empty($selbstreflexion)) {
        $buttonSelfRefText = "Aktualisieren";
      }
    }
    $stmtFetch->close();
  }
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
        <span class="layout-title">Tätigkeitsbearbeitung</span>

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
          
          <?php if (isset($taetigkeit)): ?>
            <!-- Wenn $taetigkeit definiert ist, zeige die Daten an -->
            <h2><?= htmlspecialchars($taetigkeit['Name']) ?> <!--- Dokumentation--></h2>
          <?php else: ?>
            <!-- Falls keine Tätigkeit gefunden wird -->
            <p>Keine Tätigkeit gefunden oder ungültige ID übergeben.</p>
            <?php endif; ?>
          <?php if(isset($datum)):?>
            Zuletzt bearbeitet: <?= htmlspecialchars($datum) ?>
          <?php endif; ?>
          <!--p class="deadline">Deadline: 10.12.2024, 23:59</p-->
          <!--p class="lehrer">Prof. Dr. Lorem</p-->
        </div>
        <br>
        <div class="beschreibung">
          <h3>Kategorie</h3>
          <?php if (isset($taetigkeit)): ?>
            <p class="kat">
              <?= htmlspecialchars(string: $taetigkeit['Kategorie']) ?>
            <?php else: ?>
            <p>Sie haben noch keine Dokumentation geschrieben.</p>
          <?php endif; ?>
          </p>
        </div>
        <br>
        <div class="dokumentation">
          <div class="flex">
            <h3>Dokumentation der Tätigkeit</h3>
            <?php
            if ($buttonText == "Aktualisieren") {
              echo '<div class="check-mark">
                            <img src="images/correct.png" alt="Logo" class="icon-user">
                        </div>';
            }
            ?>
          </div>
          <!--?php if (!empty($dokumentation_text)): ?>
                  <div class="saved-dokumentation">
                      <p>Sie haben bereits eine Dokumentation abgegeben:</p>
                      <p><!?= nl2br(htmlspecialchars($dokumentation_text)) ?></p>
                  </div>
                <!?php endif; ?-->
          <br>

          <form id="textarea" action="" method="POST">
            <textarea id="dokumentation-textarea" name="dokumentation" rows="5" cols="44"
              placeholder="<?= htmlspecialchars(string: $placeHolderText) ?>"><?= htmlspecialchars(string: $dokumentation_text) ?></textarea><br><br>
            <input type="hidden" name="taetigkeit_id" value="<?= $taetigkeit['ID'] ?>">
            <input type="submit" value="<?= htmlspecialchars(string: $buttonText) ?>">
          </form>
        </div><br>

        <div class="dokumentation">
          <div class="flex">
            <h3>Selbstreflexion</h3>
            <?php
            if ($buttonSelfRefText == "Aktualisieren") {
              echo '<div class="check-mark">
                            <img src="images/correct.png" alt="Logo" class="icon-user">
                        </div>';
            }
            ?>

          </div>
          <br>

          <form action="" method="POST">
            <textarea id="dokumentation-textarea" name="selbstreflexion" rows="5" cols="44"
              placeholder="<?= htmlspecialchars($placeHolderTextRef) ?>"><?= htmlspecialchars(string: $self_reflection_text) ?></textarea><br><br>
            <input type="hidden" name="taetigkeit_id" value="<?= $taetigkeit['ID'] ?>">
            <!--label for="file-upload">Datei hochladen:</label>
            <input type="file" id="file-upload" name="file-upload"><br><br-->
            <input type="submit" value="<?= htmlspecialchars(string: $buttonSelfRefText) ?>">
          </form>
        </div><br>
        <?php if($generelle_bewertung != "Noch keine Bewertung"): ?>
        <div class="dokumentation">
          <h3>Bewertung</h3>
          <p><?= htmlspecialchars($generelle_bewertung) ?></p>
          <p><strong>EPA-Bewertung:</strong> <?= htmlspecialchars($epa_bewertung) ?></p>
          <p><strong>BÄK-Bewertung:</strong> <?= htmlspecialchars($baek_bewertung) ?></p><br>
          <p>Erhalten von Prof. <?= htmlspecialchars($row['vorname']) ?> <?=htmlspecialchars($row['nachname'])?></p>

        </div>
        <?php else: ?>
          <p><?= htmlspecialchars($generelle_bewertung) ?></p>
        <?php endif; ?>
      </div>
    </main>
  </div>
  <script src="script.js">
    </script>
</body>

</html>