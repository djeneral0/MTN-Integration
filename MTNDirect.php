<?php
  class MTNDirect{
    // anything that starts with dis deals with disbursement
    // anything that also starts with col deals with collection
    // for security reasons i had remove the keys ):
    // and primary key or secondary key can be used where ever you see Ocp-Apim-Subscription-Key
    // this is for both collection and disbursement

    private $_disPrimKey,
            $_disSecdKey,
            $_disXRefId,
            $_disApiUser,
            $_disApiKey;

    private $_colPrimKey,
            $_colSecdKey,
            $_colXRefId,
            $_colApiUser,
            $_colApiKey;

    public function __construct(){
      // the _disXRefId and _disApiUser are the same
      // likewise _colXRefId and _colApiUser

      $this->_disPrimKey = "";
      $this->_disSecdKey = "";
      $this->_disXRefId = "686bf8c9-732e-4602-a25a-ab2b90f0497f";
      $this->_disApiUser = "686bf8c9-732e-4602-a25a-ab2b90f0497f";
      $this->_disApiKey = "385794e283854a40a937882b1f832f1e";

      $this->_colPrimKey = "";
      $this->_colSecdKey = "";
      $this->_colXRefId = "686bf8c9-732e-4602-a25a-ab2b90f0497f";
      $this->_colApiUser = "686bf8c9-732e-4602-a25a-ab2b90f0497f";
      $this->_colApiKey = "385794e283854a40a937882b1f832f1e";
    }

   public function apiUser($xRefID){
     // the version 4 UUID is used to generate User API
     // different apiuse must be generated to handle disbursement and collection
     $data = '{
       "providerCallbackHost": "clinic.com"
     }';
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/v1_0/apiuser");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'X-Reference-Id: '.$xRefID,
       'Ocp-Apim-Subscription-Key: '.$this->_disPrimKey,
       'X-Target-Environment: sandbox'));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_POST, TRUE);
     curl_setopt($curl, CURLOPT_HEADER, TRUE);
     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

     $result = curl_exec($curl);
     curl_close($curl);

     var_dump($result);
   }

   public function apiUserKey($xRefID){
     // the same version 4 used to generate the APIuser is used to generate APIUser key
     // _disXRefId or _disApiUser is passed as $xRefID
     // _colXRefId or _colApiUser is passed as $xRefID
     $data = '{

     }';
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/{$xRefID}/apikey");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Ocp-Apim-Subscription-Key: '.$this->_disPrimKey,
       'X-Target-Environment: sandbox'));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_POST, TRUE);
       curl_setopt($curl, CURLOPT_HEADER, TRUE);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

       $result = curl_exec($curl);
       curl_close($curl);

       var_dump($result);
   }

   public function apiUserDetails($xRefID){
     // this function is for getting the details of the apiuser created
     // _disXRefId or _disApiUser is passed as $xRefID
     // _colXRefId or _colApiUser is passed as $xRefID
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/{$xRefID}");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Ocp-Apim-Subscription-Key: '.$this->_disPrimKey
     ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_HEADER, false);

     $result = curl_exec($curl);
     curl_close($curl);

     var_dump($result);
   }
   //Disbursements
   public function disToken(){
     // this function is for generation of token to be used for everthing related to collections
     // and if the token expires, you can generate another token
     // encode the the apiuser and apiuserkey generated to base 64
     // the encoded base 64 is sent using the Bearer token
     $base64 = base64_encode($this->_disApiUser .":". $this->_disApiKey);
     $data = '{

     }';
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/disbursement/token/");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Ocp-Apim-Subscription-Key: '.$this->_disSecdKey,
       'Authorization: Basic '.$base64
     ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_POST, TRUE);
       curl_setopt($curl, CURLOPT_HEADER, false);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

       $result = json_decode(curl_exec($curl));
       curl_close($curl);

       return $result->access_token;
       // var_dump($result);
   }

   public function disTransfer($amount, $number, $currency){
     // encode the the apiuser and apiuserkey generated to base 64
     // the access taken returned from the disToken() function is sent suing the Basic token
     $base64 = base64_encode($this->_disApiUser .":". $this->_disApiKey);
     $externalID = "YourExternalID";
     $data = '{
       "amount": "'.$amount.'",
       "currency": "'.$currency.'",
       "externalId": "'.$externalID.'",
       "payee": {
         "partyIdType": "MSISDN",
         "partyId": "'.$number.'"
       },
       "payerMessage": "From Moolre",
       "payeeNote": "Transaction ID '.$externalID.'"
     }';
     //print_r($data);
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/disbursement/v1_0/transfer");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Ocp-Apim-Subscription-Key: '.$this->_disPrimKey,
       'X-Target-Environment: sandbox',
       'Authorization: Bearer '.$this->disToken(),
       'X-Reference-Id: '.$this->_disXRefId
     ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_POST, TRUE);
       curl_setopt($curl, CURLOPT_HEADER, true);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

       $result = curl_exec($curl);
       curl_close($curl);

       var_dump($result);
   }

   public function disTransferStatus(){
      // the access taken returned from the disToken() function is sent suing the Basic token
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/disbursement/v1_0/transfer/{$this->_disXRefId}");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Ocp-Apim-Subscription-Key: '.$this->_disSecdKey,
       'X-Target-Environment: sandbox',
       'Authorization: Bearer '.$this->disToken()
     ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_HEADER, FALSE);

     $result = curl_exec($curl);
     curl_close($curl);

     var_dump($result);
   }

   public function disCheckBalance(){
     // this function is used to check the account holder of the user or client
     // the access taken returned from the disToken() function is sent suing the Basic token
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/disbursement/v1_0/account/balance");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Ocp-Apim-Subscription-Key: '.$this->_disSecdKey,
       'X-Target-Environment: sandbox',
       'Authorization: Bearer '.$this->disToken()
     ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_HEADER, true);

     $result = curl_exec($curl);
     curl_close($curl);

     var_dump($result);
   }

   public function disCheckAccountHolder($accountHolderId){
     // the access taken returned from the disToken() function is sent suing the Basic token
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/disbursement/v1_0/accountholder/msisdn/{$accountHolderId}/active");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Ocp-Apim-Subscription-Key: '.$this->_disSecdKey,
       'X-Target-Environment: sandbox',
       'Authorization: Bearer '.$this->disToken()
     ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_HEADER, true);

     $result = curl_exec($curl);
     curl_close($curl);

     var_dump($result);
   }

   //collection
   public function colToken(){
     // this function is for generation of token to be used for everthing related to collections
     // and if the token expires, you can generate another token
     // encode the the apiuser and apiuserkey generated to base 64
     // the encoded base 64 is sent using the Bearer token
     $base64 = base64_encode($this->_colApiUser .":". $this->_colApiKey);
     $data = '{

     }';
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/collection/token/");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Ocp-Apim-Subscription-Key: '.$this->_colPrimKey,
       'Authorization: Basic '.$base64
     ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_POST, TRUE);
       curl_setopt($curl, CURLOPT_HEADER, false);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

       $result = json_decode(curl_exec($curl));
       curl_close($curl);

       return $result->access_token;

       // var_dump($result);
   }

   public function colRequestToPay($amount, $number, $currency){
     // encode the the apiuser and apiuserkey generated to base 64
     // the access taken returned from the colToken() function is sent suing the Basic token
     $externalID = "YourExternalID";
     $data = '{
       "amount": "'.$amount.'",
       "currency": "'.$currency.'",
       "externalId": "'.$externalID.'",
       "payer": {
         "partyIdType": "MSISDN",
         "partyId": "'.$number.'"
       },
       "payerMessage": "From Moolre",
       "payeeNote": "Transaction ID '.$externalID.'"
     }';

     //print_r($data);
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "http://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Ocp-Apim-Subscription-Key: '.$this->_colPrimKey,
       'X-Target-Environment: sandbox',
       'Authorization: Bearer '.$this->colToken(),
       'X-Reference-Id: '.$this->_colXRefId
     ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_POST, TRUE);
       curl_setopt($curl, CURLOPT_HEADER, false);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

       $result = curl_exec($curl);
       curl_close($curl);

       var_dump($result);
   }

   public function colStatus(){
     // this function is for checking the status of the transaction
     // the access taken returned from the colToken() function is sent suing the Basic token
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/{$this->_colXRefId}");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Ocp-Apim-Subscription-Key: '.$this->_colSecdKey,
       'X-Target-Environment: sandbox',
       'Authorization: Bearer '.$this->colToken()
     ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_HEADER, false);

     $result = curl_exec($curl);
     curl_close($curl);

     var_dump($result);
   }

   public function colCheckBalance(){
     // the access taken returned from the colToken() function is sent suing the Basic token
     // for checking of balance
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/collection/v1_0/account/balance");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Ocp-Apim-Subscription-Key: '.$this->_colPrimKey,
       'X-Target-Environment: sandbox',
       'Authorization: Bearer '.$this->colToken()
     ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_HEADER, false);

     $result = curl_exec($curl);
     curl_close($curl);

     var_dump($result);
   }

   public function colCheckAccountHolder($accountHolderId){
     // the access taken returned from the colToken() function is sent suing the Basic token
     // this function is used to check the account holder of the user or client
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "https://sandbox.momodeveloper.mtn.com/collection/v1_0/accountholder/msisdn/{$accountHolderId}/active");
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Ocp-Apim-Subscription-Key: '.$this->_colSecdKey,
       'X-Target-Environment: sandbox',
       'Authorization: Bearer '.$this->colToken()
     ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_HEADER, true);

     $result = curl_exec($curl);
     curl_close($curl);

     var_dump($result);
   }

  }
 ?>
