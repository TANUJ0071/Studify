<?php
// Database connection
$host = 'localhost';
$db = 'studify';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function deleteCourse($courseId) {
    global $pdo;
    $sql = "DELETE FROM courses WHERE course_id = :course_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':course_id' => $courseId]);
}

if (isset($_GET['delete_course'])) {
    deleteCourse($_GET['delete_course']);
    echo "<script>window.location.href = 'index.php';</script>"; // Redirect back after deletion
}
?>
