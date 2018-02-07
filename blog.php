<?php
/**
 * This will display the full post if the characters exceed the limit of 200.
 * The index will pass the id for the correct row.
 * Author: Mark Nunez
 * Date Modified: Jan 31, 2018
 */
	require('connect.php');
	if(isset($_GET['id']))
	{
		$validItem = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

		if(valid($validItem))
		{
			$id = $validItem;

			$blogQuery = "SELECT postID, title, timestamp, content FROM blog WHERE postID = :id LIMIT 1";
			$blogStatement = $db->prepare($blogQuery);
			$blogStatement->bindValue('id', $id, PDO::PARAM_INT);
			$blogStatement->execute();
			$row = $blogStatement->fetch();
		}
		else
		{
			header('Location: index.php');
			exit;
		}
	}

	function valid($item)
	{
		return filter_var($item, FILTER_VALIDATE_INT);
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Ninja Blog! - <?= $row['title']?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles/bootstrap.css" />
		<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Shojumaru" rel="stylesheet">
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body class="bg-dark text-center" style="font-family: 'Shojumaru', cursive;">
	<div class='container-fluid bg-dark text-danger'>
		<h1 class="display-3">Ninja Blog: Mission Board</h1>
	</div>
	<div class='container bg-danger text-uppercase'>
		<?php include('nav.html');?>
	</div>
	<div class='container bg-danger text-left'>
		<div class='jumbotron bg-dark text-danger'>
			<div class="card bg-dark">
				<div class="card-header">
					<h3><a class="card-link text-danger" data-toggle= "collapse" data-parent="#recentPosts" href="#collapse"><?= $row['title']?></a>
					</h3>
					<p class='date'><?= date("F d, Y g:i a",strtotime($row['timestamp']))?>
						<a href="edit.php"><span class='badge badge-light float-right'>Edit</span></a>
					</p>
				</div>
				<div id="collapse" class="collapse show">
					<div class="card-body text-dark bg-secondary">
						<p class='blog'>
							<?= $row['content']?>	
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
		<!-- script allows the tooltip I used for the navbar. -->
		<script>
			$(document).ready(function(){
			    $('[data-toggle="tooltip"]').tooltip();   
			});
		</script>
		<?php include('footer.html') ?>
</body>
</html>