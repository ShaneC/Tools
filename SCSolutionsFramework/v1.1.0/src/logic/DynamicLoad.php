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

if( !isset( $_REQUEST['target'] ) || empty( $_REQUEST['target'] ) ){
	header( "HTTP/1.0 404 Not Found" );
	exit();
}

// Load Current Theme's authorized list
$db = ( isset( $db ) ) ? $db : new Database();
$styleID = $db->sanitize( $_REQUEST['style_id'] );
$loadCode = $db->sanitize( $_REQUEST['target'] );
$sql = $db->query( "SELECT `SourceFile` FROM `" . DBPREFIX . "styles_authorizedload` WHERE `StyleID` = '" . $styleID . "' AND `LoadCode` = '" . $loadCode . "' LIMIT 0,1" );
try {
	if( $sql->num_rows != 1 )
		throw new SysException( "Unknown Style ID or LoadCode referenced. Style ID [{$styleID}] ; LoadCode [{$loadCode}]." );
	$row = $sql->fetch_row();
	$sourceFile = $row[0];
}catch( SysException $e ){
	$e->logStackTrace();
	$e->show( "Whoops! We were unable to load key system resources. Please try again later." );	
}

$replacementArr = array( "[INCLUDES_JS]" => PS . "/includes/js" );

foreach( $replacementArr as $find => $replace )
	$sourceFile = str_replace( $find, $replace, $sourceFile );

if( !file_exists( $sourceFile ) ){
	//header( "HTTP/1.0 404 Not Found" );
	echo( $sourceFile );
	exit();
}

require_once( $sourceFile );
exit();

?>