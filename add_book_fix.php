<?php

require_once('./components/BikeFixComponents.php');
require_once ('views/header.php');
require_once('utils/SanitizationService.php');

my_error_logging_principles();

  $navigation = getNavigation();
  $bikeFixComponents = new BikeFixComponents();
  $purifier=new SanitizationService();


 ## Uutta korjausta ei voida kirjata, jos ei tiedetä, mille 
 ## pyörälle se tulee. Tieto tulee bikeid-kätketyssä kentässä.
 if (isset($_POST["bookid"]))
{
  $bookid=$_POST["bookid"];
  $book_fix_form = $bikeFixComponents->getBookFixForm($bookid); 
}
else {
    ## Virhe. Puutteelliset parametrit! Lopetetaan 
    ## tähän.
    return "<html>Korjauslomaketta ei voi näyttää, 
    koska pyörän id-kenttää ei ole välitetty 
    lomakkeelle.</html>";
}
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
<div>
 <?php
        
        echo $navigation
        
 ?>
</div>
<div class="container">    
<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Lisää pyörälle korjaustoimenpide</h1>
        <?php
        
        echo $book_fix_form
        
        ?>
    </div>
</div>  
</div>  
</body></html>