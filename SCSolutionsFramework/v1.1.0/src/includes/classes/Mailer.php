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

class Mailer {
	
	public $fromName, $fromEmail;
	private $smtpConfig;
	
	public $emailHeader = 	'';
	
	public function __construct() {
		global $sys;
		require_once( PS . "/includes/config/Config.php" );
		$this->smtpConfig = $sys['mail'];
		$db = new Database();
		$this->fromName = $db->getConfig( 'emailFromName' );
		$this->fromEmail = $db->getConfig( 'emailFromEmail' );
	}
	
	public function send( $to, $subject, $message, $toName = NULL, $fromEmail = NULL, $fromName = NULL, $addHeaders = array() ){
		if( !$this->smtpConfig['smtp'] )
			return $this->sendNormal( $to, $subject, $message, $toName, $fromEmail, $fromName, $addHeaders );
		else
			return $this->sendSMTP( $to, $subject, $message, $toName, $fromEmail, $fromName, $addHeaders );
	}
	
	public function sendNormal( $to, $subject, $message, $toName, $fromEmail, $fromName, $addHeaders ){
		if( $fromName == NULL ){
			$fromName = ( $this->fromName == NULL ) ? $this->fromEmail : $this->fromName;
		}else
			$fromName = $fromName;
		
		$fromEmail = ( $fromEmail == NULL ) ? $this->fromEmail : $fromEmail;
		
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: \"" . $fromName . "\" <" . $fromEmail . ">\r\n";
		
		foreach( $addHeaders as $key => $value )
			$headers .= $key . ": " . $value;
		
		$msgBody = $this->emailHeader . $message;
		$msgBody .= '</body></html>';
		
		$sendVar = mail( $to, $subject, wordwrap( $msgBody ), $headers );
		try{
			if( !$sendVar )
				throw new SysException( "Unable to send e-mail. TO:[" . $to . "] Subject:[" . $subject . "] Headers:[" . $headers . "]" );
		}catch( SysException $e ){
			$e->logStackTrace();	
		}
		return( $sendVar );
	}
	
	public function sendSMTP( $to, $subject, $message, $toName, $fromEmail, $fromName, $addHeaders ){
		
		if( $fromName == NULL ){
			$fromName = ( $this->fromName == NULL ) ? $this->fromEmail : $this->fromName;
		}else
			$fromName = $fromName;
		
		$fromEmail = ( $fromEmail == NULL ) ? $this->fromEmail : $fromEmail;
		
		$headers = array( 'MIME-Version' => "1.0", 'Content-type' => 'text/html; charset=iso-8859-1;', 
						  'From' => "\"" . $fromName . "\" <" . $fromEmail . ">", 'To' => $to, 'Subject' => $subject );
		
		array_push( $headers, $addHeaders );
		
		$msgBody = $this->emailHeader . $message;
		$msgBody .= '</body></html>';
		
		require_once( "Mail.php" );
		$smtp = Mail::factory( 'smtp', array( 'host' => "localhost", 'auth' => false ) );
		$mail = $smtp->send( $to, $headers, wordwrap( $msgBody ) );
		
		$sendVar = PEAR::isError( $mail );
		try{
			if( !$sendVar )
				throw new SysException( "Unable to send e-mail. TO:[" . $to . "] Subject:[" . $subject . "] Headers:[" . print_r( $headers, true ) . "]" );
		}catch( SysException $e ){
			$e->logStackTrace();	
		}
		return( $sendVar );
		
	}
	
}


?>