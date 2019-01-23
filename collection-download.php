<?php
    
    require_once("functions.php");
    
    
    if(isset($_GET['collection'])) {
        // Proccesing an entire cohort
        
        // Check hash matches
        $collectionDetails = getCollectionDetails($_GET['collection']);
        
        if($_GET['token'] != sha1($actionSalt.$_SESSION['username'].'download'.$collectionDetails->id.$collectionDetails->creationdate)) {
            die('Access denied!'.$_GET['token'].' - '.sha1($actionSalt.$_SESSION['username'].'download'.$collectionDetails->id.$collectionDetails->creationdate));
        }
        
        $usersInCollection = getUserDetailsFromCollectionLog($collectionDetails->id);
        
        $headings = Array(q('username'), q('password'), q('firstname'), q('lastname'), q('email'), q('status'));
        
        $output = Array();
        
        foreach($usersInCollection as $user) {
            $output[] = Array(
                $user->username,
                $user->password,
                $user->firstname,
                $user->lastname,
                $user->email,
                $user->status
            );
        }
        
        header('Content-Type: application/csv');
        header('Content-disposition: filename="collection-details-'.$_GET['collection'].'.csv"');
        
        echo implode(', ', $headings).PHP_EOL;
        
        foreach($output as $row) {
            echo implode(', ', $row).PHP_EOL;
        }
    }
    
?>