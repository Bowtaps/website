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
	
	if ($submit_success) {
		$email = filter_var($email_string, FILTER_VALIDATE_EMAIL);
		if ($email === FALSE) {
			$submit_success = FALSE;
		}
	}
	
	if ($submit_success) {
		if (isset($_POST['name'])) {
			$name_string = $_POST['name'];
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
		$email_val = $db_conn->real_escape_string($email_string);
		$name_val = $db_conn->real_escape_string($name_string);
		$inserted_rows = $db_conn->query("INSERT IGNORE INTO BetaSignups (Email, Name) VALUES ('$email_val', '$name_val')");
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
    <div class="page">
      <img class="logo app" src="images/crowd-control.png" alt="Crowd Control Logo" />
      <h1 class="page-title">Crowd Control Beta Signup</h1>
      <form action="." method="post">
        <label>
          Name
          <input type="text" name="name" placeholder="John Smith" />
        </label>
        
        <label>
          Email
          <input type="email" name="email" placeholder="john.smith@email.com" />
        </label>

        <input type="submit" />
      </form>

<?php if ($submitted): ?>
<?php   if ($submit_success): ?>
      <div class="message success">
        <p>Your submission was successful.</p>
      </div>
<?php   else: ?>
      <div class="message fail">
<?php     if ($email === FALSE): ?>
        <p>We're sorry! Unable to recognize email.</p>
<?php     else: ?>
        <p>We're sorry! An error occured during submission.</p>
<?php     endif; ?>
<?php   endif; ?>
      </div>
<?php endif;?>
    </div>
    
    <footer class="footer">
      <span class="copyright">Copyright &copy; 2016 Bowtaps LLC.</span>
      <img class="logo company" src="images/bowtaps-white.png" alt="Bowtaps LLC" />
    </footer>
  </body>
</html>
