<?
session_start();

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php"); // Redirect if not logged in
  exit;
}

$mysqli = require __DIR__ . "/connection.php";

// Get ID from URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM Taetigkeiten WHERE ID = $id";
  $result = $mysqli->query($sql);
  $taetigkeit = $result->fetch_assoc();

  if (!$taetigkeit) {
      die("Tätigkeit nicht gefunden.");
  }
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
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet"
    />
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <div class="fixed-header-drawer">
      <header class="layout-header">
        <div class="header-row">
          <div class="button-icon-menu" id="menuButton">
            <i class="material-icons">menu</i>
          </div>
          <span class="layout-title">Startseite</span>

          <div class="layout-spacer"></div>
          <div class="textfield-expandable-floating-label">
            <!--label class="button-icon-search" for="fixed-header-drawer-exp">
              <i class="material-icons">search</i>
            </label-->
            <div class="textfield-expandable-holder">
              <input
                class="textfield-input"
                type="text"
                name="sample"
                id="fixed-header-drawer-exp"
              />
            </div>
          </div>
        </div>
      </header>
      <div class="layout-drawer" id="drawer">
        <nav class="navigation">
                <a class="navigation-link" href="startseite.php">Startseite</a>
                <a class="navigation-link" href="">Suche</a>
                <a class="navigation-link" href="tätigKatalog.php">Tätigkeitenkatalog</a>
                <a class="navigation-link" href="">Profil</a>
                <a class="navigation-link" href="">Einstellungen</a>
                <a class="navigation-link" href="">Hilfe</a>
        </nav>
      </div>
      <main class="layout-content">
        <div class="Page-content">
          <div class="tätigkeit">
          <h2><?= htmlspecialchars($taetigkeit['Name']) ?> - Dokumentation</h2>
          </div>
          <br>
          <div class="dokumentation">
              <h3>Dokumentation der Tätigkeit</h3>
              <form action="save_dokumentation.php" method="POST">
                  <textarea id="dokumentation" name="dokumentation" rows="5" cols="44" placeholder="Schreiben Sie den Text der Dokumentation"></textarea><br>
                  <input type="hidden" name="taetigkeit_id" value="<?= $taetigkeit['ID'] ?>">
                  <input type="submit" value="Abschicken">
              </form>
          </div>
        </div>
      </main>
    </div>
    <script>
      document
        .getElementById("menuButton")
        .addEventListener("click", function () {
          document.body.classList.toggle("drawer-open");
        });
    </script>
  </body>
</html>
