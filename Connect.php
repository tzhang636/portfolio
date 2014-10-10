<?php

/**
 * 
 * Connects the database.
 * Require this file for every file that needs to connect to the database.
 */
$host = "localhost";
$username = "root";
$password = "";
$database = "comments";

$con = mysql_connect($host, $username, $password) or die("Could not connect: " . mysql_error());
mysql_select_db($database) or die ("database doesn't exist");

?>