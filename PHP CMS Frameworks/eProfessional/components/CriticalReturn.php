<?php

# Function to display a critical error message

define( "SYS_FCRITICALRETURN", TRUE );

function sysCriticalFail( $err = NULL ){

	global $sys;
	
	if( !isset( $sys['lang'] ) ){
		if( empty( $err ) ){
			$err = "A critical error has caused the website to crash. Please try your request again later.";
		}
		die( print( "<h3>Critical Error:</h3>" . $err ) );
	}else{
		if( empty( $err ) )
			$err = $sys['lang']['return']['error']['generic'];
			
		die( print( "<h3>" . $sys['lang']['return']['error']['citical_title'] . "</h3>" . $err ) );
	}

}
