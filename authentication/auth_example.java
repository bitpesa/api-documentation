import java.security.MessageDigest;
import java.util.Arrays;
import java.net.*;
import java.io.*;
import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

public class Transaction {
  public static void main(String[] args) {
    final String hmacSha512 = "HmacSHA512";
    String api_key = "c3CKwQktM0zbPLuc1REXF9q92GSPcErIs50TouifPfBgeqXrJWzlPEuBXrHKYz85JXXKk7xehz4yXMrJQc4utw==";
    String api_secret = "6KNxZjOuuYKl3vDGkoVNQbZ3V2nzyJr3Umwt2b0I2lN83o0TDaIFTBYCElcR0JUrEgzMkTv7/46ydzNGJ/jwEg==";
    String nonce = java.util.UUID.randomUUID().toString(); // => 00c6a48a-ccb8-4653-a0c8-de7c1ab67529
    String url = "https://api-sandbox.bitpesa.co/v1/senders";
    String request_method = "POST";
    String body = "{\"sender\":{\"country\":\"UG\",\"phone_country\":\"UG\",\"phone_number\":\"752403639\",\"email\":\"example@home.org\",\"first_name\":\"Johnny\",\"last_name\":\"English\",\"city\":\"Kampala\",\"street\":\"Unknown 17-3\",\"postal_code\":\"798983\",\"birth_date\":\"1900-12-31\",\"ip\":\"127.0.0.1\",\"metadata\":{\"my\":\"data\"}}}";

    try {
      MessageDigest md = MessageDigest.getInstance("SHA-512");
      byte[] sha512 = md.digest(body.getBytes("UTF-8"));
      StringBuffer md5HashOfBody = new StringBuffer(sha512.length * 2);
      for (int i = 0; i < sha512.length; i++) {
        int intVal = sha512[i] & 0xff;
        if (intVal < 0x10) {
          md5HashOfBody.append("0");
        }
        md5HashOfBody.append(Integer.toHexString(intVal));
      }
      String type = "application/json";
      URL u = new URL(url);
      HttpURLConnection conn = (HttpURLConnection) u.openConnection();
      conn.setDoOutput(true);
      conn.setRequestMethod("POST");
      conn.setRequestProperty( "Content-Type", type );
      conn.setRequestProperty( "Accept", type );
      conn.setRequestProperty( "Content-Length", String.valueOf(body.length()));
      conn.setRequestProperty("Authorization-Key", api_key);
      conn.setRequestProperty("Authorization-Nonce", nonce);
      String signature = String.format("%s&%s&%s&%s", nonce, request_method, url, md5HashOfBody.toString());

      byte [] byteKey = api_secret.getBytes("UTF-8");
      Mac sha512_HMAC = Mac.getInstance(hmacSha512);      
      SecretKeySpec keySpec = new SecretKeySpec(byteKey, hmacSha512);
      sha512_HMAC.init(keySpec);
      byte [] mac_data = sha512_HMAC.doFinal(signature.getBytes("UTF-8"));

      StringBuffer hmacResult = new StringBuffer(mac_data.length * 2);
      for (int i = 0; i < mac_data.length; i++) {
        int intVal = mac_data[i] & 0xff;
        if (intVal < 0x10) {
          hmacResult.append("0");
        }
        hmacResult.append(Integer.toHexString(intVal));
      }

      conn.setRequestProperty("Authorization-Signature", hmacResult.toString());
      OutputStream os = conn.getOutputStream();
      os.write(body.getBytes());

      System.out.println(signature);
      System.out.println(hmacResult.toString());

      System.out.println(conn.getResponseMessage());
    } catch (Exception exception) {
      exception.printStackTrace();
    }
  }
}