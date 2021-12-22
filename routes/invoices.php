<?php
include_once __DIR__ . '/../models/invoice.php';
include_once __DIR__ . '/../utilities/authenticate.php';

function invoices_route()
{
  define('ROUTENAME', 'invoices');

  $last_path_element = url_get_last_element();
  $req_method = $_SERVER['REQUEST_METHOD'];
  $invoice = new Invoice();

  $auth = new Authenticator(true);

  if (!$auth->is_valid) {
    // ERROR HANDLING
    $auth->send_unauthorized_response();
    return;
  }

  switch ($req_method) {
    case 'GET':
      $invoice_id = $last_path_element;
      $result = $invoice->get_invoice($invoice_id, $auth);

      echo json_encode($result);
      break;
    case 'POST':
      $customer_id = $_POST['customerId'];

      // customer is only allowed to create invoice for own id
      if ($auth->customer_id === intval($customer_id) || $auth->is_admin) {
        $invoice_id = $invoice->create_invoice($_POST);
        echo json_encode($invoice_id);
        return;
      }

      // ERROR HANDLING
      $auth->send_unauthorized_response();

      break;
    case 'PUT':
      break;
    case 'DELETE':
      break;

    default:
      echo 'Hit default in switch - error';
  }
}
