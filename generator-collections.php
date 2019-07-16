<?php
    
    require_once('functions.php');
    
    echo PHP_EOL;
    
    for($i = 31; $i <= 50; $i++) {
        
        $numberOfUsers = rand(25,1000);
        
        $users = getRandomUsers($numberOfUsers);
        
        foreach($users as $user) {
            AddUserToCollection($user, $i);
        }
        
    }
    
?>