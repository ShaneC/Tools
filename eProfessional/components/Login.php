<?php

# Class to handle logins

class Login extends UserAuth {
	
	public function Login(){
		
		global $sys, $output;
		
		$sys['FinalPage'] = "epLogin.tpl";
		$output['sysPageTitle'] = " | Login";
		
		if( isset( $_SESSION['sysLoggedIn'] ) ){
			$error = new Error();
			$error->errorCustom( $sys['lang']['return']['error']['login']['loggedin'] );
		}
		
		if( !isset( $output['err'] ) ){ $output['err'] = ""; }
		
		# User has submitted data
		if( isset( $_POST['sysLoginSubmit'] ) ){
			
			$returnErr = false;
			if( empty( $_POST['sysLoginUsername'] ) ){
				$output['err'] .= $sys['lang']['return']['error']['login']['need_username'] . "<br />";
				$returnErr = true;
			}
			if( empty( $_POST['sysLoginPassword'] ) ){
				$output['err'] .= $sys['lang']['return']['error']['login']['need_password'] . "<br />";
				$returnErr = true;
			}
			if( $returnErr )
				return;
			
			$UserAuth = new UserAuth( $_POST['sysLoginUsername'], $_POST['sysLoginPassword'] );
			if( $UserAuth->UserAuthorized() ){
				
				$dbUser = $UserAuth->ReturnData();
				
				$_SESSION['sysUserName'] = $dbUser['user_name'];
				$_SESSION['sysUserPassword'] = $dbUser['user_password'];
				$_SESSION['sysUserID'] = $dbUser['user_id'];
				
				$_SESSION['sysLoggedIn'] = time();
				
				$result = new ResultDisplay( "You have successfully logged in!", "Success!", true );
				return;
				
			}else{
				$output['err'] .= $sys['lang']['return']['error']['login']['invalid_login'] . "<br />";
				return;
			}
			
		}
		
		
	}
	
}