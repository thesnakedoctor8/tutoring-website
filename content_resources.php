<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}

	$getSubjectId = $_GET['subject_id'];
	$getType = $_GET['type'];
	
	//Forms posted
	if(!empty($_POST))
	{
		if(!empty($_POST['deleteResource']))
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
		
		//Create new Resource
		if(!empty($_POST['newResourceName']) && !empty($_POST['newResourceAddress']) && !empty($_POST['newResourceSubject']))
		{
			$name = trim($_POST['newResourceName']);
			$type = trim($_POST['newResourceType']);
			$subject_id = $_POST['newResourceSubject'];
			$address = trim($_POST['newResourceAddress']);
			
			if(!subjectIdExists($subject_id))
			{
				$errors[] = "Invalid subject entered";
			}
			else
			{
				if (addResource($subject_id, $type, $name, $address))
				{
					$successes[] = lang("RESOURCE_CREATION_SUCCESSFUL", array($name));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
	}
	
	$subjectData = fetchAllSubjects();
	$videos = fetchAllResourcesType("video");
	$links = fetchAllResourcesType("link");
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);

	echo "
	<div style='width:600px;'>
		<h2>Resources</h2>
		
		<form class='form-horizontal' name='resources' action='".$_SERVER['PHP_SELF']."' method='post'>
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
								<input type='checkbox' name='deleteResource[".$v1['id']."]' id='deleteResource[".$v1['id']."]' value='".$v1['id']."'> ".$v1['id']."
							</td>
							<td>
								<a href='content_resource.php?id=".$v1['id']."'>".$v1['name']."</a>
							</td>
							<td>
								".$v1['type']."
							</td>
							<td>
								<a href='content_subject.php?id=".$v1['subject_id']."'>".fetchSubjectDetails($v1['subject_id'])['name']."</a>
							</td>
						</tr>";
					}
					foreach ($links as $v1)
					{
						echo "
						<tr>
							<td>
								<input type='checkbox' name='deleteQuiz[".$v1['id']."]' id='deleteQuiz[".$v1['id']."]' value='".$v1['id']."'> ".$v1['id']."
							</td>
							<td>
								<a href='content_resource.php?id=".$v1['id']."'>".$v1['name']."</a>
							</td>
							<td>
								".$v1['type']."
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
				<label class='col-sm-5 control-label'>Resource Name</label>
				<div class='col-sm-7'>
					<input type='text' class='form-control' name='newResourceName' />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-sm-5 control-label'>Type</label>
				<div class='col-sm-7'>
					<select class='form-control' name='newResourceType'>";
						if($getType == "video")
						{
							echo "
							<option value='video' selected>video</option>
							<option value='link'>link</option>";
						}
						else
						{
							echo "
							<option value='video'>video</option>
							<option value='link' selected>link</option>";
						}
					echo "
					</select>					
				</div>
			</div>
			<div class='form-group'>
				<label class='col-sm-5 control-label'>Subject ID</label>
				<div class='col-sm-7'>
					<input type='text' class='form-control' name='newResourceSubject' value='".$getSubjectId."'/>
				</div>
			</div>";
			
			/*
			echo "
			<div class='form-group'>
				<label class='col-sm-5 control-label'>Subject</label>
				<div class='col-sm-7'>					
					<select class='form-control' name='newResourceSubject>";
						for ($i = sizeof($subjectData); $i >= 0; $i--)
						{
							echo "<option value='".$subjectData[$i]['id']."'>".$subjectData[$i]['name']."</option>";
						}
						
						foreach ($subjectData as $v1)
						{
							//echo "<option value='".$v1['id']."'>".$v1['name']."</option>";
							
							//if($subjectSelected == $v1['id'])
							//{
							//	echo "<option selected='selected' value='".$v1['id']."'>".$v1['name']."</option>";
							//}
							//else
							//{
							//	echo "<option value='".$v1['id']."'>".$v1['name']."</option>";
							//}
						}
					echo "
					</select>
				</div>
			</div>";
			*/
			
			echo "
			<div class='form-group'>
				<label class='col-sm-5 control-label'>Resource Address</label>
				<div class='col-sm-7'>
					<input type='text' class='form-control' name='newResourceAddress' />
				</div>
			</div>
			<div class='form-group'>
				<div class='col-sm-offset-5 col-sm-7'>
					<button type='submit' class='btn btn-primary' name='Submit'>Submit</button>
				</div>
			</div>
		</form>			
	</div>";
	echo "</center>";
	
	include 'models/footer.php';

?>
