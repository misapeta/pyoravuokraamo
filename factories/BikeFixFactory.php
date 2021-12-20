<?php


class BikeFixFactory {

public function createBikeFixFromArray($array_bike_fix) { 
            $bike_fix = new BikeFix();

            if (isset($array_bike_fix['id']))
                $bike_fix->id=$array_bike_fix['id'];
            if (isset($array_bike_fix['description']))    
                $bike_fix->description=$array_bike_fix['description'];
            if (isset($array_bike_fix['fixdate'])){
                $bike_fix->bookid=$array_bike_fix['fixdate'];
            }
            ## Korjaukseen sisältyy myös viittaus pyörään,
            ## jolle huoltotoimenpide lisätään.
            if (isset($array_bike_fix['bookid']))
                $bike_fix->bookid=$array_bike_fix['bookid'];
            return $bike_fix;
       }

    public function createBikeFix($description, $fixdate, $bookid) {
            $bike_fix = new BikeFix();
            $bike_fix->bookid = $bookid;
            $bike_fix->fixdate = $fixdate;
            $bike_fix->description = $description;
            return $bike_fix;
    }
}


?>