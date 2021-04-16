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

/** \brief Data Validation Class, virtually entirely static in its implementation.
  * @author Shane Chism 
 **/
class ValidateData {
	
	/** String passed into the constructor for use in methods checking a string **/
	var $str;
	
	/** Class Constructor.
	 *  @param str generic string passed into a member variable
	 **/
	public function __construct( $str = NULL ){
		$this->str = $str;
	}
	
	/** Determines if a string is a valid e-mail address.
	 *  @param str generic string to be checked
	 *  @returns Returns TRUE on valid e-mail, FALSE if not.
	 **/
	public function email( $str = NULL ){
		return( filter_var( $str, FILTER_VALIDATE_EMAIL ) );
	}
	
	/** Determines if a string is a valid phone number.
	 *  @param str generic string to be checked
	 *  @returns Returns TRUE on valid phone number, FALSE if not.
	 **/
	public function phone( $str = NULL ){
		$regex = '/^([0-9]{1,3}[\-\." "\_]?)?[0-9]{3}[\-\." "\_]?[0-9]{3}[\-\." "\_]?[0-9]{4}$/';
		return( filter_var( $str, FILTER_VALIDATE_REGEXP, array( 'options' => array( 'regexp' => $regex ) ) ) );	
	}
	
	/** Determines if a string is a valid zip code.
	 *  @param str generic string to be checked
	 *  @returns Returns TRUE on valid zip, FALSE if not.
	 **/
	public function zip( $str = NULL ){
		$regex = '/^[0-9]{5}$/';
		return( filter_var( $str, FILTER_VALIDATE_REGEXP, array( 'options' => array( 'regexp' => $regex ) ) ) );	
	}
	
	/** Determines if a string is a valid date.
	 *  @param str generic string to be checked
	 *  @returns Returns TRUE on valid date, FALSE if not.
	 **/
	public function date( $year, $month, $day ){
		if( $year < 1 || $month < 1 || $day < 1 || $month > 12 )
			return false;
		if( $month == 2 && $day == 29 ){
			$isLeap = ( ( $year % 4 == 0 ) && ( $year % 100 != 0 ) );
			return $isLeap;
		}elseif( $month == 2 && $day > 28 )
			return false;
		$thirty = array( 4, 6, 9, 11 );
		if( in_array( $month, $thirty ) && $day > 30 )
			return false;
		if( !in_array( $month, $thirty ) && $day > 31 )
			return false;
		return true;
	}
	
	/** Checks to see if a filename is of a particular extension.
	 *  @param filename string containing the complete file name
	 *  @param desiredExt string||array containing the desired extension(s)
	 *  @returns Returns TRUE if the extension is an exact match (case sensitive), FALSE if not.
	 **/
	public function extension( $filename, $desiredExt ){
		$ext = strtolower( substr( strrchr( $filename, "." ), 1 ) );
		if( is_array( $desiredExt ) )
			return in_array( $ext, $desiredExt );
		return ( $ext === $desiredExt );
	}
	
	/** Checks to see if all values of an array are set in post.
	 *  @param arr array containing $_POST indexes to be checked (i.e. array( 'name', 'zip' ))
	 *  @returns Returns TRUE if all values of the array are present in post, FALSE if not.
	 **/
	public function checkEmptyPOST( $arr ){
		foreach( $arr as $val ){
			if( !isset( $_POST[$val] ) || $_POST[$val] === NULL || $_POST[$val] == "" )
				return false;
		}
		return true;
	}
	
	/** Checks to see if all values of an array are set in post.
	 *  @param arr array containing $_REQUEST indexes to be checked (i.e. array( 'name', 'zip' ))
	 *  @returns Returns TRUE if all values of the array are present in request, FALSE if not.
	 **/
	public function checkEmptyREQUEST( $arr ){
		foreach( $arr as $val ){
			if( !isset( $_REQUEST[$val] ) || $_REQUEST[$val] === NULL || $_REQUEST[$val] == "" )
				return false;
		}
		return true;
	}
	
	/** Converts a $_POST array to a variable array.
	 *  @param arr array containing $_POST indexes to be added (i.e. array( 'name', 'zip' ))
	 *  @param checkEmpty if set to TRUE the variables will first confirmed to be present in the $_POST array (default FALSE)
	 *  @returns (checkEmpty = TRUE) Returns variable array containing ( field => value ) on success, FALSE if a field is empty
	 *  @returns (checkEmpty = FALSE) Returns variable array containing ( field => value ) on success, omitting empty indexes.
	 **/
	public function postToVar( $arr, $checkEmpty = false, $options = NULL ){
		if( $checkEmpty && !self::checkEmptyPOST( $arr ) )
			return false;
		$vars = array();
		if( is_array( $options ) && isset( $options['FILES'] ) && $options['FILES'] == true ){
			foreach( $arr as $val ){
				if( !isset( $_POST[$val] ) && !isset( $_FILES[$val] ) )
					continue;
				$key = ( is_array( $options ) && isset( $options['elimPrefix'] ) && $options['elimPrefix'] != NULL ) ? 
						preg_replace( '/' . $options['elimPrefix'] . '/', '', $val, 1 ) : $val;
				if( isset( $_POST[$val] ) )
					$vars[$key] = $_POST[$val];
				else
					$vars[$key] = $_FILES[$val];
			}
		}else{		
			foreach( $arr as $val ){
				if( !isset( $_POST[$val] ) )
					continue;
				$key = ( is_array( $options ) && isset( $options['elimPrefix'] ) && $options['elimPrefix'] != NULL ) ? 
						preg_replace( '/' . $options['elimPrefix'] . '/', '', $val, 1 ) : $val;
				$vars[$key] = $_POST[$val];
			}
		}
		return $vars;
	}
	
	/** Converts a $_REQUEST array to a variable array.
	 *  @param arr array containing $_REQUEST indexes to be added (i.e. array( 'name', 'zip' ))
	 *  @param checkEmpty if set to TRUE the variables will first confirmed to be present in the $_REQUEST array (default FALSE)
	 *  @returns (checkEmpty = TRUE) Returns variable array containing ( field => value ) on success, FALSE if a field is empty
	 *  @returns (checkEmpty = FALSE) Returns variable array containing ( field => value ) on success, omitting empty indexes.
	 **/
	public function requestToVar( $arr, $checkEmpty = false ){
		if( $checkEmpty && !self::checkEmptyREQUEST( $arr ) )
			return false;
		$vars = array();
		foreach( $arr as $val ){
			if( !isset( $_REQUEST[$val] ) )
				continue;
			$vars[$val] = urldecode( $_REQUEST[$val] );
		}
		return $vars;
	}
	
	/** Sanitize all variables in an array using a database resource
	 *  @param arr array containing variables to be sanitized
	 *  @param db database resource
	 *  @returns array of sanitized variables
	 **/
	public function sanitizeVars( &$arr, $db ){
		$values = array();
		foreach( $arr as $key => $val )
			$values[$key] = $db->sanitize( htmlentities( $val ) );
		$arr = $values;
		return $arr;
	}

}

?>