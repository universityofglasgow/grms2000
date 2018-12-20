<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'scripts';

	$pageTitle = "Create Collection";
	
	require("header.php");
	

?>
<div class="island">
    <div class="island-header">
        <h3>Create Collection</h3>
    </div>
    <form class="form-horizontal" id="collection-creation-form" method="post" action="bulk-create-action.php" enctype="multipart/form-data">
        <div class="island-body">
        
            <div class="form-group">
                <label for="file" class="control-label col-sm-3">Upload a CSV File</label>
                <div class="col-sm-9">
                    <input type="file" id="file" name="file" class="form-control" required="required" />
                </div>
            </div>
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-3">Collection Name</label>
                <div class="col-sm-9">
                    <input type="text" id="sessionName" name="sessionName" class="form-control" value="<?php echo $_SESSION['firstname']; ?>'s Uploads - <?php echo prettifyDate(time(), 'ssd'); ?> at <?php echo prettifyDate(time(), 'sst'); ?>" required="required" />
                    <p class="form-text text-muted">You can use the <strong>collection name</strong> to give this collection of accounts a memorable name &ndash; something like <strong>Dentistry Auditors 2018</strong>. You can manage an entire collection of users at a later date, if you need to extend all the accounts or reset all the passwords. Choosing a good name will make the accounts easier to find.</p>
                </div>
            </div>
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-3">Duration</label>
                <div class="col-sm-9">
                    <select id="duration" name="duration" class="form-control">
                        <option value="30">1 Month</option>
                        <option value="180">6 Months</option>
                        <option value="365" selected="selected">12 Months</option>
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
        <p>To use this system, create a spreadsheet with three columns. If you have an existing spreadsheet of course participants, you can copy this information to a new spreadsheet and move the columns around.</p>
        <ul>
            <li>The first column should be the <strong>email address</strong> of the person.</li>
            <li>The second column should be the <strong>first name</strong> of the person.</li>
            <li>The third column should be the <strong>last name</strong> of the person.</li>
        </ul>
        <p>Once you're done, export this spreadsheet as a CSV and upload it here.</p>
        <p><?php echo $systemName; ?> will check whether each person already has an account. If they don't, it will create one. If they do, it will extend the expiry date for the account.</p>
        <p>Once it's done, you'll see a list of all the accounts that were created, and the ones that already existed, and you'll be able to download a spreadsheet of these users to enrol on your Moodle courses.</p>
    </div>
</div>

<?php include("footer.php"); ?>