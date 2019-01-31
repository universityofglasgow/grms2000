<?php
    
    require_once("functions.php");
    
    
    if(isset($_GET['username'])) {
        // Processing one user
        
        // Check hash matches
        $userDetails = getUserDetails($_GET['username']);
        
        if($_GET['token'] != sha1($actionSalt.$_SESSION['username'].$_GET['action'].$userDetails->userid.$userDetails->username)) {
            die('Access denied!');
        }
        
        // Build array of users to deal with (only one)
        
        $usersToProcess = Array($userDetails->username);
    }
    
    if(isset($_GET['collection'])) {
        // Proccesing an entire cohort
        
        // Check hash matches
        $collectionDetails = getCollectionDetails($_GET['collection']);
        
        if($_GET['token'] != sha1($actionSalt.$_SESSION['username'].$_GET['action'].$collectionDetails->id.$collectionDetails->creationdate)) {
            die('Access denied!');
        }
        
        // Build array of users to deal with
        
        $usersToProcess = Array();
        
        $usersInCollection = getUsersInCollection($collectionDetails->id);
        
        foreach($usersInCollection as $user) {
            $usersToProcess[] = $user;
        }
    }
    
    // Now we've checked everything's OK and built the list of users - perform the action:
    
    $_SESSION['lastprocess'] = Array();
    
    foreach($usersToProcess as $username) {
        switch($_GET['action']) {
            case 'resetpassword':
                resetPassword($username);
                $_SESSION['lastprocess'][] = Array(
                    'username' => $username,
                    'colour'   => 'success',
                    'status'   => 'This user\'s password was reset and the password has been emailed to them.'
                );
                break;
            case 'extend':
                extendDate($username);
                $_SESSION['lastprocess'][] = Array(
                    'username' => $username,
                    'colour'   => 'success',
                    'status'   => 'This user\'s expiry date has been extended.'
                );
                break;
            case 'activate':
                setAccountActivation($username, 1);
                $_SESSION['lastprocess'][] = Array(
                    'username' => $username,
                    'colour'   => 'success',
                    'status'   => 'This account has been activated.'
                );
                break;
            case 'deactivate':
                setAccountActivation($username, 0);
                $_SESSION['lastprocess'][] = Array(
                    'username' => $username,
                    'colour'   => 'success',
                    'status'   => 'This account has been deactivated.'
                );
                break;
        }
        logAction($_GET['action'], $username);
        updateLastTouched($username);
    }
    
    if(count($usersToProcess) !== 0) {
        header('location: processed.php');
    }
    
    die();
    
?>