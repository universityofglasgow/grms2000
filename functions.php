<?php
    
require_once('idiorm.php');
    
if(php_sapi_name() !== 'cli') {
    session_set_cookie_params(time()+7200, '/grms', 'moodleinspector.gla.ac.uk', false, true);
    session_start();
}

require_once("config.php");
    
function require_login() {
	if(!isset($_SESSION['username'])) {
		header("location: login.php");
	}
}

function getLDAPConnection() {
    
    // I know. I know. This is the result of config being spun off into config.php
    
    global $ldapconfig;
	
	return $ldapconfig;
}

function ldap_authenticate($user, $password) {
    
    $ldapconfig = getLDAPConnection();
    
    if ($user != "" && $password != "") {
        $ds = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
        ldap_bind($ds, $ldapconfig['binduser'], $ldapconfig['bindpass']);
        $r = ldap_search( $ds, $ldapconfig['basedn'], 'cn=' . $user);
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
        if ($r) {
            $result = ldap_get_entries( $ds, $r);
            if ($result[0]) {
                if (ldap_bind( $ds, $result[0]['dn'], $password) ) {
                    processUserFlags($result[0][$ldapconfig['grouparray']]);
	               getMyAccountTypes();
	                if(userHasFlag('grms')) {
	                	$_SESSION['username'] = $result[0][$ldapconfig['usernamefield']][0];
	                	$_SESSION['email'] = $result[0][$ldapconfig['emailfield']][0];
	                	$_SESSION['firstname'] = $result[0][$ldapconfig['firstnamefield']][0];
	                	$_SESSION['surname'] = $result[0][$ldapconfig['lastnamefield']][0];
	                	$_SESSION['fullname'] = $result[0]['givenname'][0] . ' ' . $result[0][$ldapconfig['lastnamefield']][0];
						return 0; // Successful login
					} else {
    					return 1; // No access to Inspector in the flag table
					}
                } else {
                    return 2; // Account exists, incorrect password
                }
            } else {
                return 3; // Account doesn't exist
            }
        } else {
            return 4; // Active Directory isn't happy
        }
    }
    return 5; // Someone submitted a blank form
}

function getMyAccountTypes() {
    global $accountTypes;
    
    $myAccountTypes = Array();
    if(userHasFlag('admin')) {
        $_SESSION['types'] = $accountTypes;
        return true;
    }
    foreach($_SESSION['flags'] as $flag) {
        if(strpos($flag, 'account-') === 0) {
            $myAccountTypes[substr($flag, 8)] = $accountTypes[substr($flag, 8)];
        }
    }
    $_SESSION['types'] = $myAccountTypes;
}

function getDurationForAccountType($code) {
    global $accountTypes;
    
    return $accountTypes[$code]['duration'];
}

function userHasFlag($flag) {
	
	return (isset($_SESSION['flags']['admin']) || isset($_SESSION['flags'][$flag]));
	
}

function searchLDAP($search) {
    $ldapconfig = getLDAPConnection();
	
    $ds=ldap_connect($ldapconfig['host'], $ldapconfig['port']);
    ldap_bind($ds, 'CN=LDAPInspector,ou=service,o=gla', '23jj12ad83px54ad');
    $r = ldap_search( $ds, $ldapconfig['basedn'], $search);
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
    if ($r) {
        $result = ldap_get_entries( $ds, $r);
        if ($result[0]) {
            return($result[0]);
        } else {
            return false;
        }
    }
}

function checkIfUserExists($email) {
    $ldapconfig = getLDAPConnection();
    
    // See if a proper LDAP account exists
    
    $userDetails = searchLDAP($ldapconfig['emailfield'].'='.$email);
                
    if ($userDetails !== false) {
        return Array('type'=>'ldap', 'username'=> $userDetails['cn'][0]);
    }
    
    $userDetails = searchLDAP($ldapconfig['altemailfield'].'='.$email);
                
    if ($userDetails !== false) {
        return Array('type'=>'ldap', 'username'=> $userDetails['cn'][0]);
    }
    
    // See if a local account exists
    
    $userExists = ORM::forTable('users')->where(Array('email'=>$email))->findOne();
    error_log($email, true);
    error_log($userExists, true);
    
    if($userExists !== false) {
        return Array('type'=>'local', 'username'=>$userExists->username);
    }
    
    return false;
}

