# Ruby SDK

- RubyGem: https://rubygems.org/gems/bitpesa-sdk
- Git Repository: https://github.com/bitpesa/bitpesa-sdk-ruby

## Example

A small example illustrating authenticating and accessing the Currency Info endpoint:

```ruby
# load the gem
require 'bitpesa-sdk'

api_instance = Bitpesa::CurrencyInfoApi.new

begin
  #Getting a list of possible requested currencies
  result = api_instance.info_currencies
  p result
rescue Bitpesa::ApiError => e
  if e.validation_error
    puts "WARN: Validation error occured when calling the endpoint"
    result = e.response_object("CurrencyListResponse")
    p result
  else
    puts "Exception when calling CurrencyInfoApi->info_currencies: #{e}"
  end
end
```

Full examples for all steps required by our [quick integration guide](../quick-integration.md) can be found at: https://github.com/bitpesa/bitpesa-sdk-ruby/blob/master/example/client.rb
