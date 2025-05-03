<?php
ob_start(); // Start output buffering
session_start();

// Your existing PHP code here
// ...
// At the end of your script:
ob_end_flush(); // Send output
?>

<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "freebee_platform";

// Initialize variables
$errors = [];
$success = false;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $prenom = htmlspecialchars(trim($_POST["prenom"] ?? ''));
    $nom = htmlspecialchars(trim($_POST["nom"] ?? ''));
    $cin = htmlspecialchars(trim($_POST["cin"] ?? ''));
    $telephone = htmlspecialchars(trim($_POST["telephone"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $raw_password = $_POST["motdepasse"] ?? '';
    $age = intval($_POST["age"] ?? 0);
    $sexe = htmlspecialchars(trim($_POST["sexe"] ?? ''));
    $type_allergie = htmlspecialchars(trim($_POST["type_allergie"] ?? ''));

    // Validate inputs
    if (empty($prenom)) $errors[] = "Le prénom est requis.";
    if (empty($nom)) $errors[] = "Le nom est requis.";
    if (empty($cin)) $errors[] = "Le CIN est requis.";
    if (empty($telephone)) $errors[] = "Le téléphone est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (strlen($raw_password) < 8) $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    if ($age < 1) $errors[] = "L'âge doit être un nombre positif.";

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        $motdepasse = password_hash($raw_password, PASSWORD_DEFAULT);

        // Connect to database
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        // Check if email already exists
        $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            $errors[] = "Cette adresse email est déjà utilisée.";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (prenom, nom, cin, telephone, email, motdepasse, age, sexe, type_allergie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssiss", $prenom, $nom, $cin, $telephone, $email, $motdepasse, $age, $sexe, $type_allergie);

            if ($stmt->execute()) {
                $success = true;
                header("Location: login.php?success=1"); // Redirect on success
                exit();
            } else {
                $errors[] = "Erreur lors de l'inscription : " . $stmt->error;
            }
            $stmt->close();
        }
        $check_email->close();
        $conn->close();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FreeBee - Register</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Fredoka', sans-serif;
      background: linear-gradient(135deg, #fff7ae, #c2f0fc);
      overflow-x: hidden;
    }

    .decorations {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
    }

    .decorations img {
      position: absolute;
      opacity: 0.6;
      animation: float 6s ease-in-out infinite;
    }

    .decorations .sun {
      top: 20px;
      right: 40px;
      width: 100px;
    }

    .decorations .flower1 {
      bottom: 10px;
      left: 20px;
      width: 60px;
    }

    .decorations .flower2 {
      bottom: 30px;
      right: 60px;
      width: 60px;
    }

    .decorations .bee {
      top: 100px;
      left: 50%;
      width: 50px;
      animation-delay: 2s;
    }

    @keyframes float {
      0% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
      100% { transform: translateY(0); }
    }

    .container {
      max-width: 500px;
      background: #fffdf0;
      margin: 50px auto;
      padding: 30px 40px;
      border-radius: 25px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      position: relative;
      z-index: 1;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #ff9800;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 10px;
      border: 2px solid #ffd54f;
      font-size: 14px;
    }

    .btn {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      background: linear-gradient(to right, #ffb74d, #ff8a65);
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 25px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: linear-gradient(to right, #ffa726, #f44336);
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .login-link a {
      color: #ff7043;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="decorations">
  <img src="https://img.icons8.com/color/96/000000/sun--v1.png" class="sun" />
  <img src="https://img.icons8.com/color/96/000000/bee.png" class="bee" />
  <img src="https://img.icons8.com/color/96/000000/flower.png" class="flower1" />
  <img src="https://img.icons8.com/color/96/000000/flower-doodle.png" class="flower2" />
</div>

<div class="container">
  <h2>Créer un compte FreeBee 🌻</h2>
  <form action="register.php" method="POST">
    <label for="prenom">Prénom</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" required>

    <label for="cin">CIN</label>
    <input type="text" id="cin" name="cin" required>

    <label for="telephone">Téléphone</label>
    <input type="text" id="telephone" name="telephone" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="motdepasse">Mot de passe</label>
    <input type="password" id="motdepasse" name="motdepasse" required>

    <label for="age">Âge</label>
    <input type="number" id="age" name="age" required>

    <label for="sexe">Sexe</label>
    <select id="sexe" name="sexe" required>
      <option value="">--Choisir--</option>
      <option value="Femme">Femme</option>
      <option value="Homme">Homme</option>
    </select>

    <label for="type_allergie">Type d'allergie</label>
    <select id="type_allergie" name="type_allergie" required>
      <option value="">--Choisir--</option>
      <option value="Seasonal Allergic Rhinitis">Rhume des foins (allergie au pollen)</option>
      <option value="Polymorphous Light Eruption">Éruption cutanée au soleil (PMLE)</option>
      <option value="Asthma">Asthme</option>
    </select>

    <button type="submit" class="btn">S'inscrire</button>
  </form>

  <div class="login-link">
    Déjà inscrit ? <a href="login.php">Connectez-vous</a>
  </div>
</div>

<?php
// 1. CONNECT TO DATABASE
$conn = new mysqli("localhost", "root", "", "freebee_platform");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// 2. CHECK IF CIN EXISTS FIRST (FASTEST METHOD)
$cin = $_POST['cin'];
$check = $conn->query("SELECT 1 FROM users WHERE cin = '$cin' LIMIT 1");

if ($check->num_rows > 0) {
    // 3. REDIRECT BACK WITH ERROR (NO DUPLICATES ALLOWED)
    header("Location: register.php?error=duplicate_cin");
    exit();
}

// 4. INSERT IF CIN IS UNIQUE (OPTIMIZED QUERY)
$sql = "INSERT INTO users (prenom, nom, cin, telephone, email, motdepasse, age, sexe, type_allergie) 
        VALUES (
            '" . $conn->real_escape_string($_POST['prenom']) . "',
            '" . $conn->real_escape_string($_POST['nom']) . "',
            '" . $conn->real_escape_string($cin) . "',
            '" . $conn->real_escape_string($_POST['telephone']) . "',
            '" . $conn->real_escape_string($_POST['email']) . "',
            '" . password_hash($_POST['motdepasse'], PASSWORD_DEFAULT) . "',
            " . intval($_POST['age']) . ",
            '" . $conn->real_escape_string($_POST['sexe']) . "',
            '" . $conn->real_escape_string($_POST['type_allergie']) . "'
        )";

if ($conn->query($sql)) {
    header("Location: login.php?success=1"); // SUCCESS!
} else {
    die("Error: " . $conn->error); // SHOW ERRORS IMMEDIATELY
}

$conn->close();
?>

</body>
</html> 