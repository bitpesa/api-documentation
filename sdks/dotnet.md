# .NET SDK

- NuGet Package: https://www.nuget.org/packages/BitPesa.Sdk/
- Git Repository: https://github.com/bitpesa/bitpesa-sdk-dotnet

## Example

A small example illustrating authenticating and accessing the Currency Info endpoint:

### C#

```csharp
using System;
using System.Diagnostics;
using BitPesa.Sdk.Api;
using BitPesa.Sdk.Client;
using BitPesa.Sdk.Model;

namespace Example
{
    public class InfoCurrenciesExample
    {
        public void main()
        {
            Configuration configuration = new Configuration();
            configuration.ApiKey = "<key>";
            configuration.ApiSecret = "<secret>";
            configuration.BasePath = "https://api-sandbox.bitpesa.co/v1";

            var apiInstance = new CurrencyInfoApi(configuration);

            try {
                // Getting a list of possible requested currencies
                CurrencyListResponse result = apiInstance.InfoCurrencies();
                Debug.WriteLine(result);
            } catch (ApiException e)
            {
                if (e.IsValidationError) {
                    // In case there was a validation error, obtain the object
                    CurrencyListResponse result = e.ParseObject<CurrencyListResponse>();
                    Debug.WriteLing("There was a validation error while processing!");
                    Debug.WriteLine(result);
                } else {
                    Debug.Print("Exception when calling CurrencyInfoApi.InfoCurrencies: " + e.Message );
                }
            }
        }
    }
```

### VB.NET

```vb
Imports BitPesa.Sdk.Api;
Imports BitPesa.Sdk.Client;
Imports BitPesa.Sdk.Model;
Imports System
Imports System.Collections.Generic
Imports System.Linq
Imports System.Text
Imports System.Threading.Tasks

Module Example
    Sub Main(ByVal args As String())
        Dim configuration As Configuration = New Configuration()
        configuration.ApiKey = "KEY"
        configuration.ApiSecret = "SECRET"
        configuration.BasePath = "https://api-sandbox.bitpesa.co/v1"

        Dim debitsApi As AccountDebitsApi = New AccountDebitsApi(configuration)

        Dim apiInstance = new CurrencyInfoApi(configuration)


        Try
            REM Getting a list of possible requested currencies
            Dim result As CurrencyListResponse = apiInstance.InfoCurrencies()
            Debug.WriteLine(result)
        Catch e as ApiException
            If e.IsValidationError Then
                REM In case there was a validation error, obtain the object
                Dim result as CurrencyListResponse = e.ParseObject(Of CurrencyListResponse)()
                Debug.WriteLine("There was a validation error while processing!")
                Debug.WriteLine(result)
            Else
                Debug.Print("Exception when calling CurrencyInfoApi.InfoCurrencies: " + e.Message )
            End If
        End Try
    End Sub
End Module
```
Full examples for all steps required by our [quick integration guide](../quick-integration.md) can be found at: https://github.com/bitpesa/bitpesa-sdk-javascript/blob/master/examples/examples.js