function generateUsername($lastname) {
    
    $cleanLastName = substr(preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($lastname)), 0, 4);
    
    $number = ORM::forTable('users')->whereLike(Array('username'=>$cleanLastName.'%'))->count();
    
    return $cleanLastName.str_pad($number+1, 4, "0", STR_PAD_LEFT);
    
}

function generatePassword() {
    $c = 'bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ23456789';
    $l = rand(8,12);
    $p = substr(str_shuffle(sha1(rand().time()).$c),0,$l);
    return $p;
}

function extendDate($username, $duration=false) {
    $userExists = ORM::forTable('users')->where(Array('username'=>$username))->findOne();
    
    if($duration===false) {
        $duration = getDurationForAccountType($userExists->type);
    }
    
    $currentExpiryTimestamp = strtotime($userExists->expiryDate);
    error_log('CURR: '.$currentExpiryTimestamp);
    $potentialExpiryTimestamp = time()+($duration*86400);
    error_log('PROP: '.$potentialExpiryTimestamp);
    error_log('PTXT: '.prettifyDate($potentialExpiryTimestamp, 'ymd'));
    
    error_log(print_r($userExists, true));
    
    if($currentExpiryTimestamp < $potentialExpiryTimestamp) {
        $userExists->expiryDate = prettifyDate($potentialExpiryTimestamp, 'ymd');
    }
    
    $userExists->save();
}

function resetPassword($username) {
    $userDetails = getUserDetails($username);
    $newPassword = generatePassword();
    
    $user = ORM::forTable('users')->where(Array('username'=>$username))->findOne();
    $user->password = $newPassword;
    $user->save();
    
    sendEmail($userDetails->email, $userDetails->firstname, $userDetails->username, $newPassword, 'reset');
    
    return $newPassword;
}

function setAccountActivation($username, $active=0) {
    $user = ORM::forTable('users')->where(Array('username'=>$username))->findOne();
    $user->active = $active;
    $user->save();
}

function createAccount($firstname, $lastname, $email, $startDate, $endDate, $type, $username=false) {
    $user = ORM::for_table('users')->create();
    if($username === false) {
        $username = generateUsername($lastname);
    }
    $user->username = $username;
    $user->password = generatePassword();
    $user->firstname = $firstname;
    $user->lastname = $lastname;
    $user->email = $email;
    $user->creationDate = $startDate;
    $user->expiryDate = $endDate;
    if(isset($_SESSION['username'])) {
        $user->creator = $_SESSION['username'];
    } else {
        $user->creator = 'aw262w';
    }
    $user->active = 1;
    $user->type = $type;
    $user->save();
    
    //sendEmail($email, $firstname, $lastname, $password, 'created');
    
    return $username;
}

function getUserDetails($username) {

    $user = ORM::forTable('users')->where(Array('username'=>$username))->findOne();
    
    return $user;
}

function getAccountsMatching($term) {
    
    $searchTerm='%'.$term.'%';
    
    $users = ORM::forTable('users')->raw_query('SELECT * FROM users WHERE CONCAT(firstname, " ", lastname) like :term or username like :term or email like :term', array('term'=>$searchTerm))->findMany();
        
    $results = Array();
    
    foreach($users as $thisUser) {
        $results[] = $thisUser->username;
    }
    
    return $results;
    
}

function getUserDetailsByEmail($email) {
    
    $user = ORM::forTable('users')->where(Array('email'=>$email))->findOne();
    
    return $user;
}

