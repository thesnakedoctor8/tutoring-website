<?php
	require_once("models/config.php");
	
	if (!securePage($_SERVER['PHP_SELF']))
	{
		die();
	}
	
	require_once("models/header.php");
?>

<div class="row">
	<div class="col-md-3">
		<p class="lead">View Subjects</p>
		<div class="list-group">
			<?php
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
			?>
		</div>
	</div>

	<div class="col-md-9">

		<div class="thumbnail">
			<img class="img-responsive" src="models/site-templates/images/banner.png">
			<div class="caption-full">
				<h4>
					<a href="#">Site Description</a>
				</h4>
				<p>Description paragraph about the site and more information about stuff</p>
				<p>Really like our subjects? Subscribe to a new subject
					<strong><a href="subscription.php">by picking from our subjects</a>
					</strong>that we have available</p>
				<p>Long paragraph explaining more about the site and what we offer. How stuff works and a long description about it. Talk about more stuff and mention important things that users can read when visiting the site. Long paragraph explaining more about the site and what we offer. How stuff works and a long description about it. Talk about more stuff and mention important things that users can read when visiting the site.</p>
			</div>
		</div>

		<div class="well">

			<div class="text-right">
				<a class="btn btn-success">Leave a Review</a>
			</div>

			
			<?php
				/* Dynamic reviews
				
				$numberOfReviews = total number of reviews;
				if($numberOfReviews > 5)
				{
					$numberOfReviews = 5;
				}
				
				for ($x = 0; $i < $numberOfReviews; $i++)
				{
					echo "<hr>";
					echo "<div class='row'>";
						echo "<div class='col-md-12'>";
							for ($i = 0; $i < 5; $i++)
							{
								if($i < $numberOfStars))
								{
									echo "<span class='glyphicon glyphicon-star'></span>";
								}
								else
								{
									echo "<span class='glyphicon glyphicon-star-empty'></span>";
								}
							}
							echo $reviewersName;
							echo "<span class='pull-right'>".$numberOfDaysAgoReviewed."</span>";
							echo "<p>".$reviewsDescription."</p>";
						echo "</div>";
					echo "</div>";
				}
				
				if($numberOfReviews == 5)
				{
					echo "<hr>";
					echo "<div class='row'>";
						echo "<div class='col-md-12'>";
							echo "<center><a href='#' class='col-md-12-link'>...</a></center>";
						echo "</div>";
					echo "</div>";
				}
				*/
			?>
			
			<hr>

			<div class="row">
				<div class="col-md-12">
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
					Anonymous
					<span class="pull-right">10 days ago</span>
					<p>Generic description about how well I like the site.</p>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col-md-12">
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					Anonymous 2
					<span class="pull-right">12 days ago</span>
					<p>I've already subscribed again!</p>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col-md-12">
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
					Anonymous 3
					<span class="pull-right">15 days ago</span>
					<p>This site was great in terms of quality. I would subscribe to it again!</p>
				</div>
			</div>
			
			<hr>
			
			<div class="row">
				<div class="col-md-12">
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
					<span class="glyphicon glyphicon-star-empty"></span>
					Anonymous 4
					<span class="pull-right">16 days ago</span>
					<p>Terrible site. The programmer should be fired.</p>
				</div>
			</div>

		</div>

	</div>

</div>
	
	
<?php
	include 'models/footer.php';
?>
