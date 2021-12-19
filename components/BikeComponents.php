<?php

require('./components/BikeFixComponents.php');

class BikeComponents {

     function __construct() {
        $this->bikeFixComponents=new BikeFixComponents();
    }

public $bikeFixComponents;

/**
 *  Funktio palauttaa vakio-merkkijonon. Määrittelemällä se staattiseksi
  * funktio toimii nopeasti. 
**/

static function getBikeForm(){
    $form_str='<div>  
            <form method="post" action="index.php"> 
            <div class="form-group"> 
            <label for="brand_name">Merkki *</label> 
            <input type="text" class="form-control" name="brand_name" /> </div>
            <div class="form-group"> 
            <label for="model">Malli *</label> 
            <input type="text" class="form-control" name="model" /> </div>
            <div class="form-group"> 
            <label for="year">Valmistusvuosi</label> 
            <input type="text" class="form-control" name="year" /> </div>
            <div class="form-group"> 
            <label for="type">Pyörän tyyppi</label> 
            <input type="text" class="form-control" name="type" /> </div>
            <div class="form-group"> 
            <label for="serial_number">Sarjanumero</label> 
            <input type="text" class="form-control" name="serial_number" /> </div>
            
            <button type="submit" name="action" value="addNewBook" class="btn btn-primary">Lisää kirja</button>
            </form>
        </div>';

    return $form_str;
}

function getEditBikeForm($bike){
    ##echo print_r($book);
    $form_str='<div>  
            <form method="post" action="index.php"> 
            <div class="form-group"> 
            <input type="hidden" name="id" value ="'.$bike->id.'">
            <input type="hidden" name="action" value ="updateBook">
            <div class="form-group"> 
            <label for="brand_name">Merkki *</label> 
            <input type="text" class="form-control" name="brand_name" value="'.$bike->brand_name.'" /> </div>
            <div class="form-group"> 
            <label for="model">Malli *</label> 
            <input type="text" class="form-control" name="model" value="'.$bike->model.'" /> </div>
            <div class="form-group"> 
            <label for="year">Valmistusvuosi</label> 
            <input type="text" class="form-control" name="year" value="'.$bike->year.'" /> </div>
            <div class="form-group"> 
            <label for="type">Pyörän tyyppi</label> 
            <input type="text" class="form-control" name="type" value="'.$bike->type.'" /> </div>
            <div class="form-group"> 
            <label for="serial_number">Sarjanumero</label> 
            <input type="text" class="form-control" name="serial_number" value="'.$bike->serial_number.'" /> </div>
            
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
                $newBookFixButton = $this->bikeFixComponents->getBookFixesButton($book->id);
                $editBookButton = $this->getEditBookButton($book->id);
                $deleteBookButton = $this->getDeleteBookButton($book->id);

                $books_str=$books_str.'<tr>
                    <td>'.$book->id.'</td>
                    <td>'.$book->brand_name.'</td>
                    <td>'.$book->model.'</td>
                    <td>'.$book->year.'</td>
                    <td>'.$book->type.'</td>
                    <td>'.$book->serial_number.'</td>
                    
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