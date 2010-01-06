<?
/* RFX - A lightweight PHP web framework.
 * Copyright (C) 2007-2008. REI
 * Licensed under MIT license. See the LICENSE.txt file for  details.
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
