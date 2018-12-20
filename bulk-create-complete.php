<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Create Account";
	
	$collectionDetails = getCollectionDetails($_GET['collection']);
	
	require("header.php");

?>

<div class="island">
	<div class="island-header">
		<h3>Collection Created</h3>
	</div>
	<div class="island-body">
    	<p>Your spreadsheet has been processed, and your collection <strong><?php echo $collectionDetails->name; ?></strong> has been created. The results are below.</p>
    	<a class="btn btn-block btn-info spaceBelow" href="download-last-session.php"><i class="fa fa-download"></i> Download this information as a spreadsheet</a>
    	<table class="table table-striped">
        	<thead>
            	<tr>
                	<th style="width: 5%;"></th>
                	<th style="width: 35%;">Email Address</th>
                	<th style="width: 15%;">Username</th>
                	<th style="width: 45%;">Status</th>
            	</tr>
        	</thead>
        	<tbody>
            	<?php
                	foreach($_SESSION['lastbatch'] as $user) {
                    	echo '<tr>';
                    	if($user['status'] == 'skipped') {
                            echo '<td><i class="fa fa-exclamation warn"></i></td>';
                        	echo '<td>'.$user['email'].'</td>';
                        	echo '<td class="space">&ndash;</td>';
                        	echo '<td>'.$user['reason'].'</td>';
                    	} else {
                        	echo '<td>';
                        	switch($user['status']) {
                            	case 'created':
                            	    echo '<i class="fa fa-check good"></i>';
                            	    break;
                                case 'extended':
                                    echo '<i class="fa fa-clock-o info"></i>';
                                    break;
                                case 'ldap':
                                    echo '<i class="fa fa-times fail"></i>';
                                    break;
                        	}
                        	echo '</td>';
                        	echo '<td>'.$user['email'].'</td>';
                        	echo '<td>'.$user['username'].'</td>';
                        	echo '<td>';
                        	switch($user['status']) {
                            	case 'created':
                            	    echo 'New account created. Details sent.';
                            	    break;
                                case 'extended':
                                    echo 'Account existed. Expiry extended.';
                                    break;
                                case 'ldap':
                                    echo 'This person already has a GUID.';
                                    break;
                        	}
                        	echo '</td>';
                        }
                    	echo '</tr>';
                	}
                ?>
        	</tbody>
    	</table>
    	
    	<a class="btn btn-block btn-info" href="download-last-session.php"><i class="fa fa-download"></i> Download this information as a spreadsheet</a>
	</div>
</div>

<?php include("footer.php"); ?>