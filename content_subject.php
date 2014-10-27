<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	$subjectId = $_GET['id'];

	//Check if selected subject level exists
	if(!subjectIdExists($subjectId))
	{
		header("Location: content_subjects.php");
		die();
	}

	$subjectDetails = fetchSubjectDetails($subjectId); //Fetch information specific to subject level

	//Forms posted
	if(!empty($_POST))
	{
	/*
		//Delete selected subject level
		if(!empty($_POST['delete']))
		{
			$deletions = $_POST['delete'];
			if ($deletion_count = deleteSubject($deletions))
			{
				$successes[] = lang("SUBJECT_DELETIONS_SUCCESSFUL", array($deletion_count));
			}
			else
			{
				$errors[] = lang("SQL_ERROR");	
			}
		}
		else
		{
			//Update subject level name
			if($subjectDetails['name'] != $_POST['name'])
			{
				$subject = trim($_POST['name']);
				
				//Validate new name
				if (subjectNameExists($subject))
				{
					$errors[] = lang("ACCOUNT_subjectNAME_IN_USE", array($subject));
				}
				else if (minMaxRange(1, 50, $subject))
				{
					$errors[] = lang("ACCOUNT_subject_CHAR_LIMIT", array(1, 50));	
				}
				else
				{
					if (updateSubjectName($subjectId, $subject, $subjectDetails->price, $subjectDetails->description)
					{
						$successes[] = lang("SUBJECT_NAME_UPDATE", array($subject));
					}
					else
					{
						$errors[] = lang("SQL_ERROR");
					}
				}
			}
			
			//Remove access to pages
			if(!empty($_POST['removesubject']))
			{
				$remove = $_POST['removesubject'];
				if ($deletion_count = removesubject($subjectId, $remove))
				{
					$successes[] = lang("subject_REMOVE_USERS", array($deletion_count));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			
			//Add access to pages
			if(!empty($_POST['addsubject']))
			{
				$add = $_POST['addsubject'];
				if ($addition_count = addsubject($subjectId, $add))
				{
					$successes[] = lang("subject_ADD_USERS", array($addition_count));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			
			//Remove access to pages
			if(!empty($_POST['removePage']))
			{
				$remove = $_POST['removePage'];
				if ($deletion_count = removePage($remove, $subjectId))
				{
					$successes[] = lang("subject_REMOVE_PAGES", array($deletion_count));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			
			//Add access to pages
			if(!empty($_POST['addPage']))
			{
				$add = $_POST['addPage'];
				if ($addition_count = addPage($add, $subjectId))
				{
					$successes[] = lang("subject_ADD_PAGES", array($addition_count));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			$subjectDetails = fetchsubjectDetails($subjectId);
		}
	*/
	}

	//$quizzes = fetchQuizzes($subjectId); //Retrieve list of accessible pages
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);

	echo "
	<div style='width:700px;'>
		<h2>Subject: ".$subjectDetails['name']."</h2>
		
		<form name='contentSubjects' action='".$_SERVER['PHP_SELF']."?id=".$subjectId."' method='post'>
			<table class='table table-striped'>
				<thead>
					<tr>
						<th>Subject Information</th>
						<th>Value</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>
							<b>ID:</b>
						</td>
						<td>
							".$subjectDetails['id']."
						</td>
					</tr>
					<tr>
						<td>
							<b>Name:</b>
						</td>
						<td>
							<input type='text' class='form-control' name='name' value='".$subjectDetails['name']."' />
						</td>
					</tr>
					<tr>
						<td>
							<b>Price:</b>
						</td>
						<td>
							<input type='text' class='form-control' name='name' value='".$subjectDetails['price']."' />
						</td>
					</tr>
					<tr>
						<td>
							<b>Description: (change to textarea)</b>
						</td>
						<td>
							<input type='text' class='form-control' name='name' value='".$subjectDetails['description']."' />
						</td>
					</tr>					
					<tr>
						<td>
							<b>Delete:</b>
						</td>
						<td>
							<input type='checkbox' name='delete[".$subjectDetails['id']."]' id='delete[".$subjectDetails['id']."]' value='".$subjectDetails['id']."'>
						</td>
					</tr>

					<thead>
						<tr>
							<th>Quizzes</th>
							<th>Quiz Names</th>
						</tr>
					</thead>	

					<tr>
						<td>
							<b>TODO</b>
						</td>
						<td>
							<b>TODO</b>
						</td>
					</tr>
					
				</tbody>
			</table>
			<input type='submit' value='Update' class='btn btn-primary' style='max-width:200px;' />
		</form>
	</div>";
	
	echo "</center>";
	
	include 'models/footer.php';
?>
