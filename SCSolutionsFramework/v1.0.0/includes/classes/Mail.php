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

class Mail {
	
	public $fromName, $fromEmail;
	
	public function __construct() {
		$db = new Database();
		$this->fromName = $db->getConfig( 'emailFromName' );
		$this->fromEmail = $db->getConfig( 'emailFromEmail' );
	}
	
	public function send( $to, $subject, $message, $toName = NULL, $fromEmail = NULL, $fromName = NULL, $addHeaders = NULL ){
		
		if( $fromName == NULL ){
			$fromName = ( $this->fromName == NULL ) ? $this->fromEmail : $this->fromName;
		}else
			$fromName = $fromName;
		
		$fromEmail = ( $fromEmail == NULL ) ? $this->fromEmail : $fromEmail;
		
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: \"" . $fromName . "\" <" . $fromEmail . ">\r\n";
		$headers .= $addHeaders;
		
		$body = '<!DOCTYPE html>
				<html>
				<head>
					<meta name="viewport" content="width=740">
					<meta http-equiv="content-type" content="text/html; charset=utf-8">
					<title></title>
					<style type="text/css">
						body {
							font-family: Tahoma, Geneva, sans-serif; font-size: 12px;	
						}
					</style>
				</head>
				<body>';
		$body .= $message;
		$body .= '</body></html>';
		
		mail( $to, $subject, wordwrap( $body ), $headers );
		
	}
	
}


?>