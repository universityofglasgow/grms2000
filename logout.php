<?php 

	require_once('functions.php');
	logAction('logout', false, $_SESSION['username']);
	session_destroy();
	header("Location: index.php");

?>