<?php

require_once('./dao/RentDAO.php');
require_once('./dao/CustomerDAO.php');
require_once('./model/Renting.php');
require_once('./components/RentComponents.php');
require_once('utils/SanitizationService.php');
require_once('factories/RentFactory.php');
require_once('factories/CustomerFactory.php');
require_once ('views/header.php');
require_once ('views/head.php');
require_once ('views/footer.php');

my_error_logging_principles();

$rentDAO = new RentDAO();
$customerDAO = new CustomerDAO();
$rentFactory = new RentFactory();
$rentComponents = new RentComponents();
$customerFactory = new CustomerFactory();
$purifier=new SanitizationService();
$head = getHead();
$navigation2 = getNavigation2();
$footer = getFooter();

?>

<!DOCTYPE html>
<html lang="en">
<?php
    echo $head;
?>
<body>
<div>
  <?php
    echo $navigation2;
    
  ?>
</div>

<div class="container"> 
<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-5" style="color: red; margin: 1em;">Hae ensin asiakas, jonka vuokraushistoriaa haluat tarkastella</h1>
        <p>Valitse ensin asiakas, jonka vuokraushistoriaa haluat tarkastella! <a href="./customers.php">Siirry asiakkaan valintaan</a></p>
    </div>
    <?php
    echo $footer;
    
  ?>
</div>  
</body>
</html>