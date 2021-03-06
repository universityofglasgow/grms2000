<?php
    
    // Copy this file and rename it to config.php
    
    // Database used to hold data
    
    $dbsettings = Array(
        'connection_string'  => 'mysql_host=__database_server__;dbname=__database_name__',
        'username'           => '__username__',
        'password'           => '__password__',
        'return_result_sets' => true
    );
    
    // This is used as a salt for generating action tokens for password resets etc.

    $actionSalt = 'k@T3_8u$H';
    
    // The name of the application - displayed throughout the site.

    $systemName = 'GRMS 2000';
    
    // LDAP Binding Details
    
    $ldapconfig['host'] = ' ldap://ldap.myorganisation.ac.uk';
	$ldapconfig['port'] = '636';
	$ldapconfig['basedn'] = 'O=mycompany';
	$ldapconfig['authrealm'] = NULL;
	
	$ldapconfig['binduser'] = 'CN=letmecheckldap,ou=service,o=mycompany';
	$ldapconfig['bindpass'] = 'ld4pb1ndp@ssw0rd';
	
	$ldapconfig['usernamefield'] = 'cn';
	$ldapconfig['firstnamefield'] = 'givenname';
	$ldapconfig['lastnamefield'] = 'sn';
	$ldapconfig['emailfield'] = 'mail';
	
	// These are the types of accounts that you can create.
	//
	// array key - the code stored in the 'users' table
	//
	// name - the friendly name shown in the UI
	//
	// duration - how long each account will last from the day it is created,
	// and how long the 'extend' button adds to the lifetime - in days
	//
	// creationgroup - if a logged in user has the LDAP group, they can create
	// this account type
	
	$accountTypes = Array(
        'moodle'    =>  Array(
                                'name'              => 'Moodle Account',
                                'duration'          => '365',
                                'creationgroup'     =>  ''
                        ),
        'wifi'   =>  Array(
                                'name'              => 'Wifi Account',
                                'duration'          => '30',
                                'creationgroup'     =>  ''
                        )
    );
    
    ORM::configure($dbsettings);
    
    // Maps LDAP groups to roles within the system
    
    groupFlags = Array(
        'cn=ITStaff,o=myorg'    => 'account-wifi',
        'cn=ELearning,o=org'    => 'account-moodle',
        'cn=Sysadmins,o=myorf'  => 'admin'
    );
    
?>