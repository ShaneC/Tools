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

if( !defined( "NO_STYLING" ) )
	define( "NO_STYLING", true );

if( !isset( $_REQUEST['t'] ) || empty( $_REQUEST['t'] ) )
	die( "Invalid AJAX Request." );

switch( $_REQUEST['t'] ){
	case 'case'				: require( PS . "/logic/Ajax/Case.php" ); break;
	default					: die( "Invalid AJAX Request." ); break;	
}

?>