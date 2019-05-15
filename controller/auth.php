<?php

//require_once ('classes/crm_webservice.php');

//access key of the user admin, found on my preferences page.
//URL should be the vtiger installed path
// username and password of API
$endpointUrl = 'https://xxxxxxxx/webservice.php';
$userName = 'xxxxxxxxx';
$userAccessKey = 'yyyyyyyyy';


// Create instance for class
$crmobj= new crm_webservice($endpointUrl,$userName,$userAccessKey);



$challengeToken = $crmobj->get_token();
//echo $challengeToken['result']['token'];
//echo $challengeToken['result']['serverTime'];
//echo $challengeToken['result']['expireTime'];
//echo "<br />";
$login = $crmobj->get_access();
$userid=$login['result']['userId'];
$sessionid=$login['result']['sessionName'];





?>
