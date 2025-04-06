<?php
// Assuming you have a PDO or MySQLi connection to your database
header('Content-Type: application/json');

$host = 'localhost';
$db = 'studify'; // Change to your database name
$user = 'root';  // Your database username
$pass = '';      // Your database password

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch all courses (include title, description, and image)
    $stmt = $pdo->query('SELECT title, description, image FROM courses');
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode(['courses' => $courses]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error connecting to the database']);
}
?>
