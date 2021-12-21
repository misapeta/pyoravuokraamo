<?php


class DBUtils {

function connectToDatabase(){
  $myPDO = new PDO('sqlite:bikedatabasefile');
  return $myPDO;
} 


function connectToDatabaseCustomer(){
  $myPDO = new PDO('sqlite:customerdatabasefile');
  return $myPDO;
}


}

?>