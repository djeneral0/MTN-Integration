# MTN-Integration
# Docomentation
The MTN gateway has 4 product namely
* Collection Widget
* Collections - Enables remote automatic collection of bills, fees or taxes
* Disbursements
* Remittances

# This demostration is for Collections and Disbursements only

# Subscriptions
Developers are issued a Primary Key and Secondary Key for every product.Both primary and secondary Subscription key provides access to the API. Without one of them a developer cannot access any of the APIs. Subscriptions are stored under the user profile and have no expiry.

# generation of API User and API Key
There are two credentials used in the Open API.
* Subscription Key
* API User and API Key for Oauth 2.0

The subscription key is used to give access to APIs in the API Manager portal. A user is assigned a subscription Key as and when the user subscribes to products in the API Manager Portal.

The API User and API Key are used to grant access to the wallet system in a specific country. API user and Key are wholly managed by the merchant through Partner Portal.

# Create API User
1. send a POST (baseURL}/apiuser request.

Example https://sandbox.momodeveloper.mtn.com/v1_0/apiuser
2. Provide UUID in the Reference ID in the request Hearder and subscription key.

Example: X-Reference-Id: c72025f5-5cd1-4630-99e4-8ba4722fad56
3. The user is created with a response 201.

# Create API Key
1. sens a POST {baseURL}/apiuser/{APIUser}/apikey request
Example https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/{686bf8c9-732e-4602-a25a-ab2b90f0497f}/apikey
2. The API User is specified in the URL and subscription Key in the header.
Example: Ocp-Apim-Subscription-Key: d484a1f0d34f4301916d0f2c9e9106a2
3. The API key is created and responds with 201 Created with the newly Created API Key in the Body.

# Get API User Details
1. Send a Get {baseURL}/apiuser/{APIUser} request

Example: https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/{686bf8c9-732e-4602-a25a-ab2b90f0497f}
2. Specify the API User inthe URL and subscription Key in the header.
3. Response with 2001 Ok and user details of the user.

# Oauth 2.0
The Open API is using Oauth 2.0 token for authentication of request. Client will request an access token using Client Credential Grant according to RFC 6749. The token received is according to RFC 6750 Bearer Token.

The API user and API key are used in the basic authentication header when requesting the access token. The API user and key are managed in the Partner GUI for the country where the account is located.

# Important: 
The token must be treated as a credential and kept secret. The party that have access to the token will be authenticated as the user that requested the token. The below sequence describes the flow for requesting a token and using the token in a request.

