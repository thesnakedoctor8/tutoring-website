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
		if(!empty($_POST['numberOfQuestions']))
		{
			$numOfQuestions = $_POST['numberOfQuestions'];
		}
	
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
			
		}
	*/
	}
	
	require_once("models/header.php");
	
	echo "<center>";

	echo "
	<h2>Create Quiz For: ".$subjectDetails['name']."</h2>
	<br>
	
	<div style='width:500px;'>
		<form name='createQuiz' class='form-horizontal' action='' method='post'>";
			if(empty($_POST))
			{
				echo "
				<div class='form-group'>
					<label class='col-sm-5 control-label'>Number of Questions</label>
					<div class='col-sm-7'>
						<select class='form-control' name='numberOfQuestions'>
							<option value='1'>1</option>
							<option value='2'>2</option>
							<option value='3'>3</option>
							<option value='4'>4</option>
							<option value='5'>5</option>
							<option value='6'>6</option>
							<option value='7'>7</option>
							<option value='8'>8</option>
							<option value='9'>9</option>
							<option value='10'>10</option>
						</select>
					</div>
				</div>
				<div class='form-group'>
					<div class='col-sm-offset-5 col-sm-7'>
						<button type='submit' class='btn btn-primary' name='Update'>Create Quiz</button>
					</div>
				</div>";
			}
			else
			{
				echo "				
				<br>
				<div class='form-group'>
					<label class='col-sm-3 control-label'>Quiz Title</label>
					<div class='col-sm-9'>
						<input type='text' class='form-control' name='quizName'>
					</div>
				</div>
				<br>
				<br>";
				
				for ($i = 1; $i <= $numOfQuestions; $i++)
				{
					echo "
					<div class='form-group'>
						<label class='col-sm-4 control-label'>Question ".$i."</label>
						<div class='col-sm-8'>
							<textarea class='form-control' name='question".$i."' rows='2' ></textarea>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-5 control-label'>Answer 1</label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' name='question".$i."answer1'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-5 control-label'>Answer 2</label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' name='question".$i."answer2'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-5 control-label'>Answer 3</label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' name='question".$i."answer3'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-5 control-label'>Answer 4</label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' name='question".$i."answer4'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Answer Key</label>
						<div class='col-sm-6'>
							<select class='form-control' name='question".$i."answerKey'>
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
								<option value='4'>4</option>
							</select>
						</div>
					</div>
					<br>";
				} 
				
				echo "
				<div class='form-group'>
					<div class='col-sm-offset-5 col-sm-7'>
						<button type='submit' class='btn btn-primary' name='Update'>Add Quiz</button>
					</div>
				</div>";
			}
		echo "
		</form>			
	</div>";
	
	echo "</center>";
	
	include 'models/footer.php';
?>
