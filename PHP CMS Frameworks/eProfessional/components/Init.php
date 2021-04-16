<?php

# This script will perform the start up includes and function. It does some
# security checks, loads configuration information, and may load the Setup
# function depending on whether or not APP_INSTALLED returns false.

# Get beginning runtime of the installation
$sys['RequestTime'] = microtime( true );

# Set the global path string
$PS = realpath( '.' );

# Search for the configuration file. If it exists the application
# is installed. If not, use default config and flag accordingly.

if( !file_exists( "$PREPS/config/Config.php" ) ){
	require_once( "$PREPS/install/DefaultConfig.php" );
	define( 'APP_INSTALLED', false );
}else{
	require_once( "$PREPS/config/Config.php" );
	define( 'APP_INSTALLED', true );
}

# Test for PHP bug which breaks PHP 5.0.x on 64-bit...
# As of 1.8 this breaks lots of common operations instead
# of just some rare ones like export.
$borked = str_replace( 'a', 'b', array( -1 => -1 ) );
if( !isset( $borked[-1] ) ) {
	echo "PHP 5.0.x is buggy on the 64-bit system; you must upgrade to PHP 5.1.x\n" .
	     "or higher. ABORTING. (http://bugs.php.net/bug.php?id=34879 for details)\n";
	exit;
}

# Initalize global definitions for the application
require_once( $sys['ComponenetPath'] . "Defines.php" );

# Load all of the components and utility functions for use
require_once( $sys['ComponenetPath'] . "AutoLoader.php" );

# Load eP version information
require_once( "$PREPS/config/VersionInfo.php" );

ob_start();