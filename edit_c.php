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

function fetchCourse($courseId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = :course_id");
    $stmt->execute([':course_id' => $courseId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function editCourse($courseId, $title, $description, $category, $instructor_id) {
    global $pdo;
    $sql = "UPDATE courses SET title = :title, description = :description, 
            category = :category, instructor_id = :instructor_id WHERE course_id = :course_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':course_id' => $courseId,
        ':title' => $title,
        ':description' => $description,
        ':category' => $category,
        ':instructor_id' => $instructor_id
    ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_course'])) {
    editCourse($_POST['course_id'], $_POST['title'], $_POST['description'], $_POST['category'], $_POST['instructor_id']);
    echo "<script>window.location.href = 'index.php';</script>"; // Redirect after editing
}

$courseId = $_GET['edit_course'];
$course = fetchCourse($courseId);
$instructors = $pdo->query("SELECT user_id, first_name, last_name FROM users WHERE role = 'instructor'")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
</head>
<body>
    <h3>Edit Course</h3>
    <form method="POST">
        <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
        <label for="title">Course Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"><?php echo htmlspecialchars($course['description']); ?></textarea><br>
        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($course['category']); ?>" required><br>
        <label for="instructor_id">Instructor:</label><br>
        <select id="instructor_id" name="instructor_id" required>
            <option value="">Select Instructor</option>
            <?php foreach ($instructors as $instructor): ?>
                <option value="<?php echo $instructor['user_id']; ?>" <?php echo $instructor['user_id'] == $course['instructor_id'] ? 'selected' : ''; ?>>
                    <?php echo $instructor['first_name'] . ' ' . $instructor['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit" name="edit_course">Save Changes</button>
    </form>
</body>
</html>
