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
  <h1 class="display-3">BikeRental - Maastopyörien vuokrausjärjestelmä</h1>
  

  <div class="card">
    <div class="card-header">
      Vuokrapyörävalikoima sisältää tällä hetkellä ainoastaan maastopyörät. 
    </div>
    <div class="card-body">
      <h5 class="card-title">Maastopyörät</h5>
      <p class="card-text">Tällä hetkellä vuokrattavissa olevat maastopyörät.</p>>
    </div>
  </div>
  
    <div class="container">
      <div class="container border border-primary">
        <div class="row">
          <div class="col-sm">
            <div class="card" style="width: 18rem;">
              <figure class="figure">
                <img src="./pictures/epic1.JPG" class="figure-img img-fluid rounded" alt="Specialized Epic Expert 2016.">
                <figcaption class="figure-caption">Tuotekuva 1</figcaption>
              </figure>
              <div class="card-body">
                <h5 class="card-title">Maastoretkille</h5>
                <p class="card-text">Maastopyörän voi pakata pidemmällekin retkelle.</p>
                <a href="./price_list.php" class="btn btn-primary">Tutustu vuokrapyöriin</a>
              </div>
            </div>
          </div>
          <div class="col-sm">
            <div class="card" style="width: 18rem;">
              <figure class="figure">
                <img src="./pictures/epic2.JPG" class="figure-img img-fluid rounded" alt="Specialized Epic Expert 2016.">
                <figcaption class="figure-caption">Tuotekuva 2</figcaption>
              </figure>
              <div class="card-body">
                <h5 class="card-title">Kisa-ajoon</h5>
                <p class="card-text">Laadukkailla maastopyörillä voit myös osallistua mtb-kilpailuihin.</p>
                <a href="./price_list.php" class="btn btn-primary">Tutustu vuokrapyöriin</a>
              </div>
            </div>
          </div>
          <div class="col-sm">
            <div class="card" style="width: 18rem;">
              <figure class="figure">
                <img src="./pictures/epic4.JPG" class="figure-img img-fluid rounded" alt="Specialized Epic Expert 2016.">
                <figcaption class="figure-caption">Tuotekuva 3</figcaption>
              </figure>
              <div class="card-body">
                <h5 class="card-title">Ryhmävuokraukset</h5>
                <p class="card-text">Vuokratessasi vähintään kolme pyörää saat alennusta 15 % normaalihinnaston hinnasta..</p>
                <a href="./price_list.php" class="btn btn-primary">Tutustu vuokrapyöriin</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<?php 
   echo $footer;
?>     


</body>
</html>