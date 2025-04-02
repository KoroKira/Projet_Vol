<?php
$host = "localhost"; // A changer
$dbname = "Gestion_de_Vol-Desarcy-Fraissine-Deligny"; // A changer
$username = "root"; // A changer
$password = ""; // A changer

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
