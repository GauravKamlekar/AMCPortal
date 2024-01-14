<?php
$con = mysqli_connect("localhost", "root", "", "amcportal_db"); // Connect to the database
if (! $con) {
    die('Could not connect: ' . mysqli_connect_errno()); // Return an error if connection fails
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $session_id= $_SESSION['sessionID'];
    $query = $con->prepare("INSERT INTO financial_data (Session_id, Project_name, Budget) VALUES (?, ?, ?)");
    
    // Bind parameters
    $session_id = $_POST["session_id"];
    $project_name = $_POST["project_name"];
    $budget = $_POST["budget"];
    
    $query->bind_param('iss', $session_id, $project_name, $budget);
    
    // Execute the query
    if ($query->execute()) {
        // Set a query parameter for success
        header("Location: Homepage.php");
        exit(); // Ensure that no further code is executed after the redirect
    } else {
        // Set a query parameter for failure
        echo "Failed";
        exit(); // Ensure that no further code is executed after the redirect
    }
}
?>