<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	

	$subjectData = fetchAllSubjects(); //Retrieve list of all subjects
	
	require_once("models/header.php");

	echo "<center>";

	echo "test1<br>";
	
	$quizDetails = fetchQuiz(2);
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
	
	$correct = 0;
	$temmp = array(4, 5, 6);
	$answers = array(4, 9, 6);
	for ($i = 0; $i < 3; $i++)
	{
		$userAnswers[$i] = $temmp[$i];
		if($userAnswers[$i] == $answers[$i])
		{
			$correct++;
		}
	}
	echo "correct: ".$correct;
	
	echo "</center>";
	
	include 'models/footer.php';
?>
