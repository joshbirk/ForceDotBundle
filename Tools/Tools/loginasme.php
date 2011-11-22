<?php

require_once('soapclient/SforcePartnerClient.php');
$fr = fopen("php://stdin", "r");
  while (!feof ($fr)) {
    $input .= fgets($fr);
  }
  fclose($fr);

$params = split(":", trim($input));
$client = new SforcePartnerClient();
$client->createConnection('soapclient/partner.wsdl.xml');
$loginResult = $client->login($params[0],$params[1]);
print $client->getSessionId();

?>