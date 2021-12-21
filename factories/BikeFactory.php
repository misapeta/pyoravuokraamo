<?php


class BikeFactory {

     public function createBikeFromArray($array_bike) {
        $bike = new Bike();
        if (isset($array_bike['id']))
            $bike->id=$array_bike['id'];
        if (isset($array_bike['brand_name']))
            $bike->brand_name=$array_bike['brand_name'];
        if (isset($array_bike['model']))
            $bike->model=$array_bike['model'];
        if (isset($array_bike['year']))
            $bike->year=$array_bike['year'];
        if (isset($array_bike['type']))
            $bike->type=$array_bike['type'];
        if (isset($array_bike['serial_number']))
            $bike->serial_number=$array_bike['serial_number'];
            
        return $bike;
    }

      
    public function createBike($brand_name, $model, $year, $type, $serial_number, $id=null) {
            $bike = new Bike();
            $bike->id = $id;
            $bike->brand_name = $brand_name;
            $bike->model = $model;
            $bike->year = $year;
            $bike->type = $type;
            $bike->serial_number = $serial_number;
            
            return $bike;
    }

}

?>