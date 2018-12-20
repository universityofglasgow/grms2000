<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Accounts I Created";
	
	require("header.php");

?>

<div class="island">
	<div class="island-header">
		<h3>Accounts I Created</h3>
	</div>
	<div class="island-body">
    	<p>These are the accounts you have created, with the newest at the top. If you're looking for something specific, you can use the search bar above to find it.</p>
    	
    	<?php
        
            $accounts = getMyAccounts();
            
            foreach($accounts as $account) {
                outputUserPanel($account);
            }
        	
        ?>
	</div>
</div>

<?php include("footer.php"); ?>