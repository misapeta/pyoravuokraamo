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
<head>  
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<title>BikeRental</title>
</head>
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
</div>  
</body>
</html>