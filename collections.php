<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Accounts I Created";
	
	require("header.php");

?>
<ul class="breadcrumb">
    <li>
        <a href="index.php">
            <i class="fa fa-home"></i> Home
        </a>
    </li>
    <li class="last">
        <a href="collections.php">
            <i class="fa fa-pencil"></i> My Collections
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