<?php


class BikeFactory {

     public function createBikeFromArray($array_book) {
        $book = new Bike();
        if (isset($array_book['id']))
            $book->id=$array_book['id'];
        if (isset($array_book['brand_name']))
            $book->brand_name=$array_book['brand_name'];
        if (isset($array_book['model']))
            $book->model=$array_book['model'];
        if (isset($array_book['year']))
            $book->year=$array_book['year'];
        if (isset($array_book['type']))
            $book->type=$array_book['type'];
        if (isset($array_book['serial_number']))
            $book->serial_number=$array_book['serial_number'];
            
        return $book;
    }

      
    public function createBook($brand_name, $model, $year, $type, $serial_number, $id=null) {
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