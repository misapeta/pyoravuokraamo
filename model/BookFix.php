<?php

require_once ('views/header.php');



my_error_logging_principles();

class BookFix {

    public $id;
    public $description;
    public $fixdate;
    //Mihin kirjaan korjaus kohdistuu
    public $bookid;





    public static function checkBookFix($description, $fixdate, $bookid){
        //echo $description. " ".$fixdate." ".$bookid;
        if ($description==null || $fixdate==null || $bookid==null){
            return false;
        }
        if ($description=="" || $fixdate==""){
            return false;
        }
        if (!is_numeric($bookid)){
          return false;
        }
        $dt = DateTime::createFromFormat('Y-m-d', $fixdate);
        if ($dt==null){
            return false;
        }
        return true;
    }

}

?>