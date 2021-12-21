<?php

require_once('./dao/CustomerDAO.php');
require_once('./model/Customer.php');
require_once('./components/CustomerComponents.php');
require_once ('views/header.php');
require_once ('views/footer.php');
require_once('utils/SanitizationService.php');

my_error_logging_principles();

$customerDAO = new CustomerDAO();
$purifier=new SanitizationService();
$customer=null;

if (isset($_POST["id"])){
    ##echo $_POST["id"];
    $id = $_POST["id"];
    $customer = $customerDAO->getCustomerById($id);  
}
else {
    //Päätetään sivun kirjoitus, kun tarvittavaa 
    //parametria ei ole annettu.
    return("<html>Asiakkaan id puuttuu</html>");
}
$navigation = getNavigation();
$customersComponents = new CustomerComponents();
$customer_form = $customersComponents->getEditCustomerForm($customer);

?>


<!DOCTYPE html><html lang="en">
<head>  
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<title>BikeRental</title>  
<body>  
<div class="container">    
 <?php
        
        echo $navigation
        
 ?>
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Muokkaa asiakkaan tietoja</h1>
        <?php
        echo $customer_form
        
        ?>
    </div> 
</div>  
</body></html>