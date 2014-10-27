<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	require_once("models/header.php");
	
	
	
	echo "
	<center>
	Hello, $loggedInUser->displayname
	<br>
	User title: $loggedInUser->title  (can be changed in the admin panel)
	<br>
	Registered on: " . date("M d, Y", $loggedInUser->signupTimeStamp()) . "
	<br>
	<br>";
	
	$subscriptions = fetchAllSubscriptions($loggedInUser->user_id);
	if($subscriptions == null)
	{
		echo "No Subscriptions";
	}
	else
	{
		echo "Subscribed to:<br>";
		$subscription = explode("-", $subscriptions);
		foreach ($subscription as $s)
		{
			echo fetchSubjectDetails($s)['name']."<br>";
		}
	}
	
	echo "
	</center>";
	
	include 'models/footer.php';
?>