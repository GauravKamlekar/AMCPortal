<?php
?>
<!---
<b><i>Registration form</i></b>
--->

<html>
<head>
<link rel="stylesheet" href="http://localhost/AMCPortal/css/registerpage.css">
</head>
<body>
    <form action="register.php" method="post">
        <h2>User Registration</h2>
		<img src="http://localhost/AMCPortal//images/tplogo-course-search.png" alt="Logo">
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <input type="text" name="profilepic" placeholder="Profile Picture(Optional)">
            <input type="number" name="departmentid" placeholder="Department ID" pattern="[0-9]{10}" required>
        </div>

        <div class="input-group">
            <input type="text" name="role" placeholder="Role (supervisor/manager/employee)" required>
        </div>

        <button type="submit">Submit</button>

        <a href="loginpage.php">Already have an account? Login</a>
    </form>
</body>

</html>
</html>