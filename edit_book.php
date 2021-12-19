<?php

require_once('./dao/BookDAO.php');
require_once('./model/Bike.php');
require_once('./components/BookComponents.php');
require_once ('views/header.php');
require_once('utils/SanitizationService.php');

//Tämä ei ole luokka, joten nämä eivät ole 
//jäsenmuuttujia, eikä niitä siksi kutsuta myöhemmin
//this-määreellä.

my_error_logging_principles();


$bookDAO = new BookDAO();
$purifier=new SanitizationService();
$book=null;

if (isset($_POST["id"])){
   ##echo $_POST["id"];
   $id = $_POST["id"];
   $book = $bookDAO->getBookById($id);  
  }
  else {
         //Päätetään sivun kirjoitus, kun tarvittavaa 
         //parametria ei ole annettu.
         return("<html>Kirjan id puuttuu</html>");
   }
  $navigation = getNavigation();
  $booksComponents = new BookComponents();
  $book_form = $booksComponents->getEditBookForm($book); 
?>




<!DOCTYPE html><html lang="en">
<head>  
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<title>Library</title>  
<body>  
<div class="container">    
 <?php
        
        echo $navigation
        
 ?>
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Muokkaa kirjaa</h1>
        <?php
        
        echo $book_form
        
        ?>
    </div> 
</div>  
</body></html>