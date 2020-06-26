<?php
	ob_start();
	session_start();
	$pagetitle = 'Show Items';
	include 'init.php';

	
	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

	
	$stmt = $con->prepare("SELECT 
								items.*, 
								categories.Name AS category_name, 
								users.UserName 
							FROM 
								items
							INNER JOIN 
								categories 
							ON 
								categories.ID = items.CatID 
							INNER JOIN 
								users 
							ON 
								users.UserID = items.MemberID 
							WHERE 
								ItemID = ?
							AND 
								approve = 1");

	// Execute Query
	$stmt->execute(array($itemid));

	$count = $stmt->rowCount();

    if ($count > 0)
     {

	// Fetch The Data
	$item = $stmt->fetch();
?>
<h1 class="text-center"><?php echo $item['Name'] ?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="img.png" alt="" />
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $item['Name'] ?></h2>
			<p><?php echo $item['Description'] ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Added Date</span> : <?php echo $item['AddDate'] ?>
				</li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<span>Price</span> : <?php echo $item['Price'] ?>
				</li>
				<li>
					<i class="fa fa-building fa-fw"></i>
					<span>Made In</span> : <?php echo $item['CountryMade'] ?>
				</li>
				<li>
					<i class="fa fa-tags fa-fw"></i>
					<span>Category</span> : <a href="categories.php?pageid=<?php echo $item['CatID'] ?>"><?php echo $item['category_name'] ?></a>
				</li>
				<li>
					<i class="fa fa-user fa-fw"></i>
					<span>Added By</span> : <a href="#"><?php echo $item['UserName'] ?></a>
				</li>
				<li class="tags-items">
					<i class="fa fa-user fa-fw"></i>
					<span>Tags</span> : 
					
				</li>
			</ul>
		</div>
	</div>
	<hr class="custom-hr">
	<?php if (isset($_SESSION['user'])) { ?>
	<!-- Start Add Comment -->
	<div class="row">
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Add Your Comment</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['ItemID'] ?>" method="POST">
					<textarea name="comment" required></textarea>
					<input class="btn btn-primary" type="submit" value="Add Comment">
				</form>
				<?php 
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {

						$comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
						$itemid 	= $item['ItemID'];
						$userid 	=  $_SESSION['uid'];

						if (! empty($comment)) {

							$stmt = $con->prepare("INSERT INTO 
								comments(Comment, Status, CommentDate, Item_ID, User_ID)
								VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");

							$stmt->execute(array(

								'zcomment' => $comment,
								'zitemid' => $itemid,
								'zuserid' => $userid

							));

							if ($stmt) {

								echo '<div class="alert alert-success">Comment Added</div>';

							}

						} else {

							echo '<div class="alert alert-danger">You Must Add Comment</div>';

						}

					}
				?>
			</div>
		</div>
	</div>
	<!-- End Add Comment -->
	<?php } else {
		echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
	} ?>
	<hr class="custom-hr">
		<?php

			// Select All Users Except Admin 
			$stmt = $con->prepare("SELECT 
										comments.*, users.UserName AS Member  
									FROM 
										comments
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.User_ID
									WHERE 
										Item_ID = ?
									AND 
										status = 1
									ORDER BY 
										C_ID DESC");

			// Execute The Statement

			$stmt->execute(array($item['ItemID']));

			// Assign To Variable 

			$comments = $stmt->fetchAll();

		?>
	
	<?php foreach ($comments as $comment) { ?>
		<div class="comment-box">
			<div class="row">
				<div class="col-sm-2 text-center">
					<img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="" />
					<?php echo $comment['Member'] ?>
				</div>
				<div class="col-sm-10">
					<p class="lead"><?php echo $comment['Comment'] ?></p>
				</div>
			</div>
		</div>
		<hr class="custom-hr">
	<?php } ?>
</div>
<?php
} else {
		echo '<div class="container">';
			echo '<div class="alert alert-danger">There\'s no Such ID Or This Item Is Waiting Approval</div>';
		echo '</div>';
	}
	
  
    
	
	include $tpl . 'footer.php';
	ob_end_flush();
 