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

/** Screen Global Configurables */
define( "ALWAYS_LOAD_FACEBOX", false );

/** Facebox Configurables */
define( "FACEBOX_DEFAULT_STYLE", "generic" );

/** \brief Template implementation class.
 *  @author Shane Chism 
 */
class Screen {
	
	/** Stores whether or not the Facebox plugin has been loaded (prevents duplication) */
	private $faceboxLoaded = false;
	
	/** Stores defaults set through class methods **/
	private $defaults;
	
	/**
	 * Initialize the screen class and perform any necessary global options
	 * @returns Pointer to class object
	 */
	public function __construct(){
		if( ALWAYS_LOAD_FACEBOX )
			$this->loadFacebox();
	}
	
	/** Selects template files, outputs them to the screen, and terminates the application.
	 *  @param file string containing the path/name of the template file (i.e. master/myFile.tpl)
	 *  @param title OPTIONAL string containing the page title
	 *  @param noOverall OPTIONAL boolean specifying whether this uses overall headers or not
	 *  @param header OPTIONAL string containing a header file to overwrite the master header
	 *  @param footer OPTIONAL string containing a footer file to overwrite the master footer
	 */
	public function show( $file, $title = NULL, $noOverall = false, $header = NULL, $footer = NULL ){
		global $output, $sys;
		
		$output['page']['js'] = ( !isset( $output['page']['js'] ) ) ? '<!-- NONE -->' : $output['page']['js'];
		$output['page']['css'] = ( !isset( $output['page']['css'] ) ) ? '<!-- NONE -->' : $output['page']['css'];
		
		if( !isset( $output['page']['js-script'] ) )
			$output['page']['js-script'] = '<!-- NONE -->';
		else{
			$output['page']['js-script'] =	'<script type="text/javascript">' . "\n\t\t" . 
												'$(document).ready( function() {' . 
												 $output['page']['js-script'] . "\n\t\t" . 
												'});' . "\n\t" . 
											'</script>';
		}
		
		$output['page']['js-script'] = ( !isset( $output['page']['js-script'] ) ) ? '<!-- NONE -->' : $output['page']['js-script'];
		
		if( !$noOverall ){
			$header = ( $header === NULL ) ? 'master/overall_header.tpl' : $header;
			$footer = ( $footer === NULL ) ? 'master/overall_footer.tpl' : $footer;
		}
		
		$output['page']['title'] = ( $title === NULL ) ? $sys['page']['title'] : $title . " | " . $sys['page']['title'];
		
		if( !$noOverall )
			require( PS . '/style/' . $header );
		require( PS . '/style/' . $file );
		if( !$noOverall )
			require( PS . '/style/' . $footer );
		exit();
	}
	
	/** Outputs to screen based on pre-set default parameters
	 *  @param defaultName string containing the name of the default created using registerDefault
	 *  @param file string containing the path/name of the template file (i.e. master/myFile.tpl)
	 *  @param title OPTIONAL string containing the page title
	 */
	public function showDefault( $defaultName, $file, $title = NULL ){
		if( !isset( $this->defaults[$defaultName] ) )
			throw new Exception( "No default has been added with the name of {$defaultName}." );
		$this->show( $file, $title, $this->defaults[$defaultName]['noOverall'],
									$this->defaults[$defaultName]['header'],
									$this->defaults[$defaultName]['footer']
					);
	}
	
	/** Add a non-standard Javascript file to the template file.
	 *  @param file string containing the path/name of the javascript file relative to the js dir (i.e. myScript.js)
	 */
	public function addJS( $file ){
		global $output;
		$output['page']['js'] = ( !isset( $output['page']['js'] ) ) ? '' : $output['page']['js'];
		$output['page']['js'] .= "<script src='" . HTTP_ROOT . "/includes/js/" . $file . "' type='text/javascript'></script>\n";
	}
	
	/** Add a non-standard CSS file to the template file.
	 *  @param file string containing the path/name of the css file relative to the style dir (i.e. master/css/buttons.css)
	 *  @param relativeDir optional string. if set the $file var will become relative to the value of this variable
	 */
	public function addCSS( $file, $relativeDir = NULL ){
		global $output;
		$output['page']['css'] = ( !isset( $output['page']['css'] ) ) ? '' : $output['page']['css'];
		if( $relativeDir !== NULL )
			$output['page']['css'] .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . HTTP_ROOT . "/" . $relativeDir . "/" . $file . "\"/>\n";
		else
			$output['page']['css'] .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . HTTP_ROOT . "/style/" . $file . "\"/>\n";
	}
	
	/** Add a block of JavaScript to the page header
	 *  @param script string containing the JavaScript code to include
	 */
	public function addJSScript( $script ){
		global $output;
		$output['page']['js-script'] = ( !isset( $output['page']['js-script'] ) ) ? '' : $output['page']['js-script'];
		$output['page']['js-script'] .= "\n\t\t\t" . $script;
	}
	
	/** 
	 * Attaches files needed for use of the Facebox jQuery plugin
	 * @param style string specifying the facebox style
	 * @returns Adds to the Screen the Facebox files
	 */
	public function loadFacebox( $style = FACEBOX_DEFAULT_STYLE ){
		if( $this->faceboxLoaded )
			return;
		if( !is_dir( PS . "/includes/js/plugins/jquery_facebox/styles/" . $style ) )
			throw new Exception( "Invalid Facebox style option specified." );
		if( !file_exists( PS . "/includes/js/plugins/jquery_facebox/styles/" . $style . "/jquery-facebox.css" ) )
			throw new Exception( "Unable to load Facebox style resources." );
		$this->addJS( "plugins/jquery_facebox/jquery-facebox.js" );
		$this->addCSS( "js/plugins/jquery_facebox/styles/" . $style . "/jquery-facebox.css", "includes" );
		$this->addJSScript(	"$('a[rel*=facebox]').facebox({\n" . 
								"\t\t\t\tloadingImage : '/includes/js/plugins/jquery_facebox/styles/" . $style . "/images/loading.gif',\n" . 
								"\t\t\t\tcloseImage : '/includes/js/plugins/jquery_facebox/styles/" . $style . "/images/closelabel.png'\n" . 
							"\t\t\t});" );
		$this->faceboxLoaded = true;
	}
	
	/** 
	 * Stores parameters for use during the current session
	 *  @param noOverall OPTIONAL boolean specifying whether this uses overall headers or not
	 *  @param header OPTIONAL string containing a header file to overwrite the master header
	 *  @param footer OPTIONAL string containing a footer file to overwrite the master footer
	 */
	public function registerDefault( $name, $noOverall = false, $header = NULL, $footer = NULL ){
		if( !isset( $this->defaults ) )
			$this->defaults = array();
		try {
			if( isset( $this->defaults[$name] ) )
				throw new SysException( "The Screen Default of name {$name} already exists." );
			if( !$noOverall && ( $header == NULL || $footer == NULL ) )
				throw new SysException( "Please specify a header and footer file to include by default." );
		}catch( SysException $e ){
			$e->show( $e->getMessage() );	
		}
		$this->defaults[$name]['noOverall'] = $noOverall;
		$this->defaults[$name]['header'] = ( $noOverall ) ? NULL : $header;
		$this->defaults[$name]['footer'] = ( $noOverall ) ? NULL : $footer;
	}
	
}

?>