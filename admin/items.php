<?php
 session_start();
 $pagetitle = 'Items';
 if(isset($_SESSION['username']))
 {
    include 'init.php';
    $do ="";
    if(isset($_GET['do']))
    {
        $do=$_GET['do'];

    } 
    else
    {
        $do='manage';
    }
	if($do=="manage")
	{

		
        $stmt = $con->prepare("SELECT items.* , categories.Name AS categoryname , users.UserName AS username FROM items
        INNER JOIN categories ON categories.ID = items.CatID
        INNER JOIN users ON users.UserID = items.MemberID ");

		

			$stmt->execute();

		

			$items = $stmt->fetchAll();

			if (! empty($items)) {

			?>

			<h1 class="text-center">Manage Items</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Item Name</td>
							<td>Description</td>
							<td>Price</td>
							<td>Adding Date</td>
							<td>Category</td>
							<td>Username</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($items as $item) {
								echo "<tr>";
									echo "<td>" . $item['ItemID'] . "</td>";
									echo "<td>" . $item['Name'] . "</td>";
									echo "<td>" . $item['Description'] . "</td>";
									echo "<td>" . $item['Price'] . "</td>";
                                    echo "<td>" . $item['AddDate'] ."</td>";
                                    echo "<td>" . $item['categoryname'] ."</td>";
									echo "<td>" . $item['username'] ."</td>";
								
									echo "<td>
										<a href='items.php?do=edit&itemid=" . $item['ItemID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='items.php?do=delete&itemid=" . $item['ItemID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										
								
								
								if ($item['approve']==0)
								{
									echo "<a href='items.php?do=approve&itemid=" . $item['ItemID'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Activate</a>";
										
								}
								echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="items.php?do=add" class="btn btn-sm btn-primary">
					<i class="fa fa-plus"></i> New Item
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Items To Show</div>';
					echo '<a href="items.php?do=add" class="btn btn-sm btn-primary">
							<i class="fa fa-plus"></i> New Item
						</a>';
				echo '</div>';

			} ?>

		<?php 
    }
    if($do == "add")
    { ?>
        <h1 class="text-center">Add New Item</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=insert" method="POST">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required"  
								placeholder="Name of The Item" />
						</div>
					</div>
					<!-- End Name Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="description" 
								class="form-control" 
								required="required"  
								placeholder="Description of The Item" />
						</div>
					</div>
					<!-- End Description Field -->
					<!-- Start Price Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="price" 
								class="form-control" 
								required="required" 
								placeholder="Price of The Item" />
						</div>
					</div>
					<!-- End Price Field -->
					<!-- Start Country Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Country</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="country" 
								class="form-control" 
								required="required" 
								placeholder="Country of Made" />
						</div>
					</div>
					<!-- End Country Field -->
					<!-- Start Status Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-10 col-md-6">
							<select name="status">
								<option value="0">...</option>
								<option value="1">New</option>
								<option value="2">Like New</option>
								<option value="3">Used</option>
								<option value="4">Very Old</option>
							</select>
						</div>
					</div>
                    <!-- End Status Field -->
                    <div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Member</label>
						<div class="col-sm-10 col-md-6">
							<select name="member">
								<option value="0">...</option>
                                <?php
                                $stmt = $con-> prepare("SELECT * FROM users");
                                $stmt ->execute();
                                $users = $stmt -> fetchAll();
                                foreach($users as $user){
                                echo "<option value=' ". $user['UserID'] . " '>  " . $user['UserName'] . "</option>"  ;
                                }

								?>
							</select>
						</div>
                    </div>
                    <div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Categories</label>
						<div class="col-sm-10 col-md-6">
							<select name="category">
								<option value="0">...</option>
								<?php
									$allCats = getallitems("*", "categories", "where Parent = 0", "", "ID");
									foreach ($allCats as $cat) {
										echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
										$childCats = getallitems("*", "categories", "where Parent = {$cat['ID']}", "", "ID");
										foreach ($childCats as $child) {
											echo "<option value='" . $child['ID'] . "'>--- " . $child['Name'] . "</option>";
										}
									}
								?>

								?>
							</select>
						</div>
					</div>
				
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>

    <?php
    }
    if($do == "insert")
    {
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            echo "<h1 class='text-center'>Insert Item</h1>";
				echo "<div class='container'>";

				

				$name		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
                $status 	= $_POST['status'];
                $member 	= $_POST['member'];
                $cat 		= $_POST['category'];
				

				

				

				$formErrors = array();

				if (empty($name)) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if (empty($price)) {
					$formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
				}

				if (empty($country)) {
					$formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
				}

				if ($status == 0) {
					$formErrors[] = 'You Must Choose the <strong>Status</strong>';
				}

			
			

                foreach($formErrors as $error)
                 {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				

                if (empty($formErrors)) 
                {
                    $stmt= $con ->prepare("INSERT INTO items (Name,Description,Price,CountryMade,Status,AddDate,CatID, MemberID) VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now() ,:zcat, :zmember)");
                    $stmt->execute(array(

						'zname' 	=> $name,
						'zdesc' 	=> $desc,
						'zprice' 	=> $price,
						'zcountry' 	=> $country,
                        'zstatus' 	=> $status,
                        'zcat'		=> $cat,
						'zmember'	=> $member,
						
                    ));
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

					redirectHome($theMsg, 'back');

                }

        }
        else 
        {

            echo "<div class='container'>";

            $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

            redirectHome($theMsg);

            echo "</div>";

        }

        echo "</div>";
 

	}
	elseif ($do == 'edit') {

		// Check If Get Request item Is Numeric & Get Its Integer Value

		$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

		// Select All Data Depend On This ID

		$stmt = $con->prepare("SELECT * FROM items WHERE ItemID = ?");

		// Execute Query

		$stmt->execute(array($itemid));

		// Fetch The Data

		$item = $stmt->fetch();

		// The Row Count

		$count = $stmt->rowCount();

		// If There's Such ID Show The Form

		if ($count > 0) 
		{ ?>


			<h1 class="text-center">Edit Items</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="POST">
					<input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required"  
								placeholder="Name of The Item"
								value="<?php echo $item['Name'] ?>" />
						</div>
					</div>
					<!-- End Name Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="description" 
								class="form-control" 
								required="required"  
								placeholder="Description of The Item"
								value="<?php echo $item['Description'] ?>" />
						</div>
					</div>
					<!-- End Description Field -->
					<!-- Start Price Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="price" 
								class="form-control" 
								required="required" 
								placeholder="Price of The Item"
								value="<?php echo $item['Price'] ?>" />
						</div>
					</div>
					<!-- End Price Field -->
					<!-- Start Country Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Country</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="country" 
								class="form-control" 
								required="required" 
								placeholder="Country of Made"
								value="<?php echo $item['CountryMade'] ?>" />
						</div>
					</div>
					<!-- End Country Field -->
					<!-- Start Status Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-10 col-md-6">
							<select name="status">
								<option value="1" <?php if ($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
								<option value="2" <?php if ($item['Status'] == 2) { echo 'selected'; } ?>>Like New</option>
								<option value="3" <?php if ($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
								<option value="4" <?php if ($item['Status'] == 4) { echo 'selected'; } ?>>Very Old</option>
							</select>
						</div>
					</div>
					<!-- End Status Field -->
					<!-- Start Members Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Member</label>
						<div class="col-sm-10 col-md-6">
							<select name="member">
								<?php
									$stmt = $con ->prepare("SELECT * FROM users");
									$stmt -> execute();
									$users =$stmt->fetchall();
									foreach ($users as $user) {
										echo "<option value='" . $user['UserID'] . "'"; 
										if ($item['MemberID'] == $user['UserID']) { echo 'selected'; } 
										echo ">" . $user['UserName'] . "</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!-- End Members Field -->
					<!-- Start Categories Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-10 col-md-6">
							<select name="category">
								<?php
								$stmt = $con ->prepare("SELECT * FROM categories");
								$stmt -> execute();
								$cats =$stmt->fetchall();
								foreach ($cats as $cat) {
									echo "<option value='" . $cat['ID'] . "'"; 
									if ($item['CatID'] == $cat['ID']) { echo 'selected'; } 
									echo ">" . $cat['Name'] . "</option>";
								}
									
								?>
							</select>
						</div>
					</div>
					<!-- End Categories Field -->
				
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Save Item" class="btn btn-primary btn-sm" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
				<?php
				$stmt=$con->prepare("SELECT comments.* , users.UserName AS Member
                                 FROM comments
    							INNER JOIN
                                 users
                                 ON
                                 users.UserID=comments.User_ID
								 where 
								 Item_ID=?


                                 ");
			$stmt->execute(array($itemid));
			$rows=$stmt->fetchAll();
			if (! empty($rows)) {
			?>
			<h1 class="text-center">Manage <?php echo $item['Name'] ?> Comments</h1>
				<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							
							<td>Comment</td>
							<td>Date</td>
							
                            <td>Member Name</td>
                            <td>Control</td>
						
							
						</tr>
						<?php
							foreach($rows as $row)
							{
								echo "<tr>";
							
									
									echo "<td>" . $row['Comment'] . "</td>";
									echo "<td>" . $row['CommentDate'] . "</td>";
									
									echo "<td>" . $row['Member'] . "</td>";
									echo "<td>
									<a href='comments.php?do=edit&comid=" . $row['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='comments.php?do=delete&comid=" . $row['C_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
								
								
								if ($row['Status']==0)
								{
									echo "<a 
													href='comments.php?do=activate&comid=" . $row['C_ID'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Activate</a>";
										
								}
								echo "</td>";
								echo "</tr>";

							}
						?>
						
                   </table>
                

			

				
		<?php
			}
		}

		// If There's No Such ID Show Error Message

		 else {

			echo "<div class='container'>";

			$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

			redirectHome($theMsg);

			echo "</div>";

		}	
	}		

	
		

	elseif ($do == 'update')
		{

			echo "<h1 class='text-center'>Update Item</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') 
			{

				// Get Variables From The Form

				$id 		= $_POST['itemid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country	= $_POST['country'];
				$status 	= $_POST['status'];
				$cat 		= $_POST['category'];
				$member 	= $_POST['member'];
				

				// Validate The Form

				$formErrors = array();

				if (empty($name)) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if (empty($price)) {
					$formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
				}

				if (empty($country)) {
					$formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
				}

				if ($status == 0) {
					$formErrors[] = 'You Must Choose the <strong>Status</strong>';
				}

				if ($member == 0) {
					$formErrors[] = 'You Must Choose the <strong>Member</strong>';
				}

				if ($cat == 0) {
					$formErrors[] = 'You Must Choose the <strong>Category</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					// Update The Database With This Info

					$stmt2 = $con->prepare("UPDATE 
												items 
											SET 
												Name = ?, 
												Description = ?, 
												Price = ?, 
												CountryMade = ?,
												Status = ?,
												CatID = ?,
												MemberID = ?
												
											WHERE 
												ItemID = ?");

					$stmt2->execute(array($name, $desc, $price, $country, $status, $cat, $member,$id));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . ' Record Updated</div>';
 
					redirectHome($theMsg, 'back');

				}

			} 
			else 
			{

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

			}
		}
		elseif($do =="delete")
	{
		if(isset($_GET['itemid']) && is_numeric($_GET['itemid']))
        { 
			
			echo "<h1 class='text-center'>Delete Items</h1>";
			echo "<div class='container'>";
			$check = checkItems('ItemID', 'items', $_GET['itemid']);

			if($check>0)
			{
				$stmt=$con->prepare("DELETE FROM items WHERE ItemID= :zitem");
				$stmt->bindParam("zitem",$_GET['itemid']);
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
	
	elseif($do == 'approve')
	{  
		
		if(isset($_GET['itemid']) && is_numeric($_GET['itemid']))
        {  
			
			echo "<h1 class='text-center'>	Activate item</h1>";
			echo "<div class='container'>";
			$check = checkItems('ItemID', 'items', $_GET['itemid']);
			

			if($check>0)
			{
				$stmt=$con->prepare("UPDATE items SET approve = 1 where ItemID=?");
				$stmt->execute(array($_GET['itemid']));
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

	

			
    
    	include $tpl .'footer.php';
}

 else
{
    header("location:index.php");
     
}

