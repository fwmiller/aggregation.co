<?php

require("include/header.php");
require("include/db.php");

// This variable will be used to re-display the user's username to them
// in the login form if they fail to enter the correct password.  It is
// initialized here to an empty value, which will be shown if the user
// has not submitted the form.
$submitted_username = '';

// This if statement checks to determine whether the login form has been
// submitted.  If it has, then the login code is run, otherwise the form is
// displayed
if(!empty($_POST)) {
	// This query retreives the user's information from the database using
	// their username.
	//$query = "SELECT id, username, password, salt, email FROM users WHERE username = :username";
	$query = "SELECT id, username, password, salt FROM users WHERE username = :username";
        $query_params = array(':username' => $_POST['username']);
	try {
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
        } catch(PDOException $ex) {
		die("Query for usernames failed");
	}
	// This variable tells us whether the user has successfully logged
	// in or not.  We initialize it to false, assuming they have not.
	// If we determine that they have entered the right details, then
	// we switch it to true.
	$login_ok = false;
        
	// Retrieve the user data from the database.  If $row is false,
	// then the username they entered is not registered.
	$row = $stmt->fetch();
	if($row) {
		// Using the password submitted by the user and the salt
		// stored in the database, we now check to see whether the
		// passwords match by hashing the submitted password and
		// comparing it to the hashed version already stored in
		// the database.
		$check_password =
			hash('sha256', $_POST['password'] . $row['salt']);
		for($round = 0; $round < 65536; $round++) {
			$check_password = hash('sha256',
				$check_password . $row['salt']);
		}
		if($check_password === $row['password']) {
			$login_ok = true;
		}
	}
	// If the user logged in successfully, then we send them to the
	// private members-only page.  Otherwise, we display a login
	// failed message and show the login form again
	if($login_ok) {
		// Here I am preparing to store the $row array into the
		// $_SESSION by removing the salt and password values from
		// it.  Although $_SESSION is stored on the server-side,
		// there is no reason to store sensitive values in it
		// unless you have to.  Thus, it is best practice to remove
		// these sensitive values first.
		unset($row['salt']);
		unset($row['password']);
            
		// This stores the user's data into the session at the
		// index 'user'.  We will check this index on the private
		// members-only page to determine whether or not the user
		// is logged in.  We can also use it to retrieve the user's
		// details.
		$_SESSION['user'] = $row;

		// Added a session value that is the user id.
		$_SESSION['id'] = $row['id'];

		// Redirect the user to the private members-only page.
		header("Location: index.php");
		die("Redirecting to: index.php");

	} else {
		print("Login failed");

		// Show them their username again so all they have to do
		// is enter a new password.  The use of htmlentities
		// prevents XSS attacks.  You should always use
		// htmlentities on user submitted values before displaying
		// them to any users (including the user that submitted
		// them).  For more information:
		// http://en.wikipedia.org/wiki/XSS_attack
		$submitted_username = htmlentities($_POST['username'],
			ENT_QUOTES, 'UTF-8');
	}
}

?>

<div class="login">
<img src="favicon.ico" alt="Cornfed Systems">
Welcome to <b>aggregation.co</b>  This site is an RSS aggregator. It brings
together multiple RSS feeds and presents them in a simple way.
</div>

<center>
<form action="login.php" method="post">
  <table>
    <tr valign="middle">
      <td align="right">Username:</td>
      <td align="left"><input type="text" name="username"
        value="<?php echo $submitted_username; ?>" /></td>
    </tr>

    <tr valign="middle">
      <td align="right">Password:</td>
      <td align="left"><input type="password" name="password" value="" /></td>
    </tr>

    <tr valign="middle">
      <td colspan="2" align="right"><input type="submit" value="Login" /></td>
    </tr>

    <tr valign="bottom">
      <td colspan="2" align="right"><a href="register.php"><font size="-1">Register</font></a></td>
    </tr>
  </table>
</form>
</center>

<?php require("include/footer.php"); ?>
