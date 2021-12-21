<?php

class Customer {

    public $first_name;
    public $last_name;
    public $birth_date;
    public $email;
    public $phone;
    
   /**
    * Tarkistetaan pakolliset kentät. Siinä vaiheessa, kun 
    * kirjaa lisätään, sillä ei vielä ole id-kenttää.
   */
    public static function checkCustomer($first_name, $last_name, $birth_date, $email, $phone){
        if ( $first_name == null ||$last_name == null || $birth_date == null || $email == null || $phone == null){
            return false;
        }
        if ( $first_name == "" ||$last_name == "" || $birth_date == "" || $email == "" || $phone == ""){
            return false;
        }
        if (!is_numeric($phone)){
            return false;
        }
        return true;
    }
}
?>