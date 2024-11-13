<?php
// Database configuration
$servername = "localhost"; // Change if necessary
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "ravi"; // Replace with your DB name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $conn->real_escape_string($_POST['name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $state = $conn->real_escape_string($_POST['state']);
    $location = $conn->real_escape_string($_POST['location']);
    $qualification = $conn->real_escape_string($_POST['qualification']);
    $year = $conn->real_escape_string($_POST['year']);

    // Handle file upload
    $resume = $_FILES['resume'];
    $resumePath = 'uploads/' . basename($resume['name']);

    // Check if uploads directory exists, if not create it
    if (!file_exists('uploads')) {
        mkdir('uploads', 0755, true);
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($resume['tmp_name'], $resumePath)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO applications (name, gender, dob, email, phone, state, location, qualification, year, resume_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $name, $gender, $dob, $email, $phone, $state, $location, $qualification, $year, $resumePath);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Application submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error uploading resume.";
    }
}

// Close the connection
$conn->close();
?>
