<?

$_route_mappings = array();
$_route_args = array();

/* Add a a new URI route. Route is absolute path which can have integer
 * or string arguments. The arguments can be accessed using uri_args()
 * function. You shouldn't call this function directly; define the routes
 * in the configuration file.
 */
function uri_route($route, $controller)
{
    global $_route_mappings;
    
    $pats = array();
    $args = array();

    if (!$route)
        return;
    
    foreach (explode('/', $route) as $r) {
        $p = false;
        $a = false;
        
        switch ($r[0]) {
            case ':':
                $p = '([0-9]+)';
                $a = substr($r, 1);
                break;
            case '$':
                $p = '([^/]+)';
                $a = substr($r, 1);
                break;
            default:
                $p = preg_quote($r);
        }
        
        $pats[] = $p;
        if ($a) $args[] = $a;
    }
    
    $pattern = '|^' . join('/', $pats) . '$|';
    
    $_route_mappings[] = array('pattern' => $pattern, 'args' => $args,
                               'controller' => $controller);
}

/* Parses the uri, detects controller to dispatch, and prepares the args.
 * Called by the framework, don't use it directly. */
function uri_dispatch()
{
    global $config;
    global $_route_mappings;
    global $_route_args;
    
    $route = uri_get_str($config['uri']['pathspec']);

    foreach ($_route_mappings as $m) {
        if (preg_match($m['pattern'], $route, &$to)) {
            array_shift($to);
            $args = array();
            for ($i = 0, $max = count($to); $i < $max; $i++) {
                $args[$m['args'][$i]] = $to[$i];
            }
            $_route_args = $args;
            return $m['controller'];            
        }
    }

    return $config['routes'][false];
}

/* Returns an associative array of arguments to this controller, as defined
 * in the route that was triggered. */
function uri_args()
{
    global $_route_args;    
    return $_route_args;
}

/* Returns a particular argument from the route or null if there is no such
 * argument. */
function uri_arg($name)
{
    global $_route_args;
    if (!isset($_route_args[$name])) return null;
    return $_route_args[$name];
}

/* Return an integer GET parameter, or null if there's no such param. */
function uri_get_int($name) {
    if (!isset($_GET[$name])) return null;
    return intval($_GET[$name]);
}

/* Return a string GET parameter, or null if there's no such param. */
function uri_get_str($name) {
    if (!isset($_GET[$name])) return null;
    return $_GET[$name];
}

/* Returns true if current request is POST request, false otherwise. */
function uri_is_post_request()
{
    return ($_SERVER['REQUEST_METHOD'] == 'POST');
}

/* Return an associative array consisting of POST fields, but only use the
 * fields enumerated in the specified $fields array (effectively, create
 * a subset of $_POST with the wanted fields). If a particular field is
 * not set, it is not included in the result. */
function uri_post_params($fields) {
    $ret = array();
    foreach ($fields as $f)
        if (isset($_POST[$f])) $ret[$f] = $_POST[$f];
    return $ret;
}

/* Handles file upload for $field, and copies it to $dest. If $dest is a
 * directory, a unique file in the directory will be created. If $ext is
 * true, the original extension will be kept. Returns new file name on
 * success, false on failure. */
function uri_file_upload($field, $dest=false, $useext=true)
{
    $from = $_FILES[$field]['tmp_name'];
    $orig = $_FILES[$field]['name'];
    $err = (int) $_FILES[$field]['error'];
    $size = (int) $_FILES[$field]['size'];

    if ($err) return false;
    if (!$size) return false;
    if (!$from) return false;

    $ext = '';
    if ($useext) {
        if (strchr($orig, '.')) {
            $idx = explode('.', $orig);
            $ext = '.' . $idx[count($idx) - 1];
        }
    }

    if (is_dir($dest)) {
        if ($dest[strlen($dest) - 1] != '/') $dest .= '/';
        $fname = md5(time() . getmypid() . $from . $orig) . $ext;
        while (file_exists($dest . $fname))
            $fname = md5(time() . $fname) . $ext;
        $dest = $dest . $fname;
    }

    if (!move_uploaded_file($from, $dest))
        return false;

    return $dest;
}

/* Make an absolute URL pointing to $action, with optional $params. The
 * function returns: http://<base-uri>/$action/$param1/$param2... */
function uri($action, $params=array()) {
    global $config;
    
    if ($action != '') $action .= '/';
    
    $u .= $config['uri']['base'] . $action . join('/', $params);
    return $u;
}

/* Redirect the browser to $target and immediately exit. You can prepare
 * the target with uri() method. */
function redirect($target) {
    global $config;
    
    header('Location: ' . $target);
    exit;
}

/* Install the configured routes. */
foreach ($config['routes'] as $route => $controller) {
    uri_route($route, $controller);
}

?>
