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
            
            <button type="submit" name="action" value="addNewBike" class="btn btn-primary">Lisää pyörä</button>
            </form>
        </div>';

    return $form_str;
}

function getEditBikeForm($bike){
    ##echo print_r($bike);
    $form_str='<div>  
            <form method="post" action="index.php"> 
            <div class="form-group"> 
            <input type="hidden" name="id" value ="'.$bike->id.'">
            <input type="hidden" name="action" value ="updateBike">
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
            
            <button type="submit" class="btn btn-primary">Tallenna muutos</button>
            </form>
        </div>';

    return $form_str;
}

function getNewBikeButton(){
    return '<a style="margin: 19px;" href="add_bike.php" class="btn btn-primary">
        Lisää uusi pyörä</a>';
}

function getEditBikeButton($bikeid){
   return '<form action="edit_bike.php" method="post"> 
           <input type="hidden" name="id" value="'.$bikeid.'">
           <button class="btn btn-secondary" type="submit">Muokkaa</button> 
           </form>';
}

function getDeleteBikeButton($bikeid){
    return '<form action="index.php" method="post">
            <input type="hidden" name="id" value="'.$bikeid.'">
            <button class="btn btn-danger" name="action" value="deleteBike" type="submit">Poista</button> 
            </form>';
}

function getBikesComponent($bikes){
    ##echo print_r($bikes);

    
    $bikes_str='<table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nimi</th>
                    <th>Malli</th>
                    <th>Valmistusvuosi (2000->)</th>
                    <th>Tyyppi</th>
                    <th colspan=3 style="vertical-align: center">Runkonumero</th>
                </tr>
            </thead>
            <tbody>';
            foreach($bikes as $bike){  
                ## Jokaisella pyörällä on oma painike huoltotoimenpiteen lisäämistä varten.
                $newBikeFixButton = $this->bikeFixComponents->getBikeFixesButton($bike->id);
                $editBikeButton = $this->getEditBikeButton($bike->id);
                $deleteBikeButton = $this->getDeleteBikeButton($bike->id);

                $bikes_str=$bikes_str.'<tr>
                    <td>'.$bike->id.'</td>
                    <td>'.$bike->brand_name.'</td>
                    <td>'.$bike->model.'</td>
                    <td>'.$bike->year.'</td>
                    <td>'.$bike->type.'</td>
                    <td>'.$bike->serial_number.'</td>
                    
                    <td>'.$newBikeFixButton.'</td>
                    <td>
                    <td>'.$editBikeButton.'</td>
                    <td>
                        '.$deleteBikeButton.'
                    </td>
                </tr>';
            };
                $bikes_str=$bikes_str.'</tbody></table>';
                return $bikes_str;
}
}