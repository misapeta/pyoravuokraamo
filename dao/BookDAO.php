<?php


## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.

require_once('utils/DBUtils.php');
require_once('model/Book.php');
require_once ('views/header.php');
require_once('factories/BookFactory.php');

my_error_logging_principles();

class BookDAO {

    
    function __construct() {
        #print "BOOKDAO constructor\n";
        $dbutils=new DBUtils();
        $this->bookFactory = new BookFactory();
        $this->dbconnection=$dbutils->connectToDatabase();
    }

    public $dbconnection;
    public $bookFactory;



function addBook($book){
    try { 
        $sql = 'INSERT INTO BOOKS (name, author, published) VALUES(:name, :author, :published)';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindParam('name', $book->name, PDO::PARAM_STR, 30);
        $sth->bindParam('author', $book->author, PDO::PARAM_STR, 20);
        $sth->bindParam('published', $book->published, PDO::PARAM_INT);
        
        $result = $sth->execute();
        return $result;
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when adding a book!"));
    }
}

function updateBook($book){
    try { 
        ##echo print_r($book);
        $sql = 'UPDATE BOOKS SET name= :name, author= :author, published= :published WHERE id= :id';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindParam('id', $book->id, PDO::PARAM_INT);
        $sth->bindParam('name', $book->name, PDO::PARAM_STR, 30);
        $sth->bindParam('author', $book->author, PDO::PARAM_STR, 20);
        $sth->bindParam('published', $book->published, PDO::PARAM_INT);
        $result = $sth->execute();
        return $result;
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when updating a book!"));
    }
}

/**
  * Kun kirjoihin liitetään lainauksia, pitää kirjaan liittyvät 
 * lainaukset poistaa ennen kuin kirja voidaan poistaa. Muuten 
   *kirjan poisto epäonnistuu lapsitietuiden vuoksi.
**/

function deleteBook($id){
    try { 
        $sql = 'DELETE FROM BOOKS  
        WHERE id = :id';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when deleting a book!"));
    }
}
/**
 * Return list of Book -objects. You need to convert every row to 
  *object using the constructor of the Book-class, which converts 
 * array to object.
**/

function getBooks(){
    try {
        $sql = 'SELECT * FROM BOOKS';  
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute();
        $book_rows = $sth->fetchAll();
        $books = [];
        foreach ($book_rows as $book_row) {
          //echo print_r($book_row);
          array_push($books, $this->bookFactory->createBookFromArray($book_row));
        }
        return $books;
    }
    catch (PDOException $exception) {
        error_log($exception->getMessage());
        throw (new Exception("Error when getting books!"));
    }
}




function getBookById($id){
    try { 
        $sql = 'SELECT * FROM BOOKS  
        WHERE id = :id';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
        $book_row = $sth->fetch();
        if ($book_row==null){
            //echo "book was null";
            return null;
        }
        //Kun rivejä on vain yksi, muunnetaan se kirja-objektiksi
        //ennen palautusta.
        else {
            ##echo print_r($book_row);
            return $this->bookFactory->createBookFromArray($book_row);
        }
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when getting book by id!"));
    }
}

function createBooksTable(){
    try {
         $dbutils=new DBUtils();
         $db=$dbutils->connectToDatabase();
         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "CREATE TABLE BOOKS (
             id INTEGER PRIMARY KEY AUTOINCREMENT,
             name VARCHAR(200) NOT NULL,
             author VARCHAR(200) NOT NULL,
             published integer,
             description TEXT);";
        $db->exec($sql);
        
    }
    catch (Exception $exception){
        //älä liitä mukaan varsinaista virhe tekstiä $exception->getMessage()
        //koska se voi sisältää liikaa tietoa tietokannan rakenteesta 
        //joka ei kuulu loppukäyttäjälle. 
       error_log($exception->getMessage());
       throw (new Exception('Creating database failed. '.$exception->getMessage()));
    }
}
}
?>