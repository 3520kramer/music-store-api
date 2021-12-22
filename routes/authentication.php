<?php
include_once __DIR__ . '/../utilities/authenticate.php';
include_once __DIR__ . '/../utilities/helpers.php';
include_once __DIR__ . '/../models/admin.php';
include_once __DIR__ . '/../models/customer.php';

function authentication_route(){

  $req_method = $_SERVER['REQUEST_METHOD'];

  // auth/
  switch($req_method){
    case 'GET':
      break;
    case 'POST':
      $email = $_POST['username'] ?? null;
      $pass = $_POST['password'] ?? null;

      if(!$email || !$pass) {
        http_response_code(400);
        echo json_encode('Error');
        return;
      }

      $jwt_payload = [];

      // check if admin is in url
      if (url_get_last_element() === 'user'){
        $customer = new Customer();
        $customer_info = $customer->check_password($email, $pass);

        if(!$customer_info) {
          echo 'invalid password';
          return;
        }

        $jwt_payload['is_admin'] = false; // might not be needed
        $jwt_payload['customer_id'] = $customer_info['CustomerId'];
        $jwt_payload['first_name'] = $customer_info['FirstName'];
        $jwt_payload['last_name'] = $customer_info['LastName'];
        
      }else{
        $admin = new Admin();
        $is_admin = $admin->check_password($pass);
        
        if(!$is_admin) {
          echo 'invalid password';
          return;
        }
        $jwt_payload['customer_id'] = 0;
        $jwt_payload['is_admin'] = true;
      };

      $jwt = Authenticator::generate_jwt($jwt_payload);
      $json = json_encode(['token' => $jwt]);
      echo $json;

      break;
    case 'DELETE':
      break;
    default:
      echo 'ERROR';
  }
  if($req_method !== 'POST'){
    echo 'ERROR';
    return;
  }

  // $user = $_POST['username'] ?? null;
  // $pass = $_POST['password'] ?? null;

  // #echo $user . $pass . '<br>';

  // $jwt = generate_jwt(array($user, $pass));
  // echo $jwt;
  
}