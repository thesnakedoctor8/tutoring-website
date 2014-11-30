<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
		
	if(empty($_GET['id']))
	{
		header("Location: subject.php?id=".$_SESSION['returnSubjectId']);
		die();
	}
	
	$quizId = $_GET['id'];
	//Check if selected subject level exists
	if(!quizIdExists($quizId))
	{
		header("Location: subject.php?id=".$_SESSION['returnSubjectId']);
		die();
	}
	
	$quizDetails = fetchQuiz($quizId);
	$name = $quizDetails['name'];
	$subjectId = $quizDetails['subject_id'];
	$_SESSION['returnSubjectId'] = $subjectId;
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
		$correct = 0;
		for ($i = 0; $i < sizeof($questions)-1; $i++)
		{
			$userAnswers[$i] = $_POST["question".$i."answer"];
			if($userAnswers[$i] == $answers[$i])
			{
				$correct++;
			}
		}
	}
	
	require_once("models/header.php");
	
	echo "<center>";
	
	echo "
	<h2>".$name."</h2>
	<br>";
	
	if(acheivementExists($quizId, $loggedInUser->user_id))
	{
		$acheivement = fetchSingleAchievement($quizId, $loggedInUser->user_id);
		echo "<h3>You scored ".$acheivement['score']."% on this quiz</h3>";
		echo "
			<br>
			<a href='subject.php?id=$subjectId' class='btn btn-primary btn-lg' role='button'>Back</a>";
	}
	else
	{
		if(empty($_POST))
		{	
			echo "		
			<div style='width:500px;'>
				<form name='takeQuiz' class='form-horizontal' action='' method='post'>
					<div class='jumbotron'>";
						for ($i = 0; $i < sizeof($questions)-1; $i++)
						{
							echo "
							<div class='form-group'>
								<h4>".$questionPieces[$i][0]."</h4>
							</div>
							<div class='form-group'>
								<h5>1)  ".$questionPieces[$i][1]."</h5>
							</div>
							<div class='form-group'>
								<h5>2)  ".$questionPieces[$i][2]."</h5>
							</div>
							<div class='form-group'>
								<h5>3)  ".$questionPieces[$i][3]."</h5>
							</div>
							<div class='form-group'>
								<h5>4)  ".$questionPieces[$i][4]."</h5>
							</div>
							<div class='form-group'>
								<label class='col-sm-5 control-label'>Answer</label>
								<div class='col-sm-3'>
									<select class='form-control' name='question".$i."answer'>
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
							<button type='submit' class='btn btn-primary' name='Submit'>Submit Quiz</button>
						</div>
					</div>";
				echo "
				</form>			
			</div>";
		}
		else
		{
			echo "		
			<div style='width:500px;'>
				<form name='showAnswers' class='form-horizontal' action='' method='link'>
					<div class='jumbotron'>";
						for ($i = 0; $i < sizeof($questions)-1; $i++)
						{
							echo "
							<div class='form-group'>
								<h4>".$questionPieces[$i][0]."</h4>
							</div>
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Correct Answer</label>
								<div class='col-sm-4'>
									<h5>".$answers[$i]."</h5>
								</div>
							</div>
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Your Answer</label>
								<div class='col-sm-4'>";
									if($answers[$i] == $userAnswers[$i])
									{
										echo "<h5><span class='label label-success'>".$userAnswers[$i]."</span></h5>";
									}
									else
									{
										echo "<h5><span class='label label-danger'>".$userAnswers[$i]."</span></h5>";
									}
								echo "	
								</div>
							</div>
							<br>";
						}
						$scorePercent = 100*number_format($correct/sizeof($userAnswers), 2, '.', '');
						echo 
						"<h3>Score: ".$correct."/".sizeof($userAnswers)." (".$scorePercent."%)</h3>
						<br>
						<div class='form-group'>
							<button type='submit' class='btn btn-primary'>Done</button>
						</div>
					</div>
				</form>			
			</div>";
			
			addAchievement($subjectId, $quizId, $loggedInUser->user_id, $name, $scorePercent);
		}
	}
	echo "</center>";
	
	include 'models/footer.php';
?>
