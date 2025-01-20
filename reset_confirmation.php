<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="login.css" rel="stylesheet" type="text/css">
</head>

<body class="pagelayout-login">
    <div id="page" class="container-fluid">
        <div id="page-content" class="row">
            <div class="login-wrapper">
                <div class="login-container">
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="loginform">
                            <div id="loginlogo" class="login-logo">
                                <img id="logoimage" src="images/PlatzhalterLogin.png">
                                <h1 class="login-heading sr-only">Kennwort zurücksetzen</h1>
                            </div>

                            <form method="post" action="process_password_reset.php" class="login-form" id="reset-form">
                                <p id="reset-text">Ein Link zum Zurücksetzen des Kennworts wurde an Ihre E-Mail-Adresse
                                    gesendet. Bitte überprüfen Sie Ihre Mailbox.</p>

                                <div class="login-form-back form-group">
                                    <a class="navigation-link" href="index.php">Zurück zur Anmeldung</a>
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