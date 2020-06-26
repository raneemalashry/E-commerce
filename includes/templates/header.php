<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?php get_title();?></title>
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>backend.css">


</head>
<body>

	<div class="upper-bar">
		<div class="container">
			<?php 
				
				if (isset($_SESSION['user'])) { ?>

				<img class="my-image img-thumbnail img-circle" src="img.png" alt="" />
				<div class="btn-group my-info">
					<span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<?php echo $sessionuser; ?>
						<span class="caret"></span>
					</span>
					<ul class="dropdown-menu">
						<li><a href="profile.php">My Profile</a></li>
						<li><a href="newad.php">New Item</a></li>
						
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>

				<?php

				} else {
			?>
			<a href="login.php">
				<span class="pull-right">Login/Signup</span>
			</a>
			<?php } ?>
		</div>
	</div>
	<nav class="navbar navbar-inverse">
	  <div class="container">
	    <div class="navbar-header">
	      <button 
	      		type="button" 
	      		class="navbar-toggle collapsed" 
	      		data-toggle="collapse" 
	      		data-target="#app-nav" 
	      		aria-expanded="false">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="index.php">Homepage</a>
	    </div>
	    <div class="collapse navbar-collapse" id="app-nav">
	      <ul class="nav navbar-nav navbar-right">
              <?php 
              $categories =getallitems( "*" ,"categories", "WHERE Parent=0",$and=NULL, "ID", $ordering="DESC");
              foreach($categories as $cat)
              {
                echo 
				'<li>
                    <a href="categories.php?pageid=' . $cat['ID'] .'&pagename='. str_replace(' ', '-' ,$cat['Name']). ' ">
                    ' . $cat['Name'] . '
					
					</a>
				</li>';
              }
              ?>
	     
	      </ul>
	    </div>
	  </div>
	</nav>
