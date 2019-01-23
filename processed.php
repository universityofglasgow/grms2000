<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Create Account";
	
	require("header.php");

?>
<ul class="breadcrumb">
    <li>
        <a href="index.php">
            <i class="fa fa-home"></i> Home
        </a>
    </li>
    <li class="last">
        <a href="#">
            <i class="fa fa-gear"></i> Accounts Processed
        </a>
    </li>
    <li class="help">
        <a href="help.php">
            <i class="fa fa-question-circle"></i> Help
        </a>
    </li>
</ul>


<div class="island">
	<div class="island-header">
		<h3>Accounts Processed</h3>
	</div>
	<div class="island-body">
    <?php
        
        foreach($_SESSION['lastprocess'] as $processeduser) {
            echo '<div class="alert alert-'.$processeduser['colour'].'">'.$processeduser['status'].'</div>';
            if($processeduser['username']!==false) {
                outputUserPanel($processeduser['username']);
            }
        }
        
    ?>
	</div>
</div>

<?php include("footer.php"); ?>