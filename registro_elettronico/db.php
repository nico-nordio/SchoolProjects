<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "registro_elettronico";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Errore connessione: " . $conn->connect_error);
}
?>
