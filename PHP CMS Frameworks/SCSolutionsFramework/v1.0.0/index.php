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

define( "RUNTIME", true );
require( 'includes/Init.php' );

$mode = ( !isset( $_REQUEST['p'] ) || empty( $_REQUEST['p'] ) ) ? "home" : sysEncode( $_REQUEST['p'] );

switch( $mode ){
	
	# Errors
	case '403'				:	sysError( 'Access Denied', 'The page you requested is restricted.', 'Unauthorized Access' );
	
	# Utility
	case 'ajax'				:	require( PS . "/logic/Ajax.php" ); break;
	
	# Home Page
	case 'home'				:	require( PS . "/logic/Home.php" ); break;
	
	# Default Case
	default					:	sysError404(); break;
	
}

?>