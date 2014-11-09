<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}

	//Forms posted
	if(!empty($_POST))
	{
		$deletions = $_POST['deleteResource'];
		if ($deletion_count = deleteResource($deletions))
		{
			$successes[] = lang("RESOURCE_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else
		{
			$errors[] = lang("SQL_ERROR");
		}
	}
	
	$subjectData = fetchAllSubjects();
	$videos = fetchAllResources($subjectId, "video");
	$links = fetchAllResources($subjectId, "link");
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);

	echo "
	<div style='width:600px;'>
		<h2>Resources</h2>
		
		<form name='resources' action='".$_SERVER['PHP_SELF']."' method='post'>
			<table class='table table-striped'>
				<thead>
					<tr>
						<th>Delete</th>
						<th>Resource Name</th>
						<th>Type</th>
						<th>For Subject</th>
					</tr>
				</thead>

				<tbody>";
					//Cycle through Resources
					foreach ($videos as $v1)
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
					if(empty($videos) && empty($links))
					{
						echo "
						<tr>
							<td>
								-
							</td>
							<td>
								No Resources
							</td>
							<td>
								-
							</td>
							<td>
								-
							</td>
						</tr>";
					}
				
				echo "
				</tbody>
			</table>
			<div class='form-group'>
				<label class='col-sm-4 control-label'>Resource Name</label>
				<div class='col-sm-8'>
					<input type='text' class='form-control' name='newResourceName' />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-sm-4 control-label'>Type</label>
				<div class='col-sm-8'>
					<select class='form-control' name='newResourceType'>
						<option value='video'>Video</option>
						<option value='link'>Link</option>
					</select>					
				</div>
			</div>
			
			<div class='form-group'>
				<label class='col-sm-4 control-label'>Subject</label>
				<div class='col-sm-8'>					
					<select class='form-control' name='newResourceSubject>";
						foreach ($subjectData as $v1)
						{
							echo "<option value='".$v1['id']."'>".$v1['name']."</option>";
							/*
							if($subjectSelected == $v1['id'])
							{
								echo "<option selected='selected' value='".$v1['id']."'>".$v1['name']."</option>";
							}
							else
							{
								echo "<option value='".$v1['id']."'>".$v1['name']."</option>";
							}
							*/
						}
					echo "
					</select>
					
				</div>
			</div>
			
			<div class='form-group'>
				<label class='col-sm-4 control-label'>Resource Address</label>
				<div class='col-sm-8'>
					<input type='text' class='form-control' name='newResourceAddress' />
				</div>
			</div>
			<div class='form-group'>
				<div class='col-sm-offset-4 col-sm-8'>
					<button type='submit' class='btn btn-primary' name='Submit'>Submit</button>
				</div>
			</div>
		</form>			
	</div>";
	echo "</center>";
	
	include 'models/footer.php';

?>
