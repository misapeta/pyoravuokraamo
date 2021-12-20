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
        $sql = 'INSERT INTO BOOKFIX (description, fixdate, bookid) VALUES(:description, :fixdate, :bookid)';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindParam('description', $bikefix->description, PDO::PARAM_STR, 200);
        $sth->bindParam('fixdate', $bikefix->fixdate, PDO::PARAM_STR, 10);
        $sth->bindParam('bookid', $bikefix->bookid, PDO::PARAM_INT);
        
        $result = $sth->execute();
        return $result;
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when adding a bikefix!"));
    }
}

function updateBookFix($bookfix){
    try { 
        ##echo print_r($book);
        $sql = 'UPDATE BOOKFIX SET description= :description, fixdate= :fixdate, bookid= :bookid WHERE id= :id';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindValue('id', $bookfix->id);
        $sth->bindValue('description', $bookfix->description);
        $sth->bindValue('fixdate', $bookfix->fixdate);
        $sth->bindValue('bookid', $bookfix->bookid);
        $result = $sth->execute();
        return $result;
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when updating a book!"));
    }
}


function deleteBikeFix($id){
    try { 
        $sql = 'DELETE FROM BOOKFIX 
        WHERE id = :id';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when deleting a bookfix!"));
    }
}


function deleteFixesFromBike($bookid){
    try { 
        $sql = 'DELETE FROM BOOKFIX  
        WHERE bookid = :bookid';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':bookid' => $bookid));
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when deleting a bikefixes from bike!"));
    }
}

/**
*  Return list of BikeFix -objects. List does not select all 
 * bikefixes, but book fixes related to certain book.
**/

function getBookFixes($bookid){
    try {
        $sql = 'SELECT * FROM BOOKFIX WHERE BOOKID=:bookid';  
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':bookid' => $bookid));
        $book_fix_rows = $sth->fetchAll();
        $bikefixes = [];
        foreach ($book_fix_rows as $book_fix_row) {
          //echo print_r($book_row);
          array_push($bikefixes, $this->bikeFixFactory->createBikeFixFromArray($book_fix_row));
        }
        return $bikefixes;
    }
    catch (PDOException $exception) {
        error_log($exception->getMessage());
        throw (new Exception("Error when getting bikefixes!"));
    }
}




function getBookFixById($id){
    try { 
        $sql = 'SELECT * FROM BOOKFIX  
        WHERE id = :id';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
        $book_fix_row = $sth->fetch();
        if ($book_fix_row==null){
            echo "boofix was null";
            return null;
        }
        //Kun rivejä on vain yksi, muunnetaan se kirjakorjaus-objektiksi
        //ennen palautusta.
        else {
            ##echo print_r($book_row);
            return $this->bikeFixFactory->createBikeFixFromArray($book_fix_row);
        }
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when getting book by id!"));
    }
}

/**
 * Huomaa, SQLLitessä ei ole date-tyyppistä kenttää, joka olisi
  *oikea tietotyyppi päivämäärälle. Tässä pvm talletetaan 
 * varchar-kenttään tekstinä. Se voidaan muuntaa päivämääräksi 
  *ohjelmakoodissa.
  **/

function createBikeFixTable(){
    try {
         $dbutils=new DBUtils();
         $db=$dbutils->connectToDatabase();
         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "CREATE TABLE BOOKFIX (
             id INTEGER PRIMARY KEY AUTOINCREMENT,
             description TEXT NOT NULL,
             fixdate VARCHAR(200) NOT NULL,
             bookid integer not null,
             FOREIGN KEY (bookid) REFERENCES BIKES (bookid));";
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