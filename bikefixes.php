<?php


require_once('./dao/BookFixDAO.php');
require_once('./dao/BookDAO.php');
require_once('./model/BikeFix.php');
require_once('./components/BikeFixComponents.php');
require_once('utils/SanitizationService.php');
require_once('factories/BookFixFactory.php');
require_once('factories/BookFactory.php');

require_once ('views/header.php');

my_error_logging_principles();


$bikeFixDAO = new BookFixDAO();
$bookDAO = new BookDAO();
$bookFixFactory = new BookFixFactory();
$bookFactory = new BookFactory();
$purifier=new SanitizationService();

## Tarkista isset-funktiolla, että kyseinen parametri on 
## asetettu. bikeid pitää olla aina asetettu, jotta voidaan 
## hakea oikean pyörän huollot.


if (isset($_POST["bookid"])){
  $bookid = $purifier->sanitizeHtml($_POST["bookid"]);
  ## Olemme saaneet tiedon, mihin pyörään nämä huollot liittyvät.
  ## haetaan tietokannasta kyseisen pyörän nimi sivulla näytettäväksi.
  $book = $bookDAO->getBookById($bookid);
  
  if ($book!=null){
    ## Jos löytyi, otsikossa näytetään pyörän nimi.
    ##echo print_r($book);
    ##Huom! muuttuja on voimassa koko loppudokumentin ajan.
    $bookName=$book->brand_name;
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

    if ($action == "addNewBookFix"){
      try {
        $p_description = $purifier->sanitizeHtml($_POST['description']);
        $p_fixdate = $purifier->sanitizeHtml($_POST['fixdate']);
        $p_book_id = $purifier->sanitizeHtml($_POST['bookid']);

        $bookfix_ok = BikeFix::checkBikeFix($p_description, $p_fixdate, $p_book_id);

        if (!$bookfix_ok){
          $error_text = "Tarkista syötekentät";
        }
        else {
          $bookFix = $bookFixFactory->createBookFix($p_description, $p_fixdate, $p_book_id);
          $result = $bikeFixDAO->addBookFix($bookFix);
        }
       }
      catch (Exception $e){
        $error_text="Huoltotoimenpiteen lisääminen epäonnistui";
      }
    }
    else if ($action == "deleteBookFix"){
      try {
        $p_id = $purifier->sanitizeHtml($_POST['id']);
        //Ei anneta tietoa käyttäjälle epäonnistumisesta, koska 
        //se paljastaisi tietoa sovelluksen toiminnasta.
        //koska jos alla oleva ei pidä paikkansa, käyttäjä on
        //yrittänyt jotain luvatonta, eli muokannut hidden-kenttää 
        //itse. 
        if (is_numeric($p_id)){
          $result = $bikeFixDAO->deleteBookFix($p_id);
        }
      }
      catch (Exception $e){
        $error_text = "Huoltotoimenpiteen poistaminen epäonnistui";
      }
    }
}



?>
<html>
<head>
    <meta charset="utf-8">
    <title>Library</title>
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
  $bikeFixComponents = new BikeFixComponents();
  $new_book_fix_button = $bikeFixComponents->getNewBookFixButton($bookid); 
  echo $navigation;
  echo $new_book_fix_button;
?>


 <h1 class="display-5">Huoltotoimenpiteet pyörälle <?php echo $bookName ?></h1>

<?php 

   $bikeFixes = $bikeFixDAO->getBookFixes($bookid);
   
   $bookFixList = $bikeFixComponents->getBikeFixesComponent($bikeFixes);
   echo $bookFixList;
?>     
</div>

</body>
</html>