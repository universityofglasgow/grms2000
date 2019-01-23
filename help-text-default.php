<?php
    
    // Don't change this file! Make a copy and call it 'help-text.php'. It will
    // automatically be included instead of this file, and it won't be
    // overwritten when you upgrade.

?>
<div class="island">
    <div class="island-header">
        <h3>Table of Contents</h3>
    </div>
    <div class="island-body">
        <ul>
            <li>
                <a href="#guest-accounts">
                    What are guest accounts and who needs them?
                </a>
            </li>
            <li>
                <a href="#account-types">
                    What are the different account types?
                </a>
            </li>
            <li>
                <a href="#extending-accounts">
                    When do accounts expire? What if I need an account for
                    a longer period of time?
                </a>
            </li>
        </ul>
    </div>
</div>

<a name="guest-accounts"></a>
<div class="island">
    <div class="island-header">
        <h3>What are guest accounts and who needs them?</h3>
    </div>
    <div class="island-body">
        <p><?php echo $systemName; ?> is a system for managing guest accounts.
        These accounts are useful for giving external users access to systems,
        including:</p>
        <ul>
            <li>Letting conference guests access the University wi-fi during
            their stay</li>
            <li>Giving guest lecturers and tutors access to Moodle</li>
        </ul>
        <p>Not every external user needs a guest account. If an external user is
        being paid by the University, for example an external examiner or
        auditor, they will already have a GUID.</p>
        <p>Don't worry if you think a user might have a GUID. When you try and
        make a new guest account, <?php echo $systemName; ?> will check if that
        person has a GUID. If they do, it won't make a guest account and will
        tell you what their GUID is.</p>
    </div>
</div>

<a name="account-types"></a>
<div class="island">
    <div class="island-header">
        <h3>What are the different account types?</h3>
    </div>
    <div class="island-body">
        <p>Depending on your role within the University, you might have the
        option to create different account types. Different account types let
        the guest user access different systems, and they also last for
        different periods of time.</p>
        <ul>
            <li><strong>Visitor Wireless</strong> &ndash; all members
            of staff can create these accounts. They let visitors connect to
            the <strong>GUVisitor</strong> wireless network. They last for 7
            days, but you can extend them if you need to.</li>
            <li><strong>Conference Wireless</strong> &ndash; these are similar
            to Visitor Wireless accounts, but the recipient receives an email
            with details that are more relevant to conference attendees. These
            accounts last for 28 days.</li>
            <li><strong>Desktop Login</strong> &ndash; users can log in to
            University desktop computers (SSD or CSCE) in the same way an
            ordinary user with a GUID can. They're typically used for courses
            and are created by local IT support staff. They can last for up to
            28 days.</li>
            <li><strong>External eJournal</strong> &ndash; accounts are created
            by library staff. They let external users access online journals
            and e-Resources through Shibboleth. They last for 7 days.</li>
            <li><strong>Moodle Accounts</strong> &ndash; used by guest lecturers
            and external auditors. These accounts can last for up to a year,
            and can be renewed for another year at any time.</li>
        </ul>
    </div>
</div>

<a name="account-types"></a>
<div class="island">
    <div class="island-header">
        <h3>When do accounts expire? What if I need an account for a longer
        period of time?</h3>
    </div>
    <div class="island-body">
        <p>There are different types of accounts, depending on what they're
        being used for. Each type of account lasts for a certain period of time.
        You can find<a href="#account-types">a list of the different account
        types</a> elsewhere on this page.</p>
        <p>For auditing and security reasons, accounts expire after a certain
        number of days. If you don't need the account any more, you can let it
        expire. If you still need it, you can extend the account to keep it
        active. All you have to do is find the account in
        <?php echo $systemName; ?> and click the <strong>Extend Expiry</strong>
        button. This button adds the same number of days to the account's
        lifetime, starting from the day you click the button. For example,
        wireless accounts usually last 7 days. If you click the
        <strong>Extend</strong> button, the account will expire seven days from
        today.</p>
    </div>
</div>