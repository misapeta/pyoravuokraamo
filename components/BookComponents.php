<?php

require('./components/BookFixComponents.php');

class BookComponents {

     function __construct() {
        $this->bookFixComponents=new BookFixComponents();
    }

public $bookFixComponents;

/**
 *  Funktio palauttaa vakio-merkkijonon. Määrittelemällä se staattiseksi
  * funktio toimii nopeasti. 
**/

static function getBookForm(){
    $form_str='<div>  
            <form method="post" action="index.php"> 
            <div class="form-group"> 
            <label for="name">Nimi *</label> 
            <input type="text" class="form-control" name="name" /> </div>
            <div class="form-group"> 
            <label for="author">Kirjailija *</label> 
            <input type="text" class="form-control" name="author" /> </div>
            <div class="form-group"> 
            <label for="price">Julkaisuvuosi</label> 
            <input type="text" class="form-control" name="published" /> </div>
            <button type="submit" name="action" value="addNewBook" class="btn btn-primary">Lisää kirja</button>
            </form>
        </div>';

    return $form_str;
}

function getEditBookForm($book){
    ##echo print_r($book);
    $form_str='<div>  
            <form method="post" action="index.php"> 
            <div class="form-group"> 
            <input type="hidden" name="id" value ="'.$book->id.'">
            <input type="hidden" name="action" value ="updateBook">
            <label for="name">Nimi *</label> 
            <input type="text" class="form-control" name="name" value="'.$book->name.'"/> </div>
            <div class="form-group"> 
            <label for="author">Kirjailija *</label> 
            <input type="text" class="form-control" name="author" value="'.$book->author.'"/> </div>
            <div class="form-group"> 
            <label for="price">Julkaisuvuosi</label> 
            <input type="text" class="form-control" name="published" value="'.$book->published.'"/> </div>
            <button type="submit" class="btn btn-primary">Tallenna</button>
            </form>
        </div>';

    return $form_str;
}

function getNewBookButton(){
    return '<a style="margin: 19px;" href="add_book.php" class="btn btn-primary">
        Lisää uusi kirja</a>';
}

function getEditBookButton($bookid){
   return '<form action="edit_book.php" method="post"> 
           <input type="hidden" name="id" value="'.$bookid.'">
           <button class="btn btn-secondary" type="submit">Muokkaa</button> 
           </form>';
}

function getDeleteBookButton($bookid){
    return '<form action="index.php" method="post">
            <input type="hidden" name="id" value="'.$bookid.'">
            <button class="btn btn-danger" name="action" value="deleteBook" type="submit">Poista</button> 
            </form>';
}

function getBooksComponent($books){
    ##echo print_r($books);

    
    $books_str='<table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nimi</th>
                    <th>Kirjailija</th>
                    <th>Julkaisuvuosi</th>
                    <th colspan=3 style="vertical-align: center">Toimenpiteet</th>
                </tr>
            </thead>
            <tbody>';
            foreach($books as $book){  
                ## Jokaisella kirjalla on oma painike korjauksen lisäämistä
                ## varten.
                $newBookFixButton = $this->bookFixComponents->getBookFixesButton($book->id);
                $editBookButton = $this->getEditBookButton($book->id);
                $deleteBookButton = $this->getDeleteBookButton($book->id);

                $books_str=$books_str.'<tr>
                    <td>'.$book->id.'</td>
                    <td>'.$book->name.'</td>
                    <td>'.$book->author.'</td>
                    <td>'.$book->published.'</td>
                    <td>'.$newBookFixButton.'</td>
                    <td>
                    <td>'.$editBookButton.'</td>
                    <td>
                        '.$deleteBookButton.'
                    </td>
                </tr>';
            };
                $books_str=$books_str.'</tbody></table>';
                return $books_str;
}
}