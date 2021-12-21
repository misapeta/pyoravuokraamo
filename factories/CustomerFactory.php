<?php

class CustomerFactory {

     public function createCustomerFromArray($array_customer) {
        $customer = new Customer();
        if (isset($array_customer['id']))
            $customer->id=$array_customer['id'];
        if (isset($array_customer['first_name']))
            $customer->first_name=$array_customer['first_name'];
        if (isset($array_customer['last_name']))
            $customer->last_name=$array_customer['last_name'];
        if (isset($array_customer['birth_date']))
            $customer->birth_date=$array_customer['birth_date'];
        if (isset($array_customer['email']))
            $customer->email=$array_customer['email'];
        if (isset($array_customer['phone']))
            $customer->phone=$array_customer['phone'];
            
        return $customer;
    }

      
    public function createCustomer($first_name, $last_name, $birth_date, $email, $phone, $id=null) {
            $customer = new Customer();
            $customer->id = $id;
            $customer->first_name = $first_name;
            $customer->last_name = $last_name;
            $customer->birth_date = $birth_date;
            $customer->email = $email;
            $customer->phone = $phone;
            
            return $customer;
    }
}

?>