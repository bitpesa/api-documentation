<?php
// This has been tested using PHP 7.1

// This or some other function will be required to generate a GUID
function guidv4(){
  if (function_exists('com_create_guid') === true)
    return trim(com_create_guid(), '{}');

  $data = openssl_random_pseudo_bytes(16);
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// Use SHA512 algorithm
define('DIGEST', 'SHA512');

// Authentication details
define('API_KEY', 'YOUR_API_KEY');       // From the developer portal
define('API_SECRET', 'YOUR_API_SECRET'); // From the developer portal

// API Endpoint
define('API_URL', 'https://api-sandbox.bitpesa.co/v1/senders');
define('API_METHOD', 'POST');

// Request-specific data
$nonce = guidv4(); // Must be unique per request
$body = json_encode([
  'sender' => [
    'country' => 'UG',
    'phone_country' => 'UG',
    'phone_number' => '752403639',
    'email' => 'email@domain.com',
    'first_name' => 'Example',
    'last_name' => 'User',
    'city' => 'Kampala',
    'street' => 'Somewhere 17-3',
    'postal_code' => '798983',
    'birth_date' => '1970-01-01',
    'documents' => [
      [
        'upload' => "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAACXBIWXMAAAsT\nAAALEwEAmpwYAAAAB3RJTUUH4gEeCTEzbKJEHgAAAB1pVFh0Q29tbWVudAAA\nAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAAADElEQVQI12P4z8AAAAMBAQAY\n3Y2wAAAAAElFTkSuQmCC",
        'upload_file_name' => 'passport.png',
        'metadata' => [ 'meta' => 'data' ]
      ]
    ],
    'ip' => '127.0.0.1',
    'metadata' => [ 'meta' => 'data' ]
  ]
]);
$body_hash = openssl_digest($body, DIGEST); // 15acba46cb2d76400e0d770c8ea132daeecf68238a414f42a0ff757a82bd72a78ac31e99f010251e1cbf3c0052216fbe8934ad4f12dfcf276c45dff4a3274ae8

// Generate the signature
$to_sign = implode('&', [
  $nonce,
  API_METHOD,
  API_URL,
  $body_hash
]);

$auth_signature = hash_hmac(
  DIGEST,
  $to_sign,
  API_SECRET
);

// Build the request headers
$headers = [
  'Accept: application/json',
  'Content-Type: application/json',
  'Authorization-Key: ' . API_KEY,
  'Authorization-Nonce: ' . $nonce,
  'Authorization-Signature: ' . $auth_signature
];

// Send the request
$ch = curl_init(API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$data = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

// Expected response
'{"object":{"id":"41477e29-f959-47ee-a235-ad3c2c035af2","type":"person","state":"initial","country":"UG","street":"Somewhere 17-3","postal_code":"798983","city":"Kampala","phone_country":"UG","phone_number":"752403639","email":"email@domain.com","ip":"127.0.0.1","address_description":null,"first_name":"Example","last_name":"User","birth_date":"1970-01-01","documents":[{"id":"0e63f6f3-ad40-4695-97f8-1d794d02d32f","upload":"https://document.bitpesa.co/path/to/passport.png","metadata":{"meta":"data"},"upload_file_name":"passport.png","upload_content_type":"image/png","upload_file_size":152}],"metadata":{"meta":"data"},"providers":{"NGN::Bank":"paga"}}}'
?>