<?php
require_once ('views/header.php');
require_once ('views/footer.php');

class CustomerComponents {

    // poista alta kommentit ja etene, jos lisätään muokkaustoiminto asiakkaalle
     #function __construct() {
     #   $this->customerFixComponents=new CustomerFixComponents();
     #}

/**
 *  Funktio palauttaa vakio-merkkijonon. Määrittelemällä se staattiseksi
  * funktio toimii nopeasti. 
**/
static function getCustomerForm(){
    $form_str='<div>  
            <form method="post" action="index.php"> 
            <div class="form-group"> 
            <label for="first_name">Etunimi *</label> 
            <input type="text" class="form-control" name="first_name" /> </div>
            <div class="form-group"> 
            <label for="last_name">Sukunimi *</label> 
            <input type="text" class="form-control" name="last_name" /> </div>
            <div class="form-group"> 
            <label for="birth_date">Syntymäaika *</label> 
            <input type="text" class="form-control" name="birth_date" /> </div>
            <div class="form-group"> 
            <label for="email">Sähköposti </label> 
            <input type="text" class="form-control" name="email" /> </div>
            <div class="form-group"> 
            <label for="phone">Puhelin</label> 
            <input type="text" class="form-control" name="phone" /> </div>
            <div class="form-group">            
            <button type="submit" name="action" value="addNewBike" class="btn btn-primary">Lisää tiedot</button>
            </form>
        </div>';

    return $form_str;
}

function getNewCustomerButton(){
    return '<a style="margin: 19px;" href="add_bike.php" class="btn btn-primary">
        Lisää uusi asiakas</a>';
}

function getEditCustomerButton($customerid){
    return '<form action="edit_bike.php" method="post"> 
            <input type="hidden" name="id" value="'.$customerid.'">
            <button class="btn btn-secondary" type="submit">Muokkaa</button> 
            </form>';
 }
 
 function getDeleteCustomerButton($customerid){
     return '<form action="index.php" method="post">
             <input type="hidden" name="id" value="'.$customerid.'">
             <button class="btn btn-danger" name="action" value="deleteBike" type="submit">Poista</button> 
             </form>';
 }
 
 
function getCustomerComponent($customers){
    ##echo print_r($bikes);

    
    $bikes_str='<table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Etunimi</th>
                    <th>Sukunimi</th>
                    <th>Syntymäaika</th>
                    <th>Sähköposti</th>
                    <th colspan=3 style="vertical-align: center">Puhelin</th>
                </tr>
            </thead>
            <tbody>';
            foreach($customers as $cust){  
                ## Jokaisella pyörällä on oma painike huoltotoimenpiteen lisäämistä varten.
                $editBikeButton = $this->getEditCustomerButton($cust->id);
                $deleteBikeButton = $this->getDeleteCustomerButton($cust->id);

                $bikes_str=$bikes_str.'<tr>
                    <td>'.$cust->id.'</td>
                    <td>'.$cust->first_name.'</td>
                    <td>'.$cust->last_name.'</td>
                    <td>'.$cust->birth_date.'</td>
                    <td>'.$cust->email.'</td>
                    <td>'.$cust->phone.'</td>
                    
                    <td>'.$newBikeFixButton.'</td>
                    <td>
                    <td>'.$editCustomerButton.'</td>
                    <td>
                        '.$deleteCustomerButton.'
                    </td>
                </tr>';
            };
                $bikes_str=$bikes_str.'</tbody></table>';
                return $bikes_str;
}


##getNewCustomerButton

}
?>