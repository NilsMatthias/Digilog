<?php
  session_start();

  if (isset($_SESSION["user_id"])) {
     
    $mysqli = require __DIR__ . "/connection.php";

    $sql = "SELECT * FROM Userdaten_Hash
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
  }

  
 ?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="login.css" rel="stylesheet" type="text/css">
</head>

<body class="pagelayout-login">
    <div id="page" class="container-fluid" >
        <div id="page-content" class="row">
            <div class="login-wrapper">
                <div class="login-container">
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="loginform">
                            <div id="loginlogo" class="home-page flex">
                                <img id="images/logoimage" src="PlatzhalterLogin.png">
                                <span class="login-heading sr-only home-title">Home </span>
                            </div>

                            <?php if(isset($user)): ?>

                                <p>Hello <?= htmlspecialchars($user["username"]) ?> </p>
                                
                                <p><a href="logout.php">Log out</a></p>
                            
                            <?php else: ?>
                                <p><a href="login.php">Log in</a> or <a href="signup.html">Sign up</a> </p>

                            <?php endif;    ?>

                    
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>