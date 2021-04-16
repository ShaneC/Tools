<?php

class Encryption {
	
	static private function generateSalt(){
		
		global $sysSaltLength;
		
		if( $sysSaltLength != 0 && $sysSaltLength != NULL ){
			$salt = substr( md5( uniqid( rand(), true ) ), 0, $sysSaltLength );
		}else{
			$salt = "";
		}
		
		return $salt;
		
	}
	
	static public function generatePassword( $input ){
		
		$salt = $this->generateSalt();
		return $salt . sha1( $salt . $input ) );
		
	}
	
}