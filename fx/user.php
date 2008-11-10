<?php

$_auth_current = null;

function _auth_authenticate()
{
    global $config;
    global $_auth_current;

    if (array_key_exists('_auth_current', $_SESSION)) {
        $_auth_current = $_SESSION['_auth_current'];
        return;
    }

    if (!uri_is_post_request())
        return;

    $u = $config['auth']['user-key'];
    $p = $config['auth']['pass-key'];
    $x = array('u' => $_POST[$u], 'p' => $_POST[$p]);
    
    if (isset($x['u']) and isset($x['p'])) {
        $_auth_current = $config['auth']['login-auth']($x['u'], $x['p']);

        if (auth_ok()) {
            $_SESSION['_auth_current'] = $_auth_current;
            return;
        }
    }
}

/* Returns true if current visitor is authorized user, false otherwise. */
function auth_ok()
{
    global $_auth_current;
    return (!is_null($_auth_current) and $_auth_current);
}

/* Returns the current user data (associative array). */
function auth_current()
{
    global $_auth_current;
    return $_auth_current;
}

/* Log out the current user. */
function auth_logout()
{
    global $config;
    global $_auth_current;
    
    if (!auth_ok())
        return;

    unset($_SESSION['_auth_current']);
    $_auth_current = null;
}

/* Check that the current visitor is authorized user. If not, redirect
 * to $target or just exit. */
function auth_require($target=false)
{
    global $config;

    if (auth_ok())
        return;
    
    if ($target)
        redirect(uri($target));
    
    exit;
}

/* Performs simple config-file based authentication. */
function auth_simple($user, $pass)
{
    global $config;
    
    if (array_key_exists($user, $config['auth']['simple-auth'])) {
        $p = $config['auth']['simple-auth'][$user];
        if ($p == $pass) {
            return array('user' => $user);
        }
    }
    
    return false;
}

_auth_authenticate();

?>
