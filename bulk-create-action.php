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
    
     $myAccountTypes = getMyAccountTypes();
    // Don't trust the posted account type - actually check they are allowed to create it.
    if(!isset($myAccountTypes[$_POST['type']])) {
        die('Incorrect account type!');
    }
    
    // If dates are set, check and use them. Otherwise, use the defaults
    
    $accountStartDate = prettifyDate(time(), 'ymd');
    
    if(userHasFlag($_SESSION['username'], 'conference')) {
        if(!empty($_POST['startdate'])) {
            $accountStartDate = $_POST['startdate'];
        }
        
        if(!empty($_POST['startdate']) && !empty($_POST['enddate'])) {
            if(strtotime($_POST['startdate']) > strtotime($_POST['enddate'])) {
                die('Your start date is after your end date.');
            }
        
        }
        
        if(!empty($_POST['enddate'])) {
            $accountEndDate = $_POST['enddate'];
        } else {
            $accountEndDate = prettifyDate(strtotime($accountStartDate)+(getDurationForAccountType($_POST['type'])*86400), 'ymd');
        }
    }
    
    $useConferenceWord = false;
    
    var_dump($_POST);
    
    if(!empty($_POST['useconferenceword'])) {
        // User has chosen consistent usernames. Get a conference word.
        $useConferenceWord = true;
        $word = getConferenceWord();
        echo 'Conference mode enabled. Word is '.$word;
    }
    
    // CSV exists, so create a new session.
    
    $collection = createCollection($_POST['sessionName']);
    $accountsCreate = Array();
    $accountsExtended = Array();
    $accountsGUID = Array();
    
    $result = Array();
    
    $i = 1;
    
    foreach($csv as $currentUser) {
        
        $thisResult = Array('email'=>$currentUser[0]);
        
        $status = 'not-tried';
        
        // Only process if first column has an "@"
        
        if(count($currentUser) == 3) {
            if(strpos($currentUser[0], '@', 1) !== false) {
                $doesExist = checkIfUserExists($currentUser[0]);
                if($doesExist === false) {
                    // Account doesn't exist. Create one.
                    if($useConferenceWord) {
                        $thisUsername = $word.str_pad($i, 3, "0", STR_PAD_LEFT);
                    } else {
                        $thisUsername = false;
                    }
                    $newUsername = createAccount($currentUser[1], $currentUser[2], $currentUser[0], $accountStartDate, $accountEndDate, $_POST['type'], $thisUsername);
                    $thisResult['status'] = 'created';
                    $thisResult['username'] = $newUsername;
                    addUserToCollection($newUsername, $collection);
                    saveToCollectionLog($collection, $currentUser[0], $newUsername, 'local', 'created');
                } else {
                    switch($doesExist['type']) {
                        case 'local':
                            extendDate($doesExist['username'], $_POST['duration']);
                            $thisResult['status'] = 'extended';
                            $thisResult['username'] = $doesExist['username'];
                            addUserToCollection($doesExist['username'], $collection);
                            saveToCollectionLog($collection, $currentUser[0], $doesExist['username'], 'local', 'existed');
                            break;
                        case 'ldap':
                            $thisResult['status'] = 'ldap';
                            $thisResult['username'] = $doesExist['username'];
                            saveToCollectionLog($collection, $currentUser[0], $doesExist['username'], 'ldap', 'external');
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
        
        $i++;
    }
    
    if($useConferenceWord) {
        markConferenceWordAsUsed($word);
    }
        
    $_SESSION['lastbatch'] = $results;
    
    header('location: bulk-create-complete.php?collection='.$collection);
    
?>