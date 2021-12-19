<?php

//phpinfo();

## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.
require_once('./dao/BookDAO.php');
require_once('./dao/BookFixDAO.php');
require_once('./model/Bike.php');
require_once('./components/BookComponents.php');
require_once ('views/header.php');
require_once('utils/SanitizationService.php');
require_once('factories/BookFactory.php');


my_error_logging_principles();


$bookDAO = new BookDAO();
$bookFixDAO = new BookFixDAO();
$purifier=new SanitizationService();

$bookFactory = new BookFactory();


## Kun sivua kutsutaan ensimmäisen kerran, luodaan tarvittavat 
## taulut. Näitä ei saa olla mukana tuotantokoodissa, vaan tietokanta
##  luodaan erikseen. Eli taululuonti ei ole osa sovellusta!!!

#$bookDAO->createBooksTable();
#$bookFixDAO->createBookFixTable();

$status_text = "";
$error_text = "";

if (isset($_POST["action"])){
   $action = $_POST["action"];

   if ($action == "addNewBook"){
     try {
        $p_book_name = $purifier->sanitizeHtml($_POST['name']);
        $p_book_author = $purifier->sanitizeHtml($_POST['author']);
        $p_book_published = $purifier->sanitizeHtml($_POST['published']);
        $book_ok=Book::checkBook($p_book_name, $p_book_author, $p_book_published);
        if(!$book_ok){
          $error_text="Tarkista syötekentät";
        }
        else {
          $book = $bookFactory->createBook($p_book_name, $p_book_author, $p_book_published);
          $result = $bookDAO->addBook($book);
          $status_text = "Kirjan lisäys onnistui";
        }
     }
     catch (Exception $e){
       error_log($e->getMessage());
       $error_text = "Kirjan lisäys epäonnistui";
     }
   }
   else if ($action == "deleteBook"){
     try {
      //Puhdista myös hidden-parametrina saadut kentät!
      $p_id = $purifier->sanitizeHtml($_POST['id']);
      //Tarkista myös hidden-parametrina saadut kentät!
      if (is_numeric($p_id)){
        $bookFixDAO->deleteFixesFromBook($p_id);
        $result = $bookDAO->deleteBook($p_id);
        $status_text = "Kirja poistettiin";
      }
     }
     catch (Exception $e){
       $error_text = "Kirjan poisto epäonnistui";
     }
   }
   else if ($action == "updateBook"){
     try {
        $p_book_name = $purifier->sanitizeHtml($_POST['name']);
        $p_book_author = $purifier->sanitizeHtml($_POST['author']);
        $p_book_published = $purifier->sanitizeHtml($_POST['published']);
        $p_id = $purifier->sanitizeHtml($_POST['id']);
        $book_ok=Book::checkBook($p_book_name, $p_book_author, $p_book_published, $p_id);
       
        if(!$book_ok){
          $error_text="Tarkista syötekentät";
        }
        else if (is_numeric($p_id)){
           $book = $bookDAO->getBookById($p_id);
           if ($book==null){
              $error_text = "Päivitettävää kirjaa ei löytynyt";
           }
           else {
             $book->name=$p_book_name;
             $book->author=$p_book_author;
             $book->published=$p_book_published;
             $result = $bookDAO->updateBook($book);
             $status_text = "Kirja päivitettiin";
           }
        }
     }
     catch (Exception $e){
       error_log($e->getMessage());
       $error_text = "Kirjan päivitys epäonnistui";
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
  $booksComponents = new BookComponents();
  $new_book_button = $booksComponents->getNewBookButton(); 
  echo $navigation;
  echo $new_book_button;
?>

 <h1 class="display-3">Kirjat</h1>

<?php 

   $books = $bookDAO->getBooks();
   $bookList = $booksComponents->getBooksComponent($books);
   echo $bookList;
?>     
</div>

</body>
</html>