<?php
 /*
 * General Website Framework
 * @requires PHP >= 5.2
 * @homepage http://shanechism.com
 *
 * Copyright (c) Shane Chism <schism@acm.org> 2011
 * \ Reproduction and distribution prohibited
 * \ Modification permitted as needed for licensed website
 */
 
if( !defined( "RUNTIME" ) ){
	print( "<b>Error:</b> Invalid file access.<br />Improper runtime environment." );
	exit();
}

/** \brief Event Class to handle system hooks.
  * @author Shane Chism <schism@acm.org>
 **/
class SAction {
	
	private $hooks = array();
	
	public function add( $hook_event, $callback, $params = NULL, $priority = "1" ){
		$this->hooks[$hook_event][] = new SHook( $callback, $params, $priority );
	}
	
	public function fire( $hook_event ){
		if( !isset( $this->hooks[$hook_event] ) || count( $this->hooks[$hook_event] ) < 1 )
			return;
		uasort( $this->hooks[$hook_event], 'SAction::compare' );
		
		// Loop through queued actions, sorted based on priority.
		// The Do-While here will keep looping through the queue
		// so long as things keep leaving it. This allows modules
		// with callable dependencies to execute, even if they're
		// queued first.
		do {
			$count = count( $this->hooks[$hook_event] );
			foreach( $this->hooks[$hook_event] as $key => $hook ){
				if( !is_callable( $hook->callback() ) )
					continue;
				if( is_array( $hook->params() ) )
					call_user_func_array( $hook->callback(), $hook->params() );
				else
					call_user_func( $hook->callback() );
				unset( $this->hooks[$hook_event][$key] );
			}
		}while( $count != count( $this->hooks[$hook_event] ) );
	}
	
	private static function compare( $a, $b ){
		if( $a->priority == $b->priority )
			return 0;
		return( $a->priority < $b->priority ) ? -1 : 1;
	}
	
}

class SHook {
	
	private $callback, $params, $priority;
	
	public function __construct( $callback, $params, $priority ){
		$this->callback = $callback;
		$this->params = $params;
		$this->priority = $priority;
	}
	
	public function callback(){
		return $this->callback;	
	}
	
	public function params(){
		return $this->params;	
	}
	
	public function priority(){
		return $this->priority;	
	}
	
}

?>