<?php

## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.

require_once('utils/DBUtils.php');
require_once ('views/header.php');
require_once('model/Bike.php');
require_once('factories/BikeFactory.php');

my_error_logging_principles();

class BikeDAO {

        
    function __construct() {
        #print "BIKEDAO constructor\n";
        $dbutils=new DBUtils();
        $this->bikeFactory = new BikeFactory();
        $this->dbconnection=$dbutils->connectToDatabase();
    }

    public $dbconnection;
    public $bikeFactory;



    function addBike($bike){
        try { 
            $sql = 'INSERT INTO BIKES (brand_name, model, year, type, serial_number) VALUES(:brand_name, :model, :year, :type, :serial_number)';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindParam('brand_name', $bike->brand_name, PDO::PARAM_STR);
            $sth->bindParam('model', $bike->model, PDO::PARAM_STR);
            $sth->bindParam('year', $bike->year, PDO::PARAM_STR);
            $sth->bindParam('type', $bike->type, PDO::PARAM_STR);
            $sth->bindParam('serial_number', $bike->serial_number, PDO::PARAM_STR);
            $result = $sth->execute();
            
            return $result;

        } catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when adding a bike!"));
        }
    }

    function updateBike($bike){
        try { 
            ##echo print_r($bike);
            $sql = 'UPDATE BIKES SET brand_name=:brand_name, model=:model, year=:year, type=:type, serial_number=:serial_number WHERE id= :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindParam('id', $bike->id, PDO::PARAM_INT);
            $sth->bindParam('brand_name', $bike->brand_name, PDO::PARAM_STR);
            $sth->bindParam('model', $bike->model, PDO::PARAM_STR);
            $sth->bindParam('year', $bike->year, PDO::PARAM_STR);
            $sth->bindParam('type', $bike->type, PDO::PARAM_STR);
            $sth->bindParam('serial_number', $bike->serial_number, PDO::PARAM_STR);
            $result = $sth->execute();
            return $result;
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when updating a bike!"));
        }
    }

    /**
     * Kun pyöriin liitetään vuokrauksia, pitää pyörään liittyvät 
    * vuokraukset poistaa ennen kuin pyörä voidaan poistaa. Muuten 
    * pyörän poisto epäonnistuu lapsitietuiden vuoksi.
    **/
    function deleteBike($id){
        try { 
            $sql = 'DELETE FROM BIKES  
            WHERE id = :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $id));
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when deleting a bike!"));
        }
    }

    /**
     * Palauttaa listan Bike -objekteista. Konvertoidaan jokainen rivi
     * objektiksi Bike-luokan konstruktorin avulla (joka hoitaa konvertoinnin)
     **/
    function getBikes(){
        try {
            $sql = 'SELECT * FROM BIKES';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute();
            $bike_rows = $sth->fetchAll();
            $bikes = [];
            foreach ($bike_rows as $bike_row) {
            //echo print_r($bike_row);
            array_push($bikes, $this->bikeFactory->createBikeFromArray($bike_row));
            }
            return $bikes;
        }
        catch (PDOException $exception) {
            error_log($exception->getMessage());
            throw (new Exception("Error when getting bikes!"));
        }
    }


    function getBikeById($id){
        try { 
            $sql = 'SELECT * FROM BIKES  
            WHERE id = :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $id));
            $bike_row = $sth->fetch();
            if ($bike_row==null){
                //echo "bike was null";
                return null;
            }
            //Kun rivejä on vain yksi, muunnetaan se pyörä-objektiksi ennen palautusta.
            else {
                ##echo print_r($bike_row);
                return $this->bikeFactory->createBikeFromArray($bike_row);
            }
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when getting bike by id!"));
        }
    }

    function createBikesTable(){
        try {
            $dbutils=new DBUtils();
            $db=$dbutils->connectToDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE TABLE BIKES
            (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                brand_name VARCHAR(200) NOT NULL,
                model VARCHAR(200) NOT NULL,
                year VARCHAR(200) NOT NULL,
                type VARCHAR(200) NOT NULL,
                serial_number VARCHAR(200) NOT NULL,
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
