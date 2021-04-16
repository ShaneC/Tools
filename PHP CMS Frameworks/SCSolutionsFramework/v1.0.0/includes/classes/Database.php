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

/** \brief Custom Database Class handling front end operations of the MySQL database (extends MySQLi).
  * @author Shane Chism <schism@acm.org>
 **/
class Database extends MySQLi {
	
	/** Constructor, creating a connection to the database.
	 *  @returns Connect is opened on success. Generates a critical system error on failure.
	 **/
	public function __construct( $dbName = NULL ){
		global $sys;
		
		@parent::__construct( $sys['db']['host'], $sys['db']['user'], $sys['db']['pass'], $sys['db']['name'] );
		
		try {
			if( mysqli_connect_error() )
				throw new SysException( "Connection Error [" . mysqli_connect_errno() . "] - " . mysqli_connect_error() );
		}catch( SysException $e ){
			$e->logStackTrace();
			$e->show( "Whoops! Please try again later.", "We can't connect to our database" );	
		}
	}
	
	/** Executes a MySQL query.
	 *  @param query string
	 *  @returns Returns FALSE on failure. Returns a result object on success.
	 **/
	public function query( $str ){
		try {
			$result = parent::query( $str );
			if( !$result )
				throw new SysException( $this->error );
		}catch( SysException $e ){
			$e->logStackTrace();
			$e->show( "Whoops! Please try again later.", "There's a problem with our database" );		
		}
		return $result;
	}
	
	// -----------------------------------
	// Modified Insert Methods
	// -----------------------------------
	/** insert - Query Builder: Allows for inserting an array of values into a table.
	 *  @param table string containing table name
	 *  @param arr array of values ( key => value ) as ( column => insertValue )
	 *  @returns Returns FALSE on failure. Returns a result object on success.
	 **/
	public function insert( $table, $arr ){
		if( !is_array( $arr ) ){
			$e = SysException( "Supplied argument $arr = {$arr} not a valid array." );
			$e->logStackTrace();
			$e->show();
		}
		
		$columns = $values = "";
		foreach( $arr as $key => $val ){
			$columns .= "`" . $key . "`,";
			$values .= "'" . $val . "',";
		}
		return( $this->query( "INSERT INTO `" . DBPREFIX . $table . "` ( " . substr( $columns, 0, -1 ) . " ) VALUES ( " .  substr( $values, 0, -1 ) . " )" ) );
	}
	
	// -----------------------------------
	// Modified Selection Methods
	// -----------------------------------
	/** Get's a particular configuration value without needing a query.
	 *  @param  configName string containing the configuration option's name
	 *  @returns Returns NULL on failure. Returns configuration value on success.
	 **/
	public function getConfig( $configName ){
		$sql = $this->query( "SELECT `configValue` FROM `" . DBPREFIX . "config` WHERE `configName` = '" . $this->sanitize( $configName ) . "' LIMIT 0,1" );
		if( $sql->num_rows != 1 )
			return NULL;
		$result = $sql->fetch_array();
		return $result['configValue'];
	}
	
	// -----------------------------------
	// Modified Update Methods
	// -----------------------------------
	/** update - Query Builder: Allows for updating an array of values in a table for a particular entry.
	 *  @param table string containing table name
	 *  @param arr array of values ( key => value ) as ( column => insertValue )
	 *  @param where The SQL conditional statement that follows WHERE (i.e. `firstName`='John',`lastName`='Smith')
	 *  @returns Returns FALSE on failure. Returns TRUE on success.
	 **/
	public function update( $table, $arr, $where ){
		try {
			if( !is_array( $arr ) )
				throw new SysException( "Supplied argument $arr = {$arr} not a valid array." );
			if( !isset( $where ) )
				throw new SysException( "No WHERE condition specified." );
		}catch( SysException $e ){
			$e->logStackTrace();
			$e->show();
		}
		$entries = "";
		foreach( $arr as $key => $val )
			$entries .= "`" . $key . "` = '" . $val . "',";
		$where = ( $where === false ) ? '' : ' WHERE ' . $where;
		return( $this->query( "UPDATE `" . DBPREFIX . $table . "` SET " . substr( $entries, 0, -1 ) . $where ) );
	}
	/** updateAll - Query Builder: Allows for updating an array of values in a table for all entries in the table.
	 *  @param table string containing table name
	 *  @param arr array of values ( key => value ) as ( column => insertValue )
	 *  @returns Returns FALSE on failure. Returns TRUE on success.
	 **/
	public function updateAll( $table, $arr ){
		return( $this->update( $table, $arr, false ) );
	}
	
	// -----------------------------------
	// Data Handling Functions
	// -----------------------------------
	/** Sanitizes a given string for DB entry.
	 *  @paramstr generic string
	 *  @returns Returns sanitized string safe for MySQL queries
	 **/
	public function sanitize( $str ){
		return $this->real_escape_string( $str );	
	}
	/** De-sanitizes a given string.
	 *  @param str sanitized string
	 *  @returns Returns an unsanitized string
	 **/
	public function decode( $str ){
		return htmlentities( $str );	
	}
	
}

?>