<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}

	//Forms posted
	if(!empty($_POST))
	{
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

	$quizzes = fetchEveryQuizzes();
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);

	echo "
	<div style='width:600px;'>
		<h2>Admin Users</h2>
		
		<form name='adminUsers' action='".$_SERVER['PHP_SELF']."' method='post'>
			<table class='table table-striped'>
				<thead>
					<tr>
						<th>Delete</th>
						<th>Quiz Name</th>
						<th>For Subject</th>
					</tr>
				</thead>

				<tbody>";
					//Cycle through users
					foreach ($quizzes as $v1)
					{
						echo "
						<tr>
							<td>
								<input type='checkbox' name='deleteQuiz[".$v1['id']."]' id='deleteQuiz[".$v1['id']."]' value='".$v1['id']."'> ".$v1['id']."
							</td>
							<td>
								<a href='content_quiz.php?id=".$v1['id']."'>".$v1['name']."</a>
							</td>
							<td>
								<a href='content_subject.php?id=".$v1['subject_id']."'>".fetchSubjectDetails($v1['subject_id'])['name']."</a>
							</td>
						</tr>";
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
