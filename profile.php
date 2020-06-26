<?php
	ob_start();
	session_start();
	$pagetitle = 'Profile';
    include 'init.php';
    if (isset($_SESSION['user'])) {
		$getuser= $con -> prepare("SELECT * FROM users WHERE UserName=?");
		$getuser-> execute(array($sessionuser));
		$info = $getuser->fetch();
    ?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Login Name</span> : <?php echo $info['UserName'];?>
					</li>
					<li>
						<i class="fa fa-envelope-o fa-fw"></i>
						<span>Email</span> : <?php echo $info['Email'];?>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Full Name</span> :  <?php echo $info['FullName'];?>
					</li>
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Registered Date</span> : <?php echo $info['Date'];?>
					</li>
					<li>
						<i class="fa fa-tags fa-fw"></i>
						<span>Fav Category</span> :
					</li>
				</ul>
				<a href="#" class="btn btn-default">Edit Information</a>
			</div>
		</div>
	</div>
</div>

<div  class="myads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">MY ITEMS</div>
			<div class="panel-body">
			
			<?php
		

			$allItems = getitems('MemberID',$info['UserID'],3);
			if (! empty($allItems)) {
			foreach ($allItems as $item) {
				echo '<div class="col-sm-6 col-md-3">';
					echo '<div class="thumbnail item-box">';
					if ($item['approve'] == 0) { 
						echo '<span class="approve-status">Waiting Approval</span>'; 
					}
						echo '<span class="price-tag">' . $item['Price'] . '</span>';
						echo '<img class="img-responsive" src="img.png" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid='. $item['ItemID'] .'">' . $item['Name'] .'</a></h3>';
							echo '<p>' . $item['Description'] . '</p>';
							echo '<div class="date">' . $item['AddDate'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		} else {
			echo 'Sorry There\' No Ads To Show, Create <a href="newad.php">New Ad</a>';
		}
		
		?>
			</div>
		</div>
	</div>
</div>

<div class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel-body">
			<?php
				$stmt=$con->prepare("SELECT Comment FROM comments WHERE User_ID=? ");
			$stmt->execute(array($info['UserID']));
			$rows=$stmt->fetchAll();
			if (! empty($rows)) {
				foreach($rows as $row)
				{
					echo '<p>' . $row['Comment'] . '</p>';
				}
			}
			else {
				echo 'There\'s No Comments to Show';
			}
			
			?>
		
			</div>
		</div>
	</div>
</div>

<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>