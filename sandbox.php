<?php
  require_once 'UUID.php';
  require_once 'MTNDirect.php';

  $uuid = new  UUID();
  $momo = new  MTNDirect();

  // echo $uuid->gen_uuid();
  // 686bf8c9-732e-4602-a25a-ab2b90f0497f

  // $momo->apiUser("686bf8c9-732e-4602-a25a-ab2b90f0497f");

  // $momo->apiUserKey("686bf8c9-732e-4602-a25a-ab2b90f0497f");
  // 385794e283854a40a937882b1f832f1e

  // $momo->apiUserDetails("686bf8c9-732e-4602-a25a-ab2b90f0497f");
  // string(67) "{"providerCallbackHost":"clinic.com","targetEnvironment":"sandbox"}"

  // $momo->disToken();

  // $momo->disTransfer("10011","233551300186","EUR");

  // $momo->disTransferStatus();

  // $momo->disCheckBalance();

  // $momo->disCheckAccountHolder("233277104923");

  // $momo->colToken();

  // $momo->colRequestToPay("10013", "233277104923", "EUR");

  // $momo->colStatus();

  //$momo->colCheckBalance();

  // $momo->colCheckAccountHolder("233277104923");
 ?>
