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

// Functions for CRUD operations

// Add new user
function addUser($username, $email, $firstName, $lastName, $role) {
    global $pdo;
    $sql = "INSERT INTO users (username, email, first_name, last_name, role) 
            VALUES (:username, :email, :first_name, :last_name, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':role' => $role
    ]);
}

// Edit user details
function editUser($userId, $username, $email, $firstName, $lastName, $role) {
    global $pdo;
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
}

// Delete user
function deleteUser($userId) {
    global $pdo;
    $sql = "DELETE FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
}

// Fetch all data for users (and other tables)
function fetchAllData($table, $columns) {
    global $pdo;
    $stmt = $pdo->query("SELECT $columns FROM $table");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch data
$users = fetchAllData('users', 'user_id, username, email, first_name, last_name, role');
$courses = fetchAllData('courses', 'course_id, title, category, instructor_id');
$categories = fetchAllData('categories', 'category_id, category_name');
$lessons = fetchAllData('course_lessons', 'lesson_id, title, section_id');
$enrollments = fetchAllData('enrollments', 'enrollment_id, user_id, course_id, enrollment_date, completion_status');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Education Platform</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f7fa;
    }

    .admin-container {
        display: flex;
    }

    .sidebar {
        background-color: #333;
        color: white;
        width: 250px;
        padding: 20px;
        height: 100vh;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin: 15px 0;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;
    }

    .sidebar ul li a:hover {
        text-decoration: underline;
    }

    .content {
        flex: 1;
        padding: 20px;
    }

    h3 {
        color: #333;
    }

    .section {
        display: none;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        margin: 10px 0;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f4f7fa;
    }

    td {
        background-color: #fff;
    }

</style>

<script>
    function showSection(sectionId) {
        const sections = document.querySelectorAll('.section');
        sections.forEach(section => {
            section.style.display = 'none';
        });
        const activeSection = document.getElementById(sectionId);
        activeSection.style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', () => {
        showSection('users');
    });
</script>

<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="#" onclick="showSection('users')">Manage Users</a></li>
                <li><a href="manage_c.php">Manage Courses</a></li>
                <li><a href="#" onclick="showSection('categories')">Manage Categories</a></li>
                <li><a href="#" onclick="showSection('enrollments')">Manage Enrollments</a></li>
                <li><a href="#" onclick="showSection('course-lessons')">Manage Course Lessons</a></li>
            </ul>
        </div>

        <div class="content">
            <!-- Users Section -->
            <div id="users" class="section">
                <h3>Manage Users</h3>
                <button onclick="document.getElementById('add-user-form').style.display='block'">Add New User</button>
                <table id="users-table">
                    <tr><th>User ID</th><th>Username</th><th>Email</th><th>First Name</th><th>Last Name</th><th>Role</th><th>Actions</th></tr>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <a href="edit.php?edit_user=<?php echo $user['user_id']; ?>">Edit</a> | 
                                <a href="delete.php?delete_user=<?php echo $user['user_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                
                <!-- Add User Form -->
                <div id="add-user-form" style="display:none;">
                    <h3>Add New User</h3>
                    <form method="POST">
                        <label for="username">Username:</label><br>
                        <input type="text" id="username" name="username" required><br>
                        <label for="email">Email:</label><br>
                        <input type="email" id="email" name="email" required><br>
                        <label for="first_name">First Name:</label><br>
                        <input type="text" id="first_name" name="first_name" required><br>
                        <label for="last_name">Last Name:</label><br>
                        <input type="text" id="last_name" name="last_name" required><br>
                        <label for="role">Role:</label><br>
                        <select id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="instructor">Instructor</option>
                            <option value="student">Student</option>
                        </select><br><br>
                        <button type="submit" name="add_user">Add User</button>
                    </form>
                </div>
            </div>

            <?php
            // Handle Add User Form submission
            if (isset($_POST['add_user'])) {
                addUser($_POST['username'], $_POST['email'], $_POST['first_name'], $_POST['last_name'], $_POST['role']);
                echo "<script>location.reload();</script>";
            }
            ?>
        </div>
    </div>
</body>
</html>
