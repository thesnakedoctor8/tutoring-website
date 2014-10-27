<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	$userId = $_GET['id'];

	//Check if selected user exists
	if(!userIdExists($userId))
	{
		header("Location: admin_users.php");
		die();
	}

	$userdetails = fetchUserDetails(NULL, NULL, $userId); //Fetch user details

	//Forms posted
	if(!empty($_POST))
	{	
		//Delete selected account
		if(!empty($_POST['delete']))
		{
			$deletions = $_POST['delete'];
			if ($deletion_count = deleteUsers($deletions))
			{
				$successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
			}
			else
			{
				$errors[] = lang("SQL_ERROR");
			}
		}
		else
		{
			//Update display name
			if ($userdetails['display_name'] != $_POST['display'])
			{
				$displayname = trim($_POST['display']);
				
				//Validate display name
				if(displayNameExists($displayname))
				{
					$errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
				}
				else if(minMaxRange(5,25,$displayname))
				{
					$errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
				}
				else if(!ctype_alnum($displayname))
				{
					$errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
				}
				else
				{
					if (updateDisplayName($userId, $displayname))
					{
						$successes[] = lang("ACCOUNT_DISPLAYNAME_UPDATED", array($displayname));
					}
					else
					{
						$errors[] = lang("SQL_ERROR");
					}
				}
				
			}
			else
			{
				$displayname = $userdetails['display_name'];
			}
			
			//Activate account
			if(isset($_POST['activate']) && $_POST['activate'] == "activate")
			{
				if (setUserActive($userdetails['activation_token']))
				{
					$successes[] = lang("ACCOUNT_MANUALLY_ACTIVATED", array($displayname));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			
			//Update email
			if ($userdetails['email'] != $_POST['email'])
			{
				$email = trim($_POST["email"]);
				
				//Validate email
				if(!isValidEmail($email))
				{
					$errors[] = lang("ACCOUNT_INVALID_EMAIL");
				}
				elseif(emailExists($email))
				{
					$errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
				}
				else
				{
					if (updateEmail($userId, $email))
					{
						$successes[] = lang("ACCOUNT_EMAIL_UPDATED");
					}
					else
					{
						$errors[] = lang("SQL_ERROR");
					}
				}
			}
			
			//Update title
			if ($userdetails['title'] != $_POST['title'])
			{
				$title = trim($_POST['title']);
				
				//Validate title
				if(minMaxRange(1,50,$title))
				{
					$errors[] = lang("ACCOUNT_TITLE_CHAR_LIMIT",array(1,50));
				}
				else
				{
					if (updateTitle($userId, $title))
					{
						$successes[] = lang("ACCOUNT_TITLE_UPDATED", array ($displayname, $title));
					}
					else
					{
						$errors[] = lang("SQL_ERROR");
					}
				}
			}
			
			//Remove permission level
			if(!empty($_POST['removePermission']))
			{
				$remove = $_POST['removePermission'];
				if ($deletion_count = removePermission($remove, $userId))
				{
					$successes[] = lang("ACCOUNT_PERMISSION_REMOVED", array ($deletion_count));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			
			//Add permission level
			if(!empty($_POST['addPermission']))
			{
				$add = $_POST['addPermission'];
				if ($addition_count = addPermission($add, $userId))
				{
					$successes[] = lang("ACCOUNT_PERMISSION_ADDED", array ($addition_count));
				}
				else
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			
			// Remove subject from Subscription
			if(!empty($_POST['removeSubject']))
			{
				$remove = $_POST['removeSubject'];
				foreach($remove as $r)
				{
					deleteSubscription($userId, $r);
				}
			}
			
			// Add subject to Subscription
			if(!empty($_POST['addSubject']))
			{
				$add = $_POST['addSubject'];
				foreach($add as $a)
				{
					addSubscription($userId, $a);
				}
			}
			
			$userdetails = fetchUserDetails(NULL, NULL, $userId);
		}
	}

	$userPermission = fetchUserPermissions($userId);
	$permissionData = fetchAllPermissions();

	require_once("models/header.php");
	
	echo "<center>";

	echo resultBlock($errors, $successes);

	echo "
	<div style='width:650px;'>
		<h2>Edit User: ".$userdetails['user_name']."</h2>
		
		<form name='adminUser' action='".$_SERVER['PHP_SELF']."?id=".$userId."' method='post'>
			<table class='table table-striped'>
				<thead>
					<tr>
						<th>User Information</th>
						<th>Value</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>
							<b>ID:</b>
						</td>
						<td>
							".$userdetails['id']."
						</td>
					</tr>
					<tr>
						<td>
							<b>Username:</b>
						</td>
						<td>
							".$userdetails['user_name']."
						</td>
					</tr>
					<tr>
						<td>
							<b>Display Name:</b>
						</td>
						<td>
							<input type='text' class='form-control' name='display' value='".$userdetails['display_name']."' />
						</td>
					</tr>
					<tr>
						<td>
							<b>Email:</b>
						</td>
						<td>
							<input type='text' class='form-control' name='email' value='".$userdetails['email']."' />
						</td>
					</tr>
					<tr>
						<td>
							<b>Active:</b>
						</td>
						<td>";
							//Display activation link, if account inactive
							if ($userdetails['active'] == '1')
							{
								echo "
								Yes";	
							}
							else
							{
								echo "
								<input type='checkbox' class='form-control' name='activate' id='activate' value='activate'>
								Activate now";
							}
							echo "
						</td>
					</tr>
					<tr>
						<td>
							<b>Title:</b>
						</td>
						<td>
							<input type='text' class='form-control' name='title' value='".$userdetails['title']."' />
						</td>
					</tr>
					<tr>
						<td>
							<b>Sign Up:</b>
						</td>
						<td>
							".date("j M, Y", $userdetails['sign_up_stamp'])."
						</td>
					</tr>
					<tr>
						<td>
							<b>Last Sign In:</b>
						</td>
						<td>";
							//Last sign in, interpretation
							if ($userdetails['last_sign_in_stamp'] == '0')
							{
								echo "Never";	
							}
							else
							{
								echo date("j M, Y", $userdetails['last_sign_in_stamp']);
							}
							echo "
						</td>
					</tr>
					<tr>
						<td>
							<b>Delete:</b>
						</td>
						<td>
							<input type='checkbox' name='delete[".$userdetails['id']."]' id='delete[".$userdetails['id']."]' value='".$userdetails['id']."'>
							Remove User
						</td>
					</tr>
					
					<thead>
						<tr>
							<th>Permission Setting</th>
							<th>Groups</th>
						</tr>
					</thead>
					<tr>
						<td>
							<b>Remove Permission:</b>
						</td>
						<td>";
							//List of permission levels user is apart of
							foreach ($permissionData as $v1)
							{
								if(isset($userPermission[$v1['id']]))
								{
									echo "<input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
									echo "<br>";
								}
							}
						echo "
						</td>
					</tr>
					<tr>
						<td>
							<b>Add Permission:</b>
						</td>
						<td>";						
							//List of permission levels user is not apart of
							foreach ($permissionData as $v1)
							{
								if(!isset($userPermission[$v1['id']]))
								{
									echo "<input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
									echo "<br>";
								}
							}

							echo"
						</td>
					</tr>
					<tr>
						<td>
							<b>Remove Subscriptions:</b>
						</td>
						<td>";						
							//List of subscriptions user is apart of
							$subscriptions = fetchAllSubscriptions($loggedInUser->user_id);
							if($subscriptions == null)
							{
								echo "No Subscriptions";
							}
							else
							{
								$subscription = explode("-", $subscriptions);
								foreach ($subscription as $v1)
								{
									echo "<input type='checkbox' name='removeSubject[".$v1['id']."]' id='removeSubject[".$v1['id']."]' value='".$v1['id']."'> ".fetchSubjectDetails($v1)['name'];
									echo "<br>";
								}
							}
							echo"
						</td>
					</tr>
					<tr>
						<td>
							<b>Add Subscriptions:</b>
						</td>
						<td>";						
							//List of subscriptions user is apart of
							$subscriptions = fetchAllSubscriptions($loggedInUser->user_id);
							$subscription = explode("-", $subscriptions);
							foreach ($_SESSION["subjectList"] as $item)
							{
								if(!in_array($item['id'], $subscription))
								{
									echo "<input type='checkbox' name='addSubject[".$item['id']."]' id='addSubject[".$item['id']."]' value='".$item['id']."'> ".fetchSubjectDetails($item['id'])['name'];
									echo "<br>";
								}
							}
							echo"
						</td>
					</tr>
				</tbody>
			</table>
			<input type='submit' value='Update' class='btn btn-primary' style='max-width:200px;' />
		</form>
	</div>";
	
	echo "</center>";
	
	include 'models/footer.php';
?>