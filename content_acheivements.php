<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}

	//Forms posted
	if(!empty($_POST))
	{
		$deletions = $_POST['deleteAchievement'];
		if ($deletion_count = deleteAchievement($deletions))
		{
			$successes[] = lang("ACHIEVEMENT_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else
		{
			$errors[] = lang("SQL_ERROR");
		}
		
		$deletions = $_POST['deleteQuiz'];
		if ($deletion_count = deleteQuiz($deletions))
		{
			$successes[] = lang("QUIZ_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else
		{
			$errors[] = lang("SQL_ERROR");
		}
	}

	$achievements = fetchAllAchievements();
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);

	echo "
	<div style='width:600px;'>
		<h2>Achievements</h2>
		
		<form name='achievements' action='".$_SERVER['PHP_SELF']."' method='post'>
			<table class='table table-striped'>
				<thead>
					<tr>
						<th>ID</th>
						<th>Username</th>
						<th>Quiz ID</th>
						<th>Score</th>
					</tr>
				</thead>

				<tbody>";
					if($achievements == null)
					{
						echo "
						<tr>
							<td>
								--
							</td>
							<td>							
								--
							</td>
							<td>
								--
							</td>
							<td>
								--
							</td>
						</tr>";
					}
					else
					{
						//Cycle through achievements
						foreach ($achievements as $v1)
						{
							echo "
							<tr>
								<td>
									<input type='checkbox' name='deleteAchievement[".$v1['id']."]' id='deleteAchievement[".$v1['id']."]' value='".$v1['id']."'> ".$v1['id']."
								</td>
								<td>							
									".fetchUserDetails(NULL,NULL,$v1['user_id'])['user_name']."
								</td>
								<td>
									<a href='content_quiz.php?id=".$v1['quiz_id']."'>".$v1['name']."</a>
								</td>
								<td>
									".$v1['score']."
								</td>
							</tr>";
						}
					}
				echo "
				</tbody>
			</table>
					
			<input type='submit' name='Submit' value='Delete' class='btn btn-primary' style='max-width:200px;'/>
		</form>
	</div>";
	echo "</center>";
	
	include 'models/footer.php';

?>
