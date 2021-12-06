<?php

include_once __DIR__ . '/../database/database.php';

class Customer extends Database
{

  function check_password($email, $password)
  {
    $query = <<<SQL
      SELECT `Password`, `FirstName`, `LastName` from `customer` WHERE `Email` = :email;
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
