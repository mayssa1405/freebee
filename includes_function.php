<?php
// Fetch user by ID
function getUserById($user_id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $user ? $user : null;
}
function registerUser($first_name, $last_name, $cin, $phone, $email, $password) {
    global $db;

    // Check if CIN already exists
    $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE cin = :cin');
    $stmt->execute([':cin' => $cin]);
    if ($stmt->fetchColumn() > 0) {
        return false;  // CIN already exists
    }

    //Proceed with the registration if CIN is unique
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password before storing
    $stmt = $db->prepare('
        INSERT INTO users (first_name, last_name, cin, phone, email, password)
        VALUES (:first_name, :last_name, :cin, :phone, :email, :password)
    ');

    $stmt->execute([
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':cin' => $cin,
        ':phone' => $phone,
        ':email' => $email,
        ':password' => $hashed_password
    ]);

    return $db->lastInsertId(); // Return the new user's ID
}






// Get user allergies
function getUserAllergies($user_id) {
    global $db;
    $stmt = $db->prepare('SELECT allergy_type FROM user_allergies WHERE user_id = :user_id');
    $stmt->execute([':user_id' => $user_id]);
    $allergies = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    return $allergies ? $allergies : [];
}

// Fetch weather data for the user (using external API or database)
function getWeatherForUser($user_id) {
    global $db;
    
    $stmt = $db->prepare('SELECT * FROM user_locations WHERE user_id = :user_id LIMIT 1');
    $stmt->execute([':user_id' => $user_id]);
    $location = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$location) {
        return null;
    }

    $stmt = $db->prepare('SELECT * FROM weather_data WHERE location_id = :location_id LIMIT 1');
    $stmt->execute([':location_id' => $location['id']]);
    $weather = $stmt->fetch(PDO::FETCH_ASSOC);
  
    if (!$weather || time() - strtotime($weather['last_updated']) > 3600) {
        $weather = fetchWeatherFromAPI($location['city']);
       

        if ($weather) {
        
            $stmt = $db->prepare('INSERT INTO weather_data (location_id, temperature, `weather_condition`, last_updated) 
                                  VALUES (:location_id, :temperature, :condition, NOW()) 
                                  ON DUPLICATE KEY UPDATE temperature = :temperature, `weather_condition` = :condition, last_updated = NOW()');
            $stmt->execute([
                ':location_id' => $location['id'],
                ':temperature' => $weather['temperature'],
                ':condition' => $weather['condition']
            ]);
        }
        
    }

    return $weather;
}

function fetchWeatherFromAPI($city) {
    $apiKey = '763c0a004c22972bcc317f4eed79987b'; // Replace with your actual API key
    $url = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";
    
    $response = file_get_contents($url);
    
    if ($response) {
        $data = json_decode($response, true);
    
      // Debugging the response from the API
        
        if ($data && $data['cod'] == 200) {
            return [
                'temperature' => $data['main']['temp'],
                'condition' => $data['weather'][0]['description']
            ];
        }
    }
    
    return null; // If API call fails or doesn't return valid data
}



function getUserAlerts($user_id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM user_alerts WHERE user_id = :user_id');
    $stmt->execute([':user_id' => $user_id]);
    $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    return $alerts;
}
// In your functions.php
function setPollenSensitivity($userId, $level) {
    // Store user's sensitivity threshold
}

// ✅ NEW: Fetch user by email (used in registration to check if email exists)
function getUserByEmail($email) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ? $user : null;
}
// Function to validate user credentials
function validateUser($email, $password) {
    global $db; // Ensure you have access to the $db connection

    // Query to fetch user by email
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify password
    if ($user && password_verify($password, $user['password'])) {
        return $user;  // Return user data if credentials are correct
    }
    
    return false; // Return false if no user is found or password is incorrect
}

?>
