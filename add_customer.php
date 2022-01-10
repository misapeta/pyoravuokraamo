<?php

require_once('./components/CustomerComponents.php');
require_once ('views/header.php');
require_once ('views/head.php');
require_once ('views/footer.php');


my_error_logging_principles();

$navigation2 = getNavigation2();
$footer = getFooter();
$head = getHead();

$customer_form = CustomerComponents::getCustomerForm(); 
?>

<!DOCTYPE html>
<html lang="en">
<?php    
    echo $head;
?>
<body>  
<div class="container">    
    <?php
        echo $navigation2;        
    ?>

    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Lisää asiakastiedot</h1>
        <?php        
            echo $customer_form;
            echo $footer;
        ?>
    </div> 
</div>  
</body>
</html>