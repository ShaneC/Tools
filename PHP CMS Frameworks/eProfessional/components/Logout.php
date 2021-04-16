<?php

# Class to handle logins

class Logout {
	
	public function Logout(){
		
		global $sys, $output;
		
		unset( $_SESSION['sysUserName'] );
		unset( $_SESSION['sysUserPassword'] );
		unset( $_SESSION['sysUserID'] );
		unset( $_SESSION['sysLoggedIn'] );
				
		$feedback = new ResultDisplay( "You have been succesfully logged out.", "Success!", true );
		
	}
	
}