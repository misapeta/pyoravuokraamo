<?php

require_once ('views/header.php');



my_error_logging_principles();

class Renting {

	public $id;
	public $price;
	public $rentdate;
	public $returndate;
	//Asiakas, johon vuokraus kohdistetaan
	public $customerid;

public static function checkRent($price, $rentdate, $returndate, $customerid) {

	if ($price==null || $rentdate==null || $returndate==null || $customerid==null){
		return false;
    }
	if ($price=="" || $rentdate=="" || $returndate=="" || $customerid=="") {
		return false;
    }
	if (!is_numeric($price) || !is_numeric($customerid)){
    	return false;
    }
    $dt1 = DateTime::createFromFormat('Y-m-d', $rentdate);
    $dt2 = DateTime::createFromFormat('Y-m-d', $returndate);
	if ($dt1==null || $dt2==null){
		return false;
	}
	return true;
}

}

?>