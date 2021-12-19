<?php

class Book {

    public $id;
    public $name;
    public $author;
    public $published;



   /**
     * Tarkistetaan pakolliset kentät. Siinä vaiheessa kun 
      *kirjaa lisätään, sillä ei vielä ole id-kenttää.
   */
    public static function checkBook($name, $author, $published){
        if ($name==null || $author==null){
            return false;
        }
        if ($name=="" || $author=""){
            return false;
        }
        if ($published!=null){
            if (!is_numeric($published)){
                return false;
        }
        }
        return true;
    }

    
}

?>