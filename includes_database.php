<?php
// Inclure la fonction pour charger les variables d’environnement
require_once __DIR__ . '/load_env.php';

// Charger le fichier .env
loadEnv(__DIR__ . '/.env');

// Récupérer les variables d’environnement
$apiKey     = $_ENV['API_KEY'] ?? '';
$dbHost     = $_ENV['DB_HOST'] ?? 'localhost';
$dbName     = $_ENV['DB_NAME'] ?? '';
$dbUser     = $_ENV['DB_USER'] ?? '';
$dbPassword = $_ENV['DB_PASSWORD'] ?? '';

try {
    // Créer une instance PDO avec les données récupérées
    $db = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8",
        $dbUser,
        $dbPassword
    );

    // Activer les erreurs PDO
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Afficher une erreur claire si la connexion échoue
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>
