<?php

//phpinfo();

## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.
require_once('./dao/BikeDAO.php');
require_once('./dao/BikeFixDAO.php');
require_once('./model/Bike.php');
require_once('./components/BikeComponents.php');
require_once ('views/header.php');
require_once('utils/SanitizationService.php');
require_once('factories/BikeFactory.php');


my_error_logging_principles();


$bikeDAO = new BikeDAO();
$bikeFixDAO = new BikeFixDAO();
$purifier=new SanitizationService();

$bikeFactory = new BikeFactory();


## Kun sivua kutsutaan ensimmäisen kerran, luodaan tarvittavat 
## taulut. Näitä ei saa olla mukana tuotantokoodissa, vaan tietokanta
## luodaan erikseen. Eli taulunluonti ei ole osa sovellusta!
#$bikeDAO->createBikesTable();
#$bikeFixDAO->createBikeFixTable();

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
<html>
<head>
    <meta charset="utf-8">
    <title>BikeRental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container">
<?php
  print_status_message($status_text, "ok");
  print_status_message($error_text, "error");

  $navigation = getNavigation();
  $bikesComponents = new BikeComponents();
  $new_bike_button = $bikesComponents->getNewBikeButton(); 
  echo $navigation;
  echo $new_bike_button;
?>

 <h1 class="display-3">Pyörät</h1>

<?php 

   $bikes = $bikeDAO->getBikes();
   $bikeList = $bikesComponents->getBikesComponent($bikes);
   echo $bikeList;
?>     
</div>

</body>
</html>