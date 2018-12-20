<?php

	include_once("functions.php");
	require_login();

	$pageTitle = "Create Conference Accounts";
	
	require("header.php");
	

?>
<div class="island">
    <div class="island-header">
        <h3>Create Conference Accounts</h3>
    </div>
    <form class="form-horizontal" id="conference-creation-form" method="post" action="conference-create-action.php">
        <div class="island-body">
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-4">Conference Name</label>
                <div class="col-sm-8">
                    <input type="text" id="sessionName" name="sessionName" class="form-control" value="<?php echo $_SESSION['firstname']; ?>'s Conference - <?php echo prettifyDate(time(), 'ssd'); ?> at <?php echo prettifyDate(time(), 'sst'); ?>" required="required" />
                    <p class="form-text text-muted">You can look up these conference accounts using this name, if you need to extend the accounts or reset the passwords. It's best to pick something memorable to make the accounts easier to find.</p>
                </div>
            </div>
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-4">Account Type</label>
                <div class="col-sm-8">
                    <select id="type" name="type" class="form-control">
                        <?php
                            $accountTypes = getMyAccountTypes();
                            
                            foreach($accountTypes as $code=>$friendlyName) {
                                echo '<option value="'.$code.'">'.$friendlyName['name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-4">Number of Accounts</label>
                <div class="col-sm-8">
                    <input type="number" id="quantity" name="quantity" class="form-control" required="required" min="1" max="999" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8 col-sm-push-4">
                   <button type="submit" class="btn btn-block btn-success"><i class="fa fa-plus-circle"></i> Create Accounts</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include("footer.php"); ?>