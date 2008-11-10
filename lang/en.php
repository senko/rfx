<?php

$TRANSLATION_TABLE = array(
    'Logiraj se' => 'Log in',
    'Lozinka' => 'Password',
    'Registriraj se' => 'Register',
    'Više informacija' => 'Find out more',
);

function __($txt)
{
    global $TRANSLATION_TABLE;
    if (isset($TRANSLATION_TABLE[$txt])) return $TRANSLATION_TABLE[$txt];
    return $txt;
}

?>
