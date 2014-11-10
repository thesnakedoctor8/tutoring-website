<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	$subscriptions = fetchAllSubscriptions($loggedInUser->user_id);
	
	require_once("models/header.php");	
	
	echo "
	<center>
		<div style='width:700px;'>
			<div class='jumbotron'>
				<h2>Hello $loggedInUser->displayname</h2>
				<br>
				<h4>
				User title: $loggedInUser->title
				<br>
				<br>
				Registered on: ".date("M d, Y", $loggedInUser->signupTimeStamp())."
				</h4>
			</div>
		</div>
		
		<div class='list-group' style='width:300px;'>
			<a href='' class='list-group-item active'>Your Subscriptions</a>";
			if($subscriptions == null)
			{
				echo "<a href='' class='list-group-item'>No Subscriptions</a>";
				
			}
			else
			{
				$subscription = explode("-", $subscriptions);
				foreach ($subscription as $s)
				{
					echo "<a href='subject.php?id=".$s."' class='list-group-item'>".fetchSubjectDetails($s)['name']."</a>";
				}
			}
			echo "
		</div>
	</center>";
	
	include 'models/footer.php';
?>