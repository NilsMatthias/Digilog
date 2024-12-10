<?php
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    

    $mysqli = require __DIR__ . "/connection.php";

    $sql = sprintf("SELECT * FROM Userdaten_Hash
                    WHERE email = '%s'",
                    $mysqli->real_escape_string($_POST["email"]));

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if($user) {

       if( password_verify($_POST["password"], $user["password_hash"])) {

        session_start();

        session_regenerate_id();

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["rolle"] = $role["rolle"];

        if($user['rolle'] == 1) {
            header(header: "Location: startseite.php");
        }elseif ($user['rolle'] == 2){
            header(header: "Location: lehrer_startseite.php");
        }elseif ($user['rolle'] == 3){
            header(header: "Location: startseite.php");
        }
        exit;
       }

    }
    $is_invalid = true;
}
 
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Das digitale Logbuch - Anmeldung</title>
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
                            <div id="loginlogo" class="login-logo">
                                <img id="logoimage" src="images/PlatzhalterLogin.png">
                                <h1 class="login-heading sr-only">Login beim Digitalen Logbuch</h1>
                            </div>


                            <?php if ($is_invalid): ?>
                                <em>Invalid login</em>
                            <?php endif; ?>
                            </br>

                            <form method="post" class="login-form"  id="login"><!--php-->
                                <input id="anchor" type="hidden" name="anchor" value="">
                                
                                <script>document.getElementById('anchor').value = location.hash;</script>
                               
                                <input type="hidden" name="logintoken" value="CT9glFhyguE73zVG8iGFH63cpCzhBYUF">
                                <div class="login-form-username form-group">
                                    <!--label for="username" class="sr-only"> 
                                            Anmeldename oder E-Mail
                                    </label-->
                                 
                                    <input type="email" name="email" id="email" 
                                    class="form-control form-control-lg" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" placeholder="Email" autocomplete="email">
                                </div>
                                <br>

                                <div class="login-form-password form-group">
                                    <!--label for="password" class="sr-only">Kennwort </label--> 
                                    
                                    <input type="password" name="password" id="password" value="" 
                                    class="form-control form-control-lg" placeholder="Kennwort" autocomplete="current-password">
                                   
                                </div>
                                <br>

                                <div class="login-form-submit form-group">
                                    <button class="button-login"  id="loginbtn" >Login</button>
                                </div>

                                <div class="login-form-forgotpassword login-form-signup form-group">
                                    <a class ="navigation-link" href="">Kennwort vergessen?</a><!--php-->
                                    
                                    <a class ="navigation-link signup" href="signup.php">Registrierung</a><!--php-->

                                    <style> 
                                        .signup {
                                            padding-left: 30%;
                                        }
                                    </style>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>