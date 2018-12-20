<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';
    
    $collectionDetails = getCollectionDetails($_GET['id']);


	$pageTitle = "Collection Details for ".$collectionDetails->name;
	
	require("header.php");
	
?>

<div class="island">
	<div class="island-header">
		<h3><?php echo $collectionDetails->name; ?></h3>
	</div>
	<div class="island-body">
    	<p>This collection was created at <?php echo prettifyDate(strtotime($collectionDetails->creationdate), 'sst').' on '.prettifyDate(strtotime($collectionDetails->creationdate), 'ssd'); ?>.</p>
        <div class="btn-toolbar">
            <a class="btn btn-info" href="<?php echo 'action.php?collection='.$collectionDetails->id.'&action=resetpassword&token='.sha1($actionSalt.$_SESSION['username'].'resetpassword'.$collectionDetails->id.$collectionDetails->creationdate); ?>"><i class="fa fa-lock"></i> Reset All Passwords</a>
            <a class="btn btn-primary" href="<?php echo 'action.php?collection='.$collectionDetails->id.'&action=extend&days=365&token='.sha1($actionSalt.$_SESSION['username'].'extend'.$collectionDetails->id.$collectionDetails->creationdate); ?>"><i class="fa fa-calendar"></i> Extend All Expiry Dates</a>
            <a class="btn btn-danger" href="<?php echo 'action.php?collection='.$collectionDetails->id.'&action=deactivate&token='.sha1($actionSalt.$_SESSION['username'].'deactivate'.$collectionDetails->id.$collectionDetails->creationdate); ?>"><i class="fa fa-power-off"></i> Deactivate All</a>
            <a class="btn btn-success" href="<?php echo 'action.php?collection='.$collectionDetails->id.'&action=activate&token='.sha1($actionSalt.$_SESSION['username'].'activate'.$collectionDetails->id.$collectionDetails->creationdate); ?>"><i class="fa fa-power-off"></i> Activate All</a>
        </div>
    	
    	<?php
        
            $accounts = getUsersInCollection($collectionDetails->id);
            
            foreach($accounts as $account) {
                outputUserPanel($account);
            }
        	
        ?>
	</div>
</div>

<?php include("footer.php"); ?>