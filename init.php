<?php

    include "admin/connect.php";
    /*routes*/
    $sessionuser = '';
	
	if (isset($_SESSION['user'])) {
        $sessionuser = $_SESSION['user'];
        
	}
    
    $tpl="includes/templates/";
    $css ="layout/css/";
    $js="layout/js/";
    $lang ="includes/languages/";
    $func= "includes/functions/";

    /*include important files */


    include $func . "functions.php";
    include $lang ."english.php";
    include $tpl ."header.php"; 
