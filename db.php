<?php
$host = 'localhost';
$db   = 'freebee_platform'; // your database name
$user = 'root';    // your database username (XAMPP default is 'root')
$pass = '';        // your database password (XAMPP default is empty)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch as associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // disable emulation
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
?>