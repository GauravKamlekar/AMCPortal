<?php
session_start();
if (isset($_SESSION["rediretedFromLoginpage"])) {
    echo "<script>alert('Login Successful')</script>";
}
unset($_SESSION["rediretedFromLoginpage"]);

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "amcportal_db");
if (! $con) {
    die('Could not connect: ' . mysqli_connect_errno());
}
$userID=$_SESSION["userID"];
$query = "SELECT Session_ID";

?>


<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>AMCPortal Web App</title>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link rel="stylesheet"
	href="http://localhost/AMCPortal/css/mystyle.css ">
<link rel="stylesheet"
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link
	href="//db.onlinewebfonts.com/c/f3258385782c4c96aa24fe8b5d5f9782?family=Old+English+Text+MT"
	rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="http://localhost/AMCPortal/css/top-nav.css">
<link rel="stylesheet"
	href="http://localhost/AMCPortal/css/homepage.css">
<script>
$(document).ready(function(){
    // Detect when the user is leaving the page
    $(window).on('beforeunload', function(){
        // Make an AJAX request to execute your PHP script
        $.ajax({
            url: 'logout.php', // Replace with the actual path to your PHP script
            method: 'POST',
            data: {action: 'execute'}, // You can send any additional data here
            async: false, // Use synchronous AJAX to ensure the request is sent before the page closes
        });
    });
});
</script>
</head>

<body>
	<div class="bg-image-blk">
    <?php include 'C:/xampp/htdocs/AMCPortal/php/top-navigation.php';?>
    <div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10">
					<h2 class="label">Current Projects</h2>
					<br> <br> <br> <br> <br> <br>
                <?php
                $projectID = 0;
                $sessionID = 0;
                $projectName = '';
                $startDate = 0;
                $endDate = 0;
                $budget = 0;
                $expenditure = 0;
                $revenue = 0;
                $profit = 0;
                // Fetch projects from the database using prepared statement
                $stmt = $con->prepare("SELECT * FROM financial_data");
                $result = $stmt->execute();
                if (! $result) {
                    echo "Failed to load data from database";
                }
                $stmt->store_result();
                // Check if there are any rows in the result set
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($projectID, $sessionID, $projectName, $startDate, $endDate, $budget, $expenditure, $revenue, $profit);

                    echo '<table class="table table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th scope="col">Start Date</th>';
                    echo '<th scope="col">Project ID</th>';
                    echo '<th scope="col">Project Name</th>';
                    echo '<th scope="col">Budget</th>';
                    echo '<th scope="col">Expenditure</th>';
                    echo '<th scope="col">Revenue</th>';
                    echo '<th scope="col">Profit</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    session_start();
                    // Fetch and display projects
                    while ($stmt->fetch()) {
                        echo '<form action="Update_Project_Budget.php" method="post">';
                        echo '<input type="hidden" name="projectID" value="' . $projectID . '" />';
                        echo '<tr>';
                        echo '<td>' . $startDate . '</td>';
                        echo '<td>' . $projectID . '</td>';
                        $_SESSION["projectID"] = $projectID;
                        echo '<td>' . $projectName . '</td>';
                        echo '<td>' . $budget . '<input type="submit" name="updateButton" value="Update" />' . '</td>';
                        echo '<td>' . ($expenditure === null ? 'NULL' : $expenditure) . '</td>';
                        echo '<td>' . ($revenue === null ? 'NULL' : $revenue) . '</td>';
                        echo '<td>' . ($profit === null ? 'NULL' : $profit) . '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</form>';

                    // Close the statement
                    $stmt->close();
                } else {
                    // No projects available message
                    echo "No projects available. Click the button below to create a new project";
                }

                ?>
                <a href="Create_Project_Budget.php"
						class="btn btn-primary">Create New Project</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Add Bootstrap JS and jQuery -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script
		src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
	<script
		src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script
		src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<script src="https://kit.fontawesome.com/86ec7c1143.js"></script>
</body>

</html>
