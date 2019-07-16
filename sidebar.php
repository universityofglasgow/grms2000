<div class="sidebar">
    <div class="user-panel">
        <?php if(isset($_SESSION['username'])) {
            echo '<img class="user-profile" src="img/avatar-blank.jpg" alt="" data-no-retina="true" />
            <p class="user-name">Alex Walker</p>
            <p class="user-action"><a href="logout.php"><i class="fa fa-lock"></i> Log out</a>';
        } else {
            echo '<img class="user-profile" src="img/avatar-blank.jpg" alt="" data-no-retina="true" />
            <p class="user-name">Guest User</p>
            <p class="user-action"><a href="login.php"><i class="fa fa-lock"></i> Log in</a>';
        } ?>
    </div>
    <h4>Account Management</h4>
    <ul>
        <li>
            <a href="index.php"><i class="fa fa-home"></i> Home</a>
        </li>
        <li>
            <a href="create.php"><i class="fa fa-plus-circle"></i> Create New Account</a>
        </li>
        <li>
            <a href="mine.php"><i class="fa fa-list"></i> Accounts I Created</a>
        </li>
    </ul>
    <?php if(userHasFlag('conference')) { ?>
    <h4>Account Collections</h4>
    <ul>
        <li>
            <a href="bulk-create.php"><i class="fa fa-users"></i> New Collection</a>
        </li>
        <li>
            <a href="collections.php"><i class="fa fa-pencil"></i> My Collections</a>
        </li>
    </ul>
    <?php } ?>
    <?php if(userHasFlag('manager')) { ?>
    <h4>Bulk Account Management</h4>
    <ul>
        <li>
            <a href="bulk-edit.php"><i class="fa fa-table"></i> Manage With Spreadsheet</a>
        </li>
    </ul>
    <?php } ?>
</div>