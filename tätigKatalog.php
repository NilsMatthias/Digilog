<?php
  session_start();

  if (isset($_SESSION["user_id"])) {
     
    $mysqli = require __DIR__ . "/connection.php";

    $sql = "SELECT * FROM Userdaten_Hash
            WHERE id = {$_SESSION["user_id"]}";
            

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    $taetigkeiten = "SELECT * FROM Taetigkeiten $suchklausel ORDER BY  Kategorie ASC, Name ASC";
    $taetigkeitenResult = $mysqli->query($taetigkeiten);
    

    if (!$taetigkeitenResult) {
        die("Fehler beim Abrufen der Tätigkeiten: " . $mysqli->error);
    }
    $sortierung = "Kategorie ASC"; 
    $suchbegriff = "";
    $suchklausel = "";

if (isset($_GET['sortieren'])) {
    switch ($_GET['sortieren']) {
       
        /*case "Kategorie_DESC":
            $sortierung = "Kategorie DESC";
            break;
        */
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


if (isset($_GET['aktion']) && $_GET['aktion'] === 'suchen' && !empty($_GET['searchInput'])) {
    $suchbegriff = "%{$_GET['searchInput']}%";
    $suchklausel = "WHERE Name LIKE ? OR Kategorie LIKE ?";
}

    // Tätigkeiten abfragen mit Sortierung und optionaler Suche
    $taetigkeiten = "SELECT * FROM Taetigkeiten $suchklausel ORDER BY $sortierung";
    $taetigkeitenResult = $mysqli->query($taetigkeiten);

    if (!$taetigkeitenResult) {
        die("Fehler beim Abrufen der Tätigkeiten: " . $mysqli->error);
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
                <span class="layout-title">Tätigkeitenkatalog</span>

                <div class="header-right">
                    <!--span class="username-text">Hallo, <?= htmlspecialchars($user["username"]) ?> </span-->
                    <img src="icon_user_white.png" alt="Logo" class="icon-user">
                        
                    
                </div>
            </div>
        </header>
        <div class="layout-drawer" id="drawer">
            <nav class="navigation">
                <a class="navigation-link" href="startseite.php">Startseite</a>
                <a class="navigation-link" href="">Suche</a>
                <a class="navigation-link" href="">Ziele</a>
                <a class="navigation-link" href="tätigKatalog.php">Tätigkeitenkatalog</a>
                <a class="navigation-link" href="">Einstellungen</a>
                <a class="navigation-link" href="">Hilfe</a>
                <hr class="navigation-divider">
                <a class="navigation-link" href="logout.php">Log out</a>

            </nav>
            
        </div>
        


        <main class="layout-content">
            <div class="Page-content">
            <div class="tätigkeiten">
            <h2>Tätigkeiten</h2>
            <hr/><br>
            <form action="" method="get">
                <div class="search-bar">
                    <label for="searchInput">Tätigkeitssuche:</label>
                    <input type="hidden" name="aktion" value="suchen">
                    <input type="text" id="searchInput" class="styled-input" placeholder="Tätigkeiten suchen...">
                    <input type="submit" value="suchen" class="">
                </div>
            </form>
            <div class="sort-bar">
            <form method="get" action="">
            <label for="sortSelect">Sortieren nach:</label>
            <select name="sortieren" id="sortieren" class="styled-select" onchange="this.form.submit()">
                    <!--option value="Kategorie_DESC" <!-?= isset($_GET['sortieren']) && $_GET['sortieren'] == 'Kategorie_DESC' ? 'selected' : '' ?>>Kategorie (absteigend)</option-->
                    <option value="Name_ASC" <?= isset($_GET['sortieren']) && $_GET['sortieren'] == 'Name_ASC' ? 'selected' : '' ?>>Name (aufsteigend)</option>
                    <option value="Name_DESC" <?= isset($_GET['sortieren']) && $_GET['sortieren'] == 'Name_DESC' ? 'selected' : '' ?>>Name (absteigend)</option>
                    <option value="Kategorie_ASC" <?= isset($_GET['sortieren']) && $_GET['sortieren'] == 'Kategorie_ASC' ? 'selected' : '' ?>>Kategorie</option>
                </select>
            </form>
            </div>
            <br>
            <div class="tätigkeiten-link-bold">
                <?php 
                if($_GET['sortieren']=="Name_ASC" ||$_GET['sortieren']=="Name_DESC"){
                    echo "<p>Bezeichnung der Tätigkeiten</p>";
                }
                ?>
            </div>
                <?php
                    $currentCategory = null; 

                    while ($taetigkeit = $taetigkeitenResult->fetch_assoc()):
                        if ($_GET['sortieren'] === 'Kategorie_ASC' && $currentCategory !== $taetigkeit['Kategorie']) {
                            $currentCategory = $taetigkeit['Kategorie'];
                            echo "<h3>" . htmlspecialchars($currentCategory) . "</h3>";
                        }

                        echo "<p><a class='tätigkeiten-link tätigkeitenHoover' href='tätigSubpage.php?id=" . $taetigkeit['ID'] . "'>" . htmlspecialchars($taetigkeit['Name']) . "</a></p>";
                    endwhile;
                ?>



            <?php while ($taetigkeit = $taetigkeitenResult->fetch_assoc()): 
                //echo $taetigkeit['ID'];?>
                        <h4><a class="tätigkeiten-link" href="tätigSubpage.php?id=<?= $taetigkeit['ID'] ?>"><?= htmlspecialchars($taetigkeit['Name']) ?></a></h4>
                        <p><?= htmlspecialchars($taetigkeit['Kategorie']) ?></p>
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
        document.addEventListener('click', function(event) {
            var drawer = document.getElementById('drawer');
            var menuButton = document.getElementById('menuButton');

            if(!drawer.contains(event.target) && !menuButton.contains(event.target)) {
                document.body.classList.remove('drawer-open');
            }
        });
    </script>
    
    </body>
    </html>