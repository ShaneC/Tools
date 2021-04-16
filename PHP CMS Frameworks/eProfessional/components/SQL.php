<?php

# Database functions

class SQL {

	private $dbLink;

	# Database Connect
	protected function connect(){
		global $sysDBLocation, $sysDBName, $sysDBUser, $sysDBPass;
		
		$this->dbLink = @mysql_connect( $sysDBLocation, $sysDBUser, $sysDBPass );
		
		if( !$this->dbLink ){
			$err = "Failed database connection, Class {SQL}";
			if( function_exists( 'sysDebug' ) ){
				sysDebug( $err );
			}
			if( function_exists( 'sysCriticalFail' ) )
				sysCriticalFail( $err );
			return false;
		}
					
		if( !@mysql_select_db( $sysDBName, $this->dbLink ) ){
			if( function_exists( 'sysDebug' ) ){
				sysDebug( "Failed to select database, Class {SQL}" );
			}
			if( function_exists( 'sysCriticalFail' ) )
				sysCriticalFail( $err );
			return false;
		}
	}
	
	# Close Database Connection
	protected function close(){
		if( !mysql_close( $this->dbLink ) ){
			if( function_exists( 'sysDebug' ) ){
				sysDebug( "Failed to close database connection, Class {SQL}" );
			}
			return false;
		}	
	}
	
	# Encode the data to be database friendly
	public function encode( $input ){
		if( !get_magic_quotes_gpc() ){
			$temp = addslashes( $input );
		}
		return htmlentities( $input );
	}
	
	# Decode result information
	public function decode( $input ){
		return html_entity_decode( stripslashes( $input ) );
	}
	
	# Create the table name for a query
	public function tableName( $input ){
		global $sysDBPrefix;
		return $sysDBPrefix . $input;
	}
	
	# Execute a MySQL SELECT query
	public function select( $query ){
		$this->connect();
		
		$sql = mysql_query( $query );
		if( !$sql ){
			if( function_exists( 'sysDebug' ) ){
				sysDebug( "Failed DB Select Query: {$query} | MySQL Error: " . mysql_error( $this->dbLink ) );
			}
			return false;
		}else{
			$this->trackQuery();
			if( mysql_num_rows( $sql ) > 0 )
				return mysql_fetch_array( $sql );
			else
				return false;
		}
		
		$this->close();
	}
	
	# Execute a MySQL UPDATE query
	public function update( $query ){
		$this->connect();
		
		$sql = mysql_query( $query );
		if( !$sql ){
			if( function_exists( 'sysDebug' ) ){
				sysDebug( "Failed DB Update Query: {$query} | MySQL Error: " . mysql_error( $this->dbLink ) );
			}
			return false;
		}else{
			$this->trackQuery();
			return true;
		}
		
		$this->close();
	}
	
	# -- Configuration Values
	# This gives connectivity for grabbing core data values
	var $sysConfigTable = "configuration"; // Configuration table name, defaults to `configuration`
	
	private function configSelectQ( $config ){
		# Prepare configuration selection query
		$table = $this->sysConfigTable;
		
		$config = $this->encode( $config );
		return  "SELECT `config_value` " .
				"FROM `" . $this->tableName( $table ) .
				"` WHERE `config_name` = '$config' LIMIT 0,1";
	}
	private function configUpdateQ( $configName, $configValue ){
		# Prepare configuration update query
		$table = $this->sysConfigTable;
		
		$configName = $this->encode( $configName );
		$configValue = $this->encode( $configValue );
		return  "UPDATE `" . $this->tableName( $table ) .
				"` SET `config_value` = '$configValue' WHERE `config_name` = '$configName'";
	}
	
	# -- Utility Functions
	# General Use
	private function trackQuery(){
		global $sys;
		if( isset( $sys['NumQueries'] ) )
			$sys['NumQueries']++;
	}
	
	public function configRead( $config ){
		# Return a configuration option from the database
		$this->connect();
		
		$query = $this->configSelectQ( $config );
		$result = $this->select( $query );
		$this->close();
		
		if( $result ){
			foreach( $result as $key => $value )
				return $value;	
		}else return false;
	}
	
	public function configUpdate( $configName, $configValue ){
		# Updates a configuration item in the database
		$this->connect();
		
		$query = $this->configUpdateQ( $configName, $configValue );
		$result = $this->update( $query );
		$this->close();
		
		if( $result ){
			return true;
		}else return false;
	}
	
}