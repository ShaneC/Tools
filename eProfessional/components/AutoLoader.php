<?php

# This document will load the various core classes of the APP
# as well the utility functions that do not hold a particular
# class. If a core class is not included the system will
# record an error.

ini_set( 'unserialize_callback_func', '__autoload' );

global $sysAutoLoadFunctions;
$sysAutoLoadFunctions = array(
	# File Handle -> Filename
	
	# Debug Utility
	'Debug' => 'Debug.php',
	
	# Core System Utilities
	'CriticalReturn' => 'CriticalReturn.php',
	
);

global $sysAutoLoadClasses;
$sysAutoLoadClasses = array(
	# Class Name => Filename
	
	# Authentication
	'Login' => 'Login.php',
	'Logout' => 'Logout.php',
	'UserAuth' => 'UserAuth.php',
	'UserSettings' => 'UserSettings.php',
	
	# Content
	'Article' => 'Article.php',
	'Category' => 'Category.php',
	
	# Database
	'SQL' => 'SQL.php',
	'UserSQL' => 'UserAuth.php',
	
	# Utilities
	'Encryption' => 'Encryption.php',
	'Error' => 'Error.php',
	'ResultDisplay' => 'ResultDisplay.php',
	'Template' => 'Template.php',
	'Time' => 'Time.php'
	
);

class AutoLoader {
	
	static function loadAllFunctions(){
		
		global $sysAutoLoadFunctions, $sys;
	
		foreach( $sysAutoLoadFunctions as $functionName => $fileLocation ){
			$define = "SYS_F" . strtoupper( $functionName );
			if( !defined( $define ) ){
				if( file_exists( $sys['ComponenetPath'] . $fileLocation ) ){
					require_once( $sys['ComponenetPath'] . $fileLocation );
				}elseif( function_exists( 'sysDebug' ) ){
					sysDebug( "AutoLoad Function {$functionName} not found; skipped loading" );
				}
			}
		}
	}
	
	static function loadAllClasses(){
		global $sysAutoLoadClasses, $sys;
	
		foreach( $sysAutoLoadClasses as $className => $fileLocation ){
			if( !class_exists( $className ) ){
				if( file_exists( $sys['ComponenetPath'] . $fileLocation ) ){
					require_once( $sys['ComponenetPath'] . $fileLocation );
				}elseif( function_exists( 'sysDebug' ) ){
					sysDebug( "AutoLoad Class {$className} not found; skipped loading" );
				}
			}
		}
		
	}
	
	static function autoload( $class ){
		global $sysAutoLoadClasses, $sys;
		
		if( isset( $sysAutoLoadClasses[$class] ) ){
			# Class found!
			$fileLocation = $sysAutoLoadClasses[$class];
		}else{
			
			# Try the class in all lower case
			$fileLocation = false;
			$lowerClass = strtolower( $class );
			foreach( $sysAutoLoadClasses as $class2 => $file2 ){
				if( strtolower( $class2 ) == $lowerClass ){
					$fileLocation = $file2;
				}
			}
			
			if( !$fileLocation ){
				if( function_exists( 'sysDebug' ) )
					sysDebug( "AutoLoad Class {$class} not found; skipped loading" );
				# Give up
				return false;
			}
			
		}
		
		require_once( $sys['ComponenetPath'] . $fileLocation );
	}

}

# Load all utility functions defined above
AutoLoader::loadAllFunctions();

function loadAllClasses(){
	# If need be load all classes defined above
	AutoLoader::loadAllClasses();
}

if( function_exists( 'spl_autoload_register' ) ) {
	spl_autoload_register( array( 'AutoLoader', 'autoload' ) );
} else {
	function __autoload( $className ){
		# When called, load a previously unloaded class
		AutoLoader::autoload( $className );
	}
}