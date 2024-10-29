<?php
    $servername = "webengineering.ins.hs-anhalt.de";
    $username = "root";
    $password = "digilog";
    $db_name = "Login";
    $port = 11012;
    

    $mysqli = new mysqli($servername, $username, $password, $db_name, $port);

    if($mysqli->connect_errno){
        die("Connection error: " . $mysqli->connect_error);

    }
    return $mysqli;

?>
