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
	$answers = explode("<>", $answersArray);
	
	echo sizeof($questions)."<br><br>";
	foreach ($questions as $question)
	{
		$questionPieces = explode("^^", $question);
		foreach ($questionPieces as $questionPiece)
		{
			echo $questionPiece."<br>";
		}
	}
	
	foreach ($answers as $answer)
	{
		echo $answer."<br>";
	}
	
	echo "</center>";
	
	include 'models/footer.php';
?>
