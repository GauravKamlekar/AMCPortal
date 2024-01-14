<?php
session_start();
if (isset($_SESSION["rediretedFromRegisterpage"])) {
    echo "<script>alert('Registration successful');</script>";
}
unset($_SESSION["redirectedFromSecondPage"]);

?>
<html>
<head>
	<link rel="stylesheet" href="http://localhost/AMCPortal/css/loginpage.css "/>
</head>

<body>
<form method="post" action="login.php">
<h2>Log In </h2>
<img src="http://localhost/AMCPortal//images/tplogo-course-search.png" alt="Logo">
<input type="text" name="username" placeholder="Username">
<input type="text" name="password" placeholder="Password">
<button>Sign in</button>
</form>

</body>
</html>