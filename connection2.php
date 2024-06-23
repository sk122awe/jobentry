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
    // Check if all required fields are filled
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        // Collect form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
        $message = $_POST['message'];

        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO contact_form (name, email, subject, message) VALUES (?, ?, ?, ?)");

        // Check if the prepare() method failed
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            echo '<script>alert("Successful");</script>';
            echo '<script>setTimeout(function(){ window.location.href = "contact.html"; }, 0);</script>';
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Please fill in all required fields.";
    }
}

$conn->close();
?>
