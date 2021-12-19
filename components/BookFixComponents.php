<?php


class BookFixComponents {


## Kun lisätään lapsitietue, pitää antaa parametrina isätietueen
## tässä tapauksessa kirjan id, jolle korjaus lisätään. Koska se 
## on viiteavain, sitä ei näytetä asiakkaalle, vaan käytetään 
## hidden tyyppistä kenttää.

function getBookFixForm($bookid){
    $form_str='<div>  
            <form method="post" action="bookfixes.php"> 
            <div class="form-group"> 
            <input type="hidden" name="bookid" value="'.$bookid.'">
            <label for="name">Kuvaus:</label> 
            <input type="text" class="form-control" name="description" /> </div>
            <div class="form-group"> 
            <label for="fixdate">Korjauspvm (vvvv-kk-pp):</label> 
            <input type="text" class="form-control" name="fixdate" /> </div>
            <button type="submit" class="btn btn-primary" name="action" value ="addNewBookFix">Lisää korjaus</button>
            </form>
        </div>';
    return $form_str;
}


function getNewBookFixButton($bookid){
    return '<form method="post" action="add_book_fix.php"> 
            <input type="hidden" name="bookid" value="'.$bookid.'">
            <div class="form-group"> 
            <button type="submit" class="btn btn-primary">Lisää korjaus</button>
            </form>
        </div>';
}

## Painike, jolla pääsee kirjalistaussivulta katsomaan yhden
## kirjan korjauksia. 

function getBookFixesButton($bookid){
    return '<form method="post" action="bookfixes.php"> 
            <input type="hidden" name="bookid" value="'.$bookid.'">
            <div class="form-group"> 
            <button type="submit" class="btn btn-primary">Näytä korjaukset</button>
            </form>
        </div>';
}

function getBookFixesComponent($bookFixes){
    ##echo print_r($books);
    $book_fixes_str='<table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fix date</th>
                    <th>Description</th>
                    <th style="vertical-align: center">Toimenpiteet</th>
                </tr>
            </thead>
            <tbody>';
            foreach($bookFixes as $bookFix){  
                $book_fixes_str=$book_fixes_str.'<tr>
                    <td>'.$bookFix->id.'</td>
                    <td>'.$bookFix->fixdate.'</td>
                    <td>'.$bookFix->description.'</td>
                    <td>
                        <form action="bookfixes.php" method="post"> 
                        <input type="hidden" name="id" value="'.$bookFix->id.'">
                        <input type="hidden" name="bookid" value="'.$bookFix->bookid.'">
                        <button class="btn btn-danger" name="action" value="deleteBookFix" type="submit">Poista</button> 
                        </form>
                    </td>
                </tr>';
            };
                $book_fixes_str=$book_fixes_str.'</tbody></table>';
                return $book_fixes_str;
}
}