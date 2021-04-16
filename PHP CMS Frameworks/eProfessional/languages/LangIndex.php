<?php

# Global index and directory for language files

switch( SYS_LANGUAGE ){
	
	case 'english' : $sysLangDir = 'English.php'; break;
	case 'english_pirate' : $sysLangDir = 'English(Pirate).php'; break;
	default : $sysLangDir = 'English.php';
}

require_once( $sys['LangPath'] . $sysLangDir );