function outputUserPanel($username) {
    global $actionSalt;
    
    $userDetails = getUserDetails($username);
    
    echo '<div class="user-card">';
    echo '<img src="img/avatar-blank.jpg" alt="" />';
    echo '<h3><span class="name">'.$userDetails->firstname.' '.$userDetails->lastname.'</span><span class="username">'.$userDetails->username.'</span><span class="status-icon">';
    switch($userDetails->active) {
        case 0:
            echo '<span class="fail">Disabled <i class="fa fa-clock-o"></i></span>';
            break;
        case 1:
            echo '<span class="good">Active <i class="fa fa-calendar-check-o"></i></span>';
            break;
        case 2:
            echo '<span class="warn">Not Active Yet <i class="fa fa-calendar-times-o"></i></span>';
            break;
        case 3:
            echo '<span class="fail">Expired <i class="fa fa-clock-o"></i></span>';
            break;
        case 4:
            echo '<span class="fail">Expired <i class="fa fa-clock-o"></i></span>';
            break;
    }
    echo '</span></h3>';
    echo '<h4>'.$userDetails->email.'</h4>';
    echo '<p>Created on '.prettifyDate(strtotime($userDetails->creationDate), 'sd').'</p><p>Expires on '.prettifyDate(strtotime($userDetails->expiryDate), 'sd').'</p>';

    echo '<div class="account-tools"><div class="btn-toolbar">';
    switch($userDetails->active) {
        case 0:
             echo '<a href="action.php?username='.$userDetails->username.'&action=activate&token='.sha1($actionSalt.$_SESSION['username'].'activate'.$userDetails->userid.$userDetails->username).'" class="btn btn-sm btn-success"><i class="fa fa-power-off"></i> Activate</a>';
            break;
        case 1:
            echo '<a href="action.php?username='.$userDetails->username.'&action=resetpassword&token='.sha1($actionSalt.$_SESSION['username'].'resetpassword'.$userDetails->userid.$userDetails->username).'" class="btn btn-sm btn-info"><i class="fa fa-unlock-alt"></i> Reset Password</a>';
            echo '<a href="action.php?username='.$userDetails->username.'&action=extend&token='.sha1($actionSalt.$_SESSION['username'].'extend'.$userDetails->userid.$userDetails->username).'" class="btn btn-sm btn-primary"><i class="fa fa-calendar"></i> Extend Expiry Date</a>';
            echo '<a href="action.php?username='.$userDetails->username.'&action=deactivate&token='.sha1($actionSalt.$_SESSION['username'].'deactivate'.$userDetails->userid.$userDetails->username).'" class="btn btn-sm btn-danger"><i class="fa fa-power-off"></i> Deactivate</a>';
            break;
        case 2:
             echo '<a href="action.php?username='.$userDetails->username.'&action=activate&token='.sha1($actionSalt.$_SESSION['username'].'activate'.$userDetails->userid.$userDetails->username).'" class="btn btn-sm btn-success"><i class="fa fa-power-off"></i> Activate</a>';
            break;
        case 3:
            echo '<a href="action.php?username='.$userDetails->username.'&action=activate&token='.sha1($actionSalt.$_SESSION['username'].'activate'.$userDetails->userid.$userDetails->username).'" class="btn btn-sm btn-success"><i class="fa fa-power-off"></i> Renew</a>';
            break;
        case 4:
             echo '<a href="action.php?username='.$userDetails->username.'&action=activate&token='.sha1($actionSalt.$_SESSION['username'].'activate'.$userDetails->userid.$userDetails->username).'" class="btn btn-sm btn-success"><i class="fa fa-power-off"></i> Renew</a>';
            break;
    }
    
    
    if($userDetails->active == 1) {
        
    } else {
       
    }
    echo '</div></div>';
    echo '</div>';
}

