<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	if(isset($_GET['subject']))
	{
		// Error check for manually entering incorrect strings in URL
		$_SESSION['subjectSelected'] = $_GET['subject'];
	}
	else
	{
		if(!isset($_SESSION['subjectSelected']) || $_SESSION['subjectSelected'] == "")
		{
			$_SESSION['subjectSelected'] = reset($_SESSION["subjectList"])['name'];
		}
	}
	
	//isUserLoggedIn()
	
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
			</div>
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
