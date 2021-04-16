<?php

class Time {
	
	public function enTimePassed( $timestamp ){
		
		global $sys;
		
		#Calculate, in layman's terms, how long has passed since the time stamp
		$new = time() - $timestamp;
		$time = array(  $sys['lang']['time']['second'] => 1,
					  	$sys['lang']['time']['minute'] => 60,
						$sys['lang']['time']['hour']   => 3600,
						$sys['lang']['time']['day']    => 86400,
						$sys['lang']['time']['week']   => 604800,
						$sys['lang']['time']['month']  => 2630880,
						$sys['lang']['time']['year']   => 31570560,
						$sys['lang']['time']['decade'] => 315705600
				);
		
		$temp = "";
		foreach( array_reverse( $time ) as $key => $value ){
			if( $new >= $value ){
				$result = floor( $new / $value );
				$new -= ( $result * $value );
				switch( $result ){
					case 1  : $plural = ""; break;
					default : $plural = $sys['lang']['time']['plural']; break;
				}
				$temp .= $result . " " . $key . $plural . " ";
			}
		}
		return $temp . " " . $sys['lang']['time']['ago'];
	}
	
}