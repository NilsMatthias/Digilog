<?php
  session_start();

  if (isset($_SESSION["user_id"])) {
     
    $mysqli = require __DIR__ . "/connection.php";

    $sql = "SELECT * FROM Userdaten_Hash
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    $taetigkeiten = "SELECT * FROM Taetigkeiten";
    $taetigkeitenResult = $mysqli->query($taetigkeiten);

    if (!$taetigkeitenResult) {
        die("Fehler beim Abrufen der Tätigkeiten.");
    }
  }
  
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
                    <span class="username-text">Hallo, <?= htmlspecialchars($user["username"]) ?> </span>
                    <img src="icon_user_white.png" alt="Logo" class="icon-user">
                        
                    
                </div>
            </div>
        </header>
        <div class="layout-drawer" id="drawer">
            <nav class="navigation">
                <a class="navigation-link" href="startseite.php">Startseite</a>
                <a class="navigation-link" href="">Suche</a>
                <a class="navigation-link" href="">Ziele</a>
                <a class="navigation-link" href="">Aufgaben</a>
                <a class="navigation-link" href="">Hilfe</a>
                <hr class="navigation-divider">
                <a class="navigation-link" href="logout.php">Log out</a>

            </nav>
            
        </div>
        


        <main class="layout-content">
            <div class="Page-content">
            <div class="tätigkeiten">
            <h2>Tätigkeiten</h2>

            <?php while ($taetigkeit = $taetigkeitenResult->fetch_assoc()): ?>
                        <h4><a class="tätigkeiten-link" href="tätigkeiten.php?id=<?= $taetigkeit['ID'] ?>"><?= htmlspecialchars($taetigkeit['Name']) ?></a></h4>
                        <p><?= htmlspecialchars($taetigkeit['Beschreibung']) ?></p>
                    <?php endwhile; ?>
          </div></br>
          <div class="bewertungen">
            <h2>Bewertungen</h2>
            <hr />
            <p>Infusionen</p>
            <p>Steriles Abdecken</p>
          </div>

               
            </div>
        </main>
    </div>
    
    <script>
        document.getElementById('menuButton').addEventListener('click', function() {
            document.body.classList.toggle('drawer-open');
        });
    </script>
    
    </body>
    </html>