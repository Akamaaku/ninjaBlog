<?php
	/**
 * This will allow users to post new content and store it into the database.
 * The index will pass the id for the correct row.
 * Author: Mark Nunez
 * Date Modified: February 01, 2018
 */
	require ('authenticate.php');

	require('connect.php');
	
	$valid = true;

	if(isset($_POST['submit']))
	{
		if($_POST && !empty($_POST['title']) && !empty($_POST['content']))
		{
			
			$title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$content = filter_var($_POST['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$currentDate = date('Y-m-d H:i:s');

			$newQuery = "INSERT INTO blog (title, dateCreated, content) VALUES (:title, :dateCreated, :content)";

			$newStatement = $db->prepare($newQuery);
			///Bind values have PDO for columns that have parameters in the database.
			$newStatement->bindValue(':title', $title, PDO::PARAM_STR);
			$newStatement->bindValue(':dateCreated', $currentDate);
			$newStatement->bindValue(':content', $content);

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
				$newStatement->execute();
				header('Location: index.php');
			}
			else
			{
				header('Location: newpost.php');
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Ninja Blog! - Report</title>
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
			<form method="post" action="newpost.php">

				<div class="form-group">
					<label for= "title">Title</label>
					<input class="bg-secondary text-dark" id="title" type="text" name="title" autofocus="" required="">
				</div>

				<div class="form-group">
					<label for="content">Report</label>
					<textarea class="form-control bg-secondary text-dark" id="content" name="content" rows="10" required=""></textarea>
				</div>
				<button class="btn btn-light" type="submit" name="submit">Submit</button>
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