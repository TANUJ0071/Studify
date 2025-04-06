<?php
// Database connection
$host = 'localhost';
$db = 'studify';
$user = 'root'; // your MySQL username
$pass = ''; // your MySQL password

// Create PDO instance for DB connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Edit user function
if (isset($_GET['edit_user'])) {
    $userId = $_GET['edit_user'];

    // Fetch existing user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found!";
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $role = $_POST['role'];

        // Update user data in the database
        $sql = "UPDATE users SET username = :username, email = :email, first_name = :first_name,
                last_name = :last_name, role = :role WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':username' => $username,
            ':email' => $email,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':role' => $role
        ]);

        // Redirect back to the admin dashboard after successful update
        header('Location: admin.php');
        exit;
    }

} elseif (isset($_GET['edit_course'])) {
    $courseId = $_GET['edit_course'];

    // Fetch existing course data
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = :course_id");
    $stmt->execute([':course_id' => $courseId]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        echo "Course not found!";
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $instructorId = $_POST['instructor_id'];

        // Update course data in the database
        $sql = "UPDATE courses SET title = :title, description = :description, category = :category,
                instructor_id = :instructor_id WHERE course_id = :course_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':course_id' => $courseId,
            ':title' => $title,
            ':description' => $description,
            ':category' => $category,
            ':instructor_id' => $instructorId
        ]);

        // Redirect back to the admin dashboard after successful update
        header('Location: admin.php');
        exit;
    }
} else {
    echo "No valid ID specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>
    <h2>Edit <?php echo isset($user) ? 'User' : 'Course'; ?></h2>

    <?php if (isset($user)): ?>
        <!-- Edit User Form -->
        <form method="POST">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

            <label for="first_name">First Name:</label><br>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required><br>

            <label for="last_name">Last Name:</label><br>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required><br>

            <label for="role">Role:</label><br>
            <select id="role" name="role" required>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                <option value="instructor" <?php if ($user['role'] == 'instructor') echo 'selected'; ?>>Instructor</option>
                <option value="student" <?php if ($user['role'] == 'student') echo 'selected'; ?>>Student</option>
            </select><br><br>

            <button type="submit">Update User</button>
        </form>

    <?php elseif (isset($course)): ?>
        <!-- Edit Course Form -->
        <form method="POST">
            <label for="title">Course Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($course['description']); ?></textarea><br>

            <label for="category">Category:</label><br>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($course['category']); ?>" required><br>

            <label for="instructor_id">Instructor:</label><br>
            <select id="instructor_id" name="instructor_id" required>
                <?php
                // Fetch instructors for the dropdown
                $instructors = $pdo->query("SELECT user_id, username FROM users WHERE role = 'instructor'")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($instructors as $instructor) {
                    echo '<option value="' . $instructor['user_id'] . '"';
                    if ($course['instructor_id'] == $instructor['user_id']) {
                        echo ' selected';
                    }
                    echo '>' . htmlspecialchars($instructor['username']) . '</option>';
                }
                ?>
            </select><br><br>

            <button type="submit">Update Course</button>
        </form>
    <?php endif; ?>
</body>
</html>
