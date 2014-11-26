<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	$quizId = $_GET['id'];
	//Check if selected subject level exists
	if(!quizIdExists($quizId))
	{
		header("Location: content_quizzes.php");
		die();
	}

	$quizDetails = fetchQuiz($quizId);
	$name = $quizDetails['name'];
	$subjectId = $quizDetails['subject_id'];
	$questionsArray = $quizDetails['questions'];
	$answersArray = $quizDetails['answers'];
	$questions = explode("<>", $questionsArray);
	foreach ($questions as $question)
	{
		$questionPieces[] = explode("^^", $question);
	}
	$answers = explode("<>", $answersArray);
	
	//Forms posted
	if(!empty($_POST))
	{
		$formsFilledOut = true;
		for ($i = 0; $i < sizeof($questions)-1; $i++)
		{
			if(empty($_POST['quizName']) || empty($_POST["question".$i]) || empty($_POST["question".$i."answer1"])  ||
				empty($_POST["question".$i."answer2"]) || empty($_POST["question".$i."answer3"]) ||
				empty($_POST["question".$i."answer4"]) || empty($_POST["question".$i."answerKey"]))
			{
				$formsFilledOut = false;
				$errors[] = lang("QUIZ_FORMAT_ERROR")." - ".$i;
				break;
			}
		}
		
		if($formsFilledOut)
		{		
			$name = $_POST['quizName'];
			$formQuestions = "";
			$formAnswers = "";
			
			for ($i = 0; $i < sizeof($questions)-1; $i++)
			{
				$formQuestions .= $_POST["question".$i]."^^".$_POST["question".$i."answer1"]."^^".$_POST["question".$i."answer2"]."^^".$_POST["question".$i."answer3"]."^^".$_POST["question".$i."answer4"]."<>";
				$formAnswers .= $_POST["question".$i."answerKey"]."<>";
			}
			
			if (updateQuiz($quizId, $name, $subjectId, $formQuestions, $formAnswers))
			{
				$successes[] = lang("QUIZ_UPDATED");
			}
			else
			{
				$errors[] = lang("QUIZ_UPDATED_ERROR");
			}
		}
	}
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);
	
	if(!empty($successes))
	{
		echo "
		<h2>Updated Quiz ID: ".$quizDetails['id']."</h2>";
	}
	else
	{
		echo "
		<h2>Edit Quiz ID: ".$quizDetails['id']."</h2>
		<br>
		
		<div style='width:500px;'>
			<form name='editQuiz' class='form-horizontal' action='' method='post'>			
				<br>
				<div class='form-group'>
					<label class='col-sm-3 control-label'>Quiz Title</label>
					<div class='col-sm-9'>
						<input type='text' class='form-control' value='".$quizDetails['name']."' name='quizName'>
					</div>
				</div>
				<br>
				<br>";
				
				for ($i = 0; $i < sizeof($questions)-1; $i++)
				{
					$questionId = $i + 1;
					echo "
					<div class='form-group'>
						<label class='col-sm-4 control-label'>Question ".$questionId."</label>
						<div class='col-sm-8'>
							<textarea class='form-control' name='question".$i."' rows='2' >".$questionPieces[$i][0]."</textarea>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-5 control-label'>Answer 1</label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' value='".$questionPieces[$i][1]."' name='question".$i."answer1'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-5 control-label'>Answer 2</label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' value='".$questionPieces[$i][2]."' name='question".$i."answer2'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-5 control-label'>Answer 3</label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' value='".$questionPieces[$i][3]."' name='question".$i."answer3'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-5 control-label'>Answer 4</label>
						<div class='col-sm-7'>
							<input type='text' class='form-control' value='".$questionPieces[$i][4]."' name='question".$i."answer4'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Answer Key</label>
						<div class='col-sm-6'>
							<select class='form-control' name='question".$i."answerKey'>";
								for ($j = 1; $j <= 4; $j++)
								{
									if($answers[$i] == $j)
									{
										echo "<option selected='selected' value='".$j."'>".$j."</option>";
									}
									else
									{
										echo "<option value='".$j."'>".$j."</option>";
									}
								}
							echo "
							</select>
						</div>
					</div>
					<br>";
				} 
				
				echo "
				<div class='form-group'>
					<div class='col-sm-offset-3 col-sm-9'>
						<button type='submit' class='btn btn-primary' name='Submit'>Update Quiz</button>
					</div>
				</div>
			</form>			
		</div>";
	}
	
	echo "</center>";
	
	include 'models/footer.php';
?>
