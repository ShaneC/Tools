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

/** \brief Custom Exception class supporting debug utility functions.
  * @author Shane Chism
 **/
class SysException extends Exception {

	/** Shows an error page that details the exception encountered.
	 *  @param err OPTIONAL string containing the error message
	 *  @param errHeader OPTIONAL string containing the error header
	 *  @param errTitle OPTIONAL string containing the page title
	 *  @returns Displays the error page and ends the application
	 **/
	public function show( $err = NULL, $errHeader = NULL, $errTitle = NULL ){
		global $sys;
		if( $sys['debug']['mode'] ){
			$errReport =  "<br /><div style=\"font-size: 11px;\">" . 
						  $this->getErrReport();
						  "</div>";
			$err = ( ( $err === NULL ) ? 'We apologize for the trouble! Please try again later.' : $err ) . "<br />" . $errReport;
		}
		sysError( $err, $errHeader, $errTitle );
	}
	
	/** Shows an error page without any HTML.
	 *  @param err string containing the error message
	 *  @returns Displays the error page and ends the application
	 **/
	public function showPlain( $err ){
		global $sys;
		if( $sys['debug']['mode'] ){
			$errReport = $this->getErrReport();	
			$err = $err . "<br />" . $errReport;
		}
		print( $err );
		exit();
	}
	
	/** Compile a full stack trace of the error.
	 *  @returns Returns a formatted and detailed stack trace of the error
	 **/
	private function getErrReport(){
		$errReport =  "<b>Exception Code " . $this->getCode() . "</b><br />" . 
					  "<b>Message</b><br />" . 
					  $this->getMessage() . "<br />" . 
					  "<b>Line : Offending File</b><br />" .
					  $this->getLine() . " : " . $this->getFile() . "<br />" . 
					  "<b>Stack Trace</b><br /><pre>" . 
					  $this->getTraceAsString() . "</pre>";
		return $errReport;
	}
	
	/** Prints a full stack trace of the error to the screen.
	 *  @returns Prints a formatted and detailed stack trace of the error
	 **/
	public function printStackTrace(){
		$errReport =  $this->getErrReport() . "<br />";
		print( $errReport );
	}
	
	/** Prints a full stack trace of the error to an error file.
	 *  @param flag OPTIONAL string containing text to be printed at the header of the trace
	 *  @returns Assuming no error, prints a formatted stack trace of the error to an error file.
	 **/
	public function logStackTrace( $flag = NULL ){
		global $sys;
		
		switch( $flag ){
			case 'hack'		: $flagText = "HACKING ATTEMPT "; break;
			default			: $flagText = $flag . " "; break;
		}
		
		$debugTime = date( 'Y-m-d H:i:s', time() );
		$fp = fopen( PS . "/logic/Troubleshoot/" . $sys['debug']['errLogFileName'], "a+" );
		
		if( $fp ){
			$errReport =  "[" . $debugTime . "] " . $flagText . "{Exception Code " . $this->getCode() . "}\n" . 
						  "{ IP " . $_SERVER['REMOTE_ADDR'] . " }\n" . 
						  "|-Message-|\n" . 
						  $this->getMessage() . "\n" . 
						  "|-Line : Offending File-|\n" .
						  $this->getLine() . " : " . $this->getFile() . "\n" . 
						  "|-Stack Trace-|\n" . 
						  $this->getTraceAsString() . "\n";
						  
			fwrite( $fp, $errReport );
			fclose( $fp );
		}
	}
	
}

?>