<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Create Account";
	
	require("header.php");

?>

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