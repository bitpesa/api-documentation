var crypto = require('crypto')
var request = require('request') // request package installed from npm
var uuidv4 = require('uuid/v4') // uuid package installed from npm

// Authentication details
API_KEY = 'YOUR_API_KEY' // From the developer portal
API_SECRET = 'YOUR_API_SECRET' // From the developer portal

// The request method and endpoint
API_URL = 'https://api-sandbox.bitpesa.co/v1/senders'
API_METHOD = 'POST'

// Request-specific data
nonce = uuidv4() // Must be unique per request
body = JSON.stringify({
  sender: {
    country: 'UG',
    phone_country: 'UG',
    phone_number: '752403639',
    email: 'email@domain.com',
    first_name: 'Example',
    last_name: 'User',
    city: 'Kampala',
    street: 'Somewhere 17-3',
    postal_code: '798983',
    birth_date: '1970-01-01',
    documents: [
      {
        upload:
          'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAACXBIWXMAAAsT\nAAALEwEAmpwYAAAAB3RJTUUH4gEeCTEzbKJEHgAAAB1pVFh0Q29tbWVudAAA\nAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAAADElEQVQI12P4z8AAAAMBAQAY\n3Y2wAAAAAElFTkSuQmCC',
        upload_file_name: 'passport.png',
        metadata: { meta: 'data' }
      }
    ],
    ip: '127.0.0.1',
    metadata: { meta: 'data' }
  }
})

// Create hash of body data
var hash = crypto.createHash('sha512')
hash.update(body)

// Generate the signature
toSign = [
  nonce,
  API_METHOD,
  API_URL,
  hash.digest('hex')
].join('&')

var hmac = crypto.createHmac('sha512', API_SECRET)
hmac.update(toSign)
auth_signature = hmac.digest('hex')

// Build the request headers
headers = {
  Accept: 'application/json',
  'Content-Type': 'application/json',
  'Authorization-Key': API_KEY,
  'Authorization-Nonce': nonce,
  'Authorization-Signature': auth_signature
}

// Send the request
var options = {
  url: API_URL,
  method: API_METHOD,
  headers: headers,
  body: body
}

request(options, function(error, res, body) {
  if (error) {
    // handle error
    console.log(error)
  } else {
    // response received
    console.log(body)
  }
})

// Expected response body
{"object":{"id":"dd9e77f5-5727-42a9-b91d-fdded7edbeac","type":"person","state":"initial","country":"UG","street":"Unknown 17-3","postal_code":"798983","city":"Kampala","phone_country":"UG","phone_number":"752403639","email":"sender@bitpesa.demo","ip":"127.0.0.1","address_description":null,"first_name":"Johnny","last_name":"English","birth_date":"1970-01-01","documents":[{"id":"7ae470e5-743f-4ef7-8af5-2ab00fcdb966","upload":"https://bitpesa-development-documents.s3-eu-west-1.amazonaws.com/documents/uploads/7ae470e5-743f-4ef7-8af5-2ab00fcdb966/thumbnail/passport.png?X-Amz-Algorithm=AWS4-HMAC-SHA256\\u0026X-Amz-Credential=AKIAI3CWSFDBPPJHAYYQ%2F20180130%2Feu-west-1%2Fs3%2Faws4_request\\u0026X-Amz-Date=20180130T100152Z\\u0026X-Amz-Expires=3600\\u0026X-Amz-SignedHeaders=host\\u0026X-Amz-Signature=9e3a4630e4622ff8667a82aaf501fcea5e3b1300606424453cf91ca212af5ff8","metadata":{"meta":"data"},"upload_file_name":"passport.png","upload_content_type":"image/png","upload_file_size":152}],"metadata":{"my":"data"},"providers":{"NGN::Bank":"paga"}}}
