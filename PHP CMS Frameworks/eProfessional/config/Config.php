<?php

# This document includes the global properties for the
# web application that were set during install. If this
# file exists then the installation has been succesfully
# installed.

# MySQL Database Information
$sysDBLocation  = "localhost";
$sysDBName      = "eprofessional";
$sysDBUser      = "dbuser";
$sysDBPass      = "password";

# Database Table Prefix (ie: prefix_user, prefix_configuration, etc.)
$sysDBPrefix    = "ep_";

# Debug Logging
$sysDebug		= true;
$sysDebugFile   = "$PS/errorLog.txt";

# Mod_Rewrite Functionality
$sysModRewrite  = true;

# Encryption Protocols
# Changing any variables in this section after install
# will very likely corrupt authorizations in your eP installation
$sysSaltLength  = 9;

# Directory Information
$sys['AdminPath']      = "./ops/";
$sys['ComponenetPath'] = "./components/";
$sys['LangPath']       = "./languages/";
$sys['ThemesPath']     = "./themes/";