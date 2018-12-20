<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'scripts';

	$pageTitle = "Create Collection";
	
	require("header.php");
	

?>
<div class="island">
    <div class="island-header">
        <h3>Manage Users</h3>
    </div>
    <form class="form-horizontal" method="post" action="bulk-edit-action.php" enctype="multipart/form-data">
        <div class="island-body">
        
            <div class="form-group">
                <label for="file" class="control-label col-sm-3">Upload a CSV File</label>
                <div class="col-sm-9">
                    <input type="file" id="file" name="file" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="action" class="control-label col-sm-3">Action</label>
                <div class="col-sm-9">
                    <select id="action" name="action" class="form-control">
                        <option value="extend-30">Set expiry date to 1 month from today</option>
                        <option value="extend-180">Set expiry date to 6 months from today</option>
                        <option value="extend-365" selected="selected">Set expiry date to 12 months from today</option>
                        <option value="reset">Reset passwords and email users</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-push-3">
                   <button type="submit" class="btn btn-block btn-success"><i class="fa fa-plus-circle"></i> Create Accounts</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="island">
    <div class="island-header">
        <h3>Instructions for Use</h3>
    </div>
    <div class="island-body">
        <p>Create a spreadsheet with only one column. This column can either be the username or email address of the user. Once you're done, export this spreadsheet as a CSV and upload it here.</p>
    </div>
</div>

<?php include("footer.php"); ?>