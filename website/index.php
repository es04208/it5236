<?php
	
// Import the application classes
require_once('include/classes.php');

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Declare an empty array of error messages
$errors = array();

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Elijah Stall</title>
	<meta name="description" content="Elijah Stall's personal website for IT 5236">
	<meta name="author" content="Elijah Stall">
	<link rel="stylesheet" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="background">
		<?php include 'include/header.php'; ?>
		
		<h2>Food journal</h2>
		<p>
			This is a food journal to help you track your calories and food eaten throughout the weeks so you can keep track of your diets or maybe just what you eat in general!<br><br><a href="login.php">Create an account</a> or proceed directly to the 
			<a href="login.php">login page</a>.
		</p>
		<?php include 'include/footer.php'; ?>
	</div>
	<script src="js/site.js"></script>
</body>
</html>
