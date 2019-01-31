<?php

	include_once("functions.php");
	require_login();

    $activeSection = 'dashboard';

	$pageTitle = "Create Account";
	
	require("header.php");

?>

<ul class="breadcrumb">
    <li>
        <a href="index.php">
            <i class="fa fa-home"></i> Home
        </a>
    </li>
    <li class="last">
        <a href="create.php">
            <i class="fa fa-plus-circle"></i> Create Account
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
		<h3>Create Account</h3>
	</div>
	<div class="island-body">
    	<form class="form-horizontal" id="single-creation-form" method="post" action="create-process.php">
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-3">Email Address</label>
                <div class="col-sm-9">
                    <input type="email" id="email" name="email" class="form-control" required="required" />
                </div>
            </div>
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-3">First Name</label>
                <div class="col-sm-9">
                    <input type="text" id="firstname" name="firstname" class="form-control" required="required" />
                </div>
            </div>
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-3">Last Name</label>
                <div class="col-sm-9">
                    <input type="text" id="lastname" name="lastname" class="form-control" required="required" />
                </div>
            </div>
            <div class="form-group">
                <label for="sessionName" class="control-label col-sm-3">Account Type</label>
                <div class="col-sm-9">
                    <select id="type" name="type" class="form-control">
                        <?php
                            $accountTypes = $_SESSION['types'];
                            
                            foreach($accountTypes as $code=>$friendlyName) {
                                echo '<option value="'.$code.'">'.$friendlyName['name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-push-3">
                   <button type="submit" class="btn btn-block btn-success"><i class="fa fa-plus-circle"></i> Create Account</button>
                </div>
            </div>
    	</form>
	</div>
</div>

<?php include("footer.php"); ?>