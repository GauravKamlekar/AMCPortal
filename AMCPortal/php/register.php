
<!---
<b><i>Process registration</i></b>
--->
<?php

function adduser($username, $password, $email, $profilepic, $departmentid, $role)
{
    require "dbinfo.php";
    require "config.php";

    function printerror($message, $con)
    {
        echo "<pre>";
        echo "$message<br>";
        if ($con)
            echo "FAILED: " . mysqli_error($con) . "<br>";
        echo "</pre>";
    }

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
    // else printok("Selecting $db_database");
    $pwd_peppered = hash_hmac("sha256", $password, $pepper);
    $encrypted_password=password_hash($pwd_peppered, PASSWORD_ARGON2ID);
    $query = "INSERT INTO amcportal_db.user (Username, Password, Email, Profile_pic, Department_ID, Role)
		VALUES (?,?,?,?,?,?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param('ssssis', $username, $encrypted_password, $email, $profilepic, $departmentid, $role);
    $result = $stmt->execute();
    if (! $result) {
        printerror("Selecting $db_database", $con);
        die();
    } else {
        session_start();
        $_SESSION["rediretedFromRegisterpage"]= true;
        $con->close();
        header("Location: loginpage.php");
    }
    // printok("Closing connection");

}

function printmessage($message)
{
    // echo "<script>console.log(\"$message\");</script>";
    echo "<pre>$message<br></pre>";
}

// return true if checks ok
function checkpost($input, $mandatory, $pattern)
{
    $inputvalue = $_POST[$input];

    if (empty($inputvalue)) {
        printmessage("$input field is empty");
        if ($mandatory)
            return false;
        else
            printmessage("but $input is not mandatory");
    }
    if (strlen($pattern) > 0) {
        $ismatch = preg_match($pattern, $inputvalue);
        if (! $ismatch || $ismatch == 0) {
            printmessage("$input field wrong format <br>");
            if ($mandatory)
                return false;
        }
    }
    return true;
}

$checkall = true;
$alphanum= "/^[a-zA-Z0-9\s]+$/";
$checkall = $checkall && checkpost("username", true, $alphanum);
$checkall = $checkall && checkpost("password", true, $alphanum);
$checkall = $checkall && checkpost("profilepic", false, "");
$checkall = $checkall && checkpost("email", false, "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i");
// i behind the pattern is for case insensitive
$checkall = $checkall && checkpost("departmentid", false, "/^[0-9]+$/");
$checkall = $checkall && checkpost("role", true, $alphanum);

if (! $checkall) {
    printmessage("Error checking inputs<br>Please return to the registration form");
    die();
}

$username = $_POST['username'];
$password = $_POST['password'];
if (isset($_POST['role']) && strcasecmp($_POST['role'], 'supervisor') == 0) {
    $role = "Supervisor";
} elseif (isset($_POST['role']) && strcasecmp($_POST['role'], 'manager') == 0) {
    $role = "Manager";
} elseif (isset($_POST['role']) && strcasecmp($_POST['role'], 'employee') == 0) {
    $role = "Employee";
} else {
    echo "<pre>";
    echo "Please enter a valid role value";
}
$email = $_POST['email'];
$profilepic = $_POST['profilepic'];
$departmentid = $_POST['departmentid'];

adduser($username, $password, $email, $profilepic, $departmentid, $role);

?>


