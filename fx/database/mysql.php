<?php
/*
 * MySQL low-level API 
 */

function _db_init()
{
    global $config, $_dbh;

    if (!is_null($_dbh)) return;

    $_dbh = mysql_pconnect($config['db']['host'],
        $config['db']['user'], $config['db']['pass']);
    if (!$_dbh) {
        $_dbh = null;
        return;
    }

    mysql_select_db($config['db']['name']);
    mysql_query('SET NAMES ' . $config['db']['encoding']);
}

/* Returns a single row of the result of $query. */
function db_select_single($query)
{
    $q = mysql_query($query);
    if (!$q) return null;
    $res = mysql_fetch_array($q, MYSQL_ASSOC);
    mysql_free_result($q);
    return $res;
}

/* Returns a single value, the result of $query. */
function db_select_one_field($query)
{
    $q = mysql_query($query);
    if (!$q) return null;
    $row = mysql_fetch_row($q);
    $res = $row[0];
    mysql_free_result($q);
    return $res;
}

/* Returns a list of rows, results of the query. */
function db_select_list($query)
{
    $q = mysql_query($query);
    if (!$q) return null;
    $ret = array();
    while ($row = mysql_fetch_array($q, MYSQL_ASSOC)) {
        array_push($ret, $row);
    }
    mysql_free_result($q);
    return $ret;
}

/* Use this function for selecting $page-th page of results (where each page has $limit rows), by passing
 * a query like '* FROM table ORDER BY id' (don't include 'SELECT' prefix or 'LIMIT' suffix). Returns
 * hash with keys: 'pages' (total pages in result), 'count' (total records in result), 'results' (this page rows). */
function db_select_paginated($query, $page=0, $limit=false)
{
    if (!$limit) return db_select_list($query);
    
    $page = intval($page);
    $limit = intval($limit);
    $start = $page * $limit;
    
    $cmd = 'SELECT SQL_CALC_FOUND_ROWS ' . $query . ' LIMIT ' . $start . ', ' . $limit;
    $results = db_select_list($query);
    if (!$results) return false;
    
    $cmd = 'SELECT FOUND_ROWS()';
    $count = db_select_one_field($cmd);
    if ($count > 0)
        $pages = 1 + floor(($count - 1) / $limit);
    else
        $pages = 0;
    
    return array('pages' => $pages, 'count' => $count, 'results' => $results);    
}

/* Performs an insert query and returns the newly inserted ID or false on failure. */
function db_insert($query, $seq=false)
{
    $q = mysql_query($query);
    if (!$q) return null;
    return mysql_insert_id();
}

/* Performs a general SQL query and returns true on success, false on failure. */
function db_execute($query)
{
    $q = mysql_query($query);
    if (!$q) return false;
    return true;
}

function db_driver_quote($text)
{
    return mysql_escape_string($text);
}


?>