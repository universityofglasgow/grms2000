<?php
    
    require_once('functions.php');
    
    require_login();
    
    if(strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION)) != 'csv') {
        die('This is not a CSV');
    }

    if ($_FILES["file"]["error"] === 0) {
        $csv = array_map('str_getcsv', file($_FILES["file"]["tmp_name"]));
    } else {
        die('Invalid File!');
    }
    
    // CSV exists, so create a new session.
    
    $collection = createCollection($_POST['sessionName']);
    $accountsCreate = Array();
    $accountsExtended = Array();
    $accountsGUID = Array();
    
    $result = Array();
    
    foreach($csv as $currentUser) {
        
        $thisResult = Array('email'=>$currentUser[0]);
        
        $status = 'not-tried';
        
        // Only process if first column has an "@"
        
        if(count($currentUser) == 3) {
            if(strpos($currentUser[0], '@', 1) !== false) {
                $doesExist = checkIfUserExists($currentUser[0]);
                if($doesExist === false) {
                    // Account doesn't exist. Create one.
                    $newUsername = createAccount($currentUser[1], $currentUser[2], $currentUser[0], $_POST['duration']);
                    $thisResult['status'] = 'created';
                    $thisResult['username'] = $newUsername;
                    addUserToCollection($newUsername, $collection);
                } else {
                    switch($doesExist['type']) {
                        case 'local':
                            extendDate($doesExist['username'], $_POST['duration']);
                            $thisResult['status'] = 'extended';
                            $thisResult['username'] = $doesExist['username'];
                            addUserToCollection($doesExist['username'], $collection);
                            break;
                        case 'ldap':
                            $thisResult['status'] = 'ldap';
                            $thisResult['username'] = $doesExist['username'];
                            break;
                    }
                }
            } else {
                $thisResult['status'] = 'skipped';
                $thisResult['reason'] = 'First column is not an email address';
            }
        } else {
            $thisResult['status'] = 'skipped';
            $thisResult['reason'] = 'Incorrect number of columns. Should be 3, is actually '.count($currentUser).'.';
        }
        
        $results[] = $thisResult;
    }
        
    $_SESSION['lastbatch'] = $results;
    
    header('location: bulk-create-complete.php?collection='.$collection);
    
?>