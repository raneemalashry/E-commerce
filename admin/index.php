
<?php 
    session_start();
    $pagetitle="LOGIN";

    $nonavbar =" ";
    
    if( isset($_SESSION['username']))
    {
        header("location:dashboard.php");

    }
    include "init.php";
   
    

    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        $username=$_POST['user'];
        $password =$_POST['password'];
        $hashpass=sha1($password);
        //check if user exist in database
        $stmt=$con ->prepare("SELECT UserID , UserName , Password FROM users WHERE UserName=? AND Password=? and GroupID= 1 LIMIT 1" );
        $stmt->execute(array($username,$hashpass));
        $row = $stmt ->fetch();
        $count=$stmt->rowCount();
        if($count>0)
        {
            $_SESSION['username']=$username;
            $_SESSION['userid']=$row['UserID'];
            header('location:dashboard.php');
            exit();
           
        }
    }
?>
    <form class="login" action="<?php $_SERVER['PHP_SELF']?>" method="POST">
        <h4 class="text-center">Admin Login </h4>
        <input class="form control input-lg" type="text" name="user" placeholder="UserName" autocomplete="off"></br>
        <input  class="form control input-lg"  type="password" name="password" placeholder="password" autocomplete="new-password"> 
        <input  class="btn btn-primary  btn-block"  type="submit" value="LOGIN">
    </form>


<?php include $tpl."footer.php"; ?>