<?php 
    function lang ($phrase)
    {
        static $lang = array(
            #nav bar link
            "HOME_ADMIN" => "Home",
            "CATEGORIES" => "Categories",
            "ITEMS" => "Items",
            "COMMENTS" => "Comments",
            "MEMBERS" =>"Members",

        
            

        );
        return $lang[$phrase];
        
    }
?>