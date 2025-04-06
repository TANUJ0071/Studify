<?php
// Database connection
$host = 'localhost';
$db = 'studify';
$user = 'root'; // your MySQL username
$pass = ''; // your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $userId = $_GET['delete_user'];

    // Prepare delete query
    $sql = "DELETE FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);

    // Redirect back to the admin dashboard after successful deletion
    header('Location: admin.php');
    exit;
} else {
    echo "No user ID specified.";
    exit;
}
?>