function prettifyDate($date, $format="sdt") {
    switch ($format) {
	    case "sdt":
	    	return date("D j M Y, H:i", $date);
	    	break;
        case "sd":
	    	return date("l jS F Y", $date);
	    	break;
	    case "ssd":
	    	return date("j F Y", $date);
	    	break;
	    case "sst":
	    	return date("H:i", $date);
	    	break;
        case "sdt":
	    	return date("l jS F Y H:i", $date);
	    case 'ymd':
	        return date("Y-m-d", $date);
	    case 'ymdhi':
	        return date("Y-m-d H:i", $date);
	    case 'ymdhis':
	        return date("Y-m-d H:i:s", $date);
    }
}

function getMyAccounts($limit=250) {
    
    $accounts = ORM::forTable('users')->where(Array('creator'=>$_SESSION['username']))->orderByDesc('id')->limit($limit)->findMany();
    
    $results = Array();
    
    foreach($accounts as $account) {
        $results[] = $account->username;
    }
    
    return $results;
}

function sendEmail($email, $firstname, $username, $password, $type) {
    global $systemName;
    switch($type) {
        case 'created':
            $emailIntro = 'An account has been created for you on the University of Glasgow\'s Moodle.';
            $subject = 'Your University of Glasgow Moodle account';
            break;
        case 'res	et':
            $emailIntro = 'The password for your account on the University of Glasgow\'s Moodle has been reset.';
            $subject = 'Your University of Glasgow Moodle account';
            break;
    }
    
	$emailText = 'Hi '.$firstname.PHP_EOL.PHP_EOL.$emailIntro.PHP_EOL.PHP_EOL.'Username: '.$username.PHP_EOL.'Password: '.$password.PHP_EOL.PHP_EOL.'You can log into the University of Glasgow Moodle at https://bertha.mis.gla.ac.uk/moodle35'.PHP_EOL;
	
    $headers = 'From: '.$systemName.'<moodlesystem@glasgow.ac.uk>';

	echo 'I have tried to send am email but wasn\'t allowed.';
	//mail($email, $subject, $emailText, $headers);
}

function createCollection($sessionName) {
    $collection = ORM::forTable('collection')->create();
    $collection->username = $_SESSION['username'];
    $collection->name = $sessionName;
    $collection->createionDate = prettifyDate(time(), 'ymdhi');
    $collection->save();
    
    return $collection->id;
}

function addUserToCollection($username, $collection) {
    $mem = ORM::forTable('usersInCollection')->create();
    $mem->username = $username;
    $mem->collectionid = $collection;
    $mem->save();
}

function getCollectionDetails($collection) {
    
    $user = ORM::forTable('collection')->where(Array('id'=>$collection))->findOne();
    
    return $user;
}

function getMyCollections() {
    
    $myCollections = ORM::forTable('collection')->where(Array('username'=>$_SESSION['username']))->orderByDesc('id')->findMany();
    
    $collections = Array();
    
    foreach($myCollections as $coll) {
        $collections[] = $coll->id;
    }
    
    return $collections;
}

function getUsersInCollection($collection) {
    
    $usersInCollection = ORM::forTable('usersInCollection')->where(Array('collectionid'=>$collection))->findMany();
    
    $users = Array();
    
    foreach($usersInCollection as $thisUser) {
        $users[] = $thisUser->username;
    }
    
    return $users;
}

function getRandomUsers($num=100) {
    
    $users = ORM::forTable('users')->raw_query('SELECT * FROM users ORDER BY RAND() LIMIT '.$num)->findMany();
    
    $results = Array();
    
    foreach($users as $thisUser) {
        $results[] = $thisUser->username;
    }
    
    return $results;
}

