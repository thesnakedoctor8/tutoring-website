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
		<div style='width:800px;'>
			<div class='jumbotron'>
				<h2><span class='glyphicon glyphicon-user'></span> Progress Report - $loggedInUser->displayname</h2>
				<br>
				
				<div style='width:400px;'>
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
				<br>
				<div style='width:400px;'>
					<h3>Grade Progress</h3>
					<ul class='list-group'>";
						if($subscriptions == null)
						{
							echo "
							<li class='list-group-item'>
								None
							</li>";
						}
						else
						{
							$subscription = explode("-", $subscriptions);
							foreach ($subscription as $s)
							{
								$score = "";
								$occurance = false;
								$scoreValue = 0;
								$counter = 0;
								
								if($achievements == null)
								{
									$score = "None";
								}
								else
								{
									foreach ($achievements as $a)
									{
										if($a['subject_id'] == fetchSubjectDetails($s)['id'])
										{
											$occurance = true;
											$scoreValue += $a['score']; 
											$counter++;
										}
									}
									if($occurance)
									{
										$score = $scoreValue/$counter;
										$score .= "%";
									}
									else
									{
										$score = "N/A";
									}
								}								
								
								echo "
								<li class='list-group-item'>
									".fetchSubjectDetails($s)['name']." - ".$score."
								</li>";
							}
						}
						echo "
					</ul>
					
					<br>
					<a href='account.php' class='btn btn-primary' role='button'>Back</a>
				</div>
				
			</div>
		</div>		
		
	</center>";
	
	include 'models/footer.php';
?>