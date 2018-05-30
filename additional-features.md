# Additional Features

## Bank account name validation

Since it's easy to mistype the account number for a recipient, we provide a feature where you can
request more details about an account number, before creating a transaction.

To do this initiate a call to the following endpoint:

```
POST /v1/account_validations

{
  "account_id": "12345678", # account number to query
  "bank_code": "000", # bank code to query - same codes are used as for creating the transactions
  "country": "NG" # Either "NG" for Nigeria or "GH" for Ghana
}
```

The response will either provide you with the account title:

```
{
  "object": {
    "account_name": "Test User"
  }
}
```

Or an error description:

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
