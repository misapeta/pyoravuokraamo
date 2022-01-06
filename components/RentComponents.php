<?php

class RentComponents {

    ## Lapsitietue -> anna parametrina isätietueen (asiakkaan) id, jolle vuokraus lisätään.
    ## Viiteavain: ei näytetä asiakkaalle, vaan on hidden 
    function getRentForm($customerid){
        $form_str='<div>  
                <form method="post" action="rentings.php"> 
                <div class="form-group"> 
                <input type="hidden" name="customerid" value="'.$customerid.'">
                <label for="price">Sovittu vuokrahinta:</label> 
                <input type="text" class="form-control" name="price" /> </div>
                <div class="form-group"> 
                <label for="rentdate">Vuokrauksen alkaminen:</label> 
                <input type="date" class="form-control" min="2022-01-01" max="2050-31-11" name="rentdate" required="required" />
                <label for="returndate">Vuokrauksen päättyminen:</label> 
                <input type="date" class="form-control" min="2022-01-01" max="2050-31-11" name="returndate" required="required" />
                <button type="submit" class="btn btn-primary" name="action" value="addNewRent">Lisää vuokraus</button>
                </form>
            </div>';
        return $form_str;
    }

    ## Painike, jolla pääsee lisäämään valitulle asiakkaalle vuokrauksen. 
    function getNewRentButton($customerid){
        return '<form method="post" action="add_rent.php"> 
                <input type="hidden" name="customerid" value="'.$customerid.'">
                <div class="form-group"> 
                <button type="submit" class="btn btn-primary">Uusi vuokraus</button>
                </form>
            </div>';
    }

    ## Painike, jolla pääsee asiakkaiden listaussivulta katsomaan yhden asiakkaan vuokraushistorian. 
    function getRentalHistoryButton($customerid){
        return '<form method="post" action="rentings.php"> 
                <input type="hidden" name="customerid" value="'.$customerid.'">
                <div class="form-group"> 
                <button type="submit" class="btn btn-primary">Näytä vuokraushistoria</button>
                </form>
            </div>';
    }

    ## Tulostetaan sivulle asiakkaan tekemät vuokraukset
    function getRentComponent($rents){
        ##echo print_r($rents);
        $rents_str='<table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vuokrauksen alku</th>
                        <th>Vuokrauksen loppu</th>
                        <th>Hinta</th>
                        <th style="vertical-align: center">Poisto (Huom: Poistoa ei voi perua!)</th>
                    </tr>
                </thead>
                <tbody>';
                ##Rivi kerrallaan vuokraukset
                foreach($rents as $rent){  
                    $rents_str=$rents_str.'<tr>
                        <td>'.$rent->id.'</td>
                        <td>'.$rent->rentdate.'</td>
                        <td>'.$rent->returndate.'</td>
                        <td>'.$rent->price.'</td>
                        <td>
                            <form action="rentings.php" method="post"> 
                            <input type="hidden" name="id" value="'.$rent->id.'">
                            <input type="hidden" name="customerid" value="'.$rent->customerid.'">
                            <button class="btn btn-danger" name="action" value="deleteRent" type="submit">Poista vuokraustieto</button> 
                            </form>
                        </td>
                    </tr>';
                };
                $rents_str=$rents_str.'</tbody></table>';
                return $rents_str;
    }
}