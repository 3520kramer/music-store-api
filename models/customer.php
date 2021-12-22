<?php

include_once __DIR__ . '/../database/database.php';

class Customer extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  function get_customer(int $id){
    $query = <<< SQL
      SELECT CustomerId, FirstName, LastName, Company, Address, City, 
        State, Country, PostalCode, Phone, Fax, Email
      FROM customer
      WHERE CustomerId = :id
    SQL;

    $params = ['id' => $id];
    $results = $this->get_one($query, $params);
    return $results;
  }

  function check_password($email, $password)
  {
    $query = <<<SQL
      SELECT CustomerId, Password, FirstName, LastName FROM customer WHERE `Email` = :email;
    SQL;

    $params = ['email' => $email];
    $customer = $this->get_one($query, $params);

    if (!$customer) {
      echo 'No customer';
      return;
    }

    $result = password_verify($password, $customer['Password']);

    return $result ? $customer : null;
  }
}