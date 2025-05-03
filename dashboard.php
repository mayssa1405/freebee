<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start([
    'cookie_secure' => false,
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

function mapWeatherDescription($description) {
    return ucfirst($description); // Optional mapping if needed
}

try {
    $conn = new mysqli("localhost", "root", "", "freebee_platform");
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Get user
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed (user): " . $conn->error);
    }
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user) {
        throw new Exception("User not found");
    }

    // Get user location
    $stmt = $conn->prepare("
        SELECT l.latitude, l.longitude, l.pollen_level
        FROM user_locations ul
        JOIN locations l ON ul.location_id = l.id
        WHERE ul.user_id = ?
    ");
    if (!$stmt) {
        throw new Exception("Prepare failed (location): " . $conn->error);
    }
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $locationData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $pollenLevel = $locationData['pollen_level'] ?? rand(0, 10);
    $currentPollenMessage = $currentPollenMessage ?? "Stay safe this spring!"; // Default message for pollen
// Fetch personalized alerts for the logged-in user
$alerts = [];
$stmt = $conn->prepare("SELECT alert_message, alert_type, created_at FROM user_alerts WHERE user_id = ? ORDER BY created_at DESC");

if ($stmt) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $alerts[] = $row;
    }
    $stmt->close();
}

    // Default weather data
    $weather = [
        'temperature' => 'N/A',
        'condition' => 'Météo non disponible'
    ];

    if (!empty($locationData['latitude']) && !empty($locationData['longitude'])) {
        $lat = $locationData['latitude'];
        $lon = $locationData['longitude'];
        $apiKey = '763c0a004c22972bcc317f4eed79987b'; // 👈 Replace with your OpenWeather API key

        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&units=metric&appid=$apiKey&lang=fr";
        $weatherResponse = file_get_contents($weatherUrl);

        if ($weatherResponse) {
            $weatherData = json_decode($weatherResponse, true);
            if (isset($weatherData['main']['temp']) && isset($weatherData['weather'][0]['description'])) {
                $weather['temperature'] = round($weatherData['main']['temp']) . "°C";
                $weather['condition'] = ucfirst($weatherData['weather'][0]['description']);
            }
        }
    }

    $conn->close();

} catch (Exception $e) {
    error_log("ERROR: " . $e->getMessage());
    die("Erreur détectée : " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Dashboard Printanier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange-sun: #FFA500;
            --sky-blue: #A8D8E6;
            --flower-pink: #F9C2B2;
            --leaf-green: #A8D8B9;
            --bee-yellow: #FFD700;
            --tree-brown: #8B4513;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--sky-blue), var(--flower-pink));
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: bg-pulse 15s ease infinite alternate;
            margin: 0;
        }

        @keyframes bg-pulse {
            0% { background: linear-gradient(135deg, var(--sky-blue), var(--flower-pink)); }
            50% { background: linear-gradient(135deg, var(--leaf-green), var(--orange-sun)); }
            100% { background: linear-gradient(135deg, var(--orange-sun), var(--sky-blue)); }
        }

        .magic-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            width: 90%;
            max-width: 700px;
            position: relative;
            animation: float 8s ease-in-out infinite;
            border: 2px solid rgba(255, 255, 255, 0.5);
            z-index: 1;
            margin: 2rem;
            overflow: hidden;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        .pollen-widget {
            padding: 1.5rem;
            border-radius: 12px;
            margin: 1.5rem 0;
            color: #333;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background: <?= $pollenLevel > 6 ? '#ff6b6b' : ($pollenLevel > 3 ? '#ffe66d' : '#6bff6b') ?>;
            transition: all 0.3s ease;
        }

        .pollen-level {
            font-size: 1.5em;
            font-weight: bold;
            margin: 0.5rem 0;
        }

        .recommendation {
            font-weight: bold;
            margin-top: 1rem;
            padding: 0.5rem;
            border-radius: 6px;
            background: rgba(0,0,0,0.1);
        }

        .list-group-item {
            background: var(--flower-pink);
            margin: 0.5rem auto;
            padding: 1rem;
            border-radius: 12px;
            max-width: 500px;
            color: #333;
        }

        .btn {
            font-weight: 700;
            padding: 0.8rem 2.2rem;
            border-radius: 50px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            transition: all 0.4s ease;
            margin: 1rem auto;
            min-width: 180px;
            border: none;
            display: block;
            text-align: center;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--orange-sun), var(--leaf-green));
        }

        .btn-secondary {
            background: linear-gradient(45deg, var(--bee-yellow), var(--flower-pink));
        }

        .btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 12px 20px rgba(0,0,0,0.25);
        }

        .decorations svg {
            position: absolute;
            opacity: 0.8;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="magic-container">
        <h1>Bienvenue, <?= htmlspecialchars($user['first_name']) ?> 🌞</h1>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
        <?php if (!empty($allergies)): ?>
            <p>Allergies: <?= htmlspecialchars(implode(', ', $allergies)) ?></p>
        <?php endif; ?>

        <h2>Pollen Alert</h2>
        <div class="pollen-widget">
            <div class="pollen-level">Level: <?= htmlspecialchars($pollenLevel); ?>/10</div>
            <p><?= htmlspecialchars($currentPollenMessage); ?></p>
            <?php if ($pollenLevel > 6): ?>
                <div class="recommendation">⚠️ Consider staying indoors</div>
            <?php endif; ?>
        </div>
        <h4>Alertes personnalisées</h4>
<div class="list-group">
    <?php if (!empty($alerts)): ?>
        <?php foreach ($alerts as $alert): ?>
            <div class="list-group-item">
                <?= htmlspecialchars($alert['alert_message']) ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="list-group-item">Aucune alerte disponible.</div>
    <?php endif; ?>
</div>




        <h2>Météo actuelle</h2>
        <p><strong>Température :</strong> <?= htmlspecialchars($weather['temperature']) ?></p>
        <p><strong>Conditions :</strong> <?= htmlspecialchars($weather['condition']) ?></p>

        <a href="profile.php" class="btn btn-primary">Voir mon profil</a>
        <a href="logout.php" class="btn btn-secondary">Se déconnecter</a>
    </div>
</body>
</html>