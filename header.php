<!doctype html>
<html>
	<head>
		<title><?php echo $systemName; ?> - <?php echo $pageTitle; ?></title>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="css/font-awesome.css" />
		<link rel="stylesheet" href="css/base.css" />
		<link rel="stylesheet" href="css/jquery-ui.css" />
		<script src="js/jquery.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/jquery-validate.js"></script>
		<script src="js/jquery-tablesort.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/page-setup.js"></script>
	</head>
	<body>
		<div class="page-header">
    		<div class="container">
        		<div class="row">
        		    <div class="col-xs-3">
                        <h1 class="logo"><?php echo $systemName; ?></h1>
                    </div>
                    <div class="col-xs-9">
                        <?php if(isset($_SESSION['username'])) { ?>
                        <form action="search.php" method="get" class="form-horizontal user-search-form">
                			<div class="input-group">
                				<input type="text" name="q" id="q" value="<?php if(isset($_GET['q'])) { echo $_GET['q']; } ?>"class="form-control courses-autocomplete" />
                				<span class="input-group-btn"><button type="submit" class="btn btn-default"><i class="fa fa-search"></i><span class="hidden-xs-fdown"> Search</span></button></span>
                			</div>
                		</form>
                        <?php } ?>
                    </div>
        		</div>
    		</div>
		</div>
		<div class="container">
			
			<div id="panel">
				<div class="row">
    				<?php if(isset($_SESSION['username'])) { ?>
    				<div class="col-sm-9 col-sm-push-3">
                    <?php } else { ?>
                    <div class="col-xs-12">
                    <?php } ?>