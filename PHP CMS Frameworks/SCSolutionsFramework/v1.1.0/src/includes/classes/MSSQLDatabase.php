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

class Database {

	private $link;
	
	/** Gets the number of affected rows in a previous MSSQL operation **/
	public $affected_rows;

	public function __construct( $options = array() ){
		global $sys;
		
		$dbHost = ( isset( $options['host'] ) ) ? $options['host'] : $sys['db']['host'];
		$dbName = ( isset( $options['name'] ) ) ? $options['name'] : $sys['db']['name'];
		$dbUser = ( isset( $options['user'] ) ) ? $options['user'] : $sys['db']['user'];
		$dbPass = ( isset( $options['pass'] ) ) ? $options['pass'] : $sys['db']['pass'];
		
		$this->link = sqlsrv_connect( $dbHost, array( "Database" => $dbName, "UID" => $dbUser, "PWD" => $dbPass ) );
		
		try {
			if( !$this->link )
				throw new SysException( "MSSQL error [" . print_r( sqlsrv_errors(), true ) . "]"  );
		}catch( SysException $e ){
			$e->logStackTrace();
			$e->show( "Whoops! Please try again later.", "We can't connect to our database" );	
		}
	}
	
	// -----------------------------------
	// MySQLi Compatibility Methods
	// -----------------------------------
	public function query( $sql, $params = array(), $options = array( "Scrollable" => SQLSRV_CURSOR_KEYSET ) ){
		if( !in_array( "Scrollable", $options ) )
			$options["Scrollable"] = SQLSRV_CURSOR_KEYSET;
		try {
			$stmt = sqlsrv_query( $this->link, $sql, $params, $options );
			if( !$stmt )
				throw new SysException( "Unable to execute query. [" . print_r( sqlsrv_errors(), true ) . "]" );
			$result = new MSSQLResult( $stmt );
			// Load affected rows
			$stmt = sqlsrv_query( $this->link, "SELECT @@ROWCOUNT" );
			if( !$stmt )
				throw new SysException( "Unable to pull affected rows. [" . print_r( sqlsrv_errors(), true ) . "]" );
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC );
			$this->affected_rows = $row[0];
			return $result;
		}catch( SysException $e ){
			$e->logStackTrace();
			$e->show( "Whoops! Please try again later.", "There's a problem with our database" );		
		}
	}
	
	public function insert_id(){
		try {
			$stmt = sqlsrv_query( $this->link, "SELECT SCOPE_IDENTITY()" );
			if( !$stmt )
				throw new SysException( "Unable to pull last insert ID query. [" . print_r( sqlsrv_errors(), true ) . "]" );
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC );
			return $row[0];
		}catch( SysException $e ){
			$e->logStackTrace();
			$e->show( "Whoops! Please try again later.", "There's a problem with our database" );		
		}
	}
	
	public function rollback(){
		return sqlsrv_rollback( $this->link );
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
			$e = new SysException( "Supplied argument $arr = {$arr} not a valid array." );
			$e->logStackTrace();
			$e->show();
		}
		$columns = $values = "";
		foreach( $arr as $key => $val ){
			$columns .= "" . $key . ",";
			$values .= "'" . $val . "',";
		}
		return( $this->query( "INSERT INTO " . DBPREFIX . $table . " ( " . substr( $columns, 0, -1 ) . " ) VALUES ( " .  substr( $values, 0, -1 ) . " )" ) );
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
	/** increment - Increments a specified column by 1
	 *  @param table string containing table name
	 *  @param column string containing the column value to be incremented
	 *  @param where The SQL conditional statement that follows WHERE (i.e. `firstName`='John',`lastName`='Smith')
	 *  @returns Returns FALSE on failure. Returns TRUE on success.
	 **/
	public function increment( $table, $column, $where ){
		$where = ( $where === false ) ? '' : ' WHERE ' . $where;
		return( $this->query( "UPDATE `" . DBPREFIX . $table . "` SET `" . $column . "` = (" . $column . "+1)" . $where ) );
	}
	
	// -----------------------------------
	// Data Handling Functions
	// -----------------------------------
	/** Sanitizes a given string for DB entry.
	 *  @param str generic string
	 *  @returns Returns sanitized string safe for database queries
	 **/
	public function sanitize( $str ){
		return DataFormat::dbSanitize( $str );
	}
	/** De-sanitizes a given string.
	 *  @param str sanitized string
	 *  @returns Returns an unsanitized string
	 **/
	public function decode( $str ){
		return htmlentities( $str );	
	}
	
	// -----------------------------------
	// Miscellaneous Methods
	// -----------------------------------
	public function server_info(){
		return sqlsrv_server_info( $this->link );
	}
	
}

class MSSQLResult {
	public $num_rows, $stmt;
	
	public function __construct( $stmt ){
		$this->stmt = $stmt;
		$this->num_rows = sqlsrv_num_rows( $stmt );
	}
	
	public function fetch_array( $row = false ){
		$val = ( $row ) ? sqlsrv_fetch_array( $this->stmt, SQLSRV_FETCH_NUMERIC ) : sqlsrv_fetch_array( $this->stmt );
		return $val;
	}
	
	public function fetch_row(){
		return $this->fetch_array( true );
	}
	
	public function free(){
		return sqlsrv_free_stmt( $this->stmt );
	}
}

?>