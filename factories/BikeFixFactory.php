<?php


class BikeFixFactory {

public function createBookFixFromArray($array_book_fix) { 
            $book_fix = new BikeFix();

            if (isset($array_book_fix['id']))
                $book_fix->id=$array_book_fix['id'];
            if (isset($array_book_fix['description']))    
                $book_fix->description=$array_book_fix['description'];
            if (isset($array_book_fix['fixdate'])){
                $book_fix->bookid=$array_book_fix['fixdate'];
            }
            ## Korjaukseen sisältyy myös viittaus kirjaan,
            ## jolle korjaus lisätään.
            if (isset($array_book_fix['bookid']))
                $book_fix->bookid=$array_book_fix['bookid'];
            return $book_fix;
       }

    public function createBookFix($description, $fixdate, $bookid) {
            $book_fix = new BikeFix();
            $book_fix->bookid = $bookid;
            $book_fix->fixdate = $fixdate;
            $book_fix->description = $description;
            return $book_fix;
    }
}


?>