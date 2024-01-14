<?php
require "dbinfo.php";
try {
    $con = mysqli_connect($db_hostname, $db_username, $db_password);
} catch (Exception $e) {
    printerror($e->getMessage(), $con);
}
if (! $con) {
    printerror("Connecting to $db_hostname", $con);
    die();
}
// else printok("Connecting to $db_hostname");

$result = mysqli_select_db($con, $db_database);
if (! $result) {
    printerror("Selecting $db_database", $con);
    die();
}
session_start();
$userID=$_SESSION["userID"];
$query="DELETE FROM session WHERE User_ID=?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $userID);
$result= $stmt->execute();
// Displaying success or failure message
if (! $result) {
    echo "Fail";
} else {
    //echo "Successful";
    header('Location:loginpage.php');
}
session_destroy();
?>