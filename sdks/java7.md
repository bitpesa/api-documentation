# Java 7 SDK

- Maven Artifact: https://search.maven.org/artifact/co.bitpesa.sdk/bitpesa-sdk-java7
- Git Repository: https://github.com/bitpesa/bitpesa-sdk-java7

## Example

A small example illustrating authenticating and accessing the Currency Info endpoint:

```java
// Import classes:
import co.bitpesa.sdk.ApiClient;
import co.bitpesa.sdk.ApiException;
import co.bitpesa.sdk.Configuration;
import co.bitpesa.sdk.auth.*;
import co.bitpesa.sdk.api.CurrencyInfoApi;

ApiClient apiClient = new ApiClient();
apiClient.setApiKey("<key>");
apiClient.setApiSecret("<secret>");
apiClient.setBasePath("https://api-sandbox.bitpesa.co/v1");

CurrencyInfoApi apiInstance = new CurrencyInfoApi(apiClient);
try {
    CurrencyListResponse result = apiInstance.infoCurrencies();
    System.out.println(result);
} catch (ApiException e) {
    if (e.isValidationError()) {
        CurrencyListResponse result = e.getResponseObject(CurrencyListResponse.class);
        System.out.println(result);
        System.err.println("WARN: Validation error occurred when calling the endpoint");
    } else {
        System.err.println("Exception when calling CurrencyInfoApi#infoCurrencies");
        e.printStackTrace();
    }
}
```

Full examples for all steps required by our [quick integration guide](../quick-integration.md) can be found at: https://github.com/bitpesa/bitpesa-sdk-java7/blob/master/example/src/main/java/co/bitpesa/test/Application.java
