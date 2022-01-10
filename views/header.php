<?php


function my_error_logging_principles(){
    ## Voit asettaa nämä myös php.ini -tiedostossa, mutta koska sandboksissa 
    ## hakemistoon ei ole pääsyä, tässä ne asetetaan ohjelmallisesti.
    ini_set('log_error', '1');
    ## Älä talleta virhelokia samaan hakemistoon sovelluksen kanssa.
    ## sovellus sijaitsee tässä /var/www -hakemistossa, joka on 
    ## web-palvelimen hallinnassa ja saattaa päätyä julkaistavaksi 
    ## webissä. Koska kirjoitusoikeutta ei ole muihin hakemistoihin, 
    ## logitus tehdään VIRHEELLISESTI web-hakemistoon, jolloin kuka vain voi
    ## ladata sen url:llä
    ini_set('error_log', 'bikerentalapplicationerrors.log');  
}

 function getNavigation(){
     return '<div>
        <a style="margin: 19px;" href="rentings.php" class="btn btn-secondary">
        Vuokraukset</a>

        <a style="margin: 19px;" href="bikes.php" class="btn btn-secondary">
        Pyörät</a>

        <a style="margin: 19px;" href="customers.php" class="btn btn-secondary">
        Asiakkaat</a>
        </div>';
 }

 function getNavigation2(){
    return '<div style="margin: 0.5em;">
            <ul class="nav nav-pills" style="padding: 0.2em; justify-content: center; border:solid; border-width: thin;">
            <li class="nav-item">
            <a class="nav-link active" href="./index.php">Koti</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="./price_list.php">Vuokrapyörät</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="./add_customer.php">Rekisteröidy asiakkaaksi</a>
            </li>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Henkilökunta</a>
            <div class="dropdown-menu">
            <a class="dropdown-item" href="./customers.php">Asiakkaat</a>
            <a class="dropdown-item" href="./bikes.php">Pyörät</a>
            <a class="dropdown-item" href="./hae_asiakas_ensin.php">Vuokraukset</a>
            <div class="dropdown-divider"></div>
            </div>
            </li>
            <li class="nav-item">
            <a class="nav-link disabled" href="#">Ota yhteyttä</a>
            </li>
            </ul>
        </div>';
}

    /**
      * Geneerinen virheviesti Bootstrap-tyyleillä muokattuna. 
      * Tiedosto otetaan mukaan sivuskripteihin include-lauseella
      * siksi käytetään snake-case nimeämistä. Sama sopisi yllä 
      * olevaan getNavigation-funktioon.
      * Huomaa, että tämä funktio ei palauta mitään, vaan tulostaa 
      * suoraan HTML:ää. Sitä pitää siis kutsua siinä kohdassa, 
      * jossa tulostus halutaan. Myös getNavigation-funktion voisi 
      * toteuttaa samoin.
    **/
 function print_status_message($status_text, $type="primary"){
     //Jos teksti on tyhjä, älä tulosta mitään.
     if ($status_text==null || $status_text==""){
         return;
     }
     //Oletuksena neutraali viesti.
     $bt_class="alert alert-primary";
     if ($type=="error"){
         $bt_class="alert alert-danger";
     }
     else if ($type=="ok"){
         $bt_class="alert alert-success";
     }
     echo '<div class="'.$bt_class.'" style="margin-top: 5vw">'.$status_text.'</div>';
 }

?>