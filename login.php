<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'freebee_platform';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $id;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with this email.";
        }

        $stmt->close();
    } else {
        $error = "Database error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
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
      background: linear-gradient(135deg, var(--sky-blue), var(--orange-sun));
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: bg-cycle 12s ease-in-out infinite alternate;
    }

    @keyframes bg-cycle {
      0% { background: linear-gradient(135deg, var(--sky-blue), var(--orange-sun)); }
      50% { background: linear-gradient(135deg, var(--flower-pink), var(--bee-yellow)); }
      100% { background: linear-gradient(135deg, var(--orange-sun), var(--leaf-green)); }
    }

    .login-form {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 25px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      width: 350px;
      position: relative;
      z-index: 1;
    }

    .login-form h2 {
      margin-bottom: 20px;
      color: var(--orange-sun);
      text-align: center;
    }

    .login-form input[type="email"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .login-form input[type="submit"] {
      width: 100%;
      background: linear-gradient(45deg, var(--orange-sun), var(--bee-yellow));
      border: none;
      padding: 12px;
      color: white;
      font-weight: bold;
      border-radius: 50px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .login-form input[type="submit"]:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .error {
      color: red;
      margin-bottom: 15px;
      text-align: center;
    }
  </style>
</head>
<body>
  <form class="login-form" method="POST" action="login.php">
    <h2>Connexion</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="submit" value="Se connecter">
  </form>
</body>
</html>
