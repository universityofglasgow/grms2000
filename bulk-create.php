<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'scripts';

	$pageTitle = "Create Collection";
	
	require("header.php");
	

?>
<ul class="breadcrumb">
    <li>
        <a href="index.php">
            <i class="fa fa-home"></i> Home
        </a>
    </li>
    <li class="last">
        <a href="bulk-create.php">
            <i class="fa fa-group"></i> Create Collection
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
                <label for="sessionName" class="control-label col-sm-3">Account Type</label>
                <div class="col-sm-9">
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
            <?php if(userHasFlag($_SESSION['username'], 'conference')) { ?>
            <div class="form-group">
                <label for="startdate" class="control-label col-sm-3">Start Date</label>
                <div class="col-sm-9">
                    <input type="date" id="startdate" name="startdate" class="form-control date-picker-field" />
                </div>
            </div>
            <div class="form-group">
                <label for="enddate" class="control-label col-sm-3">End Date</label>
                <div class="col-sm-9">
                    <input type="date" id="enddate" name="enddate" class="form-control date-picker-field" />
                </div>
            </div>
            <div class="form-group">
                <label for="enddate" class="control-label col-sm-3">Conference Usernames</label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked="checked" id="useconferenceword" name="useconferenceword" />
                        <label class="form-check-label" for="useconferenceword">Use consistent usernames</label>
                    </div>
                    <p class="form-text text-muted">If you uncheck this box, the usernames will be based on the person's last name. If you leave it checked, the usernames for this collection will all start with the same five-letter word.</p>
                </div>
            </div>
            <?php } ?>
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