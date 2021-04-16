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

if( !defined( "FSYS_SysFunctions" ) )
	define( "FSYS_SysFunctions", true );

/** Sanitizes a generic input.
 *  @param str generic string
 *  @returns Returns an escaped version of the input string
 **/
function sysEncode( $str ){
	return( addslashes( htmlspecialchars( $str ) ) );
}

/** Shows an error page that details an encountered error.
 *  @param err string containing the error message
 *  @param errHeader string containing the error header
 *  @param errTitle string containing the page title
 *  @returns Displays the error page and ends the application
 **/
function sysError( $err = NULL, $errHeader = NULL, $errTitle = NULL ){
	
	global $output, $sys, $_SAction;
	
	$err = ( $err === NULL ) ? "We apologize for the trouble! Please try again later." : $err;
	
	if( defined( "NO_STYLING" ) && NO_STYLING == true )
		// Ajax generated error - do not return HTML
		die( $err );
	
	$errHeader = ( $errHeader === NULL ) ? "We've hit an error" : $errHeader;
	$errTitle = ( $errTitle === NULL ) ? "Oops!" : $errTitle;
	
	$output['message']['title'] = $errHeader;
	$output['message']['text'] = $err;
	
	$_SAction->fire( "EVT_USERERROR" );
	
	$screen = new Screen();
	$output['feature']['image'] = "content/content_top_block_directors.jpg";
	$screen->addCSS( 'master/css/content.css' );
	$screen->show( 'master/error_message.tpl', $errTitle );
	
	exit();
	
}

/** Shows a specialized error page for error code 404
 *  @returns Displays the error page and ends the application
 **/
function sysError404(){
	sysError( "Please check the address and try again.", "The page you requested was not found.", "Page Not Found" );	
	exit();
}

/** Shows a simple error page that details an encountered error.
 *  Used in cases where a style resource may not be available.
 *  @param err string containing the error message
 *  @param errHeader string containing the error header
 *  @param errTitle string containing the page title
 *  @returns Displays the error page and ends the application
 **/
function sysCriticalError( $err = NULL, $errHeader = NULL, $errTitle = NULL ){
	
	global $output, $sys;
	
	$err = ( $err === NULL ) ? "We apologize for the trouble! Please try again later." : $err;
	
	if( defined( "NO_STYLING" ) && NO_STYLING == true )
		// Ajax generated error - do not return HTML
		die( $err );
	
	$errHeader = ( $errHeader === NULL ) ? "We've hit an error" : $errHeader;
	$errTitle = ( $errTitle === NULL ) ? "Oops!" : $errTitle;
	
	$output['message']['title'] = $errTitle;
	$output['message']['text'] = $err;
	$output['message']['header'] = $errHeader;
	
	require( PS . "/style/global/critical_error.tpl" );
	exit();
	
}

?>