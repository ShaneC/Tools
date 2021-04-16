<?php

# Extension for user authentication and results

class UserSQL extends SQL {

	var $sysUserTable = "user"; // User table name, defaults to `user`
	
	private function userSelectColumn( $case ){
		# Determine which column to compare $value to
		switch( $case ){
			case 'username'     : return "user_name";
			case 'display_name' : return "user_display_name";
			case 'password'     : return "user_password";
			case 'email'        : return "user_email";
			default             : return "user_id";
		}
	}
	
	protected function userSelectQ( $value, $case = "id", $selection = "*" ){
		# Prepare select query for this class
		$table = $this->sysUserTable;
		if( empty( $selection ) || $selection == "*" )
			$selection = "*";
		else
			$selection = $this->encode( $selection );
		
		$column = $this->userSelectColumn( $case );	
		$value = $this->encode( $value );
		return  "SELECT $selection " .
				"FROM `" . $this->tableName( $table ) .
				"` WHERE `$column` = '$value' LIMIT 0,1";
	}
	
	protected function userUpdateQ( $updateValue, $selectValue, $updateColumn = "id", $selectColumn = "id" ){
		# Prepare select query for this class
		$table = $this->sysUserTable;
		
		# Select the correct database column name
		$updateColumn = $this->userSelectColumn( $updateColumn );
		$selectColumn = $this->userSelectColumn( $selectColumn );
		
		# Encode the values for submission into the database
		$updateValue = $this->encode( $updateValue );
		$selectValue = $this->encode( $selectValue );
		return  "UPDATE `" . $this->tableName( $table ) . "`" .
				"SET `" . $updateColumn . "` = '$updateValue' " . 
				"WHERE `" . $selectColumn . "` = '$selectValue'";
	}
	
	public function userRead( $value, $case = NULL, $selection = NULL ){
		# Return a user from the database
		$this->connect();
		
		$query = $this->userSelectQ( $value, $case, $selection );
		$result = $this->select( $query );
		$this->close();
		
		if( $result ){
			return $result;
		}else return false;
	}
	
	public function userUpdate( $updateValue, $selectValue, $updateColumn = NULL, $selectColumn = NULL ){
		# Updates a user in the database
		$this->connect();
		
		$query = $this->userUpdateQ( $updateValue, $selectValue, $updateColumn, $selectColumn );
		$result = $this->update( $query );
		$this->close();
		
		if( $result ){
			return true;
		}else return false;
	}

}

class UserAuth extends UserSQL {
	
	var $UserAuthorized;
	var $UserData;
	
	public function UserAuth( $userName, $userPassword, $reAuth = false ){
		
		global $sysSaltLength;
		
		# Note: All input into database queries is automatically encoded
		$dbUser = $this->userRead( $userName, 'username' );
		
		if( empty( $dbUser ) )
			$this->UserAuthorized = false;
		
		$dbPassword = $dbUser['user_password'];
		$this->UserData = $dbUser;
		
		if( !$reAuth ){
			$dbSalt = substr( $dbPassword, 0, $sysSaltLength );
			$userPassword = $dbSalt . sha1( $dbSalt . $userPassword );
		}
		
		if( $userPassword != $dbPassword ){
			 $this->UserAuthorized = false;
		}else{
			if( isset( $_SESSION ) )
				$_SESSION['sysUserID'] = $this->UserData['user_id'];
				
			$this->UserAuthorized = true;
		}
		
	}
	
	public function UserAuthorized(){	
		return $this->UserAuthorized;
	}
	
	public function ReturnData(){
		return $this->UserData;
	}
	
}