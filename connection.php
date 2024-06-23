<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jobentry";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    // Validate and upload the image
    if ($image['error'] == UPLOAD_ERR_OK) {
        $imgData = file_get_contents($image['tmp_name']);
    } else {
        die("Image upload failed with error code " . $image['error']);
    }

    // Save the form data in the database
    $stmt = $conn->prepare("INSERT INTO customer (name, email, description, image) VALUES (?, ?, ?, ?)");
    
    // Check if the prepare() method failed
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // The BLOB type data needs to be sent as a long data
    $stmt->bind_param("sssb", $name, $email, $description, $null);
    $stmt->send_long_data(3, $imgData);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Your job post has been successfully uploaded.";
        // Redirect to a thank you page
        header("Location: job-detail.html");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
