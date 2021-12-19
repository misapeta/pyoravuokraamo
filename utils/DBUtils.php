<?php


class DBUtils {

function connectToDatabase(){
  $myPDO = new PDO('sqlite:bikedatabasefile');
  return $myPDO;
} 



}

?>