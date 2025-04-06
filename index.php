<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Studify - Free Courses</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Lato:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Lato', sans-serif;
    }
    h1, h2, h3 {
      font-family: 'Poppins', sans-serif;
    }
    /* Animation */
    .hover-grow:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
    }
    .bg-gradient-hero {
      background: linear-gradient(to right, #1f40ff, #1e90ff);
    }
  </style>
</head>
<body class="bg-gray-900 text-white">

  <!-- Navbar -->
  <nav class="bg-gray-800 p-4 fixed top-0 left-0 right-0 z-10">
    <div class="container mx-auto flex justify-between items-center">
      <a class="text-2xl font-bold text-blue-500 hover:text-blue-400" href="#">Studify</a>
      <div class="space-x-4 hidden md:flex">
        <a class="hover:text-blue-400" href="#">Home</a>
        <a class="hover:text-blue-400" href="#">Courses</a>
        <a class="hover:text-blue-400" href="#">About</a>
        <a class="hover:text-blue-400" href="#">Contact</a>
      </div>
      <div class="space-x-4">
        <a class="hover:text-blue-400" href="#">Login</a>
        <a class="hover:text-blue-400" href="#">Sign Up</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-gradient-hero py-24 text-center text-white bg-cover bg-no-repeat bg-center" style="background-image: url('https://www.shutterstock.com/image-vector/learn-online-book-digital-futuristic-600nw-2182663111.jpg');">
    <div class="container mx-auto">
      <h1 class="text-5xl font-extrabold mb-4 text-shadow-lg">Welcome to Studify</h1>
      <p class="text-lg mb-8 drop-shadow-md">Your gateway to free online courses</p>
      <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full shadow-lg transform hover:scale-105 transition duration-300 border-2">Get Started</a>
    </div>
  </section>

  <!-- Courses Section -->
  <section class="py-20">
    <div class="container mx-auto">
      <h2 class="text-3xl font-bold mb-12 text-center text-blue-500">Free Courses</h2>
      <div id="course-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <!-- Course cards will be dynamically added here -->
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-800 py-8">
    <div class="container mx-auto text-center">
      <p class="text-gray-400">&copy; 2023 Studify. All rights reserved.</p>
      <div class="space-x-4 mt-4">
        <a href="#" class="hover:text-blue-400 transform hover:scale-110 transition-all duration-300 ease-in-out">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" class="hover:text-blue-400 transform hover:scale-110 transition-all duration-300 ease-in-out">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="#" class="hover:text-blue-400 transform hover:scale-110 transition-all duration-300 ease-in-out">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="#" class="hover:text-blue-400 transform hover:scale-110 transition-all duration-300 ease-in-out">
          <i class="fab fa-instagram"></i>
        </a>
      </div>
    </div>
  </footer>

  <script>
    // Function to fetch and display courses dynamically
    window.onload = function() {
      fetchCourses();
    };

    function fetchCourses() {
      fetch('get_courses.php')
        .then(response => response.json())
        .then(data => {
          const courseList = document.getElementById('course-list');
          data.courses.forEach(course => {
            const courseItem = document.createElement('div');
            courseItem.classList.add('bg-gray-800', 'p-6', 'rounded-lg', 'transition-all', 'duration-300', 'ease-in-out', 'hover-grow');
            courseItem.innerHTML = `
              <img src="${course.image}" alt="${course.title}" class="rounded-lg mb-4 hover:scale-105 transition-transform duration-300">
              <h3 class="text-xl font-bold mb-2">${course.title}</h3>
              <p class="text-gray-400 mb-4">${course.description}</p>
              <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transform hover:scale-110 transition-all duration-300 ease-in-out flex items-center gap-2">
                Enroll Now <i class="fas fa-arrow-right"></i>
              </a>
            `;
            courseList.appendChild(courseItem);
          });
        })
        .catch(error => console.error('Error fetching courses:', error));
    }
  </script>
</body>
</html>
