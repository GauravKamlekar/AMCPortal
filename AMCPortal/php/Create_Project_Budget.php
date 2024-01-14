<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>AMCPortal Web App</title>
<link rel="stylesheet"
	href="http://localhost/AMCPortal/css/homepage.css ">
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
	<div class="bg-image-blk" style="height: 1200px;">
        <?php include 'C:/xampp/htdocs/AMCPortal/php/top-navigation.php'; ?>
        <div class="row justify-content-center">
			<div class="col-md-6">
				<br> <br> <br> <br> <br> <br>
				<h2 class="label">Create Project</h2>
				<form method="post" action="createproject.php">
					<div class="form-group">
						<label for="project_name" class="label">Project Name:</label> <input
							type="text" class="form-control" name="project_name"
							id="project_name" required>
					</div>
					<div class="form-group">
						<label for="budget" class="label">Budget:</label> <input
							type="text" class="form-control" name="budget" id="budget"
							required>
					</div>
					<button type="submit" class="btn btn-primary">Create Project</button>
				</form>
			</div>
		</div>
	</div>
</body>

</html>
