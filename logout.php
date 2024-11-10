<?php
// logout.php
session_start();

// Destroy the session and any associated data
session_unset();
session_destroy();

// Show a logout popup message and redirect to login
echo "<script>
        alert('You have been logged out.');
        window.location.href = 'login.html';
      </script>";
exit;
