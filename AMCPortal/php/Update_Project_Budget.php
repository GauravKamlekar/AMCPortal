<?php
$con = mysqli_connect("localhost", "root", "", "amcportal_db"); // connect to the database
if (! $con) {
    die('Could not connect: ' . mysqli_connect_errno()); // return error if connect fails
}
session_start();
$projectID = $_SESSION['projectID'];
$currentBudget = 0;
// echo $projectID;
// Fetch the current budget
$stmt = $con->prepare("SELECT Budget FROM financial_data WHERE Project_id = ?");
$stmt->bind_param('i', $projectID);
$result = $stmt->execute();

if (! $result) {
    echo "Failed to load budget from the database";
}

$stmt->store_result();
$stmt->bind_result($currentBudget);
$stmt->fetch();


?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Update Budget - AMCPortal Web App</title>
<link rel="stylesheet" href="http://localhost/AMCPortal/css/homepage.css">
<link rel="stylesheet"
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link
	href="//db.onlinewebfonts.com/c/f3258385782c4c96aa24fe8b5d5f9782?family=Old+English+Text+MT"
	rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="http://localhost/AMCPortal/css/top-nav.css">
</head>

<body>
	<div class="bg-image-blk">
    <?php include 'C:/xampp/htdocs/AMCPortal/php/top-navigation.php'; ?>

    <div class="container">
			<h2 class="label">Update Budget</h2>
			<form method="post" action="updatebudget.php">
			<input type="hidden" name="projectID" value= '<?php echo $projectID ?>'/>
				<div class="form-group">
					<label for="budget" class="label">New Budget:</label>
					<input
						type="text" class="form-control" name="budget" id="budget"
						required value="<?php echo $currentBudget; ?>" />
				</div>
				<button type="submit" class="btn btn-primary">Update Budget</button>
			</form>
		</div>
	</div>

</body>

</html>
