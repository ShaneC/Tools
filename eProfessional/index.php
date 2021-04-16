<?php

/**
 * This is the main entry point to eProfessional.
 *
 * If you are reading this in your web browser your server most likely
 * is not configured properly to run PHP applications!
 *
 * See the README, INSTALL, and UPGRADE files for basic setup instructions
 * and pointers to the online documentation.
 *
 * http://www.xt-arts.com/
 *
 * ----------
 *
 * Copyright (C) 2009 Shane Chism.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

# Bring universal code online
$PREPS = dirname( __FILE__ );
require_once( "$PREPS/components/Init.php" );

# Access and run the main application object
require_once( "$PREPS/components/eP.php" );
$eP = new eProfessional();

# Do final clean up and end the sequence
$eP->ActaEstFabula( $sys['FinalPage'] );