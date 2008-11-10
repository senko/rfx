<?
/* RFX - A lightweight PHP web framework.
 * Copyright (C) 2007-2008. REI
 * All rights reserved.
 *
 * Author: Senko Rasic <senko.rasic@rei.hr>
 */

session_start();

require_once('config.php');
require_once('fx/fx.php');
require_once('lang/lang.php');
require_once('model/model.php');

require('controller/' . uri_dispatch() . '.php');

?>
