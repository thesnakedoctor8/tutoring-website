<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	$subscriptions = fetchAllSubscriptions($loggedInUser->user_id);
	$achievements = fetchAchievements($loggedInUser->user_id);
	
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
		
		
		<div class='col-md-5'>
			<div class='list-group'>
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
		</div>
		
		<div class='col-md-5 col-md-offset-2'>
			<h3>Acheivements</h3>
			<ul class='list-group'>";
				if($achievements == null)
				{
					echo "
					<li class='list-group-item'>
						None yet
					</li>";
				}
				else
				{
					foreach ($achievements as $a)
					{
						echo "
						<li class='list-group-item'>
							<span class='glyphicon glyphicon-list-alt'></span> - ".$a['name']."
						</li>";
					}
				}
			echo "
			</ul>
		</div>
	</center>";
	
	include 'models/footer.php';
?>