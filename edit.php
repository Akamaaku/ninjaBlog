<?php
	/**
 * This will allow users to update or delete content in the database.
 * The index will pass the id for the correct row.
 * Author: Mark Nunez
 * Date Modified: February 01, 2018
 */
	require ('authenticate.php');

	require('connect.php');
	
	$valid = true;
	
	if(isset($_GET['id']))
	{
		$validItem = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		
		if(valid($validItem))
		{
			$id = $validItem;

			///This pulls the current blog post that is being edited.
			$currentQuery = "SELECT postID, title, content FROM blog WHERE postID = :id LIMIT 1";
			$currentStatement = $db->prepare($currentQuery);
			$currentStatement->bindValue('id', $id, PDO::PARAM_INT);
			$currentStatement->execute();
			$row = $currentStatement->fetch();
		}
	}

	if(isset($_POST['submit1']) || isset($_POST['submit2']))
	{
		if($_POST && !empty($_POST['title']) && !empty($_POST['content']))
		{
			$title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$content = filter_var($_POST['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$currentDate = date('Y-m-d H:i:s');

			if(isset($_POST['submit1']))
			{
				$updateQuery = "UPDATE blog SET title = :title, dateModified = :dateModified, content = :content WHERE postID = :id LIMIT 1";
				$updateStatement = $db->prepare($updateQuery);
				$updateStatement->bindValue(':title', $title, PDO::PARAM_STR);
				$updateStatement->bindValue(':dateModified', $currentDate);
				$updateStatement->bindValue(':content', $content);
				$updateStatement->bindValue(':id', $id, PDO::PARAM_INT);				
				///strlen gets the character count.
				if(strlen($title) < 1)
				{
					$valid = false;
					$errorMessage = 'Ninja need to have at least 1 character in their title!';
				}
				if(strlen($content) < 1)
				{
					$valid = false;
					$errorMessage = 'Ninja cannot report nothing!';
				}

				if($valid)
				{
					$updateStatement->execute();
					header('Location: index.php');
				}
				else
				{
					$valid = false;
					$errorMessage = 'Failed the update mission.';
				}
			}

			//Tested and complete
			if(isset($_POST['submit2']))
			{
				$deleteQuery = "DELETE FROM blog WHERE postID = :id LIMIT 1";

				$deleteStatement = $db->prepare($deleteQuery);

				///Bind values have PDO for columns that have parameters in the database.
				$deleteStatement->bindValue(':id', $id, PDO::PARAM_INT);
				
				if($valid)
				{
					$deleteStatement->execute();
					header('Location: index.php');
				}
				else
				{
					$valid = false;
					$errorMessage = 'Failed the update mission.';
				}
			}
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
	<title>Ninja Blog! - Editing <?=$row['title']?></title>
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
		<h1 class="display-3">Ninja Blog: Report</h1>
	</div>
	<div class='container bg-danger text-uppercase'>
		<?php include('nav.html');?>
	</div>
	<div class='container bg-danger text-left'>
		<div class='jumbotron bg-dark text-danger'>
			<form method="post" action="#">
				<input type="hidden" name="id" value="<?=$row['postID']?>" />
				<div class="form-group">
					<label for= "title">Title</label>
					<input class="bg-secondary text-dark" id="title" type="text" name="title" value="<?=$row['title']?>" autofocus="" required="">
				</div>

				<div class="form-group">
					<label for="content">Report</label>
					<textarea class="form-control bg-secondary text-dark" id="content" name="content" rows="10" required=""><?=$row['content']?></textarea>
				</div>
				<button class="btn btn-light" type="submit" name="submit1">Update</button>
				<button class="btn btn-danger float-right" type="submit" name="submit2">Delete</button>
			</form>
			<?php if(!$valid):?>
				<div class="alert alert-danger">
					<strong>Error: </strong><?= $errorMessage?>
				</div>
			<?php endif?>
		</div>
	</div>
	<?php include('footer.html') ?>
</body>
</html>