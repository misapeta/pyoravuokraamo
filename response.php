<?php
## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.
require_once('./dao/CustomerDAO.php');
require_once('./model/Customer.php');
require_once('./components/CustomerComponents.php');
require_once ('views/header.php');
require_once ('views/footer.php');
require_once('utils/SanitizationService.php');
require_once('factories/CustomerFactory.php');

my_error_logging_principles();

$customerDAO = new CustomerDAO();
$purifier=new SanitizationService();
$customerFactory = new CustomerFactory();

$status_text = "";
$error_text = "";

if (isset($_POST["action"])){
    $action = $_POST["action"];
 
    // rivit 39- toimii ikään kuin controllerina
    if ($action == "addNewCustomer"){
      try {
          $p_customer_first_name  = $purifier->sanitizeHtml($_POST['first_name']);
          $p_customer_last_name  = $purifier->sanitizeHtml($_POST['last_name']);
          $p_customer_birth_date  = $purifier->sanitizeHtml($_POST['birth_date']);
          $p_customer_email = $purifier->sanitizeHtml($_POST['email']);
          $p_customer_phone = $purifier->sanitizeHtml($_POST['phone']);
          $customer_ok=Customer::checkCustomer($p_customer_first_name , $p_customer_last_name , $p_customer_birth_date , $p_customer_email , $p_customer_phone );
         if(!$customer_ok){
           $error_text="Tarkista syötekentät";
         }
         else {
           $customer = $customerFactory->createCustomer($p_customer_first_name , $p_customer_last_name , $p_customer_birth_date , $p_customer_email , $p_customer_phone );
           $result = $customerDAO->addCustomer($customer);
           $status_text = "Asiakkaan ".$p_customer_first_name." ".$p_customer_last_name." lisäys onnistui";
         }
      }
      catch (Exception $e){
        error_log($e->getMessage());
        $error_text = "Asiakkaan lisäys epäonnistui";
      }
    }
    else if ($action == "deleteCustomer"){
      try {
        //Puhdista myös hidden-parametrina saadut kentät!
        $p_id = $purifier->sanitizeHtml($_POST['id']);
        //Tarkista myös hidden-parametrina saadut kentät!
        if (is_numeric($p_id)){
          $result = $customerDAO->deleteCustomer($p_id);
          $status_text = "Asiakas poistettiin listalta";
        }
      }
      catch (Exception $e){
        $error_text = "Poisto epäonnistui";
      }
    }
    else if ($action == "updateCustomer"){
      try {
         $p_id = $purifier->sanitizeHtml($_POST['id']);
         $p_customer_first_name  = $purifier->sanitizeHtml($_POST['first_name']);
         $p_customer_last_name  = $purifier->sanitizeHtml($_POST['last_name']);
         $p_customer_birth_date  = $purifier->sanitizeHtml($_POST['birth_date']);
         $p_customer_email = $purifier->sanitizeHtml($_POST['email']);
         $p_customer_phone = $purifier->sanitizeHtml($_POST['phone']);
         $customer_ok=Customer::checkCustomer($p_customer_first_name , $p_customer_last_name , $p_customer_birth_date , $p_customer_email , $p_customer_phone );

         if(!$customer_ok){
           $error_text="Tarkasta syötekentät. Yritä uudelleen klikkaamalla yläpalkista kohtaa 'Rekisteröidy asiakkaaksi'";
         }
         else if (is_numeric($p_id)){
            $customer = $customerDAO->getCustomerById($p_id);
            if ($customer==null){
               $error_text = "Päivitettävää asiakasta ei löytynyt";
            }
            else {
              $customerToUpdate = $customerFactory->createCustomer($p_customer_first_name , $p_customer_last_name , $p_customer_birth_date , $p_customer_email , $p_customer_phone, $p_id);
              $result = $customerDAO->updateCustomer($customerToUpdate);
              $status_text = "Asiakkaan tiedot päivitettiin";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/css/app.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
  <?php


    $navigation2 = getNavigation2();
    $footer = getFooter();
    $customerComponents = new CustomerComponents();
    $new_customer_button = $customerComponents->getNewCustomerButton(); 

    echo $navigation2;
  ?>

  <?php 
    print_status_message($status_text, "ok");
    print_status_message($error_text, "error");
    #$customers = $customerDAO->getCustomers();
    #$customerList = $customerComponents->getCustomerComponent($customers);
    #echo $customerList;
    echo "<a href='index.php'>Palaa etusivulle</a>";
    echo $footer;
  ?>
</div>
</body>
</html>