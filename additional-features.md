# Additional Features

## Bank account name validation

Since it's easy to mistype the account number for a recipient, we provide a feature where you can
request more details about an account number, before creating a transaction.

To do this initiate a call to the following endpoint:

```
POST /v1/account_validations

{
  "bank_account": "12345678", # account number to query
  "bank_code": "000", # bank code to query - same codes are used as for creating the transactions
  "country": "NG"   # Only "NG" is supported for now
  "currency": "NGN" # Only "NGN" is supported for now
  "method": "bank"  # Only "bank" is supported for now
}
```

The response will either be a `200 OK`, and provide you with the account title:

```
{
  "object": {
    "account_name": "Test User"
  }
}
```

Or a `422 Unprocessably Entity` status code, with an error description in the body:

```
{
  "object": {
    "account_name": null
  },
  "meta": {
    "error": "Account Invalid"
  }
}
```

Once you have the account title you can compare that with the recipient details you wish to provide us, and only create a transaction if they match.
