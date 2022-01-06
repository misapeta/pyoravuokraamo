<?php
require_once('./dao/RentDAO.php');
require_once('./dao/CustomerDAO.php');
require_once('./model/Renting.php');
require_once('./components/RentComponents.php');
require_once('utils/SanitizationService.php');
require_once('factories/RentFactory.php');
require_once('factories/CustomerFactory.php');
require_once ('views/header.php');
require_once ('views/footer.php');

my_error_logging_principles();

$rentDAO = new RentDAO();
$customerDAO = new CustomerDAO();
$rentFactory = new RentFactory();
$customerFactory = new CustomerFactory();
$purifier=new SanitizationService();


## Tarkista isset-funktiolla, että kyseinen parametri on asetettu.
## customerid pitää olla aina asetettu, jotta voidaan hakea oikean asiakkaan vuokraukset.
if (isset($_POST["customerid"])){
    $customerid = $purifier->sanitizeHtml($_POST["customerid"]);
    ## Saatu tieto, kehen nämä vuokraukset liittyvät.
    ## haetaan tietokannasta kyseisen asiakkaan nimi sivulla näytettäväksi.
    $customer = $customerDAO->getCustomerById($customerid);
    
    if ($customer!=null){
      ## Jos löytyi, otsikossa näytetään asiakkaan sukunimi.
      ##echo print_r($customer);
      ##Huom! muuttuja on voimassa koko loppudokumentin ajan.
      $customerName=$customer->last_name;
    }
    else {
     ## ei jatketa pidemmälle.
     echo ("<html><body>Virhe: Asiakasta ei löydy</body></html>");
     return;
  }
  }
  else {
     ## ei jatketa pidemmälle.
     echo ("<html><body>Virhe: Asiakas id puuttuu</body></html>");
     return;
  }
  
  ## Lue valittu toimenpide. action on asetettu hidden-tyyppisessä
  ## lomakekentässä, koska kysymys on numeerisesta viiteavainarvosta, jota loppu-
  ## käyttäjä ei muokkaa. 
  
  $status_text = "";
  $error_text = "";
  
  
  if (isset($_POST["action"])){
     $action = $_POST["action"]; 
  
      if ($action == "addNewRent"){
        try {
          $p_price = $purifier->sanitizeHtml($_POST['price']);
          $p_rentdate = $purifier->sanitizeHtml($_POST['rentdate']);
          $p_returndate = $purifier->sanitizeHtml($_POST['returndate']);
          $p_customer_id = $purifier->sanitizeHtml($_POST['customerid']);
  
          $rent_ok = Renting::checkRent($p_price, $p_rentdate, $p_returndate, $p_customer_id);
  
          if (!$rent_ok){
            $error_text = "Tarkista syötekentät";
          }
          else {
            $rent = $rentFactory->createRent($p_price, $p_rentdate, $p_returndate, $p_customer_id);
            $result = $rentDAO->addRent($rent);
          }
         }
        catch (Exception $e){
          $error_text="Vuokrauksen lisääminen epäonnistui";
        }
      }
      else if ($action == "deleteRent"){
        try {
          $p_id = $purifier->sanitizeHtml($_POST['id']);
          //Ei anneta tietoa käyttäjälle epäonnistumisesta, koska se paljastaisi tietoa sovelluksen toiminnasta.
          //koska jos alla oleva ei pidä paikkansa, käyttäjä on yrittänyt jotain luvatonta, 
          //eli muokannut hidden-kenttää itse. 
          if (is_numeric($p_id)){
            $result = $rentDAO->deleteRent($p_id);
          }
        }
        catch (Exception $e){
          $error_text = "Vuokratoimenpiteen poistaminen epäonnistui";
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
  print_status_message($status_text, "ok");
  print_status_message($error_text, "error");
  $navigation = getNavigation();
  $footer = getFooter();
  $rentComponents = new RentComponents();
  $new_rent_button = $rentComponents->getNewRentButton($customerid);
  echo $navigation;
  echo $new_rent_button;
?>

 <h1 class="display-3">Asiakkaan vuokraukset</h1>

<?php 

   $rents = $rentDAO->getRents($customerid);
   $rentList = $rentComponents->getRentComponent($rents);
   echo $rentList;
   echo $footer;
?>

</div>
</body>
</html>