function getUserDetailsFromCollectionLog($collection) {
    global $ldapconfig, $systemName;
    
    $usersInCollection = ORM::forTable('collectionLog')->where(Array('collection'=>$collection))->findMany();
    
    $users = Array();
    
    foreach($usersInCollection as $thisUser) {
        $me = new stdClass();
        $me->username = $thisUser->username;
        $me->email = $thisUser->email;
        $me->type = $thisUser->type;
        
        switch($thisUser->type) {
            case 'local':
                $thisUserDetails = getUserDetails($thisUser->username);
                $me->firstname = $thisUserDetails->firstname;
                $me->lastname = $thisUserDetails->lastname;
                $me->password = $thisUserDetails->password;
                $me->expirydate = $thisUserDetails->expirydate;
                break;
            case 'guid':
                $thisUserDetails = searchLDAP('mail='.$thisUser->email);
                $me->firstname = $thisUserDetails[$ldapconfig['firstnamefield']][0];
                $me->lastname = $thisUserDetails[$ldapconfig['lastnamefield']][0];
                $me->password = '-';
                $me->expirydate = '-';
                break;
        }
        
        switch($thisUser->status) {
            case 'created':
                $me->status = 'This account was created when you made this collection.';
                break;
            case 'existed':
                $me->status = 'This user already existed, and was added to this collection. The expiry date was extended.';
                break;
            case 'external':
                $me->status = 'This user already had a GUID. This cannot be managed through '.$systemName;
                break;
            default:
                $me->status = 'Unknown status: '.$thisUser->status;
        }
                
        $users[] = $me;
    }
    
    return $users;
}

function updateLastTouched($username) {
    $row = ORM::forTable('users')->where(Array('username'=>$username))->findOne();
    $row->set('lasttouched', prettifyDate(time(), 'ymd'));
    $row->save;
}

function getConferenceWord() {
    
    $row = ORM::forTable('conferenceWords')->where(Array('alreadyUsed'=>0))->orderByAsc('word')->findOne();
    
    return $row->word;
}

function markConferenceWordAsUsed($word) {
    $row = ORM::forTable('conferenceWords')->where(Array('word'=>$word))->findOne();
    
    $row->set('alreadyUsed', 1);
    
    $row->save;
}

function createConference($name, $startDate, $endDate) {
    $conference = ORM::for_table('conference')->create();
    $conference->name = $name;
    $conference->startDate = $startDate;
    $conference->endDate = $endDate;
    $conference->creator = $_SESSION['username'];
    $conference->active = 1;
    
    $conference->save();
    
    return $conference->id;
}

function saveToCollectionLog($collection, $email, $username, $type, $status) {
    $log = ORM::for_table('collectionLog')->create();
    $log->collection = $collection;
    $log->email = $email;
    $log->username = $username;
    $log->type = $type;
    $log->status = $status;
    
    $log->save();
    
    return $log->id;
}

function createConferenceAccounts($id, $word, $quantity) {
    for($i=1;$i<=$quantity;$i++) {
        $accountName = $word.str_pad($i, 3, "0", STR_PAD_LEFT);
        $password = generatePassword();
        echo 'Creating account '.$accountName.' with password '.$password;
    }
}

