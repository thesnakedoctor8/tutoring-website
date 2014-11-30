<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	if(isUserLoggedIn())
	{
		$subscriptions = fetchAllSubscriptions($loggedInUser->user_id);
	}
	else
	{
		$subscriptions = NULL;
	}
	
	if(isset($_GET['subject']))
	{
		// TODO Error check for manually entering incorrect strings in URL
		$_SESSION['subjectSelected'] = $_GET['subject'];
	}
	else
	{
		if(!isset($_SESSION['subjectSelected']) || $_SESSION['subjectSelected'] == "")
		{
			$_SESSION['subjectSelected'] = reset($_SESSION["subjectList"])['name'];
		}
	}
	
	if(isset($_GET['unsubscribe']) && isUserLoggedIn())
	{
		error_log("here");
		error_log("var: ".$_GET['unsubscribe']);
		
		if(alreadySubscribed($loggedInUser->user_id, $_GET['unsubscribe']))
		{
			error_log("should print");
			deleteSubscription($loggedInUser->user_id, $_GET['unsubscribe']);
			header("Location: subscription.php?subject=".$_SESSION["subjectSelected"]);
		}
	}
	
	//Forms posted
	if(!empty($_POST))
	{
		foreach ($_SESSION['subjectList'] as $item)
		{
			if($item["name"] == $_SESSION["subjectSelected"])
			{
				$subject_id = $item["id"];
			}
		}
		
		if(!alreadySubscribed($loggedInUser->user_id, $subject_id))
		{
			addSubscription($loggedInUser->user_id, $subject_id);
			header("Location: account.php");
		}
		else
		{
			error_log("Already subscribed");
		}
	}

	require_once("models/header.php");
	
	echo "
	<div class='row'>
		<div class='col-md-3'>
			<p class='lead'>View Subjects</p>
			<div class='list-group'>";
				foreach ($_SESSION["subjectList"] as $item)
				{
					if($item["name"] == $_SESSION["subjectSelected"])
					{
						echo "<a href='subscription.php?subject=".$item['name']."' class='list-group-item active'>".$item['name']."</a>";
					}
					else
					{
						echo "<a href='subscription.php?subject=".$item['name']."' class='list-group-item'>".$item['name']."</a>";
					}
				}
				echo "
			</div>";
			
			if(isUserLoggedIn())
			{
				echo "
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class='lead'>Unsubscribe</p>
				<div class='list-group'>";
					if($subscriptions == null)
					{
						echo "<a href='' class='list-group-item'>No Subscriptions</a>";
					}
					else
					{
						$subscription = explode("-", $subscriptions);
						foreach ($subscription as $s)
						{
							echo "<a href='subscription.php?subject=".$_SESSION["subjectSelected"]."&unsubscribe=".fetchSubjectDetails($s)['id']."' class='list-group-item'>".fetchSubjectDetails($s)['name']."</a>";
						}
					}
				echo "
				</div>";
			}
			
		echo "
		</div>

		<div class='col-md-9 jumbotron'>";
			if(isset($_SESSION['subjectSelected']))
			{
				//echo $_SESSION['subjectSelected']." selected";
				
				foreach ($_SESSION['subjectList'] as $item)
				{
					if($item["name"] == $_SESSION["subjectSelected"])
					{
						echo "<h1>".$item["name"]."</h1>";
						echo "<h2 style='color:#4d90fe;'>$".$item["price"]."</h2>";
						echo "<p>".$item["description"]."</p>";
						echo "
						<form name='contentSubjects' action='".$_SERVER['PHP_SELF']."' method='post'>
							<button type='submit' class='btn btn-lg btn-primary' name='Submit'>Subscribe</button>
						</form>";
					}
				}				
			}
			else
			{
				echo "<p>Select a subject from the menu</p>";			
			}
		echo "
		</div>

	</div>";
	
	include 'models/footer.php';

?>
