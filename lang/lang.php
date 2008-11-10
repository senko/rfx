<?php

$_lang_selected = false;

function _lang_select()
{
    global $config;
    global $_lang_selected;
    
    $cookie = cookie_recall($config['lang']['cookie-key']);
    if (!is_null($cookie)) {
        foreach ($config['lang']['languages'] as $l) {
            if ($cookie == $l) $_lang_selected = $l;
        }
    }

    if (!$_lang_selected)
        $_lang_selected = $config['lang']['default'];

    return $_lang_selected;    
}

require_once('lang/' . _lang_select() . '.php');

?>
