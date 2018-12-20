<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Create Account";
	
	require("header.php");
	
	$actionFriendlyNames = Array(
    	'extend-30'     =>  'Expiry date set to 1 month from today',
    	'extend-180'    =>  'Expiry date set to 6 months from today',
    	'extend-365'    =>  'Expiry date set to 12 months from today',
    	'reset'         =>  'Password has been set and details have been emailed'
	);

?>

<div class="island">
	<div class="island-header">
		<h3>Collection Created</h3>
	</div>
	<div class="island-body">
    	<p>Your spreadsheet has been processed, and the following changes have been made:</p>
    	<table class="table table-striped">
        	<thead>
            	<tr>
                	<th style="width: 25%;">Username</th>
                	<th style="width: 75%;">Status</th>
            	</tr>
        	</thead>
        	<tbody>
            	<?php
                	foreach($_SESSION['lastbatch'] as $user=>$action) {
                    	echo '<tr>';
                    	echo '<td>'.$user.'</td>';
                        echo '<td>'.$actionFriendlyNames[$action].'</td>';
                    	echo '</tr>';
                	}
                ?>
        	</tbody>
    	</table>
	</div>
</div>

<?php include("footer.php"); ?>