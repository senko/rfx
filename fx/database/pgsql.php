<?php
/*
 * PostgreSQL low-level API
 */

function _db_init()
{
    global $config, $_dbh;
    
    if(!is_null($_dbh)) return;
    
    $_dbh = pg_pconnect('host=' . $config['db']['host'] . ' dbname=' .
        $config['db']['name'] . ' user=' . $config['db']['user'] . ' password=' .
        $config['db']['pass']);
    
    if(!$_dbh)
    {
        $_dbh = null;
        return;
    }
    
    pg_query("SET NAMES '" . $config['db']['encoding']. "'");
}

/* Returns a single row of the result of $query. */
function db_select_single($query)
{
    $q = pg_query($query);
    if(!$q) return null;
    $res = pg_fetch_assoc($q);
    pg_free_result($q);
    return $res;
    
}

/* Returns a single value, the result of $query. */
function db_select_one_field($query)
{
   $q = pg_query($query);
   if(!$q) return null;
   $row = pg_fetch_row($q);
   $res = $row[0];
   pg_free_result($q);
   return $res;
}

/* Returns a list of rows, results of the query. */
function db_select_list($query)
{
    $q = pg_query($query);
    if (!$q) return null;
    $ret = array();
    while ($row = pg_fetch_assoc($q)) {
        array_push($ret, $row);
    }
    pg_free_result($q);
    return $ret;
}

/* Performs an insert query and returns the newly inserted ID or false on failure. */
function db_insert($query, $seq)
{
    $q = pg_query($query);
    if (!$q) return null;
    
    return db_select_one_field("SELECT currval('" . db_driver_quote($seq) . "');");
}

/* Performs a general SQL query and returns true on success, false on failure. */
function db_execute($query)
{
    $q = pg_query($query);
    if (!$q) return false;
    return true;
}

function db_driver_quote($text)
{
    // TODO check if utf8 is needed
    // global $config
    // if($config['db']['encoding'] == 'utf8')
    //    return pg_escape_string(utf8_encode($text));
    return pg_escape_string($text);
}

?>