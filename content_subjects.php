<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	//Forms posted
	if(!empty($_POST))
	{
		//Delete permission levels
		if(!empty($_POST['delete']))
		{
			$deletions = $_POST['delete'];
			if ($deletion_count = deleteSubject($deletions))
			{
				$successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
			}
		}
		
		//Create new permission level
		if(!empty($_POST['newSubjectName']) && !empty($_POST['newSubjectPrice']) && !empty($_POST['newSubjectDescription']))
		{
			$subject = trim($_POST['newSubjectName']);
			$price = (double)$_POST['newSubjectPrice'];
			$description = trim($_POST['newSubjectDescription']);
			
			//Validate request
			if (subjectNameExists($subject))
			{
				$errors[] = lang("SUBJECT_NAME_IN_USE", array($subject));
			}
			else if (minMaxRange(1, 50, $subject))
			{
				$errors[] = lang("SUBJECT_CHAR_LIMIT", array(1, 50));	
			}
			// TODO ADD IN CHECK FOR PRICE > 0
			else
			{
				if (createSubject($subject, $price, $description))
				{
					$successes[] = lang("SUBJECT_CREATION_SUCCESSFUL", array($subject));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
	}

	$subjectData = fetchAllSubjects(); //Retrieve list of all subjects
	
	require_once("models/header.php");

	echo "<center>";

	echo resultBlock($errors, $successes);

	echo "
	<div style='width:400px;'>
		<h2>Subjects</h2>
		
		<form class='form-horizontal' name='contentSubjects' action='".$_SERVER['PHP_SELF']."' method='post'>
			<table class='table table-striped'>
				<thead>
					<tr>
						<th>Remove</th>
						<th>Subject Name</th>
					</tr>
				</thead>

				<tbody>";
					//List each permission level
					foreach ($subjectData as $v1)
					{
						echo "
						<tr>
							<td>
								<input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'>
							</td>
							<td>
								<a href='content_subject.php?id=".$v1['id']."'>".$v1['name']."</a>
							</td>
						</tr>";
					}

				echo "
				</tbody>
			</table>
			
			<div class='form-group'>
				<label class='col-sm-4 control-label'>Subject Name</label>
				<div class='col-sm-8'>
					<input type='text' class='form-control' name='newSubjectName' />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-sm-4 control-label'>Price</label>
				<div class='col-sm-8'>
					<input type='number' step='.01' class='form-control' name='newSubjectPrice' />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-sm-4 control-label'>Description</label>
				<div class='col-sm-8'>
					<textarea class='form-control' name='newSubjectDescription' rows='3' ></textarea>
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
