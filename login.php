<?php

	include_once("functions.php");
	
	if(isset($_POST['username'])) {
    	$loginStatus = ldap_authenticate($_POST['username'], $_POST['password']);
    	error_log('Loginstatus is '.$loginStatus);
	     if($loginStatus === 0) {
    	     logAction('login', false, $_POST['username']);
		     header("location: index.php");
		     die();
	     }
	}

    $activeSection = 'login';

	$pageTitle = "Login";
	
	require("header.php");
	

?>

<div class="row login-stuff">
	<div class="col-xs-push-4 col-xs-4">
		<form action="login.php" method="post" class="login-form">
			<?php if(isset($loginStatus) && $loginStatus !== 0) {
				echo '<div class="alert alert-danger">';
				switch($loginStatus) {
    				case 1:
    				    echo 'Your account doesn\'t have access to '.$systemName.'. Please <a href="http://www.gla.ac.uk/services/it/helpdesk/webform/">open a Helpdesk ticket</a> if you believe this is an error.';
    				    break;
    				case 2:
    				case 3:
    				    echo 'Incorrect GUID or password.';
    				    break;
    				case 4:
    				    echo $systemName.' couldn\'t check your username and password because something broke. Please try again later, or <a href="http://www.gla.ac.uk/services/it/helpdesk/webform/">open a Helpdesk ticket</a>.';
    				    break;
    				case 5:
    				    echo 'Yeah, I\'m gonna need to see some ID.';
    				    break;
    				default:
    				    echo 'Error: ';
    				    var_dump($loginStatus);
                }
				echo '</div>';
			} ?>
			<input type="text" class="form-control" name="username" id="username" placeholder="Username" autofocus="autofocus" />
			<input type="password" class="form-control" name="password" id="password" placeholder="Password" />
			<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-lock"></i> Sign In With Your GUID</button>
		</form>
	</div>
</div>

<?php include("footer.php"); ?>