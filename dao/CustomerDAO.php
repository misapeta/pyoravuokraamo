<?php


## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.

require_once('utils/DBUtils.php');
require_once('model/Customer.php');
require_once ('views/header.php');
require_once ('views/footer.php');
require_once('factories/CustomerFactory.php');

my_error_logging_principles();

class CustomerDAO {

    
    function __construct() {
        #print "CUSTOMERDAO constructor\n";
        $dbutils=new DBUtils();
        $this->customerFactory = new CustomerFactory();
        $this->dbconnection=$dbutils->connectToDatabaseCustomer();
    }

    public $dbconnection;
    public $bikeFactory;
}
 
?>