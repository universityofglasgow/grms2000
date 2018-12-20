<?php
    
    require_once('functions.php');
    
    require_login();

    if ($_FILES["file"]["error"] === 0) {
        $csv = array_map('str_getcsv', file($_FILES["file"]["tmp_name"]));
    } else {
        die('Invalid File!');
    }
    
    // CSV exists, so create a new session.
    
    $results = Array();
    
    foreach($csv as $currentUser) {
        
        $usernameToEdit = $currentUser[0];
        
        // If there's an @, this is the user's email address. Get the username.
        
        if(strpos($currentUser[0], '@', 1) !== false) {
            $userDetails = getUserDetailsByEmail($currentUser[0]);
            if($userDetails == false) {
                continue;
            }
            $usernameToEdit = $userDetails->username;
        } else {
            // Double check this username sctually exists
            $userDetails = getUserDetails($currentUser[0]);
            if($userDetails == false) {
                continue;
            }
        }
        
        // Now we know the user exists. Check permissions before continuing
        
        // Not necessary for certain actions
        
        switch($_POST['action']) {
            case 'extend30':
                extendDate($usernameToEdit, 30);
                break;
            case 'extend180':
                extendDate($usernameToEdit, 180);
                break;
            case 'extend365':
                extendDate($usernameToEdit, 365);
                break;
            case 'reset':
                resetPassword($usernameToEdit);
        }
        
        $results[$usernameToEdit] = $_POST['action'];
    }
        
    $_SESSION['lastbatch'] = $results;
    
    header('location: bulk-edit-complete.php');
    
?>