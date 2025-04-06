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

// Add new course
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

// Edit course details
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

// Delete course
function deleteCourse($courseId) {
    global $pdo;
    $sql = "DELETE FROM courses WHERE course_id = :course_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':course_id' => $courseId]);
}

// Fetch all courses
function fetchAllCourses() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM courses");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch instructors (for the dropdown)
function fetchInstructors() {
    global $pdo;
    $stmt = $pdo->query("SELECT user_id, first_name, last_name FROM users WHERE role = 'instructor'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle Add Course Form submission
if (isset($_POST['add_course'])) {
    addCourse($_POST['title'], $_POST['description'], $_POST['category'], $_POST['instructor_id']);
    echo "<script>location.reload();</script>";
}

// Handle Edit Course Form submission
if (isset($_POST['edit_course'])) {
    editCourse($_POST['course_id'], $_POST['title'], $_POST['description'], $_POST['category'], $_POST['instructor_id']);
    echo "<script>location.reload();</script>";
}

// Handle Delete Course
if (isset($_GET['delete_course'])) {
    deleteCourse($_GET['delete_course']);
    echo "<script>location.reload();</script>";
}

// Fetch data for courses
$courses = fetchAllCourses();
$instructors = fetchInstructors();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Courses</title>
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

    .add-course-form {
        display: none;
        background-color: #f1f1f1;
        padding: 20px;
        border-radius: 5px;
        margin-top: 20px;
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
        showSection('courses');
    });
</script>

<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="#" onclick="showSection('courses')">Manage Courses</a></li>
                <li><a href="#" onclick="showSection('users')">Manage Users</a></li>
                <li><a href="#" onclick="showSection('categories')">Manage Categories</a></li>
                <li><a href="#" onclick="showSection('enrollments')">Manage Enrollments</a></li>
                <li><a href="#" onclick="showSection('course-lessons')">Manage Course Lessons</a></li>
            </ul>
        </div>

        <div class="content">
            <!-- Courses Section -->
            <div id="courses" class="section">
                <h3>Manage Courses</h3>
                <button onclick="document.getElementById('add-course-form').style.display='block'">Add New Course</button>
                <table id="courses-table">
                    <tr><th>Course ID</th><th>Title</th><th>Category</th><th>Instructor</th><th>Actions</th></tr>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_id']); ?></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            <td><?php echo htmlspecialchars($course['category']); ?></td>
                            <td>
                                <?php 
                                    $instructor = null;
                                    foreach ($instructors as $instructorData) {
                                        if ($instructorData['user_id'] == $course['instructor_id']) {
                                            $instructor = $instructorData;
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($instructor ? $instructor['first_name'] . ' ' . $instructor['last_name'] : 'No Instructor');
                                ?>
                            </td>
                            <td>
                                <a href="edit_c.php?edit_course=<?php echo $course['course_id']; ?>">Edit</a> | 
                                <a href="?delete_course=<?php echo $course['course_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                
                <!-- Add Course Form -->
                <div id="add-course-form" class="add-course-form">
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
                </div>
            </div>
        </div>
    </div>
</body>
</html>
