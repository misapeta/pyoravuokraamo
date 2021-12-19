<?php

require_once('./components/BikeComponents.php');
require_once ('views/header.php');

my_error_logging_principles();

$navigation = getNavigation();

$bike_form = BikeComponents::getBikeForm(); 
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
        <h1 class="display-3">Lisää vuokrapyörä</h1>
        <?php
        
        echo $bike_form
        
        ?>
    </div> 
</div>  
</body></html>