<?php

/******************************************************************************
 * Database utility functions
 */

/* Returns all the rows from the database that match the $query */
function Query($db, $query)
{
	$stmt = $db->prepare($query);
	$stmt->execute();
	return $stmt->fetchAll();
}

/* Prepare a string for insertion into the database */
function RealEscapeString($db, $escapestr)
{
	return $db->quote($escapestr);
}

/******************************************************************************
 * Database access
 */

/* $username = "root"; */
$username = "cms";

/*$password = ""; */
$password = "Abby2008";

/* $host = "localhost"; */
//$host = "209.17.116.156";
$host = "205.178.137.139";

$dbname = "cornfedcms";

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
	$db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8",
		      $username, $password, $options);
} catch(PDOException $ex) {
	die($ex);
}

//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
	function undo_magic_quotes_gpc(&$array) {
		foreach($array as &$value) {
			if(is_array($value)) {
				undo_magic_quotes_gpc($value);
			} else {
				$value = stripslashes($value);
			}
		}
	}
	undo_magic_quotes_gpc($_POST);
	undo_magic_quotes_gpc($_GET);
	undo_magic_quotes_gpc($_COOKIE);
}

/* Start session */
/*
header('Content-Type: text/html; charset=utf-8');
session_start();
*/
