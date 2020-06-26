<?php 
session_start();
$pagetitle = 'Members';
if( isset($_SESSION['username']))
{
    include "init.php";
    $do="";
    if(isset($_GET['do']))
    {
       $do=$_GET['do'] ;
    }
    else
    {
        $do='manage';
    }

	if ($do=="manage")

	{  
		$query='';
		if(isset($_GET['page'])&& $_GET['page']=='pending')
		{
			$query='AND RegStatus = 0';
		}
		    
		$stmt=$con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
		$stmt->execute();
		$rows=$stmt->fetchAll();
		?>
		<h1 class="text-center">Manage Members</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>avatar</td>
							<td>Username</td>
							<td>Email</td>
							<td>Full Name</td>
							<td>Registred Date</td>
							<td>Control</td>
							
						</tr>
						<?php
							foreach($rows as $row)
							{
								echo "<tr>";

							
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td>";
									if (empty($row['Avatar'])) {
										echo 'No Image';
									} else {
										echo "<img src='uploads/avatars/" . $row['Avatar'] . "' alt='' />";
									}
									echo "</td>";
									echo "<td>" . $row['UserName'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['Date'] . "</td>";
									echo "<td>
									<a href='members.php?do=edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='members.php?do=delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
								
								
								if ($row['RegStatus']==0)
								{
									echo "<a 
													href='members.php?do=activate&userid=" . $row['UserID'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Activate</a>";
										
								}
								echo "</td>";
								echo "</tr>";

							}
						?>
						
					</table>

				</div>
				
				<a href="members.php?do=add" class="btn btn-primary">
					<i class="fa fa-plus"></i> New Member
				</a>
			
			</div>
		

<?php
	}
	elseif($do=="add")
	{ ?>
        <h1 class="text-center">Add Member</h1>
				<div class= "container">
					<form class="form-horizontal" action="?do=insert" method="POST"   enctype="multipart/form-data">
						
						<!-- Start Username Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="username" class="form-control"  autocomplete="off" required="required" placeholder="Enter your name" />
							</div>
						</div>
						<!-- End Username Field -->
						<!-- Start Password Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10 col-md-6">
								
								<input type="password" name="password" class="password form-control" autocomplete="new-password" placeholder="Make the password complex" />
								<i class="show-pass fa fa-eye fa-2x"></i>
							</div>
						</div>
						<!-- End Password Field -->
						<!-- Start Email Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" name="email"  class="form-control" required="required" placeholder="Enter your valid email" />
							</div>
						</div>
						<!-- End Email Field -->
						<!-- Start Full Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="full" class="form-control" required="required" placeholder="Enter your fullname" />
							</div>
						</div>
						<!-- End Full Name Field -->
						<!-- Start Avatar Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Avatar</label>
						<div class="col-sm-10 col-md-6">
							<input type="file" name="avatar" class="form-control" required="required" />
						</div>
					</div>
						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>
                </div><?php
             
	}
	elseif($do=='insert')
	{
		
		if($_SERVER['REQUEST_METHOD']='POSt')
		{ 
			echo '<h1 class="text-center">Insert Member</h1>';
			echo "<div class='container'>";
				// Upload Variables

				$avatarName = $_FILES['avatar']['name'];
				$avatarSize = $_FILES['avatar']['size'];
				$avatarTmp	= $_FILES['avatar']['tmp_name'];
				$avatarType = $_FILES['avatar']['type'];

				// List Of Allowed File Typed To Upload

				$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Avatar Extension

				$avatarExtension = strtolower(end(explode('.', $avatarName)));

		
			$pass=$_POST['password'];
			$user=$_POST['username'];
			$email=$_POST['email'];
			$fullname=$_POST['full'];
			$hashpass=sha1($_POST['password']);
			$formerrors=array();
			if (strlen($user) < 4) {
				$formerrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
			}

			if (strlen($user) > 20) {
				$formerrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
			}

			if (empty($user)) {
				$formerrors[] = 'Username Cant Be <strong>Empty</strong>';
			}
			if (empty($pass)) {
				$formerrors[] = 'Full Name Cant Be <strong>Empty</strong>';
			}

			if (empty($fullname)) {
				$formerrors[] = 'Full Name Cant Be <strong>Empty</strong>';
			}

			if (empty($email)) {
				$formerrors[] = 'Email Cant Be <strong>Empty</strong>';
			}
			if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
				$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
			}

			if (empty($avatarName)) {
				$formErrors[] = 'Avatar Is <strong>Required</strong>';
			}

			if ($avatarSize > 4194304) {
				$formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
			}


			foreach($formerrors as $error)
			{

				echo '<div class="alert alert-danger">' . $error . '</div>';

			}
			if(empty($formerrors)){
				$avatar = rand(0, 10000000000) . '_' . $avatarName;

				move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

				$value=$user;
				$row= checkitems('UserName','users',$value);
				if($row == 1)
				{
					$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

						redirectHome($theMsg, 'back');
				}
				else
				{

					$stmt=$con->prepare("INSERT INTO users(UserName,Password,Email,FullName,RegStatus,Date,Avatar) VALUES(:zuser, :zpass,:zemail,:zfull,1,now(),:zavatar)");
					$stmt->execute(array(
						"zuser"=>$user,
						"zpass"=>$hashpass,
						"zemail"=>$email ,
						"zfull" =>$fullname,
						"zavatar"=>$avatar
					));

					
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

					redirectHome($theMsg, 'back');
				}
			}
			

		}
		else
		{
			echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

				echo "</div>";

		}
	}
    elseif($do=="edit")
    {
        if(isset($_GET['userid']) && is_numeric($_GET['userid']))
        {
			$stmt=$con->prepare('SELECT * FROM users WHERE UserID=? LIMIT 1');
			$stmt->execute(array($_GET['userid']));
			$row=$stmt->fetch();
			$count=$stmt->rowcount();
			if($count>0)
			{?>
				<h1 class="text-center">Edit Member</h1>
				<div class= "container">
					<form class="form-horizontal" action="?do=update" method="POST" >
						<input type="hidden" name="userid" value="<?php echo $row['UserID'] ?>" />
						<!-- Start Username Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="username" class="form-control"  autocomplete="off" required="required" value="<?php echo $row['UserName'] ?>" />
							</div>
						</div>
						<!-- End Username Field -->
						<!-- Start Password Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10 col-md-6">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
							</div>
						</div>
						<!-- End Password Field -->
						<!-- Start Email Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" name="email"  class="form-control" required="required" value="<?php echo $row['Email'] ?>"/>
							</div>
						</div>
						<!-- End Email Field -->
						<!-- Start Full Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="full" class="form-control" required="required" value="<?php echo $row['FullName'] ?>" />
							</div>
						</div>
						<!-- End Full Name Field -->
						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>
                </div><?php
            }  
        
			
			else
			{
				echo "<div class='container'>";

			$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

			redirectHome($theMsg);

			echo "</div>";
			}
		}
		
			 
           

	}
	elseif($do=="update")
    {
		echo '<h1 class="text-center">Update Member</h1>';
		echo "<div class='container'>";
		if($_SERVER['REQUEST_METHOD']='POSt')
		{ 
			$pass='';
			if(empty($_POST['newpassword']))
			{
				$pass=$_POST['oldpassword'];
			}
			else{
				$pass=sha1($_POST['newpassword']);
			}
			$userid=$_POST['userid'];
			$user=$_POST['username'];
			$email=$_POST['email'];
			$fullname=$_POST['full'];
			$formerrors=array();
			if (strlen($user) < 4) {
				$formerrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
			}

			if (strlen($user) > 20) {
				$formerrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
			}

			if (empty($user)) {
				$formerrors[] = 'Username Cant Be <strong>Empty</strong>';
			}

			if (empty($fullname)) {
				$formerrors[] = 'Full Name Cant Be <strong>Empty</strong>';
			}

			if (empty($email)) {
				$formerrors[] = 'Email Cant Be <strong>Empty</strong>';
			}

			foreach($formerrors as $error)
			{

				echo '<div class="alert alert-danger">' . $error . '</div>';

			}
			
			if(empty($formerrors))
			{
			$stmt=$con->prepare('UPDATE users SET UserName=?, Email=?, FullName=?, Password=? WHERE UserID=?');
			$stmt->execute(array($user,$email,$fullname,$pass, $userid));
				
			$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

			redirectHome($theMsg, 'back');
			
			}
			
			

		}
		else
		{
			
			$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

			redirectHome($theMsg);
		}
		echo'</div>';
	

		
	}
	elseif($do =="delete")
	{
		if(isset($_GET['userid']) && is_numeric($_GET['userid']))
        { 
			
			echo "<h1 class='text-center'>Delete Member</h1>";
			echo "<div class='container'>";
			$check = checkItems('userid', 'users', $_GET['userid']);

			if($check>0)
			{
				$stmt=$con->prepare("DELETE FROM users WHERE UserID= :zuser");
				$stmt->bindParam("zuser",$_GET['userid']);
				$stmt->execute();
				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

					redirectHome($theMsg, 'back');
			}
			else {

				$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

				redirectHome($theMsg);

			}
			echo '</div>';
		}



	}
	elseif($do=="activate")
	{ 
		
		if(isset($_GET['userid']) && is_numeric($_GET['userid']))
        {  
			
			echo "<h1 class='text-center'>	Activate Member</h1>";
			echo "<div class='container'>";
			$check = checkItems('userid', 'users', $_GET['userid']);

			if($check>0)
			{
				$stmt=$con->prepare("UPDATE users SET RegStatus = 1 where UserID=?");
				$stmt->execute(array($_GET['userid']));
				$theMsg = "<div class='alert alert-success'>" . ' Record Activated</div>';

					redirectHome($theMsg, 'back');
			}
			else {

				$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

				redirectHome($theMsg);

			}
			echo '</div>';
		}

	}
	

	

    include $tpl."footer.php"; 

}
else
{
    header("location:index.php");
    exit();
}
?>