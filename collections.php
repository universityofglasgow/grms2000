<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Accounts I Created";
	
	require("header.php");

?>

<div class="island">
	<div class="island-header">
		<h3>My Collections</h3>
	</div>
	<div class="island-body">
    	<p>These are the account collections you have made.</p>
    	
    	<?php
        
            $collections = getMyCollections();
            
            foreach($collections as $c) {
                outputCollectionPanel($c);
            }
        	
        ?>
	</div>
</div>

<?php include("footer.php"); ?>