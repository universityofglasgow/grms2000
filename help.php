<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Homepage";
	
	require("header.php");

?>

<ul class="breadcrumb">
    <li class="last">
        <a href="index.php">
            <i class="fa fa-home"></i> Home
        </a>
    </li>
    <li class="help">
        <a href="help.php">
            <i class="fa fa-question-circle"></i> Help
        </a>
    </li>
</ul>


<?php
    if(file_exists('help-text.php')) {
        include('help-text.php');
    } else {
        include('help-text-default.php');
    }
?>

<?php include("footer.php"); ?>