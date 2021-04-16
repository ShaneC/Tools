<?php

# Database functions

class eProfessional {

	# Initalize the constructor
	function eProfessional(){
		
		global $PS, $sys, $output;

		# Initalize System Defaults
		$sys['FinalPage'] = "epIndex.tpl";
		$sys['NumQueries'] = 0;
		
		# Initalize Output Defaults
		$output['sysPageTitle'] = "";
		
		# Enable the database functionality
		$this->db = new SQL();
		
		# Define global variables
		define( "SYS_SITE_NAME", $this->db->configRead( 'site_name' ) );
		
		# Initalize Session
		session_start();
		
		# Determine if a user is logged in. If yes, set to their settings. If no, trigger defaults.
		if( isset( $_SESSION['sysLoggedIn'] ) ){
			# Logged in
			# Verify user's credentials
			$this->user = new UserAuth( $_SESSION['sysUserName'], $_SESSION['sysUserPassword'], true );
			if( !$this->user->UserAuthorized ){
				# Define language variable(s)
				define( "SYS_LANGUAGE", $this->db->configRead( 'site_language' ) );
				require_once( $sys['LangPath'] . "LangIndex.php" );
				# Crash script w/ hacking attempt (data in session was altered after login)
				sysCriticalFail( $sys['lang']['return']['error']['hacking_attempt'] );
			}
			
			# Logged in, trigger user defaults
			$UserSettings = new UserSettings( $_SESSION['sysUserID'] );
		}else{
			# Not logged in, set defaults:
			# Define template
			define( "SYS_TEMPLATE_PATH", $sys['ThemesPath'] . $this->db->configRead( 'site_theme' ) );
			
			# Define language variable(s)
			define( "SYS_LANGUAGE", $this->db->configRead( 'site_language' ) );
			require_once( $sys['LangPath'] . "LangIndex.php" );
		}
		
		# Determine the function being used
		if( isset( $_REQUEST['a'] ) ){
			# An article has been called
			$article = new Article( $_REQUEST['a'] );
			$output['a'] = $article->returnArticle();
			
			$category = new Category( $_REQUEST['a'] );
			$output['c'] = $category->returnCategory();
		}elseif( isset( $_REQUEST['p'] ) ){
			# An author has been called 
			
		}elseif( isset( $_REQUEST['page'] ) ){
			# A page has been called
		
		}elseif( isset( $_REQUEST['f'] ) ){
		
			switch( $_REQUEST['f'] ){
				case 'login' : $tool = new Login(); break;
				case 'logout' : $tool = new Logout(); break;
				case 'result' : $tool = new ResultDisplay( $_SESSION['sysTempResult'], $_SESSION['sysTempResultTitle'] ); break;
			}
		
		}

	}
	
	function ActaEstFabula( $pageToOutput = "epIndex.tpl" ){
		# Output all stored data
		global $sys;
		
		$sys['TotalRequestTime'] = microtime( true ) - $sys['RequestTime'];
		
		$theme = new Template();
		$theme->WrapItUp( $pageToOutput );
		
		ob_end_flush();
		exit;
	}
	
}