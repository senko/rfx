<?php

/* This file contains a simple interface to Memcached, a high performance
 * distributed caching system. It allows storing, retrieveing, and deleting
 * values in the cache based on some key.
 *
 * This wrapper takes care of automatic serialization and deserialization
 * of values passed to/from the cache.
 *
 * Functions:
 *      cache_get()
 *      cache_set()
 *      cache_del()
 *
 * Configuration keys:
 *      memcache->host - memcached server (probably localhost)
 */

require_once('config.php');

$_mch = null;

function _cache_init()
{
    global $config;
    global $_mch;
    
    if (!is_null($_mch)) return;

    $_mch = memcache_pconnect($config['memcache']['host']);
}

/* Retrieves a value for $key in the cache, or returns $defval if not found. */
function cache_get($key, $defval=false)
{
    global $_mch;
    
    $val = memcache_get($_mch, $key);
    if (!$val) return $defval;
    return unserialize($val);
}

/* Sets a value for some key to the cache. */
function cache_set($key, $val, $expire=false)
{
    global $_mch;

    if($expire)
        memcache_set($_mch, $key, serialize($val), 0, $expire);
    else
        memcache_set($_mch, $key, serialize($val));
}

/* Clears cached value for some key. */
function cache_del($key)
{
    global $_mch;
    memcache_delete($_mch, $key);
}

/* Check if cache is running */
function cache_is_running()
{
    global $_mch;
    
    if($_mch)
        return true;
    else
        return false;
}

_cache_init();

?>