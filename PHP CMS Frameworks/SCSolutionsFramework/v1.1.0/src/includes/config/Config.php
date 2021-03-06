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
$sys['site']['title'] = "SC Solutions Framework";

# Database Configuration
// -- Select Database Connection Type
// Options: mysql | mssql
$sys['db']['type'] = "mssql";
// -- Access Credentials
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
$sys['sec']['salt'] = "{insert password SALT key}";
$sys['sec']['aeskey'] = "{insert symmetric AES key}";

# Facebook Configuration
# Facebook Application Settings
$sys['fb']['appID'] = "";
$sys['fb']['apiKey'] = "";
$sys['fb']['appSecret'] = "";
$sys['fb']['userpermissions'] = array( "about_me", "email", "website" );

#Mail Options
$sys['mail']['smtp'] = false;
$sys['mail']['smtp_auth'] = true;
$sys['mail']['smtp_host'] = "smtp.example.com";
$sys['mail']['smtp_user'] = "address@example.com";
$sys['mail']['smtp_pass'] = "password";

# Style Configuration
$sys['style']['directory'] = "master";

# Troubleshooting and Debuging
$sys['debug']['mode'] = true;
$sys['debug']['errLogFileName'] = "err_log.log";

# Restricted slugs
$sys['restricted']['slugs'] = array( "includes", "logic", "style", "home", "403", "404", "dynamic_load", "ajax" );

?>