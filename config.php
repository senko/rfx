<?php

$config = array(

    /* Database configuration. */
    'db' => array(
        'host' => 'localhost',
        'name' => 'imebaze',
        'user' => 'imejuzera',
        'pass' => 'lozinka',
        'encoding' => 'utf8',
        'driver' => false /* engines: 'mysql', 'pgsql' */
    ),

    'uri' => array(
        'base' => '/',
        'pathspec' => '_rfx_path'
    ),

    'routes' => array(
        '/article/:id' => 'default',
        '/author/$name' => 'default',
        false => 'default',
    ),

    'auth' => array(
        'user-key' => 'email',
        'pass-key' => 'password',
        'login-auth' => auth_simple,
        
        'simple-auth' => array(
            'admin' => 'admin123'
        )
    ),

    'lang' => array(
        'languages' => array('hr', 'en', 'de', 'it'),
        'default' => 'en',
        'cookie-key' => 'lang_cookie',
    ),

);

?>
