<?php
// register.php
require 'db_connect.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data sent via POST from the registration form
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $address = $_POST["address"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    // Email validation (using PHP's built-in filter)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Invalid email format.');
                window.location.href = 'register.html';
              </script>";
        exit;
    }

    // Check if the email already exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        // If email exists, show an alert and redirect back to register page
        echo "<script>
                alert('This email is already registered.');
                window.location.href = 'register.html';
              </script>";
        exit;
    }

    try {
        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone_number, address, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $phone_number, $address, $password]);

        // On success, show a popup and redirect to the login page
        echo "<script>
                alert('Registration successful! You can now log in.');
                window.location.href = 'login.html';
              </script>";
        exit;
    } catch (PDOException $e) {
        // Handle database errors
        echo "<script>
                alert('Registration failed: " . addslashes($e->getMessage()) . "');
                window.location.href = 'register.html';
              </script>";
    }
}
?>
