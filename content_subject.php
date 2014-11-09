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

	$subjectDetails = fetchSubjectDetails($subjectId);
		
	//Forms posted
	if(!empty($_POST))
	{
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
					$errors[] = lang("SUBJECT_NAME_IN_USE", array($subject));
				}
				else if (minMaxRange(1, 50, $subject))
				{
					$errors[] = lang("SUBJECT_CHAR_LIMIT", array(1, 50));	
				}
				else
				{
					if (updateSubject($subjectId, $subject, $subjectDetails['price'], $subjectDetails['description']))
					{
						$successes[] = lang("SUBJECT_NAME_UPDATE", array($subject));
					}
					else
					{
						$errors[] = lang("SQL_ERROR");
					}
				}
			}
			
			//Update subject price
			if($subjectDetails['price'] != $_POST['price'])
			{
				$price = $_POST['price'];
				
				if ($price < 0)
				{
					$errors[] = lang("SUBJECT_PRICE_ERROR", array(0, 9999.99));	
				}
				else
				{
					if (updateSubject($subjectId, $subjectDetails['name'], $price, $subjectDetails['description']))
					{
						$successes[] = lang("SUBJECT_PRICE_UPDATE", array($price));
					}
					else
					{
						$errors[] = lang("SQL_ERROR");
					}
				}
			}
			
			//Update subject description
			if($subjectDetails['description'] != $_POST['description'])
			{
				$description = trim($_POST['description']);
				if (updateSubject($subjectId, $subjectDetails['name'], $subjectDetails['price'], $description))
				{
					$successes[] = lang("SUBJECT_DESCRIPTION_UPDATE");
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			
			if(!empty($_POST['deleteQuiz']))
			{
				$deletions = $_POST['deleteQuiz'];
				if ($deletion_count = deleteQuiz($deletions))
				{
					$successes[] = lang("QUIZ_DELETIONS_SUCCESSFUL", array($deletion_count));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
	}
	
	$subjectDetails = fetchSubjectDetails($subjectId);
	$quizzes = fetchAllQuizzes($subjectId);
	$videos = fetchAllResources($subjectId, "video");
	$links = fetchAllResources($subjectId, "link");
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);
	
	if(!empty($subjectDetails))
	{
		echo "
		<div style='width:700px;'>
			<h2>Subject: ".$subjectDetails['name']."</h2>
			
			<form name='contentSubjects' action='".$_SERVER['PHP_SELF']."?id=".$subjectId."' method='post'>
				<table class='table table-striped'>
					<thead>
						<tr>
							<th>Subject Information</th>
							<th>Value</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>
								<b>ID:</b>
							</td>
							<td>
								".$subjectDetails['id']."
							</td>
						</tr>
						<tr>
							<td>
								<b>Name:</b>
							</td>
							<td>
								<input type='text' class='form-control' name='name' value='".$subjectDetails['name']."' />
							</td>
						</tr>
						<tr>
							<td>
								<b>Price:</b>
							</td>
							<td>
								<input type='text' class='form-control' name='price' value='".$subjectDetails['price']."' />
							</td>
						</tr>
						<tr>
							<td>
								<b>Description:</b>
							</td>
							<td>
								<textarea class='form-control' name='description' rows='2' >".$subjectDetails['description']."</textarea>
							</td>
						</tr>					
						<tr>
							<td>
								<b>Delete:</b>
							</td>
							<td>
								<input type='checkbox' name='delete[".$subjectDetails['id']."]' id='delete[".$subjectDetails['id']."]' value='".$subjectDetails['id']."'>
							</td>
						</tr>

						<thead>
							<tr>
								<th>Quizzes (Delete)</th>
								<th>Quiz Names</th>
							</tr>
						</thead>";
						if(!empty($quizzes))
						{
							foreach ($quizzes as $v1)
							{
								echo "
								<tr>
									<td>
										".$v1['id']." <input type='checkbox' name='deleteQuiz[".$v1['id']."]' id='deleteQuiz[".$v1['id']."]' value='".$v1['id']."'>
									</td>
									<td>
										<a href='content_quiz.php?id=".$v1['id']."'>".$v1['name']."</a>
									</td>
								</tr>";
							}
						}
						else
						{
							echo "
							<tr>
								<td>
									No Quizzes
								</td>
								<td>
									No Quizzes
								</td>
							</tr>";
						}
						
						echo "
						<thead>
							<tr>
								<th>Videos (Delete)</th>
								<th>Video Names</th>
							</tr>
						</thead>";
						if(!empty($videos))
						{
							foreach ($videos as $v1)
							{
								echo "
								<tr>
									<td>
										".$v1['id']."
									</td>
									<td>
										<a href='content_resource.php?id=".$v1['id']."'>".$v1['name']."</a>
									</td>
								</tr>";
							}
						}
						else
						{
							echo "
							<tr>
								<td>
									No videos
								</td>
								<td>
									No videos
								</td>
							</tr>";
						}
						
						echo "
						<thead>
							<tr>
								<th>Links (Delete)</th>
								<th>Link Names</th>
							</tr>
						</thead>";
						if(!empty($links))
						{
							foreach ($links as $v1)
							{							
								echo "
								<tr>
									<td>
										".$v1['id']."
									</td>
									<td>
										<a href='content_resource.php?id=".$v1['id']."'>".$v1['name']."</a>
									</td>
								</tr>";
							}
						}
						else
						{
							echo "
							<tr>
								<td>
									No links
								</td>
								<td>
									No links
								</td>
							</tr>";
						}
						
					echo "
					</tbody>
				</table>
				<input type='submit' value='Update' class='btn btn-primary' style='max-width:200px;' />
				
				<br>
				<br>
				<div class='form-group'>
					<h4><a href='create_quiz.php?id=".$subjectId."'>New Quiz</a> - <a href='add_videos.php?id=".$subjectId."'>Add Videos</a> - <a href='add_links.php?id=".$subjectId."'>Add Links</a></h4>
				</div>
			</form>
		</div>";
	}
	else
	{
		echo "				
		<div class='form-group'>
				<a href='content_subjects.php'>Return</a>
		</div>";
	}
	
	echo "</center>";
	
	include 'models/footer.php';
?>
