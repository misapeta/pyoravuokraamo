<?php

require_once('utils/DBUtils.php');
require_once('model/BookFix.php');
require_once ('views/header.php');
require_once('factories/BookFixFactory.php');

my_error_logging_principles();

class BookFixDAO {

    
    function __construct() {
        #print "BOOKFIXDAO constructor\n";
        $dbutil=new DBUtils();
        $this->dbconnection=$dbutil->connectToDatabase();
        $this->bookFixFactory = new BookFixFactory();
    }

    public $dbconnection;
    public $bookFixFactory;



function addBookFix($bookfix){
   
    try { 
        $sql = 'INSERT INTO BOOKFIX (description, fixdate, bookid) VALUES(:description, :fixdate, :bookid)';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindParam('description', $bookfix->description, PDO::PARAM_STR, 200);
        $sth->bindParam('fixdate', $bookfix->fixdate, PDO::PARAM_STR, 10);
        $sth->bindParam('bookid', $bookfix->bookid, PDO::PARAM_INT);
        
        $result = $sth->execute();
        return $result;
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when adding a bookfix!"));
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


function deleteBookFix($id){
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

//deleteFixesFromBook

function deleteFixesFromBook($bookid){
    try { 
        $sql = 'DELETE FROM BOOKFIX  
        WHERE bookid = :bookid';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':bookid' => $bookid));
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when deleting a bookfixes from book!"));
    }
}

/**
*  Return list of BookFix -objects. List does not select all 
 * bookfixes, but book fixes related to certain book.
**/

function getBookFixes($bookid){
    try {
        $sql = 'SELECT * FROM BOOKFIX WHERE BOOKID=:bookid';  
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':bookid' => $bookid));
        $book_fix_rows = $sth->fetchAll();
        $bookfixes = [];
        foreach ($book_fix_rows as $book_fix_row) {
          //echo print_r($book_row);
          array_push($bookfixes, $this->bookFixFactory->createBookFixFromArray($book_fix_row));
        }
        return $bookfixes;
    }
    catch (PDOException $exception) {
        error_log($exception->getMessage());
        throw (new Exception("Error when getting bookfixes!"));
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
            echo "bookfix was null";
            return null;
        }
        //Kun rivejä on vain yksi, muunnetaan se kirjakorjaus-objektiksi
        //ennen palautusta.
        else {
            ##echo print_r($book_row);
            return $this->bookFixFactory->createBookFixFromArray($book_fix_row);
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

function createBookFixTable(){
    try {
         $dbutils=new DBUtils();
         $db=$dbutils->connectToDatabase();
         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "CREATE TABLE BOOKFIX (
             id INTEGER PRIMARY KEY AUTOINCREMENT,
             description TEXT NOT NULL,
             fixdate VARCHAR(200) NOT NULL,
             bookid integer not null,
             FOREIGN KEY (bookid) REFERENCES BOOKS (bookid));";
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