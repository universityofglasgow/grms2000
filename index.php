<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Homepage";
	
	require("header.php");

?>
<div class="island">
    <div class="island-header">
        <h3>Welcome to <?php echo $systemName; ?></h3>
    </div>
    <div class="island-body">
        <ul class="row dashboard-icons">
            <li class="col-xs-4">
                <a href="create.php">
                    <span class="icon"><i class="fa fa-plus-circle"></i></span>
                    <span class="title">Create an Account</span>
                    <span class="description">Create one new account by typing the details into a form.</span>
                </a>
            </li>
            <li class="col-xs-4">
                <a href="bulk-create.php">
                    <span class="icon"><i class="fa fa-users"></i></span>
                    <span class="title">Create Multiple Accounts</span>
                    <span class="description">Upload a spreadsheet of names and email addresses to create a batch of accounts.</span>
                </a>
            </li>
            <li class="col-xs-4">
                <a href="mine.php">
                    <span class="icon"><i class="fa fa-list"></i></span>
                    <span class="title">Recently Created Accounts</span>
                    <span class="description">View a list of all the accounts you recently created.</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<?php include("footer.php"); ?>