<?php
     function lang($phrase)
    {
       static $lang= array (
            "message"=> "اهلا",
            "admin"=>"بالادمن"
        );
        return $lang[$phrase];
    }