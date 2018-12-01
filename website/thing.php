<?php
// Import the application classes
require_once('include/classes.php');

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Declare an empty array of error messages
$errors = array();

// Check for logged in user since this page is protected
$app->protectPage($errors);

// If the page/thing is being loaded for display
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	// Get the topic id from the URL
	$thingid = $_GET['thingid'];
	
	// Attempt to obtain the topic
	$thing = $app->getThing($thingid, $errors);
	
	// If there were no errors getting the topic, try to get the comments
	if (sizeof($errors) == 0) {
	
		// Attempt to obtain the comments for this topic
		$thing = $app->getThing($thingid, $errors);
		
		// If the thing loaded successfully, load the associated comments
		if (isset($thing)) {
			$comments = $app->getComments($thing['thingid'], $errors);
		}
	
	} else {
		// Redirect the user to the things page on error
		header("Location: list.php?error=nothing");
		exit();
	}
	
	// Check for url flag indicating that a new comment was created.
	if (isset($_GET["newcomment"]) && $_GET["newcomment"] == "success") {
		$message = "New comment successfully created.";
	}
}
// If someone is attempting to create a new comment, process their request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Pull the comment text from the <form> POST
	$text = $_POST['comment'];

	// Pull the thing ID from the form
	$thingid = $_POST['thingid'];
	$attachment = $_FILES['attachment'];

	// Get the details of the thing from the database
	$thing = $app->getThing($thingid, $errors);

	// Attempt to create the new comment and capture the result flag
	$result = $app->addComment($text, $thingid, $attachment, $errors);

	// Check to see if the new comment attempt succeeded
	if ($result == TRUE) {

		// Redirect the user to the login page on success
	    header("Location: thing.php?newcomment=success&thingid=" . $thingid);
		exit();

	} else {
		if (isset($thing)) {
			$comments = $app->getComments($thing['thingid'], $errors);
		}
	}

}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Elijah Stall</title>
	<meta name="description" content="Elijah Stall's personal website for IT 5233">
	<meta name="author" content="Elijah Stall">
	<link rel="stylesheet" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<?php include 'include/header.php'; ?>
	<div class="breadcrumbs">
		<a href="list.php">Back to weekly food journal</a>
	</div>
	
	<?php include('include/messages.php'); ?>
	
	<div class="topiccontainer">
		<p class="topictitle"><?php echo $thing['thingname']; ?></p>
		<p class="topictagline"><?php echo $thing['username']; ?> on <?php echo $thing['thingcreated']; ?></p>
		<?php if ($thing['filename'] != NULL) { ?>
			<p class="topicattachment"><a href="attachments/<?php echo $thing['thingattachmentid'] . '-' . $thing['filename']; ?>"><?php echo $thing['filename']; ?></a></p>
		<?php } else { ?>
			<p class="topicattachment">No attachment</p>
		<?php } ?>
	</div>
	<ul class="comments">
		<?php foreach ($comments as $comment) { ?>
		<li>
			<?php echo $comment['commenttext']; ?>
			<br/>
			<span class="author"><?php echo $comment['username']; ?> on <?php echo $comment['commentposted']; ?></span>
			<?php if ($comment['filename'] != NULL) { ?>
				<p class="commentattachment"><a href="attachments/<?php echo $comment['attachmentid'] . '-' . $comment['filename']; ?>"><?php echo $comment['filename']; ?></a></p>
			<?php } else { ?>
				<p class="commentattachment">No attachment</p>
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
	<div class="newcomment">
		<form enctype="multipart/form-data" method="post" action="thing.php">
			<textarea name="comment" id="comment" rows="4" cols="50" placeholder="Add a comment"></textarea>
			<br/>
			<label for="attachment">Add an image, PDF, etc.</label>
			<input id="attachment" name="attachment" type="file">
			<br/>
			<input type="hidden" name="thingid" value="<?php echo $thingid; ?>" />
			<input type="submit" name="start" value="Add comment" />
		</form>
	</div>
	<?php include 'include/footer.php'; ?>
	<script src="js/site.js"></script>
</body>
</html>
