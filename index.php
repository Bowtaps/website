<?php
require_once "config.php";

$submitted = ($_SERVER['REQUEST_METHOD'] === 'POST');

$IMAGES = array(
  'images/background-0.png',
  'images/background-1.png',
  'images/background-2.png',
  'images/background-4.png',
  'images/background-5.png',
  'images/background-6.png',
  'images/background-7.png'
);
$chosen_image = $IMAGES[rand(0,count($IMAGES)-1)];

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
    <link type="text/css" rel="stylesheet" href="css/styles.css" />
    <link type="text/css" rel="stylesheet" href="css/font-awesome.min.css" />
    <link type="image/png" rel="shortcut icon" href="/favicon.png" />
  </head>
  <body>
    <div class="page">
      <img class="logo app" src="images/crowd-control.png" alt="Crowd Control Logo" />
      <h1 class="page-title">Crowd Control</h1>
      <h2>Sign up to join the beta!</h2>
      <form action="." method="post">
        <label>
          Name
          <input type="text" name="name" placeholder="John Smith" autofocus autocomplete="off" <?php if ($submitted && !$submit_success) echo 'value="'.$_POST['name'].'" '; ?>/>
        </label>
        
        <label>
          Email
          <input type="email" name="email" placeholder="john.smith@email.com" autocomplete="off" <?php if ($submitted && !$submit_success) echo 'value="'.$_POST['email'].'" '; ?>/>
        </label>

        <button type="submit">
          Submit<i class="fa fa-check"></i>
        </button>
      </form>

<?php if ($submitted): ?>
<?php   if ($submit_success): ?>
      <div class="message success">
        <p>Success! Thank you!</p>
      </div>
<?php   elseif ($email_string != ''): ?>
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
    
    <style>
      body {
        background-image: url('<?php echo $chosen_image; ?>');
      }
    </style>
  </body>
</html>
