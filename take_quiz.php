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
		header("Location: account.php");
		die();
	}
	
	$quizDetails = fetchQuiz($quizId);
	$name = $quizDetails['name'];
	$subjectId = $quizDetails['subject_id'];
	$questionsArray = $quizDetails['questions'];
	$answersArray = $quizDetails['answers'];
	$questions = explode("<>", $questionsArray);
	$answers = explode("<>", $answersArray);
	
	//Forms posted
	if(!empty($_POST))
	{
		// answers
		// grab posts and check against answer key
	}
	
	require_once("models/header.php");
	
	echo "<center>";
	
	echo "
	<h2>Create Quiz For: ".$subjectDetails['name']."</h2>
	<br>
	
	<div style='width:500px;'>
		<form name='takeQuiz' class='form-horizontal' action='' method='post'>";
			for ($i = 0; $i < sizeof($questions); $i++)
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
					<button type='submit' class='btn btn-primary' name='Submit'>Submit Quiz</button>
				</div>
			</div>";
		echo "
		</form>			
	</div>";
	
	echo "</center>";
	
	include 'models/footer.php';
?>
