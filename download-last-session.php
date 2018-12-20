<?php
    
    require_once('functions.php');
    
    header('Content-Type: application/csv');
    header('Content-disposition: filename="user-import-'.date('Y-m-d', time()).'.csv"');
    
    echo '"email","username","status"'.PHP_EOL;
    
    foreach($_SESSION['lastbatch'] as $user) {
        echo '"'.$user['email'].'",'.'"'.$user['username'].'",';
        switch($user['status']) {
            case 'created':
                echo '"new account created"';
                break;
            case 'extended':
                echo '"existing account extended"';
                break;
            case 'ldap':
                echo '"person already has guid"';
                break;
            case 'skipped':
                echo '"invalid data - record skipped"';
        }
        echo PHP_EOL;
    }
    
?>