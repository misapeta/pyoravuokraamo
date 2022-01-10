<?php
## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.
require_once('./dao/BikeDAO.php');
require_once('./model/Bike.php');
require_once('./components/BikeComponents.php');
require_once ('views/header.php');
require_once ('views/head.php');
require_once ('views/footer.php');
require_once('utils/SanitizationService.php');
require_once('factories/BikeFactory.php');

my_error_logging_principles();

$bikeDAO = new BikeDAO();
$bikeFactory = new BikeFactory();
$head = getHead();
$navigation2 = getNavigation2();
$footer = getFooter();
$status_text = "";
$error_text = "";

?>

<!DOCTYPE html>
<html lang="en">
<?php
    echo $head;
?>
<body>
<div class="container">
  
  <?php

$bikesComponents = new BikeComponents();
echo $navigation2;
?>

<h2 class="display-5" style="color: red; margin: 1em;">Tällä hetkellä vuokrattavissa olevat pyörät</h2>
  <?php 
    print_status_message($status_text, "ok");
    print_status_message($error_text, "error");
    #$customers = $customerDAO->getCustomers();
    #$customerList = $customerComponents->getCustomerComponent($customers);
    #echo $customerList;
    $bikes = $bikeDAO->getBikes();
    $bikeList = $bikesComponents->getBikelistOnlyComponent($bikes);
    echo $bikeList;
 
    echo $footer;
  ?>
</div>
</body>
</html>