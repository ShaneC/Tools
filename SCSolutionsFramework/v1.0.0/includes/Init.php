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

/** Define the root path, used in internal file includes. **/
define( "PS", realpath( '.' ) );

/** Define HTTP root, used in external file includes. **/
define( "HTTP_ROOT", "http://" . $_SERVER['HTTP_HOST'] );

# Set the default timezone
date_default_timezone_set( "America/New_York" );

# Set the global variable arrays
if( !isset( $sys ) )
	/** The global SYS Array. Contains all essential system data. **/
	$sys = array();
if( !isset( $output ) )
	/** The global OUTPUT Array. Contains all data to be passed to HTML for output. **/
	$output = array();
	
# Include configuration file
require_once( PS . '/includes/config/Config.php' );
if( $sys['debug']['mode'] )
	error_reporting( E_ALL );
else
	error_reporting( 0 );

/** Set the database table prefix, used in database queries. **/
define( "DBPREFIX", $sys['db']['prefix'] );

# Include core system functions
require_once( PS . '/includes/functions/SysFunctions.php' );

# AutoLoader
require_once( PS . '/includes/classes/AutoLoader.php' );

session_start();

?>