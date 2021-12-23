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
        $this->dbconnection=$dbutils->connectToDatabase();
    }

    public $dbconnection;
    public $customerFactory;

    function addCustomer($customer) {
        try {
            $sql = 'INSERT INTO CUSTOMERS (first_name, last_name, birth_date, email, phone) VALUES(:first_name, :last_name, :birth_date, :email, :phone)';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindParam('first_name', $customer->first_name, PDO::PARAM_STR);
            $sth->bindParam('last_name', $customer->last_name, PDO::PARAM_STR);
            $sth->bindParam('birth_date', $customer->birth_date, PDO::PARAM_STR);
            $sth->bindParam('email', $customer->email, PDO::PARAM_STR);
            $sth->bindParam('phone', $customer->phone, PDO::PARAM_STR);
            $result = $sth->execute();

            return $result;

        } catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when adding a customer!"));
        }
    }

    function updateCustomer($customer){
        try { 
            ##echo print_r($customer);
            $sql = 'UPDATE CUSTOMERS SET first_name=:first_name, last_name=:last_name, birth_date=:birth_date, email=:email, phone=:phone WHERE id= :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindParam('id', $customer->id, PDO::PARAM_INT);
            $sth->bindParam('first_name', $customer->first_name, PDO::PARAM_STR);
            $sth->bindParam('last_name', $customer->last_name, PDO::PARAM_STR);
            $sth->bindParam('birth_date', $customer->birth_date, PDO::PARAM_STR);
            $sth->bindParam('email', $customer->email, PDO::PARAM_STR);
            $sth->bindParam('phone', $customer->phone, PDO::PARAM_STR);
            $result = $sth->execute();
            
            return $result;

        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when updating a customer!"));
        }
    }

    /**
     * Varaudutaan, ettei lapsitietueita jää ennen poistoa. 
     * asiakkaan poisto voi epäonnistua, mikäli lapsitietuita.
     **/
    function deleteCustomer($id){
        try { 
            $sql = 'DELETE FROM CUSTOMERS  
            WHERE id = :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $id));
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when deleting a customer!"));
        }
    }


    /**
     * Palauttaa listan Customer -objekteista. Konvertoidaan jokainen rivi
     * objektiksi Customer-luokan konstruktorin avulla (joka hoitaa konvertoinnin)
     **/
    function getCustomers(){
        try {
            $sql = 'SELECT * FROM CUSTOMERS';  
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute();
            $customer_rows = $sth->fetchAll();
            $customers = [];
            foreach ($customer_rows as $customer_row) {
            ##echo print_r($customer_row);
            array_push($customers, $this->customerFactory->createCustomerFromArray($customer_row));
            }
            return $customers;

        }
        catch (PDOException $exception) {
            error_log($exception->getMessage());
            throw (new Exception("Error when getting customers!"));
        }
    }

    function getCustomerById($id){
        try { 
            $sql = 'SELECT * FROM CUSTOMERS  
            WHERE id = :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $id));
            $customer_row = $sth->fetch();
            if ($customer_row==null){
                //echo "customer was null";
                return null;
            }
            //Kun rivejä on vain yksi, muunnetaan se customer-objektiksi ennen palautusta.
            else {
                ##echo print_r($customer_row);
                return $this->customerFactory->createCustomerFromArray($customer_row);
            }
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when getting customer by id!"));
        }
    }
    
    function createCustomersTable(){
        try {
            $dbutils=new DBUtils();
            $db=$dbutils->connectToDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE TABLE CUSTOMERS
            (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name VARCHAR(200) NOT NULL,
                last_name VARCHAR(200) NOT NULL,
                birth_date VARCHAR(200) NOT NULL,
                email VARCHAR(200) NOT NULL,
                phone VARCHAR(200) NOT NULL,
                description TEXT
                );";
            $db->exec($sql);
        }
        catch (Exception $exception){
            //älä liitä mukaan varsinaista virhe tekstiä $exception->getMessage()
            //koska se voi sisältää liikaa tietoa tietokannan rakenteesta 
            //joka ei kuulu loppukäyttäjälle. POISTA SIIS ENNEN TOSIKÄYTTÖÄ
            error_log($exception->getMessage());
            throw (new Exception('Creating database failed. '.$exception->getMessage()));
        }
    }
}
?>