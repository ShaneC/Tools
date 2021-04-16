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

/** \brief Data Format Class, entirely static in its implementation.
  * @author Shane Chism 
 **/
class DataFormat {
	
	// -----------------------------------
	// DATA DISPLAY
	// -----------------------------------
	/** Formats bytes into a readable string
	 *  @param bytes integer containing the number of bytes to format
	 *  @returns Returns string
	 **/
	public static function filesize( $bytes ){
		$var = array();
		$var['size'] = intval( $bytes ) / 1024;
		$var['unit'] = "KB";
		if( $var['size'] > 1048576 ){
			$var['size'] = $var['size'] / 1048576;
			$var['unit'] = "GB";
		}elseif( $var['size'] > 1024 ){
			$var['size'] = $var['size'] / 1024;
			$var['unit'] = "MB";
		}	
		return $var;
	}
	
	// -----------------------------------
	// DATA INTEGRITY
	// -----------------------------------
	/** Sanitizes a given string for DB entry.
	 *  @param str generic string
	 *  @returns Returns sanitized string safe for database queries
	 **/
	public static function dbSanitize( $str ){
		if( is_numeric( $str ) )
			return $str;
		$unpacked = unpack( 'H*hex', $str );
		return '0x' . $unpacked['hex'];
	}

}

?>