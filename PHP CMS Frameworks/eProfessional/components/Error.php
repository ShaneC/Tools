<?php

class Error {

	public function error404(){
		$this->ActaEstFabula( 'epError404.tpl' );
	}
	
	public function errorCustom( $err = NULL, $errTitle = NULL ){
		
		global $sys, $output;
		
		# Determine error message
		if( $err == NULL )
			$output['return']['err'] = $sys['lang']['return']['error']['generic'];
		else
			$output['return']['err'] = $err;
			
		# Determine error title
		if( $errTitle == NULL )
			$output['return']['errTitle'] = $sys['lang']['return']['error']['title'];
		else
			$output['return']['errTitle'] = $errTitle;
		
			
		$this->ActaEstFabula( 'epErrorCustom.tpl' );
		
	}
	
	function ActaEstFabula( $pageToOutput = "epError404.tpl" ){
		# Output all stored data
		global $sys, $output;
		
		$sys['TotalRequestTime'] = microtime( true ) - $sys['RequestTime'];

		$theme = new Template();
		$theme->WrapItUp( $pageToOutput );
		
		ob_end_flush();
		exit;
	}
	
}