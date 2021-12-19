<?php


class BookFactory {

     public function createBookFromArray($array_book) {
        $book = new Book();
        if (isset($array_book['id']))
            $book->id=$array_book['id'];
        if (isset($array_book['name']))    
            $book->name=$array_book['name'];
        if (isset($array_book['author']))
            $book->author=$array_book['author'];
        if (isset($array_book['published']))
            $book->published=$array_book['published'];
        return $book;
    }
      
    public function createBook($name, $author, $published, $id=null) {
            $book = new Book();
            $book->id = $id;
            $book->name = $name;
            $book->author = $author;
            $book->published= $published;
            return $book;
    }
}

?>