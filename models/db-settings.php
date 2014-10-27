<?php
	//Database Information
	$db_host = "localhost";			//Host address
	$db_name = "website"; 			//Name of Database
	$db_user = "websiteUser"; 		//Name of database user
	$db_pass = "mVmYHYw2jkfYyuW"; 	//Password for database user
	$db_table_prefix = "uc_";

	GLOBAL $errors;
	GLOBAL $successes;
	
	$errors = array();
	$successes = array();

	/* Create a new mysqli object with database connection parameters */
	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
	GLOBAL $mysqli;

	if(mysqli_connect_errno())
	{
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
?>