function outputCollectionPanel($collection) {
    global $actionSalt;
    
    $collectionDetails = getCollectionDetails($collection);
    
    $usersInCollection = getUsersInCollection($collection);
    
    echo '<div class="user-card">';
    echo '<h3><span class="name"><a href="collection.php?id='.$collection.'">'.$collectionDetails->name.'</a></span>';
    echo '</h3>';
    echo '<h4>';
    if (count($usersInCollection) > 0) {
        $firstUser = getUserDetails($usersInCollection[0]);
        echo 'Contains '.count($usersInCollection).' users including '.$firstUser->firstname.' '.$firstUser->lastname;
        if(count($usersInCollection) > 1) {
            $firstUser = getUserDetails($usersInCollection[1]);
            echo ' and '.$firstUser->firstname.' '.$firstUser->lastname;
        }
    } else {
        echo 'There are no users in this collection.';
    }
    echo '</h4>';
    echo '<p>Created on '.prettifyDate(strtotime($collectionDetails->creationdate), 'sd').'</p>';
    echo '<div class="account-tools"><div class="btn-toolbar">';
    echo '<a class="btn btn-sm btn-info" href="action.php?collection='.$collectionDetails->id.'&action=resetpassword&token='.sha1($actionSalt.$_SESSION['username'].'resetpassword'.$collectionDetails->id.$collectionDetails->creationdate).'"><i class="fa fa-lock"></i> Reset Passwords</a>';
    echo '<a class="btn btn-sm btn-primary" href="action.php?collection='.$collectionDetails->id.'&action=extend&days=365&token='.sha1($actionSalt.$_SESSION['username'].'extend'.$collectionDetails->id.$collectionDetails->creationdate).'"><i class="fa fa-calendar"></i> Extend Expiry</a>';
    echo '<a class="btn btn-sm btn-danger" href="action.php?collection='.$collectionDetails->id.'&action=deactivate&token='.sha1($actionSalt.$_SESSION['username'].'deactivate'.$collectionDetails->id.$collectionDetails->creationdate).'"><i class="fa fa-power-off"></i> Deactivate</a>';
    echo '<a class="btn btn-sm btn-success" href="action.php?collection='.$collectionDetails->id.'&action=activate&token='.sha1($actionSalt.$_SESSION['username'].'activate'.$collectionDetails->id.$collectionDetails->creationdate).'"><i class="fa fa-power-off"></i> Activate</a>';
    echo '<a class="btn btn-sm btn-warning" href="collection-download.php?collection='.$collectionDetails->id.'&token='.sha1($actionSalt.$_SESSION['username'].'download'.$collectionDetails->id.$collectionDetails->creationdate).'"><i class="fa fa-list"></i> Download Spreadsheet</a>';
    echo '</div></div></div>';
}

function logAction($action, $subject=false, $username=false) {
    if($username===false) {
        $username = $_SESSION['username'];
    }
    $log = ORM::forTable('log')->create();
    $log->username = $username;
    $log->action = $action;
    if($subject !== false) {
        $log->account = $subject;
    }
    $log->timehappened = prettifyDate(time(), 'ymdhis');
    $log->ip = $_SERVER['REMOTE_ADDR'];
    $log->save();
}

function q($s) {
    return '"'.$s.'"';
}

function cronMatch($var, $val) {
    global $all;
    return ($all || ($var==$val));
}

function s($num, $singular='', $plural='s') {
    echo ($num==1?$singular:$plural);
}

function out($text, $prefix=" ") {
    global $timeAtStart;
    
    $secs = time() - $timeAtStart;
    
    echo '[ '.gmdate('H:i:s', $secs).' ] '.$prefix.' '.$text.PHP_EOL;
}

function getUserFlags($username) {
    
    
    $matchedFlags = ORM::forTable('userFlags')->where(Array('username'=>$username))->findMany();
    
    $flags = Array();

    foreach($matchedFlags as $row) {

        $flags[$row['flag']] = $row['flag'];
    }
    
    return $flags;
}

function processUserFlags($grouparray) {
    global $groupFlags;
    
    unset($groupArray['count']);
    
    $userFlags = getUserFlags($_SESSION['username']);
    
    foreach($grouparray as $group) {
        if(isset($groupFlags[$group])) {
            $userFlags[$groupFlags[$group]] = $groupFlags[$group];
        }
        
    }
    
    $_SESSION['flags'] = $userFlags;
}

function statCreatedToday() {
    $stat = ORM::forTable('users')->where(Array('creationDate'=>prettifyDate(time(), 'ymd')))->count();
    
    return $stat;
}

function statCurrentlyActive() {
    $stat = ORM::forTable('users')->where(Array('active'=>1))->count();
    
    return $stat;
}

function statCollections() {
    $stat = ORM::forTable('collection')->count();
    
    return $stat;
}

function canIDoThisToThisAccount($account, $action, $accountCreator=false) {
    if($accountCreator===false) {
        $accountDetails = getUserDetails($account);
        $accountCreator = $accountDetails->creator;
    }
    
    if(userHasFlag('admin')) {
        return true;
    }
    
    switch($action) {
        case 'see-email':
        case 'extend':
        case 'deactivate':
            return $accountCreator = $_SESSION['username'];
            break;
    }
    
    return false;
}
    
?>