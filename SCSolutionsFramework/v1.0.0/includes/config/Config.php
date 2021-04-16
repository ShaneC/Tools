<?php
 /*
 * General Website Framework
 * @requires PHP >= 5.2
 * @homepage http://shanechism.com
 *
 * Copyright (c) Shane Chism <schism@acm.org> 2011
 * \ Reproduction and distribution prohibited
 * \ Modification permitted as needed for licensed website
 */

if( !defined( "RUNTIME" ) ){
	print( "<b>Error:</b> Invalid file access.<br />Improper runtime environment." );
	exit();
}

# Site Configuration
$sys['page']['title'] = "SC Solutions Framework";

# Database Configuration
$sys['db']['host'] = "localhost";
$sys['db']['user'] = "dbuser";
$sys['db']['pass'] = "password";
$sys['db']['name'] = "client_db";

$sys['db']['prefix'] = "sc_";

# Cookie Configuration
$sys['cookie']['name'] = "SCSolutionsWebApp";
$sys['cookie']['expiration'] = time() + ( 60 * 60 * 24 * 30 * 12 );

# Encryption Configration
# DO NOT CHANGE THESE VALUES AFTER INSTALL
# AS IT WILL ALMOST CERTAINLY BREAK YOUR INSTALLATION
$sys['sec']['salt'] = "{insert password SALT key here}";
$sys['sec']['aeskey'] = "{insert AES key here}";

# Facebook Configuration
# Facebook Application Settings
$sys['fb']['appID'] = "";
$sys['fb']['apiKey'] = "";
$sys['fb']['appSecret'] = "";
$sys['fb']['userpermissions'] = array( "about_me", "activities", "birthday", "email",
										"education_history", "hometown", "interests",
										"photo_video_tags", "photos", "relationships",
										"relationship_details", "religion_politics", "status",
										"videos", "website", "work_history" );

# Troubleshooting and Debuging
$sys['debug']['mode'] = true;
$sys['debug']['errLogFileName'] = "err_log.log";

?>