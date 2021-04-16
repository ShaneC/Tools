<?php
/*************************************************
 * Random String Generator
 * Version: 2.1.0
 * Coded by: Shane Chism <http://shanechism.com>
 * Updates: http://shanechism.com/code/static/2
 * Distributed under the GNU Public License
 *************************************************/
 
class RandomStringGenerator {

	# Configurable values
	var $defaultSize = 10;

	var $charPool;
	public function __construct( $includeLetters = true, $includeCaps = true, $includeNumbers = true, $includeSymbols = true ){
	
		# Pool of characters to choose from
		$letters = array( "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", 
				  "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z" );
		$capital = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", 
				  "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" );
		$numbers = array( "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
		$symbols = array( "!", "%", "@", "_", "#", "+", "=", "?", "$", "-", "*", "&" );
		
		$this->charPool = array();
		if( $includeLetters )
			$this->charPool = array_merge( $this->charPool, $letters );
		if( $includeCaps )
			$this->charPool = array_merge( $this->charPool, $capital );
		if( $includeNumbers )
			$this->charPool = array_merge( $this->charPool, $numbers );
		if( $includeSymbols )
			$this->charPool = array_merge( $this->charPool, $symbols );
			
		if( count( $this->charPool ) < 1 )
			die( "<b>Random String Generator Error</b>: You must include at least one set of characters." );
			
		# Randomize the elements in the array
		shuffle( $this->charPool );
		
	}
	
	public function generate( $length = NULL ){
		
		if( $length === NULL )
			$length = $this->defaultSize;
		
		# Randomize the elements in the array
		shuffle( $this->charPool );
		
		# Initialize the string
		$outputStr = "";
		
		# Select the elements from the pool
		for( $i = 0; $i < $length; $i++ ){
			$outputStr .= $this->charPool[array_rand( $this->charPool, 1 )];
			shuffle( $this->charPool );
		}
		
		# Return the string
		return $outputStr;
		
	}

}

?>