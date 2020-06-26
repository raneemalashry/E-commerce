<?php
    session_start();
    $pagetitle = 'Categories';
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
        if($do=='manage')
        {
            $sort="ASC";
            $sort_array=array("ASC", "DESC");
            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array))
            {
                $sort= $_GET['sort'];
            }
            $stmt = $con -> prepare("SELECT * FROM categories ORDER BY Ordering $sort ");
            $stmt -> execute();
            $cats = $stmt -> fetchAll();
            if (! empty($cats)) 
            {?>
    
                    <h1 class="text-center">Manage Categories</h1>
                    <div class="container categories">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-edit"></i> Manage Categories
                                <div class="option pull-right">
                                    <i class="fa fa-sort"></i> Ordering: [
                                    <a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href="?sort=ASC">Asc</a> | 
                                    <a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href="?sort=DESC">Desc</a> ]
                                    <i class="fa fa-eye"></i> View: [
                                    <span class="active" data-view="full">Full</span> |
                                    <span data-view="classic">Classic</span> ]
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php
                                    foreach($cats as $cat)
                                    {
                                        echo "<div class='cat'>";
                                            echo "<div class='hidden-buttons'>";
                                                echo "<a href='categories.php?do=edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                                echo "<a href='categories.php?do=delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                                            echo "</div>";
                                            echo "<h3>" . $cat['Name'] . '</h3>';
                                            echo "<div class='full-view'>";
                                                echo "<p>"; if($cat['Describtion'] == '') { echo 'This category has no description'; } else { echo $cat['Describtion']; } echo "</p>";
                                                if($cat['Visibility'] == 1) { echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> Hidden</span>'; } 
                                                if($cat['AllowComment'] == 1) { echo '<span class="commenting cat-span"><i class="fa fa-close"></i> Comment Disabled</span>'; }
                                                if($cat['AllowAds'] == 1) { echo '<span class="advertises cat-span"><i class="fa fa-close"></i> Ads Disabled</span>'; }  
                                            echo "</div>"; 
                                            // Get Child Categories
							      	$childCats = getallitems("*", "categories", "where Parent = {$cat['ID']}", "", "ID", "ASC");
							      	if (! empty($childCats)) {
								      	echo "<h4 class='child-head'>Child Categories</h4>";
								      	echo "<ul class='list-unstyled child-cats'>";
										foreach ($childCats as $c) {
											echo "<li class='child-link'>
												<a href='categories.php?do=edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a>
												<a href='categories.php?do=delete&catid=" . $c['ID'] . "' class='showdelete confirm'> Delete</a>
											</li>";
										}
										echo "</ul>";
									}

								echo "</div>";
								echo "<hr>";
                                    } ?>

                                
                            </div>
                        </div>
                       
                  
                    <a class="add-category btn btn-primary" href="categories.php?do=add"><i class="fa fa-plus"></i> Add New Category</a>
                                </div>
                    

         <?php
            } 
             else {

                 echo '<div class="container">';
                    echo '<div class="nice-message">There\'s No Categories To Show</div>';
                    echo '<a href="categories.php?do=add" class="btn btn-primary">
                     <i class="fa fa-plus"></i> New Category
                    </a>';
                echo '</div>';
                    }?>




            
        <?php         
        }
        if($do =="add")
        {
            ?>
            <h1 class="text-center">Add Category</h1>
                    <div class= "container">
                        <form class="form-horizontal" action="?do=insert" method="POST" >
                            
                            
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="name" class="form-control"  autocomplete="off" required="required" placeholder="Enter your name" />
                                </div>
                            </div>
                           
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-6">
                                    
                                    <input type="text" name="description" class="form-control"  placeholder="Add the Description" />
                                  
                                </div>
                            </div>
                           
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Parent?</label>
						<div class="col-sm-10 col-md-6">
							<select name="parent">
								<option value="0">None</option>
								<?php 
									$allCats = getAllitems("*", "categories", "where Parent = 0", "", "ID", "ASC");
									foreach($allCats as $cat) {
										echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
									}
								?>
							</select>
						</div>
					</div>
				
                         
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Order</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="order"  class="form-control" placeholder="Enter The Order" />
                                </div>
                            </div>
                           
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Visibility</label>
                                <div class="col-sm-10 col-md-6">
                                   <input id="com-yes" type="radio" name="visibility" value="0" checked/>
                                   <label for="com-yes">YES</label>
                                   <input id="com-no" type="radio" name="visibility" value="1"/>
                                   <label for="com-no">NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Allow Comment</label>
                                <div class="col-sm-10 col-md-6">
                                   <input id="com-yes" type="radio" name="comment" value="0" checked/>
                                   <label for="com-yes">YES</label>
                                   <input id="com-no" type="radio" name="comment" value="1"/>
                                   <label for="com-no">NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Allow Ads</label>
                                <div class="col-sm-10 col-md-6">
                                   <input id="com-yes" type="radio" name="ads" value="0" checked/>
                                   <label for="com-yes">YES</label>
                                   <input id="com-no" type="radio" name="ads" value="1"/>
                                   <label for="com-no">NO</label>
                                </div>
                            </div>
      
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    </div>
			
            <?php
           
        }
        elseif($do =='insert')
        {
            
            if($_SERVER['REQUEST_METHOD']='POSt')
            { 
                echo '<h1 class="text-center">Insert Category</h1>';
                echo "<div class='container'>";
            
                $name=$_POST['name'];
                $descrip=$_POST['description'];
                $parent=$_POST['parent'];
                $order=$_POST['order'];
                $visibile=$_POST['visibility'];
                $comm=$_POST['comment'];
                $ads=$_POST['ads'];
               
                    $row= checkitems('Name','categories',$name);
                    if($row == 1)
                    {
                        $theMsg = '<div class="alert alert-danger">Sorry This Category Is Exist</div>';
    
                            redirectHome($theMsg, 'back');
                    }
                    else
                    {
    
                        $stmt=$con->prepare("INSERT INTO categories (Name,Describtion,Parent,Ordering,Visibility,AllowComment,AllowAds) VALUES(:zname, :zdescription,:zparent,:zorder,:zvisibility,:zcomment,:zads)");
                        $stmt->execute(array(
                            "zname"=>$name,
                            "zdescription"=>$descrip,
                            ":zparent"=>$parent,
                            "zorder"=>$order ,
                            "zvisibility" =>$visibile,
                            "zcomment" =>$comm,
                            "zads"=>$ads
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
        }
        elseif($do =="edit")
        {
            if(isset($_GET['catid']) && is_numeric($_GET['catid']))
            {
                $stmt=$con->prepare('SELECT * FROM categories WHERE ID=? LIMIT 1');
                $stmt->execute(array($_GET['catid']));
                $cat=$stmt->fetch();
                $count=$stmt->rowcount();
                if($count>0)
                {?>
                    <h1 class="text-center">Edit Category</h1>
                            <div class= "container">
                                <form class="form-horizontal" action="?do=update" method="POST" >
                                <input type="hidden" name="id" value="<?php echo $cat['ID'] ?>" />
                                    
                                    
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10 col-md-6">
                                            <input type="text" name="name" class="form-control"  required="required" placeholder="Enter your name" value="<?php echo $cat['Name']; ?>" />
                                        </div>
                                    </div>
                                
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10 col-md-6">
                                            
                                            <input type="text" name="description" class="form-control"  placeholder="Add the Description"  value="<?php echo $cat['Describtion']; ?>" />
                                        
                                        </div>
                                    </div>
                                    
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 control-label">Parent?</label>
                                        <div class="col-sm-10 col-md-6">
                                            <select name="parent">
                                                <option value="0">None</option>
                                                <?php 
                                                    $allCats = getallitems("*", "categories", "where Parent = 0", "", "ID", "ASC");
                                                    foreach($allCats as $c) {
                                                        echo "<option value='" . $c['ID'] . "'";
                                                        if ($cat['Parent'] == $c['ID']) { echo ' selected'; }
                                                        echo ">" . $c['Name'] . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
					
                                
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 control-label">Order</label>
                                        <div class="col-sm-10 col-md-6">
                                            <input type="text" name="order"  class="form-control" placeholder="Enter The Order"  value="<?php echo $cat['Ordering'] ;?>" />
                                        </div>
                                    </div>
                                
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 control-label">Visibility</label>
                                        <div class="col-sm-10 col-md-6">
                                        <input id="com-yes" type="radio" name="visibility" value="0"  <?php if ($cat['Visibility'] == 0){ echo' checked '; }?>/>
                                        <label for="com-yes">YES</label>
                                        <input id="com-no" type="radio" name="visibility" value="1"  <?php if ($cat['Visibility'] == 1){ echo' checked '; }?>/>
                                        <label for="com-no">NO</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 control-label">Allow Comment</label>
                                        <div class="col-sm-10 col-md-6">
                                        <input id="com-yes" type="radio" name="comment" value="0"  <?php if ($cat['AllowComment'] == 0){ echo' checked ';}?>/>
                                        <label for="com-yes">YES</label>
                                        <input id="com-no" type="radio" name="comment" value="1"  <?php if ($cat['AllowComment'] == 1){ echo' checked '; }?>/>
                                        <label for="com-no">NO</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 control-label">Allow Ads</label>
                                        <div class="col-sm-10 col-md-6">
                                        <input id="com-yes" type="radio" name="ads" value="0"  <?php if ($cat['AllowAds'] == 0){ echo' checked '; }?>/>
                                        <label for="com-yes">YES</label>
                                        <input id="com-no" type="radio" name="ads" value="1" <?php if ($cat['AllowAds'] == 1){ echo' checked '; }?>/>
                                        <label for="com-no">NO</label>
                                        </div>
                                    </div>
            
                                    <div class="form-group form-group-lg">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="submit" value="save category" class="btn btn-primary btn-lg" />
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                
                        
                        <?php
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
            elseif($do == "update")
            {
                echo '<h1 class="text-center">Update Member</h1>';
		        echo "<div class='container'>";
                if($_SERVER['REQUEST_METHOD']='POSt')
                { 
                    
                    $id=$_POST['id'];
                    $name=$_POST['name'];
                    $decribtion=$_POST['description'];
                    $parent=$_POST['parent'];
                    $order=$_POST['order'];
                    $visible=$_POST['visibility'];
                    $comment=$_POST['comment'];
                    $ads=$_POST['ads'];
                
                    
                
                    $stmt=$con->prepare('UPDATE categories SET Name=?, Describtion=?,Parent=?, Ordering=?, Visibility=? , AllowComment=? , AllowAds= ?  WHERE ID=?');
                    $stmt->execute(array($name,$decribtion,$parent ,$order,$visible, $comment, $ads, $id));
                        
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

                    redirectHome($theMsg, 'back');
                    
                    
                    
                    

                }
                else
                {
                    
                    $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

                    redirectHome($theMsg);
                }
                echo'</div>';
	

		
             }
             elseif($do=="delete")
             {
                if(isset($_GET['catid']) && is_numeric($_GET['catid']))
                { 
                    
                    echo "<h1 class='text-center'>Delete category</h1>";
                    echo "<div class='container'>";
                    $check = checkItems('ID', 'categories', $_GET['catid']);
        
                    if($check>0)
                    {
                        $stmt=$con->prepare("DELETE FROM categories WHERE ID= :zuser");
                        $stmt->bindParam("zuser",$_GET['catid']);
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
             
        

        
        include $tpl .'footer.php';

     

    }
       
    else
    {
        header("location:index.php");
        exit();
    }
    
   