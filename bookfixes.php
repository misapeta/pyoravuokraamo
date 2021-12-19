<?php


require_once('./dao/BookFixDAO.php');
require_once('./dao/BookDAO.php');
require_once('./model/BookFix.php');
require_once('./components/BookFixComponents.php');
require_once('utils/SanitizationService.php');
require_once('factories/BookFixFactory.php');
require_once('factories/BookFactory.php');

require_once ('views/header.php');

my_error_logging_principles();


$bookFixDAO = new BookFixDAO();
$bookDAO = new BookDAO();
$bookFixFactory = new BookFixFactory();
$bookFactory = new BookFactory();
$purifier=new SanitizationService();

## Tarkista isset-funktiolla, että kyseinen parametri on 
## asetettu. bookid pitää olla aina asetettu, jotta voidaan 
## hakea oikean kirjan korjaukset.


if (isset($_POST["bookid"])){
  $bookid = $purifier->sanitizeHtml($_POST["bookid"]);
  ## Olemme saaneet tiedon, mihin kirjaan nämä korjayket liittyvät.
  ## haetaan tietokannasta kyseisen kirjan nimi sivulla näytettäväksi.
  $book = $bookDAO->getBookById($bookid);
  
  if ($book!=null){
    ## Jos kirja löytyi, otsikossa näytetään kirjan nimi.
    ##echo print_r($book);
    ##Huom! muuttuja on voimassa koko loppudokumentin ajan.
    $bookName=$book->name;
  }
  else {
   ## ei jatketa pidemmälle.
   echo ("<html><body>Virhe: Kirjaa ei löydy</body></html>");
   return;
}
}
else {
   ## ei jatketa pidemmälle.
   echo ("<html><body>Virhe: Kirjan id puuttuu</body></html>");
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

        $bookfix_ok = BookFix::checkBookFix($p_description, $p_fixdate, $p_book_id);

        if (!$bookfix_ok){
          $error_text = "Tarkista syötekentät";
        }
        else {
          $bookFix = $bookFixFactory->createBookFix($p_description, $p_fixdate, $p_book_id);
          $result = $bookFixDAO->addBookFix($bookFix);
        }
       }
      catch (Exception $e){
        $error_text="Korjauksen lisääminen epäonnistui";
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
          $result = $bookFixDAO->deleteBookFix($p_id);
        }
      }
      catch (Exception $e){
        $error_text = "Korjauksen poistaminen epäonnistui";
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
  $bookFixComponents = new BookFixComponents();
  $new_book_fix_button = $bookFixComponents->getNewBookFixButton($bookid); 
  echo $navigation;
  echo $new_book_fix_button;
?>


 <h1 class="display-5">Kirjakorjaukset kirjalle <?php echo $bookName ?></h1>

<?php 

   $bookFixes = $bookFixDAO->getBookFixes($bookid);
   $bookFixList = $bookFixComponents->getBookFixesComponent($bookFixes);
   echo $bookFixList;
?>     
</div>

</body>
</html>