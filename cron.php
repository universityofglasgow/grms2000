<?php
    
    echo PHP_EOL;
    
    require_once('functions.php');
    
    $timeAtStart    = time();
    
    $minute         = date('i', $timeAtStart);
    $hour           = date('G', $timeAtStart);
    $weekday        = date('N', $timeAtStart); // 1 = Monday, 2 = Tuesday ... 7 = Sunday
    $day            = date('j', $timeAtStart);
    
    $dateStamp      = prettifyDate($timeAtStart, 'ymd');
    $expiryDate     = prettifyDate($timeAtStart-(86400), 'ymd');
    $deletionDate   = prettifyDate($timeAtStart-(86400*30), 'ymd');
    
    $all            = (isset($argv[1]) && ($argv[1] == 'all'));
    
    $minute = floor(($minute/10)) * 10;
    
    out($systemName.' MegaCron by Alex Walker, University of Glasgow', '*');
    out('Running at '.date('H:i:s', $timeAtStart).' on '.date('l jS F Y', $timeAtStart));
    
    
    if(cronMatch($minute, 0)) {
        // Hourly Stuff
        
        if(cronMatch($hour, 0)) {
            // Daily Stuff
                        
            out('Flagging accounts that expired yesterday...', '-');
            
            ORM::rawExecute('UPDATE users SET active=3 WHERE expiryDate = "'.$expiryDate.'" and active=1');
            
            out('Flagging accounts that are due to become active today...', '-');
            
            ORM::rawExecute('UPDATE users SET active=1 WHERE creationDate = "'.$dateStamp.'" and active=2');
            
            out('Flagging accounts that are due for LDAP deletion...', '-');
            
            ORM::rawExecute('UPDATE users SET active=4 WHERE expiryDate = "'.$deletionDate.'" and active=3');
            
            if(cronMatch($weekday, 7)) {
                // Weekly Stuff
            }
            
            if(cronMatch($day, 1)) {
                // Monthly Stuff
            }
        }
    }
    
    out($systemName.' MegaCron Complete', '*');
    
    echo PHP_EOL;

?>