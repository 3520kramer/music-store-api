<?php

include_once __DIR__ . '/../models/customer.php';
include_once __DIR__ . '/../models/invoice.php';
include_once __DIR__ . '/../utilities/authenticate.php';

function customers_route()
{
  $req_method = $_SERVER['REQUEST_METHOD'];
  $customer = new Customer();
  define('ROUTENAME', 'customers');

  $auth = new Authenticator(true);
  if (!$auth->is_valid) {
    // ERROR HANDLING
    echo"hey";
    $auth->unauthorized_response();
    return;
  }

  switch ($req_method) {
    case 'GET':

      // get all - should probably not be possible
      if (url_get_path_element(-1) === ROUTENAME) return;

      // get by id
      if (url_get_path_element(-2) === ROUTENAME) {
        $customer_id = intval(url_get_path_element(-1));

        // customer is only allowed to see their own information
        if ($auth->customer_id === intval($customer_id) || $auth->is_admin) {
          $results = $customer->get_customer($customer_id);

          echo json_encode($results);
          return;
        }
        // ERROR HANDLING
        $auth->unauthorized_response();
      }

      // get invoices related to a customer
      if (url_get_path_element(-2) === 'invoices') {
        $invoice = new Invoice();

        $customer_id = intval(url_get_path_element(-3));
        $invoice_id = url_get_path_element(-1);


        if ($auth->customer_id === intval($customer_id) || $auth->is_admin) {
          $results = $invoice->get_invoice($invoice_id, $auth);

          echo json_encode($results);
          return;
        }
        // ERROR HANDLING
        $auth->send_unauthorized_response();
      }
      break;
    case 'POST':
      $a = [];
      //$results = $customer->create_invoice($_POST);

      break;
    case 'PUT':
      break;
    case 'DELETE':
      break;

    default:
      echo 'Hit default in switch - error';
  }
}
