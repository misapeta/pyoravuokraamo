<?php


class BikeFixFactory {

public function createBikeFixFromArray($array_bike_fix) { 
            $bike_fix = new BikeFix();

            if (isset($array_bike_fix['id']))
                $bike_fix->id=$array_bike_fix['id'];
            if (isset($array_bike_fix['description']))    
                $bike_fix->description=$array_bike_fix['description'];
            if (isset($array_bike_fix['fixdate'])){
                $bike_fix->bikeid=$array_bike_fix['fixdate'];
            }
            ## Korjaukseen sisältyy myös viittaus pyörään,
            ## jolle huoltotoimenpide lisätään.
            if (isset($array_bike_fix['bikeid']))
                $bike_fix->bikeid=$array_bike_fix['bikeid'];
            return $bike_fix;
       }

    public function createBikeFix($description, $fixdate, $bikeid) {
            $bike_fix = new BikeFix();
            $bike_fix->bikeid = $bikeid;
            $bike_fix->fixdate = $fixdate;
            $bike_fix->description = $description;
            return $bike_fix;
    }
}


?>