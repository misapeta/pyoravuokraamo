<?php
require_once ('views/header.php');
require_once ('views/footer.php');

class CustomerComponents {

/**
 *  Funktio palauttaa vakio-merkkijonon. Määrittelemällä se staattiseksi
  * funktio toimii nopeasti. 
**/
static function getCustomerForm(){
    $form_str='<div>  
            <form method="post" action="customers.php"> 
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
            <button type="submit" name="action" value="addNewCustomer" class="btn btn-primary">Lisää tiedot</button>
            </form>
        </div>';

    return $form_str;
}

function getEditCustomerForm($customer){
    ##echo print_r($customer);
    $form_str='<div>  
            <form method="post" action="customers.php"> 
            <div class="form-group"> 
            <input type="hidden" name="id" value ="'.$customer->id.'">
            <input type="hidden" name="action" value ="updateCustomer">
            <div class="form-group"> 
            <label for="first_name">Etunimi *</label> 
            <input type="text" class="form-control" name="first_name" value="'.$customer->first_name.'" /> </div>
            <div class="form-group"> 
            <label for="last_name">Sukunimi *</label> 
            <input type="text" class="form-control" name="last_name" value="'.$customer->last_name.'" /> </div>
            <div class="form-group"> 
            <label for="birth_date">Valmistusvuosi</label> 
            <input type="text" class="form-control" name="birth_date" value="'.$customer->birth_date.'" /> </div>
            <div class="form-group"> 
            <label for="email">Sähköposti </label> 
            <input type="text" class="form-control" name="email" value="'.$customer->email.'" /> </div>
            <div class="form-group"> 
            <label for="phone">Puhelin </label> 
            <input type="text" class="form-control" name="phone" value="'.$customer->phone.'" /> </div>
            
            <button type="submit" class="btn btn-primary">Tallenna muutos</button>
            </form>
        </div>';

    return $form_str;
}


function getNewCustomerButton(){
    return '<a style="margin: 19px;" href="add_customer.php" class="btn btn-primary">
        Lisää uusi asiakas</a>';
}

function getEditCustomerButton($customerid){
    return '<form action="edit_customer.php" method="post"> 
            <input type="hidden" name="id" value="'.$customerid.'">
            <button class="btn btn-secondary" type="submit">Muokkaa</button> 
            </form>';
 }
 
 function getDeleteCustomerButton($customerid){
     return '<form action="index.php" method="post">
             <input type="hidden" name="id" value="'.$customerid.'">
             <button class="btn btn-danger" name="action" value="deleteCustomer" type="submit">Poista</button> 
             </form>';
 }
 
 
function getCustomerComponent($customers){
    ##echo print_r($bikes);

    
    $customers_str='<table class="table table-striped">
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
                $editCustomerButton = $this->getEditCustomerButton($cust->id);
                $deleteCustomerButton = $this->getDeleteCustomerButton($cust->id);

                $customers_str=$customers_str.'<tr>
                    <td>'.$cust->id.'</td>
                    <td>'.$cust->first_name.'</td>
                    <td>'.$cust->last_name.'</td>
                    <td>'.$cust->birth_date.'</td>
                    <td>'.$cust->email.'</td>
                    <td>'.$cust->phone.'</td>
                    <td>'.$editCustomerButton.'</td>
                    <td>'.$deleteCustomerButton.'</td>
                </tr>';
            };
                $customers_str=$customers_str.'</tbody></table>';
                return $customers_str;
}


##getNewCustomerButton

}
?>