<?php
/**
 * Obtains the information of the blog table in the database and will be displayed in the html provided.
 * Author: Mark Nunez
 * Date Modified: Jan 31, 2018
**/
	require('connect.php');
	$counter = 0;
	$query = "SELECT postID, title, dateCreated, content, dateModified FROM blog ORDER BY dateCreated DESC";

	$statement = $db->prepare($query);

	$statement->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Ninja Blog! - Home Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" >
<!-- 	<link rel="stylesheet" type="text/css" href="styles/ninja.css"> -->	
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
			<h2>Recent Entries:</h2>
			<?php if($statement->rowCount() == 0):?>
				<h2>No entries found!</h2>
			<?php else:?>
				<div id="recentPosts">
					<?php while ($row = $statement->fetch()):?>
						<?php $counter++ ?>
						<div class="card bg-dark">
							<div class="card-header">
								<h3><a class="card-link text-danger" data-toggle= "collapse" data-parent="#recentPosts" href="#collapse<?= $counter?>"><?= $row['title']?></a>
								</h3>

								<p class='date'><?= date("F d, Y g:i a",strtotime($row['dateCreated']))?>
									<a href="edit.php?id=<?=$row['postID']?>"><span class='badge badge-light float-right'>Edit</span></a>
								</p>
							</div>
							<div id="collapse<?= $counter ?>" class="collapse show">
								<div class="card-body text-dark bg-secondary">
									<?php if(strlen($row['content']) > 200): ?>
										<p class='blog'>
											<?= substr($row['content'],0,200)?>
											<a href='blog.php?id=<?=$row['postID']?>'><span class="badge badge-light">Read full post</span></a>
										</p>
										<?php else:?>
											<p class='blog'>
												<?= $row['content']?>	
											</p>
											<?php endif?>
										</div>
									</div>
								</div>
								<?php if($counter == 5):?>
									<?php break;?>
								<?php endif ?>
							<?php endwhile?>
						</div>
					<?php endif?>
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