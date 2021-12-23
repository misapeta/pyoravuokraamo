<?php

require_once('utils/DBUtils.php');
require_once('model/BikeFix.php');
require_once ('views/header.php');
require_once('factories/BikeFixFactory.php');

my_error_logging_principles();

class BikeFixDAO {

    function __construct() {
        #print "BIKEFIXDAO constructor\n";
        $dbutil=new DBUtils();
        $this->dbconnection=$dbutil->connectToDatabase();
        $this->bikeFixFactory = new BikeFixFactory();
    }

    public $dbconnection;
    public $bikeFixFactory;

    function addBikeFix($bikefix){
        try { 
            $sql = 'INSERT INTO BIKEFIX (description, fixdate, bikeid) VALUES(:description, :fixdate, :bikeid)';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindParam('description', $bikefix->description, PDO::PARAM_STR, 200);
            $sth->bindParam('fixdate', $bikefix->fixdate, PDO::PARAM_STR, 10);
            $sth->bindParam('bikeid', $bikefix->bikeid, PDO::PARAM_INT);
            
            $result = $sth->execute();
            return $result;
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when adding a bikefix!"));
        }
    }

    function updateBikeFix($bikefix){
        try { 
            ##echo print_r($bike);
            $sql = 'UPDATE BIKEFIX SET description= :description, fixdate= :fixdate, bikeid= :bikeid WHERE id= :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindValue('id', $bikefix->id);
            $sth->bindValue('description', $bikefix->description);
            $sth->bindValue('fixdate', $bikefix->fixdate);
            $sth->bindValue('bikeid', $bikefix->bikeid);
            $result = $sth->execute();
            return $result;
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when updating a bike!"));
        }
    }


    function deleteBikeFix($id){
        try { 
            $sql = 'DELETE FROM BIKEFIX 
            WHERE id = :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $id));
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when deleting a bikefix!"));
        }
    }


    function deleteFixesFromBike($bikeid){
        try { 
            $sql = 'DELETE FROM BIKEFIX  
            WHERE bikeid = :bikeid';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':bikeid' => $bikeid));
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when deleting a bikefixes from bike!"));
        }
    }

    /**
     * Return list of BikeFix -objects. List does not select all 
     * bikefixes, but bike fixes related to certain bike.
    **/
    function getBikeFixes($bikeid){
        try {
            $sql = 'SELECT * FROM BIKEFIX WHERE BIKEID=:bikeid';  
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':bikeid' => $bikeid));
            $bike_fix_rows = $sth->fetchAll();
            $bikefixes = [];
            foreach ($bike_fix_rows as $bike_fix_row) {
            //echo print_r($bike_fix_row);
            array_push($bikefixes, $this->bikeFixFactory->createBikeFixFromArray($bike_fix_row));
            }
            return $bikefixes;
        }
        catch (PDOException $exception) {
            error_log($exception->getMessage());
            throw (new Exception("Error when getting bikefixes!"));
        }
    }


    function getBikeFixById($id){
        try { 
            $sql = 'SELECT * FROM BIKEFIX  
            WHERE id = :id';
            $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id' => $id));
            $bike_fix_row = $sth->fetch();
            if ($bike_fix_row==null){
                echo "bikefix was null";
                return null;
            }
            //Kun rivejä on vain yksi, muunnetaan se pyörähuolto-objektiksi ennen palautusta.
            else {
                ##echo print_r($bike_fix_row);
                return $this->bikeFixFactory->createBikeFixFromArray($bike_fix_row);
            }
        }
        catch (PDOException $e){
            error_log($e->getMessage());
            throw (new Exception("Error when getting bike by id!"));
        }
    }

    /**
     * Huomaa, SQLitessä ei ole date-tyyppistä kenttää, joka olisi
     * oikea tietotyyppi päivämäärälle. Tässä pvm talletetaan 
     * varchar-kenttään tekstinä. Se voidaan muuntaa päivämääräksi 
     * ohjelmakoodissa.
    **/
    function createBikeFixTable(){
        try {
            $dbutils=new DBUtils();
            $db=$dbutils->connectToDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE TABLE BIKEFIX (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                description TEXT NOT NULL,
                fixdate VARCHAR(200) NOT NULL,
                bikeid integer not null,
                FOREIGN KEY (bikeid) REFERENCES BIKES (bikeid));";
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