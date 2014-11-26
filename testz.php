<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	

	$subjectData = fetchAllSubjects(); //Retrieve list of all subjects
	
	require_once("models/header.php");

	echo "<center>";

	echo "test1<br>";
	
	$subject = 34;
	
	// Remove the subscription from all subscribed users
	$userData = fetchAllUsers();
	foreach ($userData as $v1)
	{
		if(alreadySubscribed($v1['id'], $subject))
		{
			//deleteSubscription($v1['id'], $subject);
		}
	}
	
	echo "</center>";
	
	include 'models/footer.php';
?>
