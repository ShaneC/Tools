<?php

# Debug function

define( "SYS_FDEBUG", TRUE );

function sysDebug( $input ){

	global $sysDebug, $sysDebugFile;
	
	# If debug logging is disabled end the function
	if( !$sysDebug ) return true;
	
	# Attempt to open the logging file, 
	$file = @fopen( $sysDebugFile, "a" );
	if( !$file ) return false;
	
	$temp = "[" . date( 'Y-m-d', time() ) . " " . date( 'H:i:s', time() ) . "] " . $input . "\r\n";
	
	fwrite( $file, $temp );
	fclose( $file );
	
	# Ensure the error log is readable only by the system owner
	chmod( $sysDebugFile, 0600 );
	
	return true;

}