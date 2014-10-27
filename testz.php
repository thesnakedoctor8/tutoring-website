<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	

	$subjectData = fetchAllSubjects(); //Retrieve list of all subjects
	
	require_once("models/header.php");

	echo "<center>";

	echo "test";
	
	echo "</center>";
	
	include 'models/footer.php';
?>
