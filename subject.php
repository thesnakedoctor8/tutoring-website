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
		header("Location: account.php");
		die();
	}
	
	$subscriptions = fetchAllSubscriptions($loggedInUser->user_id);
	if($subscriptions == null)
	{
		header("Location: subscription.php");
		die();
	}
	else
	{
		$checkSubscribed = false;
		$subscription = explode("-", $subscriptions);
		foreach ($subscription as $s)
		{
			if($s == $subjectId)
			{
				$checkSubscribed = true;
			}
		}
		if(!$checkSubscribed)
		{
			header("Location: subscription.php");
			die();
		}
	}
	
	$quizzes = fetchAllQuizzes($subjectId);
	$videos = fetchAllResources($subjectId, "video");
	$links = fetchAllResources($subjectId, "link");
	
	if(isset($_GET['source']))
	{
		// TODO Error check for manually entering incorrect strings in URL
		$_SESSION['resourceSelected'] = $_GET['source'];
	}
	else
	{
		if(!isset($_SESSION['resourceSelected']) || $_SESSION['resourceSelected'] == "")
		{
			$_SESSION['resourceSelected'] = reset($_SESSION["resourcesList"]);
		}
	}
	
	//Forms posted
	if(!empty($_POST))
	{
		// might not need this
	}
	
	require_once("models/header.php");
	
	echo "
	<div class='row'>
		<div class='col-md-3'>
			<p class='lead'>Resources</p>
			<div class='list-group'>";
				foreach ($_SESSION["resourcesList"] as $item)
				{
					if($item == $_SESSION["resourceSelected"])
					{
						echo "<a href='subject.php?id=".$subjectId."&source=".$item."' class='list-group-item active'>".$item."</a>";
					}
					else
					{
						echo "<a href='subject.php?id=".$subjectId."&source=".$item."' class='list-group-item'>".$item."</a>";
					}
				}
				echo "
			</div>
		</div>

		<div class='col-md-9 jumbotron'>
			<center><h2>".fetchSubjectDetails($subjectId)['name']." - ".$_SESSION['resourceSelected']."</h2></center><br>";
			if($_SESSION["resourceSelected"] == "Quizzes")
			{
				if(!empty($quizzes))
				{
					foreach ($quizzes as $v1)
					{
						echo "
						<h4><a href='take_quiz.php?id=".$v1['id']."&subject_id=".$subjectId."'>".$v1['name']."</a></h4>";
					}
				}
				else
				{
					echo "<h4>No quizzes available for this subject yet</h4>";
				}
			}
			else if($_SESSION["resourceSelected"] == "Videos")
			{
				if(!empty($videos))
				{
					foreach ($videos as $v1)
					{
						//echo "<h4><a href='".$v1['address']."'>".$v1['name']."</a></h4>";
						echo "
						<div class='embed-responsive embed-responsive-16by9'>
						  <iframe class='embed-responsive-item' src='//".$v1['address']."'></iframe>
						</div>
						<br>";
					}
				}
				else
				{
					echo "<h4>No videos available for this subject yet</h4>";
				}
			}
			else if($_SESSION["resourceSelected"] == "Links")
			{
				if(!empty($links))
				{
					foreach ($links as $v1)
					{
						echo "<h4><a href='".$v1['address']."'>".$v1['name']."</a></h4>";
					}
				}
				else
				{
					echo "<h4>No links available for this subject yet</h4>";
				}
			}
			else
			{
				echo "<h4>Select a subject</h4>";
			}
		echo "
		</div>

	</div>";
	
	include 'models/footer.php';
?>
