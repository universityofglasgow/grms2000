<?php
    
    require_once("functions.php");
    
    $doesExist = checkIfUserExists($_POST['email']);
    
    $_SESSION['lastprocess'] = Array();
    
    if($doesExist === false) {
        $myAccountTypes = getMyAccountTypes();
        // Don't trust the posted account type - actually check they are allowed to create it.
        if(isset($myAccountTypes[$_POST['type']])) {
            $duration = getDurationForAccountType($_POST['type']);
            $newUsername = createAccount($_POST['firstname'], $_POST['lastname'], $_POST['email'], $duration, $_POST['type']);
            $_SESSION['lastprocess'][] = Array(
                    'username' => $newUsername,
                    'status'   => 'This account has been created and the password has been emailed to the user.'
            );
            logAction('create', $newUsername);
        } else {
            die('Access denied');
        }
    } else {
        switch($doesExist['type']) {
            case 'local':
                extendDate($doesExist['username'], $_POST['duration']);
                $_SESSION['lastprocess'][] = Array(
                    'username' => $doesExist['username'],
                    'colour'   => 'info',
                    'status'   => 'This person already has a '.$systemName.' account. Their details are below:'
                );
                logAction('not-created-local', $doesExist['username']);
                break;
            case 'ldap':
                $_SESSION['lastprocess'][] = Array(
                    'username' => false,
                    'colour'   => 'warning',
                    'status'   => 'This person already has a real LDAP account. Their username is <strong>.'.$doesExist['username'].'</strong>'
                );
                logAction('not-created-ldap', $doesExist['username']);
                break;
        }
    }
    header('location: processed.php');
?>