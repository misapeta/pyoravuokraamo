<?php
require_once ('views/header.php');


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
            <input type="text" class="form-control" name="type" /> </div>
            <div class="form-group"> 
            <label for="serial_number">Sarjanumero</label> 
            <input type="text" class="form-control" name="serial_number" /> </div>
            
            <button type="submit" name="action" value="addNewBike" class="btn btn-primary">Lisää pyörä</button>
            </form>
        </div>';

    return $form_str;
}
}
?>