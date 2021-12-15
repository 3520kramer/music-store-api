<?php

/* 
  CREDITS: https://roytuts.com/how-to-generate-and-validate-jwt-using-php-without-using-third-party-api/
*/
function base64url_encode($str)
{
	return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
}

// Default secret and expiration (in seconds)
function generate_jwt(array $payload, string $secret = 'secret', int $expire_in = 600000): string
{
	$headers = array('alg' => 'HS256', 'typ' => 'JWT');
	$headers_encoded = base64url_encode(json_encode($headers));

	$payload['exp'] = (time() + $expire_in); 
	$payload_encoded = base64url_encode(json_encode($payload));

	$signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
	$signature_encoded = base64url_encode($signature);

	$jwt = "$headers_encoded.$payload_encoded.$signature_encoded";

	return $jwt;
}

function is_jwt_valid(string $jwt, string $secret = 'secret'): bool
{
	// doesn't accept authorize header without Bearer
	if(!str_starts_with($jwt, 'Bearer ')) return false;

	// remove Bearer from the jwt
	$jwt = substr($jwt, 7);

	// split the jwt and decode
	$token_parts = explode('.', $jwt);
	$header = base64_decode($token_parts[0]);
	$payload = base64_decode($token_parts[1]);
	$signature_provided = $token_parts[2];

	// check the expiration time - note: this will cause an error if there is no 'exp' claim in the jwt
	$expiration = json_decode($payload)->exp;
	$is_token_expired = ($expiration - time()) < 0;

	// build a signature based on the header and payload using the secret
	$base64_url_header = base64url_encode($header);
	$base64_url_payload = base64url_encode($payload);
	$signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
	$base64_url_signature = base64url_encode($signature);

	// verify it matches the signature provided in the jwt
	$is_signature_valid = ($base64_url_signature === $signature_provided);

	if ($is_token_expired || !$is_signature_valid) {
		return false;
	} else {
		return true;
	}
}


// $payload = array('sub' => '1234567890', 'name' => 'John Doe', 'admin' => true);

// $jwt = generate_jwt($payload);

// #echo $jwt . '<br>';

// $jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImV4cCI6MTYzODYzNTQ0OH0.7SV_hpBEm5cF3IEQxMYNGQke-brPR_ZC8CLY6pf5OEY';
// // #$is_valid = is_jwt_valid('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImV4cCI6MTYzNjcxNzExM30.bfENemwGy9dJ2r8Z5sJIXBJFiGPObnHj9qsBi0fBTPQ');
// $is_valid = is_jwt_valid($jwt);//"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyIwIjoiYSIsIjEiOiJiIiwiZXhwIjoxNjM4NjM0MDUxfQ.f6g27HQONXMaKBJsGI_oy3554VTDKahfulzx_m8KHbk");

// if ($is_valid === true) {
// 	echo 'JWT is valid';
// } else {
// 	echo 'JWT is invalid';
// }


/* 
	Use Firebase JWT to encode and decode jwt
*/

// require __DIR__ . '/../../vendor/autoload.php';

// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

// $key = "example_key";
// $payload = array(
// 	"iss" => "http://example.org",
// 	"aud" => "http://example.com",
// 	"iat" => 1356999524,
// 	"nbf" => 1357000000
// );

// $jwt = JWT::encode($payload, $key, 'HS256');


// $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

// echo $jwt;
// var_dump($decoded);
