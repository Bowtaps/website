<?php
require_once "config.php";

$submitted = ($_SERVER['REQUEST_METHOD'] === 'POST');

/* handle form submissions */
if ($submitted) {
	
	$submit_success = TRUE;
	
	// check if expected form fields exist
	if ($submit_success) {
		if (isset($_POST['email'])) {
			$email_string = $_POST['email'];
		} else {
			$submit_success = FALSE;
		}
	}
	
	
	// connect to database
	if ($submit_success) {
		$db_conn = new mysqli($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass'], $CONFIG['db_name']);
		
		if ($db_conn->connect_error) {
			$submit_success = FALSE;
		}
	}
	
	// insert into database
	if ($submit_success) {
		$inserted_rows = $db_conn->query("INSERT IGNORE INTO BetaSignups (Email) VALUES ('".$db_conn->real_escape_string($email_string)."')");
		if ($db_conn->error) {
			$submit_success = FALSE;
		}
	}
	
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Crowd Control Beta Signup - Bowtaps</title>
    <link type="text/css" rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <h1>Test</h1>
    <form action="." method="post">
      <input type="text" name="email" />
      <input type="submit" />
    </form>
    <?php if ($submitted) { if ($submit_success) { ?>
    <p>Your submission was successful.</p>
    <?php } else { ?>
    <p>An error occured during submission!</p>
    <p><?php echo $db_conn->error; ?></p>
    <?php }} ?>
  </body>
</html>
