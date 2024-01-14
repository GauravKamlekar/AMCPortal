<?php
$con = mysqli_connect("localhost", "root", "", "amcportal_db"); // connect to the database
if (! $con) {
    die('Could not connect: ' . mysqli_connect_errno()); // return error if connect fails
}
session_start();
$projectID = $_SESSION['projectID'];
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $budget = $_POST['budget'];
    $query = $con->prepare("UPDATE financial_data SET Budget=? WHERE Project_id=?");
    $query->bind_param('ii', $budget, $projectID);

    if ($query->execute()) {
        // Redirect to the homepage after a successful update
        header("Location: Homepage.php");
        exit();
    } else {
        echo "Error: " . $query->error;
    }
}
?>