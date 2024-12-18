<?php
// login.php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $remember_me = isset($_POST["remember_me"]);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["full_name"] = $user["full_name"];

            if ($remember_me) {
                setcookie("user_id", $user["id"], time() + (86400 * 30), "/"); // 30 days
            }

            // Display a popup message and redirect to a welcome page
            echo "<script>
                    alert('Login successful! Welcome, " . addslashes($user["full_name"]) . "');
                    window.location.href = 'products.php';
                  </script>";
            exit;
        } else {
            // If login fails, show a popup error message
            echo "<script>
                    alert('Invalid email or password.');
                    window.location.href = 'login.html';
                  </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
                alert('Login failed: " . addslashes($e->getMessage()) . "');
                window.location.href = 'login.html';
              </script>";
    }
}
?>
