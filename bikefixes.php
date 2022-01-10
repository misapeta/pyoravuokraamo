<?php
require_once('./dao/BikeFixDAO.php');
require_once('./dao/BikeDAO.php');
require_once('./model/BikeFix.php');
require_once('./components/BikeFixComponents.php');
require_once('utils/SanitizationService.php');
require_once('factories/BikeFixFactory.php');
require_once('factories/BikeFactory.php');
require_once ('views/header.php');
require_once ('views/head.php');
require_once ('views/footer.php');

my_error_logging_principles();

$bikeFixDAO = new BikeFixDAO();
$bikeDAO = new BikeDAO();
$bikeFixFactory = new BikeFixFactory();
$bikeFactory = new BikeFactory();
$purifier=new SanitizationService();

## Tarkista isset-funktiolla, että kyseinen parametri on 
## asetettu. bikeid pitää olla aina asetettu, jotta voidaan 
## hakea oikean pyörän huollot.
if (isset($_POST["bikeid"])){
  $bikeid = $purifier->sanitizeHtml($_POST["bikeid"]);
  ## Olemme saaneet tiedon, mihin pyörään nämä huollot liittyvät.
  ## haetaan tietokannasta kyseisen pyörän nimi sivulla näytettäväksi.
  $bike = $bikeDAO->getBikeById($bikeid);
  
  if ($bike!=null){
    ## Jos löytyi, otsikossa näytetään pyörän nimi.
    ##echo print_r($bike);
    ##Huom! muuttuja on voimassa koko loppudokumentin ajan.
    $bikeName=$bike->brand_name;
  }
  else {
   ## ei jatketa pidemmälle.
   echo ("<html><body>Virhe: Pyörää ei löydy</body></html>");
   return;
}
}
else {
   ## ei jatketa pidemmälle.
   echo ("<html><body>Virhe: Pyörän id puuttuu</body></html>");
   return;
}

## Lue valittu toimenpide. action on asetettu hidden-tyyppisessä
## lomakekentässä, koska kysymys on numeerisesta viiteavainarvosta, jota loppu-
## käyttäjä ei muokkaa. 

$status_text = "";
$error_text = "";


if (isset($_POST["action"])){
   $action = $_POST["action"]; 

    if ($action == "addNewBikeFix"){
      try {
        $p_description = $purifier->sanitizeHtml($_POST['description']);
        $p_fixdate = $purifier->sanitizeHtml($_POST['fixdate']);
        $p_bike_id = $purifier->sanitizeHtml($_POST['bikeid']);

        $bikefix_ok = BikeFix::checkBikeFix($p_description, $p_fixdate, $p_bike_id);

        if (!$bikefix_ok){
          $error_text = "Tarkista syötekentät";
        }
        else {
          $bikeFix = $bikeFixFactory->createBikeFix($p_description, $p_fixdate, $p_bike_id);
          $result = $bikeFixDAO->addBikeFix($bikeFix);
        }
       }
      catch (Exception $e){
        $error_text="Huoltotoimenpiteen lisääminen epäonnistui";
      }
    }
    else if ($action == "deleteBikeFix"){
      try {
        $p_id = $purifier->sanitizeHtml($_POST['id']);
        //Ei anneta tietoa käyttäjälle epäonnistumisesta, koska 
        //se paljastaisi tietoa sovelluksen toiminnasta.
        //koska jos alla oleva ei pidä paikkansa, käyttäjä on
        //yrittänyt jotain luvatonta, eli muokannut hidden-kenttää 
        //itse. 
        if (is_numeric($p_id)){
          $result = $bikeFixDAO->deleteBikeFix($p_id);
        }
      }
      catch (Exception $e){
        $error_text = "Huoltotoimenpiteen poistaminen epäonnistui";
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
  $bikeFixComponents = new BikeFixComponents();
  $new_bike_fix_button = $bikeFixComponents->getNewBikeFixButton($bikeid); 
  echo $navigation2;
  echo $new_bike_fix_button;
?>


 <h1 class="display-5">Huoltotoimenpiteet pyörälle <?php echo $bikeName ?></h1>

<?php 
   $bikeFixes = $bikeFixDAO->getBikeFixes($bikeid);
   $bikeFixList = $bikeFixComponents->getBikeFixesComponent($bikeFixes);
   echo $bikeFixList;
   echo $footer;
?>     

</div>
</body>
</html>