<?php

class ResultDisplay {

	public function ResultDisplay( $result, $resultTitle, $reload = false ){
		
		global $sys, $output;
		
		$output['return']['resultTitle'] = $resultTitle;
		$output['return']['result'] = $result;
		
		if( $reload ){
			$_SESSION['sysTempResult'] = $result;
			$_SESSION['sysTempResultTitle'] = $resultTitle;
			header( 'location: /?f=result' );
			exit;
		}else if( isset( $_SESSION['sysTempResult'] ) && isset( $_SESSION['sysTempTitle'] ) ){
			unset( $_SESSION['sysTempResult'] );
			unset( $_SESSION['sysTempResultTitle'] );
		}
		
		$this->ActaEstFabula( 'epResultDisplay.tpl' );
		
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