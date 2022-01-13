<?php

## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.
require_once('./dao/BikeDAO.php');
require_once('./dao/CustomerDAO.php');
require_once('./dao/BikeFixDAO.php');
require_once('./dao/RentDAO.php');
require_once('./model/Bike.php');
require_once('./components/BikeComponents.php');
require_once ('views/header.php');
require_once ('views/head.php');
require_once ('views/footer.php');
require_once('utils/SanitizationService.php');
require_once('factories/BikeFactory.php');

my_error_logging_principles();

$bikeDAO = new BikeDAO();
$customerDAO = new CustomerDAO();
$bikeFixDAO = new BikeFixDAO();
$rentDAO = new RentDAO();
$purifier=new SanitizationService();
$bikeFactory = new BikeFactory();

## Kun sivua kutsutaan ensimmäisen kerran, luodaan tarvittavat 
## taulut (alla kommenteissa). Näitä ei saa olla mukana tuotantokoodissa, vaan tietokanta
## luodaan erikseen. Eli taulunluonti ei ole osa sovellusta!
#$bikeDAO->createBikesTable();
#$bikeFixDAO->createBikeFixTable();
#$customerDAO->createCustomersTable();
#$rentDAO->createRentTable();


?>
<!DOCTYPE html>
<html lang="en">
<?php
    $head = getHead();
    echo $head;
?>
<body>

<?php

  $navigation2 = getNavigation2();
  $footer = getFooter();
  echo $navigation2;
?>
  

<div class="container-fluid">
  <h1 class="display-3">BikeRental - Maastopyörien vuokrausjärjestelmä</h1>
  
  
  <div style="border: solid; border-width: medium; border-color: green; padding: 1em; margin-left: 5em; margin-right: 5em;">
    <h2>Pyörävuokraamo </h2>
    <p>Tervetuloa Pyörävuokraus Tmi:n verkkosivuille. Täältä löydät vuokrapyörän itsellesi tai vaikkapa lapsillesi. Varauksen voit tehdä kätevästi tällä sivulla ylävalikon "vuokraa"-linkistä. Vuokrauksen voit tehdä myös soittamalla tai yhteydenottolomakkeella, joka löytyy ylävalikon "Ota yhteyttä"-linkistä!</p>    
    <p>Vuokraamosivustolta voi valita mieleisen pyörän ja vuokrata sen. Aloita täyttämällä asiakastietolomake klikkaamalla ylävalikosta kohtaa "Rekisteröidy asiakkaaksi".
        Toiminnallisuuden osalta koitetaan myöhemmin saada toteutettua ratkaisu, jossa asiakas voi syöttää yhteystietonsa ja valita pyörän, jonka haluaa vuokrata. Valinnan jälkeen pyörälista päivittyy sellaiseksi, ettei vuokrattu pyörä ole vuokrattavien joukossa.</p>
  </div>
  
  <?php 
    echo $footer;
   ?>     

</div>

</body>
</html>