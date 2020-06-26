<?php

function getitems($where, $value, $approve = NULL)
{
    global $con ;
    if($approve== NULL)
    {
        $sql ='AND approve = 1';

    }
    else
    {
        $sql= NULL;
    }
    $getitems= $con ->prepare("SELECT * FROM ITEMS WHERE $where=? $sql ORDER BY ItemID DESC");
    $getitems -> execute(array($value));
    $items=$getitems->fetchAll();
    return $items;

}
function checkUserStatus($user) {

    global $con;

    $stmtx = $con->prepare("SELECT 
                                UserName, RegStatus 
                            FROM 
                                users 
                            WHERE 
                                UserName = ? 
                            AND 
                                RegStatus = 0");

    $stmtx->execute(array($user));

    $status = $stmtx->rowCount();

    return $status;

}
function getallitems( $field ,$table, $where = NULL,$and=NULL, $orderby, $ordering="DESC")
{
    global $con ;
   
    $getall= $con ->prepare("SELECT $field FROM $table $where $and ORDER BY $orderby $ordering");
    $getall->execute();
    $all=$getall->fetchAll();
    return $all;

}







function get_title()
{
    GLOBAL $pagetitle;
    if(isset($pagetitle))
    {
       
        echo $pagetitle;
    }
    else
    {
        echo "default";
    }
}

function redirectHome($theMsg, $url = null, $seconds = 3) {

    if ($url === null) {

        $url = 'index.php';

        $link = 'Homepage';

    } else {

        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

            $url = $_SERVER['HTTP_REFERER'];

            $link = 'Previous Page';

        } else {

            $url = 'index.php';

            $link = 'Homepage';

        }

    }

    echo $theMsg;

    echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";

    header("refresh:$seconds;url=$url");

    exit();

}
function checkitems($sel, $fro ,$value)
{
    global $con;
    $statement= $con->prepare("SELECT $sel FROM $fro WHERE $sel=?");
    $statement->execute(array($value));
    $count=$statement->rowCount();
    return $count;


}
function countitems($item,$table)
{
    global $con;
    $stmt2= $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
     
    return $stmt2->fetchColumn();

}
function getlatest ($select , $from ,$order,$limit=5)
{
    global $con;
    $stmt2=$con->prepare("SELECT $select FROM $from ORDER BY $order DESC LIMIT $limit");
    $stmt2->execute();

    return $stmt2 -> fetchAll();
}