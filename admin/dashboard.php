<?php
session_start();
if( isset($_SESSION['username']))
{
    $pagetitle="Dashboard";
	include "init.php";
	  $numusers= 5;
	$latestusers = getLatest("*", "users", "UserID", $numusers); // Latest Users Array
	$numitems =5 ;
	$latestitems =getlatest("*" , "items", "ItemID" ,$numitems);
	$numComments = 4;
    ?>
    <div class="home-stats">
			<div class="container text-center">
				<h1>Dashboard</h1>
				<div class="row">
					<div class="col-md-3">
						<div class="stat st-members">
							<i class="fa fa-users"></i>
							<div class="info">
								Total Members
								<span>
								<?php echo countitems('UserID','users'); ?>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-pending">
							<i class="fa fa-user-plus"></i>
							<div class="info">
								Pending Members
								<span>
                                <a href="members.php?do=manage&page=pending">
                                         <?php echo checkitems('RegStatus', 'users', 0); ?>
									</a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-items">
							<i class="fa fa-tag"></i>
							<div class="info">
								Total Items
								<span>
								
								<?php echo countitems('ItemID','items'); ?>
								
    
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-comments">
							<i class="fa fa-comments"></i>
							<div class="info">
								Total Comments
								<span>
								<?php echo countitems('C_ID','comments'); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="latest">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
                                <i class="fa fa-users"></i> 
                                
								Latest <?php echo $numusers ;?> Registerd Users 
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
                            <ul class="list-unstyled latest-users">
									<?php 
									
											foreach ($latestusers as $user) {
												echo '<li>';
													echo $user['UserName'];
													echo '<a href="members.php?do=edit&userid=' . $user['UserID'] . '">';
														echo '<span class="btn btn-success pull-right">';
															echo '<i class="fa fa-edit"></i> Edit';
															if ($user['RegStatus'] == 0) {
																echo "<a 
																		href='members.php?do=activate&userid=" . $user['UserID'] . "' 
																		class='btn btn-info pull-right activate'>
																		<i class='fa fa-check'></i> Approve</a>";
															}
														echo '</span>';
													echo '</a>';
												echo '</li>';
											}
										
									?>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
							<i class="fa fa-users"></i> 
                                
								Latest <?php echo $numitems ;?> Registerd items 
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							
								
							</div>
							<div class="panel-body">
							<ul class="list-unstyled latest-users">
									<?php 
									
											foreach ($latestitems as $item) {
												echo '<li>';
													echo $item['Name'];
													echo '<a href="items.php?do=edit&itemid=' . $item['ItemID'] . '">';
														echo '<span class="btn btn-success pull-right">';
															echo '<i class="fa fa-edit"></i> Edit';
															if ($item['approve'] == 0) {
																echo "<a 
																		href='items.php?do=approve&itemid=" . $item['ItemID'] . "' 
																		class='btn btn-info pull-right activate'>
																		<i class='fa fa-check'></i> Approve</a>";
															}
														echo '</span>';
													echo '</a>';
												echo '</li>';
											}
										
									?>
							</ul>
								
								
							</div>
						</div>
					</div>
					<!-- Start Latest Comments -->
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-comments-o"></i> 
								Latest <?php echo $numComments ?> Comments 
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<?php
									$stmt = $con->prepare("SELECT 
																comments.*, users.UserName AS Member  
															FROM 
																comments
															INNER JOIN 
																users 
															ON 
																users.UserID = comments.User_ID
															ORDER BY 
																c_id DESC
															LIMIT $numComments");

									$stmt->execute();
									$comments = $stmt->fetchAll();

									if (! empty($comments)) {
										foreach ($comments as $comment) {
											echo '<div class="comment-box">';
												echo '<span class="member-n">
													<a href="members.php?do=Edit&userid=' . $comment['User_ID'] . '">
														' . $comment['Member'] . '</a></span>';
												echo '<p class="member-c">' . $comment['Comment'] . '</p>';
											echo '</div>';
										}
									} else {
										echo 'There\'s No Comments To Show';
									}
								?>
							</div>
						</div>
					</div>
				</div>
				<!-- End Latest Comments -->
				</div>
				

    
    <?php
    include $tpl."footer.php"; 

}
else
{
    header("location:index.php");
    exit();
}