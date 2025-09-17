<?php
//fichier de création de conexion à la base de données SQL
$host = "localhost";
$dbname = "biblio_base";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password); // création de l’objet PDO
    /*
    L’objet PDO est une interface d’accès aux bases de données en PHP.
    Il permet de se connecter à une base de données, d’exécuter des requêtes SQL et de gérer les résultats.
    */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // gestion des erreurs 
    $pdo->exec("SET NAMES 'utf8'"); // encodage des caractères
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage()); // Récupération du message d’erreur
}
?>