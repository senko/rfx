<?php
/* This file contains the utility functions for accessing the database.
 * File has high-level API. Low-level API is defined in driver files
 * e.g 'mysql.php' in case of mysql
 *
 * The low-level API takes prepared SQL statements, and cleverly executes
 * them, returning the auto insert ID on inserts, or results as one field,
 * one row (associative array) or list of rows of result.
 * Functions:
 * db_select_single()
 * db_select_one_field()
 * db_select_list()
 * db_insert()
 * db_execute()
 *
 * The high-level API deals with records, with each record being one row
 * in the table and having a unique ID in the 'id' column. The API contains
 * functions for creating and updating records described as associative arrays.
 * It relies on the low-level api for the actual DB functionality.
 * Functions:
 * db_get_record_by_id()
 * db_insert_record()
 * db_insert_multiple_records()
 * db_update_record()
 * db_delete_record()
 *
 * Configuration keys:
 * db->host - database host
 * db->pass - password
 * db->user - username
 * db->name - database name
 * db->encoding - encoding to use (exclusive use of utf8 is encouraged)
 */

$_dbh = null;
require_once('config.php');

if ($config['db']['driver']) {
    require_once('fx/database/' . $config['db']['driver'] . '.php');
    _db_init();
}

/* Returns a row having ID equal to $id in $table. */
function db_get_record_by_id($table, $id, $idfield='id')
{
    $id = db_quote($id);
    return db_select_single('SELECT * FROM ' . $table . ' WHERE ' . $idfield . ' = ' . $id);
}

/* Inserts one record to $table.
 * The data for the record is taken from $record associative array.
 * Returns ID of the new record or false on failure. */
function db_insert_record($table, $record, $idfield='id')
{
    $keys = array();
    $values = array();
    foreach ($record as $key => $value) {
        if ($key == $idfield) continue;
        array_push($keys, $key);
        array_push($values, db_quote($value));
    }
    
    $key_str = ' (' . join(', ', $keys) . ') ';
    $val_str = ' VALUES (' . join(', ', $values) . ') ';
    $cmd = 'INSERT INTO ' . $table . $key_str . $val_str;
    // $seq argument used only in postgres databases
    $seq = $table . '_' . $idfield . '_seq';
    return db_insert($cmd, $seq);
}

/* Inserts multiple $records to $table in one SQL statement. Each element in
 * $records is treated as an associative array and escaped like in the
 * db_insert_record() function.*/
function db_insert_multiple_records($table, $records)
{
    $keys = array_keys($records[0]);
    $m_values = array();
    foreach ($records as $record) {
        $values = array();
        foreach($record as $key => $value) {
            $values[] = db_quote($value);
        }
        $m_values[] = '(' . join(', ', $values) . ')';
        unset($values);
    }
    $key_str = ' (' . join(', ', $keys) . ') ';
    $val_str = 'VALUES ' . join(', ', $m_values);
    $cmd = 'INSERT INTO ' . $table . $key_str . $val_str;
    return db_execute($cmd);
}

/* Updates a record in $table with the data provided in the $record
 * associative array. The record ID should be in $record['id']. Returns
 * true on success, false on error. */
function db_update_record($table, $record, $idfield='id')
{
    if (!$record[$idfield]) return false;
    $id = db_quote($record[$idfield]);
    $pairs = array();
    foreach ($record as $key => $value) {
        if ($key == $idfield) continue;
        array_push($pairs , $key . " = " . db_quote($value));
    }
    $cmd = 'UPDATE ' . $table . ' SET ' . join($pairs, ', ') . ' WHERE ' . $idfield . ' = ' . $id;
    return db_execute($cmd);
}

/* Deletes one record from $table with ID equal to $id. */
function db_delete_record($table, $id, $idfield='id')
{
    $id = db_quote($id);
    $cmd = 'DELETE FROM ' . $table . ' WHERE ' . $idfield . ' = ' . $id;
    return db_execute($cmd);
}

/* Database engine agnostic string escape function. Null-elements are set to
 * NULL, elements that are arrays themselves are inserted without quoting
 * (the zeroth subelement is used), and all other elements are escaped. */
function db_quote($value)
{
    if (is_null($value)) return 'NULL';
    if (is_numeric($value)) return $value;
    if (is_array($value) and count($value) == 1) return $value[0];

    return "'" . db_driver_quote($value) . "'";
}

?>
