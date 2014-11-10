<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	$resourceId = $_GET['id'];

	if(!resourceIdExists($resourceId))
	{
		header("Location: content_resources.php");
		die();
	}

	$resourceDetails = fetchResource($resourceId);
		
	//Forms posted
	if(!empty($_POST))
	{
		//Delete selected subject level
		if(!empty($_POST['delete']))
		{
			$deletions = $_POST['delete'];
			if ($deletion_count = deleteResource($deletions))
			{
				$successes[] = lang("RESOURCE_DELETIONS_SUCCESSFUL", array($deletion_count));
			}
			else
			{
				$errors[] = lang("SQL_ERROR");
			}
		}
		else
		{
			/* TODO
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
			*/
		}
	}
	
	$resourceDetails = fetchResource($resourceId);
	
	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);
	
	if(!empty($resourceDetails))
	{
		echo "
		<div style='width:700px;'>
			<h2>Quiz: ".$resourceDetails['name']."</h2>
			
			<form name='resources' action='".$_SERVER['PHP_SELF']."?id=".$resourceId."' method='post'>
				<table class='table table-striped'>
					<thead>
						<tr>
							<th>Resource Information</th>
							<th>Value</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>
								<b>Quiz ID:</b>
							</td>
							<td>
								".$resourceDetails['id']."
							</td>
						</tr>
						<tr>
							<td>
								<b>Subject ID:</b>
							</td>
							<td>
								".$resourceDetails['subject_id']." (".fetchSubjectDetails($resourceDetails['subject_id'])['name'].")
							</td>
						</tr>
						<tr>
							<td>
								<b>Type:</b>
							</td>
							<td>
								<select class='form-control' name='newResourceType'>";
									if($resourceDetails['type'] == "video")
									{
										echo "
										<option value='video' selected>video</option>
										<option value='link'>link</option>";
									}
									else
									{
										echo "
										<option value='video'>video</option>
										<option value='link' selected>link</option>";
									}
								echo "
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<b>Name:</b>
							</td>
							<td>
								<input type='text' class='form-control' name='name' value='".$resourceDetails['name']."' />
							</td>
						</tr>
						<tr>
							<td>
								<b>Address:</b>
							</td>
							<td>
								<input type='text' class='form-control' name='name' value='".$resourceDetails['address']."' />
							</td>
						</tr>
						<tr>
							<td>
								<b>Delete:</b>
							</td>
							<td>
								<input type='checkbox' name='delete[".$resourceDetails['id']."]' id='delete[".$resourceDetails['id']."]' value='".$resourceDetails['id']."'>
							</td>
						</tr>
					</tbody>
				</table>
				<input type='submit' value='Update' class='btn btn-primary' style='max-width:200px;' />
			</form>
		</div>";
	}
	else
	{
		echo "				
		<div class='form-group'>
				<a href='content_resources.php'>Return</a>
		</div>";
	}
	
	echo "</center>";
	
	include 'models/footer.php';
?>
