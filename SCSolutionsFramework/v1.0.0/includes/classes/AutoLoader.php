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

# This document will load the various core classes of the APP
# as well the utility functions that do not hold a particular
# class. If a core class is not included the system will
# record an error.

ini_set( 'unserialize_callback_func', '__autoload' );

global $sysAutoLoadFunctions;
/** Array containing all core system functions and their files ( handle => fileName ) **/
$sysAutoLoadFunctions = array(
	# File Handle -> Filename
	'SysFunctions'		=> 'SysFunctions.php'
);

global $sysAutoLoadClasses;
/** Array containing all core system classes and their files ( handle => fileName ) **/
$sysAutoLoadClasses = array(
	# Class Name => Filename
	'AES'						=> 'AES.php',
	'Database'					=> 'Database.php',
	'Facebook'					=> 'Facebook.php',
	'FacebookApiException'		=> 'Facebook.php',
	'Mail'						=> 'Mail.php',
	'RandomStringGenerator'		=> 'RandomStringGenerator.php',
	'Screen'					=> 'Screen.php',
	'SysException'				=> 'SysException.php',
	'ValidateData'				=> 'ValidateData.php'
);

/** \brief PHP Class allowing the loading of classes without explicitly including the file.
  * @author Shane Chism 
 **/
class AutoLoader {
	
	/** 
	  * Includes all function documents listed in the $sysAutoLoadFunctions array (inefficient).
	 **/
	static function loadAllFunctions(){
		global $sysAutoLoadFunctions, $sys;
	
		foreach( $sysAutoLoadFunctions as $functionName => $fileLocation ){
			if( !defined( "FSYS_" . $functionName ) ){
				if( file_exists( PS . "/includes/functions/" . $fileLocation ) ){
					require_once( PS . "/includes/functions/" . $fileLocation );
				}elseif( function_exists( 'sysError' ) ){
					if( !class_exists( 'SysException' ) && !@include_once( PS . "/includes/classes/SysException.php" ) ){
						sysError( $errText, "We've hit a problem" );
					}else{
						$e = new SysException( "Unable to load function {$functionName}" );
						$e->logStackTrace();
						$e->show( $errText, "We've hit a problem" );
					}
				}else
					die( $errText );
			}
		}
	}
	
	/** 
	  * Includes all class documents listed in the $sysAutoLoadClasses array (inefficient).
	 **/
	static function loadAllClasses(){
		global $sysAutoLoadClasses, $sys;
	
		foreach( $sysAutoLoadClasses as $className => $fileLocation ){
			if( !class_exists( $className ) ){
				if( file_exists( PS . "/includes/classes/" . $fileLocation ) ){
					require_once( PS . "/includes/classes/" . $fileLocation );
				}elseif( function_exists( 'sysError' ) ){
					if( !class_exists( 'SysException' ) && !@include_once( PS . "/includes/classes/SysException.php" ) ){
						sysError( $errText, "We've hit a problem" );
					}else{
						$e = new SysException( "Unable to load class {$class}" );
						$e->logStackTrace();
						$e->show( $errText, "We've hit a problem" );
					}
				}else
					die( $errText );
			}
		}
	}
	
	/** Shows an error page that details the exception encountered.
	 *  @param className name of the class as it appears in the key section of $sysAutoLoadClasses
	 **/
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
				$errText = "We've experienced a program malfunction. Please try again later.";
				if( $sys['debug']['mode'] )
					$errText .= "<br /><br />Cannot autoload class. Class {$class} not found.";
				
				if( function_exists( 'sysError' ) ){
					if( !class_exists( 'SysException' ) && !@include_once( PS . "/includes/classes/SysException.php" ) ){
						sysError( $errText, "We've hit a problem" );
					}else{
						$e = new SysException( "Unable to load class {$class}" );
						$e->logStackTrace();
						$e->show( $errText, "We've hit a problem" );
					}
				}else
					die( $errText );
					
				# Give up
				return false;
			}
			
		}
		
		require_once( PS . "/includes/classes/" . $fileLocation );
	}

}

if( function_exists( 'spl_autoload_register' ) ) {
	spl_autoload_register( array( 'AutoLoader', 'autoload' ) );
} else {
	/** Shows an error page that details the exception encountered.
	 *  @param className name of the class as it appears in the key section of $sysAutoLoadClasses
	 **/
	function __autoload( $className ){
		# When called, load a previously unloaded class
		AutoLoader::autoload( $className );
	}
}

?>