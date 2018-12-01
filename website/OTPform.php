<?php
// Import the application classes
require_once('include/classes.php');

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Declare an empty array of error messages
$errors = array();

/*to redirect to list page
if($providedOTP = $OTP){
	header("Location: list.php");
	exit();
}
*/
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
<?php include 'include/header.php'; ?>
	<form>
		Provided One-Time-Password: <br>
		<input type="text" name="OTP"><br>
		<input type="submit" name="Submit">
	</form>
<?php include 'include/footer.php'; ?>
<script src="js/site.js"></script>
</body>
</html>