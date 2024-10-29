<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirect if not logged in
    exit;
}

$mysqli = require __DIR__ . "/connection.php";

// Check if form data is available
if (isset($_POST['taetigkeit_id'], $_POST['dokumentation']) && !empty(trim($_POST['dokumentation']))) {
    $taetigkeit_id = $_POST['taetigkeit_id'];
    $user_id = $_SESSION['user_id'];
    $dokumentation_text = $mysqli->real_escape_string($_POST['dokumentation']);
    $created_at = date("Y-m-d H:i:s");

    // Insert documentation into the Dokumentationen table
    $sql = "INSERT INTO Durchführung (taetigkeit_id, user_id, dokumentation_text, created_at)
            VALUES ('$taetigkeit_id', '$user_id', '$dokumentation_text', '$created_at')";

    if ($mysqli->query($sql)) {
        header("Location: startseite.php?success=Dokumentation gespeichert");
        exit;
    } else {
        die("Fehler beim Speichern der Dokumentation: " . $mysqli->error);
    }
} else {
    die("Ungültige Eingaben.");
}
?>
