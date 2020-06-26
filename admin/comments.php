<?php 
session_start();
$pagetitle = 'comments';
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
		    
		$stmt=$con->prepare("SELECT comments.* ,items.Name AS Item_Name , users.UserName AS Member
                                 FROM comments
                                 INNER JOIN 
                                 items
                                 ON
                                 items.ItemID=comments.Item_ID
                                 INNER JOIN
                                 users
                                 ON
                                 users.UserID=comments.User_ID


                                 ");
		$stmt->execute();
		$rows=$stmt->fetchAll();
		?>
		<h1 class="text-center">Manage Comments</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>Comment</td>
							<td>Date</td>
							<td>Item Name</td>
                            <td>Member Name</td>
                            <td>Control</td>
						
							
						</tr>
						<?php
							foreach($rows as $row)
							{
								echo "<tr>";
							
									echo "<td>" . $row['C_ID'] . "</td>";
									echo "<td>" . $row['Comment'] . "</td>";
									echo "<td>" . $row['CommentDate'] . "</td>";
									echo "<td>" . $row['Item_Name'] . "</td>";
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

     elseif ($do == 'edit') 
            {

                
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

                

                $stmt = $con->prepare("SELECT * FROM comments WHERE C_ID = ?");


                $stmt->execute(array($comid));

                // Fetch The Data

                $row = $stmt->fetch();

                // The Row Count

                $count = $stmt->rowCount();

                // If There's Such ID Show The Form

                if ($count > 0) { ?>

                    <h1 class="text-center">Edit Comment</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=update" method="POST">
                            <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                            <!-- Start Comment Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Comment</label>
                                <div class="col-sm-10 col-md-6">
                                    <textarea class="form-control" name="comment"><?php echo $row['Comment'] ?></textarea>
                                </div>
                            </div>
                            <!-- End Comment Field -->
                            <!-- Start Submit Field -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Save" class="btn btn-primary btn-sm" />
                                </div>
                            </div>
                            <!-- End Submit Field -->
                        </form>
                    </div>

                <?php

                // If There's No Such ID Show Error Message

                } 
                else 
                {

                    echo "<div class='container'>";

                    $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

                    redirectHome($theMsg);

                    echo "</div>";
                 }

             } 
    elseif ($do == 'update') 
    { 

        echo "<h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {



            $comid 		= $_POST['comid'];
            $comment 	= $_POST['comment'];

         

            $stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE C_ID = ?");

            $stmt->execute(array($comment, $comid));

           
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

            redirectHome($theMsg, 'back');

        } else {

            $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

            redirectHome($theMsg);

        }

      

        } 
        elseif ($do == 'delete') { 

        echo "<h1 class='text-center'>Delete Comment</h1>";

        echo "<div class='container'>";

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

           

            $check = checkitems('C_ID', 'comments', $comid);

       

            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM comments WHERE C_ID = :zid");

                $stmt->bindParam(":zid", $comid);

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

                redirectHome($theMsg, 'back');

            } 
            else 
            {

                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

                redirectHome($theMsg);

            }

      

        } 
        elseif ($do == 'activate') 
        {

                echo "<h1 class='text-center'>Approve Comment</h1>";
                echo "<div class='container'>";

                    // Check If Get Request comid Is Numeric & Get The Integer Value Of It

                    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

                    // Select All Data Depend On This ID

                    $check = checkItemS('C_ID', 'comments', $comid);

                    // If There's Such ID Show The Form

                    if ($check > 0) {

                        $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE C_ID = ?");

                        $stmt->execute(array($comid));

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved</div>';

                        redirectHome($theMsg, 'back');

            } 
            else 
            {

                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

                redirectHome($theMsg);

            }

       

        }

    include $tpl."footer.php"; 

}
else
{
    header("location:index.php");
    exit();
}
