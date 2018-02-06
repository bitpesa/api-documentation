using System;
using System.Net;
using System.Security.Cryptography;
using System.Text;
using System.IO;
using System.Collections.Specialized;

namespace transaction
{
	class MainClass
	{
		public static void Main(string[] args)
		{
			// Authentication details
			var apiKey = "YOUR_API_KEY";
			var apiSecret = "YOUR_API_SECRET";
			
			// The request method and endpoint
			var url = "https://api-sandbox.bitpesa.co/v1/senders";
			var requestMethod = "POST";
			
			// Request specific data
			var nonce = System.Guid.NewGuid().ToString(); // Must be unique per request
			var body = "{\"sender\":{\"country\":\"UG\",\"phone_country\":\"UG\",\"phone_number\":\"752403639\",\"email\":\"example@home.org\",\"first_name\":\"Johnny\",\"last_name\":\"English\",\"city\":\"Kampala\",\"street\":\"Unknown 17-3\",\"postal_code\":\"798983\",\"birth_date\":\"1900-12-31\",\"ip\":\"127.0.0.1\",\"metadata\":{\"my\":\"data\"}}}";

			// Create hash of body data
			string md5HashOfBody = GetSHA512Hash(body);
			
			// Generate the signature
			var toSign = string.Format("{0}&{1}&{2}&{3}", nonce, requestMethod, url, md5HashOfBody);
			var authSignature = GetHMACHash(apiSecret, toSign);

			// Build the request headers
			HttpWebRequest req = (HttpWebRequest)WebRequest.Create(url);
			req.Accept = "application/json";
			req.ContentType = "application/json";
			req.Headers.Add("Authorization-Key", apiKey);
			req.Headers.Add("Authorization-Nonce", nonce);
			req.Method = "POST";
			req.Headers.Add("Authorization-Signature", authSignature);
			byte[] bytes = Encoding.UTF8.GetBytes(body);
			req.ContentLength = bytes.Length;

			// Send the request
			Stream requestStream = req.GetRequestStream();
			requestStream.Write(bytes, 0, bytes.Length);

			WebResponse response = req.GetResponse();
			Stream stream = response.GetResponseStream();
			StreamReader reader = new StreamReader(stream);

			var result = reader.ReadToEnd();
			stream.Dispose();
			reader.Dispose();

			Console.WriteLine(result);
		}

		static string GetSHA512Hash(string input)
		{
			SHA512 digest = SHA512.Create();
			// Convert the input string to a byte array and compute the hash.
			byte[] data = digest.ComputeHash(Encoding.UTF8.GetBytes(input));

			// Create a new Stringbuilder to collect the bytes
			// and create a string.
			StringBuilder sBuilder = new StringBuilder();

			// Loop through each byte of the hashed data 
			// and format each one as a hexadecimal string.
			for (int i = 0; i < data.Length; i++)
			{
				sBuilder.Append(data[i].ToString("x2"));
			}

			// Return the hexadecimal string.
			return sBuilder.ToString();
		}

		static string GetHMACHash(string api_secret, string input)
		{
			HMACSHA512 hmacDigest = new HMACSHA512(Encoding.UTF8.GetBytes(api_secret));
			// Convert the input string to a byte array and compute the hash.
			byte[] data = hmacDigest.ComputeHash(Encoding.UTF8.GetBytes(input));

			// Create a new Stringbuilder to collect the bytes
			// and create a string.
			StringBuilder sBuilder = new StringBuilder();

			// Loop through each byte of the hashed data 
			// and format each one as a hexadecimal string.
			for (int i = 0; i < data.Length; i++)
			{
				sBuilder.Append(data[i].ToString("x2"));
			}

			// Return the hexadecimal string.
			return sBuilder.ToString();
		}
	}
}
