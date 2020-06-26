<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    include "connect.php";

    /*routes*/
   
    
    $tpl="includes/templates/";
    $css ="layout/css/";
    $js="layout/js/";
    $lang ="includes/languages/";
    $func= "includes/functions/";

    /*include important files */


    include $func . "functions.php";
    include $lang ."english.php";
    include $tpl ."header.php";
  

    /* put nav bar on all pages execpt index */

    if (!isset($nonavbar)) { include $tpl . 'navbar.php'; }
	