<?php

class RentFactory {

    public function createRentFromArray($array_rent) { 
            $rent = new Renting();

            if (isset($array_rent['id']))
                $rent->id=$array_rent['id'];
            if (isset($array_rent['price']))    
                $rent->price=$array_rent['price'];
            if (isset($array_rent['rentdate'])){
                $rent->rentdate=$array_rent['rentdate'];
            }
            if (isset($array_rent['returndate'])){
                $rent->returndate=$array_rent['returndate'];
            }
            ## Vuokraukseen sisältyy viite asiakkaaseen,
            ## jolle toimenpide lisätään.
            if (isset($array_rent['customerid']))
                $rent->customerid=$array_rent['customerid'];
            return $rent;
    }

    public function createRent($price, $rentdate, $returndate, $customerid) {
            $rent = new Renting();
            $rent->price = $price;
            $rent->rentdate = $rentdate;
            $rent->returndate = $returndate;
            $rent->customerid = $customerid;
            return $rent;
    }
}
?>