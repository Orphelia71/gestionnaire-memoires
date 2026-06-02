<?php
$host = 'sql200.infinityfree.com';
$dbname = 'if0_42080579_gestion_memoires';
$username = 'if0_42080579';
$password = 'Orphe006'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>