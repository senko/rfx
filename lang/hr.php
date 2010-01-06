<?php

$TRANSLATION_TABLE = array(
    'Log in' => 'Logiraj se',
    'Password' => 'Lozinka',
    'Register' => 'Registriraj se',
    'Find out more' => 'Više informacija',
);

function __($txt)
{
    global $TRANSLATION_TABLE;
    if (isset($TRANSLATION_TABLE[$txt])) return $TRANSLATION_TABLE[$txt];
    return $txt;
}

?>
