<?php

use http\Cookie;

function printmessage($message)
{
    // echo "<script>console.log(\"$message\");</script>";
    echo "<pre>$message<br></pre>";
}

function printerror($message, $con) {
    echo "<pre>";
    echo "$message<br>";
    if ($con) echo "FAILED: ". mysqli_error($con). "<br>";
    echo "</pre>";
}
$checkall = true;
$alphanum= "/^[a-zA-Z0-9\s]+$/";
$checkall = $checkall && checkpost("username", true, $alphanum);
$checkall = $checkall && checkpost("password", true, $alphanum);
if (! $checkall) {
    printmessage("Error checking inputs<br>Please return to login page");
    die();
}
logindo($_POST["username"], $_POST["password"]);

// return true if checks ok
function checkpost($input, $mandatory, $pattern)
{
    $inputvalue = $_POST[$input];

    if (empty($inputvalue)) {
        printmessage("$input field is empty");
        if ($mandatory){
            return false;
        }
        else {
            printmessage("but $input is not mandatory");
        }
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
function logindo($username, $password)
{
    require "dbinfo.php";
    require "config.php";
    if(empty($username) || empty($password))
    {
        die("UserName or password is empty!");
    }

    
    try {
        $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
    } catch (Exception $e) {
        printerror($e->getMessage(), $con);
    }
    if (! $con) {
        printerror("Connecting to localhost", $con);
        die();
    }
    //else {
      //  printok("Connecting to localhost");
    //}
    $result = $con->select_db($db_database);

    if (! $result) {
        printerror("Selecting $db_database", $con);
        die();
    } //else{
        //printok("Selecting $db_database");
    //}
    $query = "SELECT * FROM $db_database.user
		WHERE Username=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $username);
    $result = $stmt->execute();
    if (! $result) {
        printerror("Querying $db_database", $con);
        die();
    } //else {
      //  printok($result);
    //}
    //$store = $result->store_result();
    $resultSet = $stmt->get_result();
    $data = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
    $pwd_peppered = hash_hmac("sha256", $password, $pepper);
    $encrypted_password= $data[0]["Password"];
    if (password_verify($pwd_peppered, $encrypted_password)) {
        $user_ID = $data[0]["User_ID"];
        if ($data[0]["Role"]=="Supervisor") {
            $sessiondata="Supervisor session";
        }
        elseif ($data[0]["Role"]=="Manager"){
            $sessiondata="Manager session";
        }
        else{
            $sessiondata="Employee session";
        }
        $expirytimestamp = date("H:i:s", time() + 600);
        $querySession = "INSERT INTO amcportal_db.session (User_id, Session_data, Expiry_timestamp)
                    VALUES (?,?,?)";
        $stmtSession = $con -> prepare($querySession);
        $stmtSession->bind_param('isd', $user_ID, $sessiondata,$expirytimestamp);
        $resultSession = $stmtSession ->execute();
        if (! $resultSession) {
            printerror("Querying $db_database", $con);
            die();
        }
        else{
            $con->close();
        }
        
        session_start();
        //session_unset();
        // You should session_start first before inserting into $_SESSION
        $_SESSION['userID'] = $data[0]["User_ID"];
        $_SESSION["username"] = $data[0]["Username"];
        $_SESSION["role"] = $data[0]["Role"];
        
        //$resultSet->free();
        
        //$con->close();
        if (isset($_SESSION["username"]) && ($_SESSION["role"]=="Supervisor" || $_SESSION["role"]=="Manager")) {
            $_SESSION["rediretedFromLoginpage"]= true;
            header("Location: Homepage.php");
            //print_r($_SESSION);
            
        }
        else{
            echo "You are not authorized to view this page". " <a href='loginpage.php'>Back to login</a> ";
        }
        
        //debug();
        // printok("Closing connection");
        
        // echo "<pre><a href='logindone.php'>Click to goto Login done</a></pre>";;
    }
    
}

?>
