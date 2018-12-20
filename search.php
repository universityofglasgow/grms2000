<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Accounts I Created";
	
	require("header.php");

?>

<div class="island">
	<div class="island-header">
		<h3>Search Results</h3>
	</div>
	<div class="island-body">
    	<?php
        
            $accounts = getAccountsMatching($_GET['q']);
            
            foreach($accounts as $account) {
                outputUserPanel($account);
            }
        	
        ?>
	</div>
</div>

<?php include("footer.php"); ?>