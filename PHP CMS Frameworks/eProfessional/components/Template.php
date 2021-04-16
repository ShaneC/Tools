<?php

class Template {
	
	public function WrapItUp( $pageToOutput ){
		
		global $PS, $sys, $output;
		
		# -DEV: Calculate release date for development purposes
		$time = new Time();
		$sys['epReleaseEN'] = $time->enTimePassed( $sys['epReleaseDate'] );
		
		if( !file_exists( SYS_TEMPLATE_PATH . "/" . $pageToOutput ) ){
			return false;
		}else{
			require_once( SYS_TEMPLATE_PATH . "/epHeader.tpl" );
			require_once( SYS_TEMPLATE_PATH . "/" . $pageToOutput );
			require_once( SYS_TEMPLATE_PATH . "/epFooter.tpl" );
			return true;
		}
		
	}
	
}