<?php

class UserSettings extends UserSQL {
	
	# Grab user settings
	
	public function UserSettings( $userID ){
		
		global $sys;
		
		$userData = $this->userRead( $userID, 'id' );
		
		# Define template (first determine if the administrator wants to override all users with one theme)
		if( $this->configRead( 'site_theme_override' ) == 0 )
			define( "SYS_TEMPLATE_PATH", $sys['ThemesPath'] . $userData['user_theme'] );
		else
			define( "SYS_TEMPLATE_PATH", $sys['ThemesPath'] . $this->configRead( 'site_theme' ) );
		
		# Define language variable(s)
		if( $this->configRead( 'site_language_override' ) == 0 )
			define( "SYS_LANGUAGE", $userData['user_language'] );
		else
			define( "SYS_LANGUAGE", $this->configRead( 'site_language' ) );

		require_once( $sys['LangPath'] . "LangIndex.php" );
		
		unset( $userData );
		
	}
	
}