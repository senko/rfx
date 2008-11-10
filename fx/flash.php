<?php

/* This file contains the utilities for setting and getting the "flash"
 * message (typically an error or information banner after some operation).
 * Functions:
 *      flash_set()
 *      flash_is_set()
 */

$_flash_message = null;

/* Set a flash message to be displayed on the next web page. If
 * $immediate is false, the message will be displayed on the *next*
 * page (typically after redirect). If $immediate is true, it will
 * be displayed during the current execution of the script. */
function flash_set($txt, $immediate=false)
{
    global $_flash_message;

    if ($immediate) {
        $_flash_message = $txt;
    } else {
        setcookie('flash', $txt, 0, '/');
    }
}

/* Checks if there is a flash message and returns it if it's set. */
function flash_is_set() {
    global $_flash_message;
    if (is_null($_flash_message)) return false;
    return $_flash_message;
}

if (isset($_COOKIE['flash'])) {
    $_flash_message = $_COOKIE['flash'];
    setcookie('flash', false, 0, '/');
}

?>
