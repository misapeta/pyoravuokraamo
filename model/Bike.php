<?php

class Bike {

    public $id;
    public $brand_name;
    public $model;
    public $year;
    public $type;
    public $serial_number;
    
   /**
     * Tarkistetaan pakolliset kentät. Siinä vaiheessa kun 
      *kirjaa lisätään, sillä ei vielä ole id-kenttää.
   */
    public static function checkBike($brand_name, $model, $year, $type, $serial_number){
        if ( $brand_name == null || $model == null || $year == null || $type == null || $serial_number == null){
            return false;
        }
        if ( $brand_name == "" || $model == "" || $year == "" || $type == "" || $serial_number == ""){
            return false;
        }
        if (!is_numeric($year) || $year < 2000 ){
            return false;
        }

        return true;
    }

    
}

?>