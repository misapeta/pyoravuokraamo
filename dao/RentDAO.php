<?php

require_once('utils/DBUtils.php');
require_once('model/Renting.php');
require_once ('views/header.php');
require_once('factories/RentFactory.php');

my_error_logging_principles();

class RentDAO { 

    function __construct() {
        #print "RENTDAO constructor\n";
        $dbutil=new DBUtils();
        $this->dbconnection=$dbutil->connectToDatabase();
        $this->rentFactory = new RentFactory();
    }

    public $dbconnection;
    public $rentFactory;

    function addRent($rent){
        try { 
            $sql = 'INSERT INTO RENT (price, rentdate, returndate, customerid) VALUES(:price, :rentdate, :returndate, :customerid)';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindParam('price', $rent->price, PDO::PARAM_STR, 200);
            $sth->bindParam('rentdate', $rent->rentdate, PDO::PARAM_STR, 10);
            $sth->bindParam('returndate', $rent->returndate, PDO::PARAM_STR, 10);
            $sth->bindParam('customerid', $rent->customerid, PDO::PARAM_INT);
            
            $result = $sth->execute();
            return $result;
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when adding!"));
        }
    }

    function updateRent($rent){
        try { 
            ##echo print_r($customer);
            $sql = 'UPDATE RENT SET price= :price, rentdate= :rentdate, returndate= :returndate, customerid= :customerid WHERE id= :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindValue('id', $rent->id);
            $sth->bindValue('price', $rent->price);
            $sth->bindValue('rentdate', $rent->rentdate);
            $sth->bindValue('returndate', $rent->returndate);
            $sth->bindValue('customerid', $rent->customerid);
            $result = $sth->execute();
            return $result;
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when updating a customer!"));
        }
    }


    function deleteRent($id){
        try { 
            $sql = 'DELETE FROM RENT 
            WHERE id = :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $id));
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when deleting a rent!"));
        }
    }


    function deleteRentsFromCustomer($customerid){
        try { 
            $sql = 'DELETE FROM RENT  
            WHERE customerid = :customerid';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':customerid' => $customerid));
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when deleting a rents from customer!"));
        }
    }

    /**
     * Return list of Rent-objects. List does not select all 
     * rents, but rentings related to certain customer.
    **/
    function getRents($customerid){
        try {
            $sql = 'SELECT * FROM RENT WHERE CUSTOMERID=:customerid';  
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':customerid' => $customerid));
            $rent_rows = $sth->fetchAll();
            $rents = [];
            foreach ($rent_rows as $rent_row) {
            //echo print_r($rent_row);
            array_push($rents, $this->rentFactory->createRentFromArray($rent_row));
            }
            return $rents;
        }
        catch (PDOException $exception) {
            error_log($exception->getMessage());
            throw (new Exception("Error when getting rents!"));
        }
    }


    function getRentById($id){
        try { 
            $sql = 'SELECT * FROM RENT  
            WHERE id = :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $id));
            $rent_row = $sth->fetch();
            if ($rent_row==null){
                echo "rent was null";
                return null;
            }
            //Kun rivejä on vain yksi, muunnetaan se vuokraus-objektiksi ennen palautusta.
            else {
                ##echo print_r($rent_row);
                return $this->rentFactory->createRentFromArray($rent_row);
            }
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when getting rent by id!"));
        }
    }

    /**
     * Huomaa, SQLitessä ei ole date-tyyppistä kenttää, joka olisi
     * oikea tietotyyppi päivämäärälle. Tässä pvm talletetaan 
     * varchar-kenttään tekstinä. Se voidaan muuntaa päivämääräksi 
     * ohjelmakoodissa.
    **/
    function createRentTable(){
        try {
            $dbutils=new DBUtils();
            $db=$dbutils->connectToDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE TABLE RENT (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                price TEXT NOT NULL,
                rentdate VARCHAR(200) NOT NULL,
                returndate VARCHAR(200) NOT NULL,
                customerid integer not null,
                FOREIGN KEY (customerid) REFERENCES CUSTOMERS (customerid));";
            $db->exec($sql);
            
        }
        catch (Exception $exception){
            //älä liitä mukaan varsinaista virhe tekstiä $exception->getMessage()
            //koska se voi sisältää liikaa tietoa tietokannan rakenteesta 
            //joka ei kuulu loppukäyttäjälle. 
        throw (new Exception('Creating database failed.'));
        }
    }
}
?>