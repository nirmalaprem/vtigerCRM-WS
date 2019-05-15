<?php

include('constant.inc');
include(ROOT_PATH.'controller/classes/crm_webservice.php');
include(ROOT_PATH.'controller/auth.php');
include(ROOT_PATH.'controller/classes/query.php');
include(ROOT_PATH.'controller/PHPMailer/PHPMailerAutoload.php');


// Get the url and sessionid from auth.php
// create instance for class
$qryobj=new crm_crudoperation($endpointUrl,$sessionid);


$crmusername="nirmala@xyz.com";
$firstname="nirmala";
$lastname="Arum";
$emailid="nirmala@xyz.com";
$street_address="test address";
$city="testcity";
$state="testprovince";
$postal="h6g8h9";

$createData=array(
"userName"=>  $crmusername,
"firstName"=>  $firstname,
"lastName"=>  $lastname,
"emailID"=>  $emailid,
"streetAddress"=>  $street_address,
"city"=>  $city,
"state"=>  $state,
"postal"=>  $postal

);

$obj=new update_crmuser($endpointUrl,$sessionid,$update_info);

function __construct($endpointUrl,$sessionid,$update_info)
{
  //Access the outside object variable
  global $qryobj;

  $this->qryobj=$qryobj;

  $this->crmusername=$update_info['userName'];
  $this->firstname=$update_info['firstName'];
  $this->lastname=$update_info['lastName'];
  $this->email=$update_info['emailID'];
  $this->street_address=$update_info['streetAddress'];
  $this->city=$update_info['city'];
  $this->state=$update_info['state'];
  $this->postal=$update_info['postal'];

  $this->update_userinfo();
}





class update_crmuser{  // Class start

  // Constructor for variable declaration
  function __construct($endpointUrl,$sessionid,$update_info)
  {
    //Access the outside object variable
    global $qryobj;
    global $mailObj;

    $this->qryobj=$qryobj;
    $this->mailObj=$mailObj;

    $this->crmusername=$update_info['userName'];
    $this->firstname=$update_info['firstName'];
    $this->lastname=$update_info['lastName'];
    $this->email=$update_info['emailID'];
    $this->street_address=$update_info['streetAddress'];
    $this->city=$update_info['city'];
    $this->state=$update_info['state'];
    $this->postal=$update_info['postal'];

    $this->update_userinfo();
  }


?>
