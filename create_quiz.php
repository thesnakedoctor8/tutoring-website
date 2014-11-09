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
			$_SESSION["numOfQuestions"] = $_POST['numberOfQuestions'];
		}
		else
		{
			$formsFilledOut = true;
			if(!empty($_POST['quizName']))
			{
				for ($i = 1; $i <= $_SESSION["numOfQuestions"]; $i++)
				{
					if(empty($_POST["question".$i]) || empty($_POST["question".$i."answer1"])  || empty($_POST["question".$i."answer2"]) 
					 || empty($_POST["question".$i."answer3"]) || empty($_POST["question".$i."answer4"]) || empty($_POST["question".$i."answerKey"]))
					{
						$formsFilledOut = false;
						$errors[] = lang("QUIZ_FORMAT_ERROR")." - ".$i;
						break;
					}
				}
			}
			else
			{
				$formsFilledOut = false;
			}
			
			if($formsFilledOut)
			{		
				$name = $_POST['quizName'];
				$questions = "";
				$answers = "";
				for ($i = 1; $i <= $_SESSION["numOfQuestions"]; $i++)
				{
					$questions .= $_POST["question".$i]."^^".$_POST["question".$i."answer1"]."^^".$_POST["question".$i."answer2"]."^^".$_POST["question".$i."answer3"]."^^".$_POST["question".$i."answer4"]."<>";
					$answers .= $_POST["question".$i."answerKey"]."<>";
				}
				
				if (addQuiz($name, $subjectId, $questions, $answers))
				{
					$successes[] = lang("QUIZ_ADDED");
				}
				else
				{
					$errors[] = lang("QUIZ_ERROR");
				}
			}
		}
	}
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);
	
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
				if(empty($_POST['quizName']))
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
					
					for ($i = 1; $i <= $_SESSION["numOfQuestions"]; $i++)
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
							<button type='submit' class='btn btn-primary' name='Submit'>Add Quiz</button>
						</div>
					</div>";
				}
				else
				{
					echo "				
					<div class='form-group'>
							<a href='content_subjects.php'>Return</a>
					</div>";
				}
			}
		echo "
		</form>			
	</div>";
	
	echo "</center>";
	
	include 'models/footer.php';
?>
