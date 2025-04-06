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

function addCourse($title, $description, $category, $instructor_id) {
    global $pdo;
    $sql = "INSERT INTO courses (title, description, category, instructor_id) 
            VALUES (:title, :description, :category, :instructor_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':category' => $category,
        ':instructor_id' => $instructor_id
    ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    addCourse($_POST['title'], $_POST['description'], $_POST['category'], $_POST['instructor_id']);
    echo "<script>window.location.href = 'index.php';</script>"; // Redirect back to courses page after adding
}

$instructors = $pdo->query("SELECT user_id, first_name, last_name FROM users WHERE role = 'instructor'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
</head>
<body>
    <h3>Add New Course</h3>
    <form method="POST">
        <label for="title">Course Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br>
        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" required><br>
        <label for="instructor_id">Instructor:</label><br>
        <select id="instructor_id" name="instructor_id" required>
            <option value="">Select Instructor</option>
            <?php foreach ($instructors as $instructor): ?>
                <option value="<?php echo $instructor['user_id']; ?>"><?php echo $instructor['first_name'] . ' ' . $instructor['last_name']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit" name="add_course">Add Course</button>
    </form>
</body>
</html>
