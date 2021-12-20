<?php


class BikeFixComponents {


## Kun lisätään lapsitietue, pitää antaa parametrina isätietueen
## tässä tapauksessa pyörän id, jolle korjaus lisätään. Koska se 
## on viiteavain, sitä ei näytetä asiakkaalle, vaan käytetään 
## hidden tyyppistä kenttää.
function getBikeFixForm($bikeid){
    $form_str='<div>  
            <form method="post" action="bikefixes.php"> 
            <div class="form-group"> 
            <input type="hidden" name="bookid" value="'.$bikeid.'">
            <label for="name">Kuvaus:</label> 
            <input type="text" class="form-control" name="description" /> </div>
            <div class="form-group"> 
            <label for="fixdate">Huoltopvm (vvvv-kk-pp):</label> 
            <input type="text" class="form-control" name="fixdate" /> </div>
            <button type="submit" class="btn btn-primary" name="action" value ="addNewBikeFix">Lisää huoltotoimenpide</button>
            </form>
        </div>';
    return $form_str;
}

## Painike, jolla pääsee lisäämään valitulle pyörälle huoltotoimenpiteitä. 
function getNewBikeFixButton($bikeid){
    return '<form method="post" action="add_bike_fix.php"> 
            <input type="hidden" name="bookid" value="'.$bikeid.'">
            <div class="form-group"> 
            <button type="submit" class="btn btn-primary">Lisää huoltotoimenpide</button>
            </form>
        </div>';
}

## Painike, jolla pääsee pyörien listaussivulta katsomaan yhden pyörän huoltotoimenpiteitä. 
function getBikeFixesButton($bikeid){
    return '<form method="post" action="bikefixes.php"> 
            <input type="hidden" name="bookid" value="'.$bikeid.'">
            <div class="form-group"> 
            <button type="submit" class="btn btn-primary">Näytä huoltohistoria</button>
            </form>
        </div>';
}

## Tulostetaan sivulle pyörälle tehdyt huoltotoimenpiteet
function getBikeFixesComponent($bikeFixes){
    ##echo print_r($books);
    $bike_fixes_str='<table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Huolto tehty</th>
                    <th>Kuvaus huoltotoimenpiteistä</th>
                    <th style="vertical-align: center">Poisto (Huom: Poistoa ei voi perua!)</th>
                </tr>
            </thead>
            <tbody>';
            ##Rivi kerrallaan huoltotoimenpiteet
            ## TODO: fixdate String-tyyppiseksi!
            foreach($bikeFixes as $bikeFix){  
                $bike_fixes_str=$bike_fixes_str.'<tr>
                    <td>'.$bikeFix->id.'</td>
                    <td>'.$bikeFix->fixdate.'</td>
                    <td>'.$bikeFix->description.'</td>
                    <td>
                        <form action="bikefixes.php" method="post"> 
                        <input type="hidden" name="id" value="'.$bikeFix->id.'">
                        <input type="hidden" name="bookid" value="'.$bikeFix->bookid.'">
                        <button class="btn btn-danger" name="action" value="deleteBikeFix" type="submit">Poista</button> 
                        </form>
                    </td>
                </tr>';
            };
                $bike_fixes_str=$bike_fixes_str.'</tbody></table>';
                return $bike_fixes_str;
}
}