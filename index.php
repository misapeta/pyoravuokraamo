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
## taulut. Näitä ei saa olla mukana tuotantokoodissa, vaan tietokanta
## luodaan erikseen. Eli taulunluonti ei ole osa sovellusta!
#$bikeDAO->createBikesTable();
#$bikeFixDAO->createBikeFixTable();
#$customerDAO->createCustomersTable();
#$rentDAO->createRentTable();

$status_text = "";
$error_text = "";

if (isset($_POST["action"])){
   $action = $_POST["action"];

   // rivit 39- toimii ikään kuin controllerina
   if ($action == "addNewBike"){
     try {
         $p_bike_brand_name = $purifier->sanitizeHtml($_POST['brand_name']);
         $p_bike_model = $purifier->sanitizeHtml($_POST['model']);
         $p_bike_year = $purifier->sanitizeHtml($_POST['year']);
         $p_bike_type = $purifier->sanitizeHtml($_POST['type']);
         $p_bike_serial_number = $purifier->sanitizeHtml($_POST['serial_number']);
         $bike_ok=Bike::checkBike($p_bike_brand_name, $p_bike_model, $p_bike_year, $p_bike_type, $p_bike_serial_number);
        if(!$bike_ok){
          $error_text="Tarkista syötekentät";
        }
        else {
          $bike = $bikeFactory->createBike($p_bike_brand_name, $p_bike_model, $p_bike_year, $p_bike_type, $p_bike_serial_number);
          $result = $bikeDAO->addBike($bike);
          $status_text = "Pyörän lisäys onnistui";
        }
     }
     catch (Exception $e){
       error_log($e->getMessage());
       $error_text = "Pyörän lisäys epäonnistui";
     }
   }
   else if ($action == "deleteBike"){
     try {
      //Puhdista myös hidden-parametrina saadut kentät!
      $p_id = $purifier->sanitizeHtml($_POST['id']);
      //Tarkista myös hidden-parametrina saadut kentät!
      if (is_numeric($p_id)){
        $bikeFixDAO->deleteFixesFromBike($p_id);
        $result = $bikeDAO->deleteBike($p_id);
        $status_text = "Pyörä poistettiin";
      }
     }
     catch (Exception $e){
       $error_text = "Pyörän poisto epäonnistui";
     }
   }
   else if ($action == "updateBike"){
     try {
        $p_id = $purifier->sanitizeHtml($_POST['id']);
        $p_bike_brand_name = $purifier->sanitizeHtml($_POST['brand_name']);
        $p_bike_model = $purifier->sanitizeHtml($_POST['model']);
        $p_bike_year = $purifier->sanitizeHtml($_POST['year']);
        $p_bike_type = $purifier->sanitizeHtml($_POST['type']);
        $p_bike_serial_number = $purifier->sanitizeHtml($_POST['serial_number']);
        $bike_ok=Bike::checkBike($p_bike_brand_name, $p_bike_model, $p_bike_year, $p_bike_type, $p_bike_serial_number);

       
        if(!$bike_ok){
          $error_text="Tarkista syötekentät";
        }
        else if (is_numeric($p_id)){
           $bike = $bikeDAO->getBikeById($p_id);
           if ($bike==null){
              $error_text = "Päivitettävää pyörää ei löytynyt";
           }
           else {
            $bikeToUpdate = $bikeFactory->createBike($p_bike_brand_name, $p_bike_model, $p_bike_year, $p_bike_type, $p_bike_serial_number, $p_id);
             $result = $bikeDAO->updateBike($bikeToUpdate);
             $status_text = "Pyörän tiedot päivitettiin";
           }
        }
     }
     catch (Exception $e){
       error_log($e->getMessage());
       $error_text = "tietojen päivitys epäonnistui";
     }
   }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
    $head = getHead();
    echo $head;
?>
<body>
<div class="container">
<?php
  print_status_message($status_text, "ok");
  print_status_message($error_text, "error");

  $navigation2 = getNavigation2();
  $footer = getFooter();
  echo $navigation2;
?>

<h1 class="display-2">BikeRental - pyörävuokraamo</h1>        
<div class="container">
<div class="row">
    <div class="col">  
        <div class="card" style="margin: 2em;">
            <div class="card-header">Vuokrapyörävalikoima</div>
            <div class="card-body">
                <h5 class="card-title">Maastopyörät</h5>
                <p class="card-text">Tällä hetkellä vuokrattavissa olevat maastopyörät.</p>
                <a href="#">Linkki tulee myöhemmin!</a>
            </div>
            <div class="card-body">
                <h5 class="card-title">Maantiepyörät</h5>
                <p class="card-text">Tällä hetkellä vuokrattavissa olevat maantiepyörät.</p>
                <a href="#">Linkki tulee myöhemmin!</a>
            </div>
            <div class="card-body">
                <h5 class="card-title">Lasten pyörät</h5>
                <p class="card-text">Tällä hetkellä vuokrattavissa olevat lasten pyörät.</p>
                <a href="#">Linkki tulee myöhemmin!</a>
            </div>
        </div>
    </div>
    <div class="col-6" style="border: solid; border-width: medium; border-color: yellow; padding: 2em;">
      <h2>Pyörävuokraamo </h2>
        <p>Tervetuloa pyörävuokraus Tmi:n verkkosivuille. Täältä löydät vuokrapyörän itsellesi tai vaikkapa lapsillesi. Varauksen voit tehdä kätevästi tällä sivulla ylävalikon "vuokraa"-linkistä. Vuokrauksen voit tehdä myös soittamalla tai yhteydenottolomakkeella, joka löytyy ylävalikon "Ota yhteyttä"-linkistä!</p>    
        <p>Vuokraamosivustolta voi valita mieleisen pyörän ja vuokrata sen. Aloita täyttämällä asiakastietolomake klikkaamalla ylävalikosta kohtaa "Rekisteröidy asiakkaaksi".
        Toiminnallisuuden osalta koitetaan myöhemmin saada toteutettua ratkaisu, jossa asiakas voi syöttää yhteystietonsa 
        ja valita pyörän, jonka haluaa vuokrata. Valinnan jälkeen pyörälista päivittyy sellaiseksi, ettei vuokrattu pyörä ole vuokrattavien joukossa.</p>
    </div>
</div>
</div>

<?php 
   echo $footer;
?>     

</div>

</body>
</html>