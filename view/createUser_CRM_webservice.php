<?php

include('constant.inc');
include(ROOT_PATH.'controller/classes/crm_webservice.php');
include(ROOT_PATH.'controller/auth.php');
include(ROOT_PATH.'controller/classes/query.php');
include(ROOT_PATH.'controller/PHPMailer/PHPMailerAutoload.php');


// Get the url and sessionid from auth.php
// create instance for class
$qryobj=new crm_crudoperation($endpointUrl,$sessionid);
// Create instance for email class
$mailObj = new PHPMailer;


/*$crmusername=$_POST['user_name'];
$userpassword=$_POST['user_password'];
$confirmpassword=$_POST['confirm_password'];
$firstname=$_POST['first_name'];
$lastname=$_POST['last_name'];
$emailid=$_POST['email1'];
$isadmin=$_POST['is_admin'];
$emp_num=$_POST['emp_num'];*/


$crmusername="testuser1";
$userpassword="test456";
$confirmpassword="test456";
$firstname="test";
$lastname="user";
$emailid="test@test.com";
$isadmin="false";
$emp_num="463";



$obj=new createcrmuser($endpointUrl,$sessionid,$crmusername,$userpassword,$confirmpassword,$firstname,
$lastname,$emailid,$isadmin,$emp_num);

//print_r($obj);

class createcrmuser{  // Class start

  // Constructor for variable declaration
  function __construct($endpointUrl,$sessionid,$crmusername,$userpassword,$confirmpassword,$firstname,
  $lastname,$emailid,$isadmin,$emp_num)
  {
    //Access the outside object variable
    global $qryobj;
    global $mailObj;
    global $conn;

    $this->qryobj=$qryobj;
    $this->mailObj=$mailObj;
    $this->dbObj=$conn;

    $this->crmusername=$crmusername;
    $this->userpassword=$userpassword;
    $this->confirmpassword=$confirmpassword;
    $this->firstname=$firstname;
    $this->lastname=$lastname;
    $this->emailid=$emailid;
    $this->isadmin=$isadmin;
    $this->emp_num=$emp_num;

    $this->createLead();
  }

  function createLead()  // Create lead function Start
  {

    $crm_userid=0;
    $createData=Array ("user_name" => $this->crmusername , "user_password" => $this->userpassword ,"confirm_password" => $this->confirmpassword ,
    "first_name" => $this->firstname , "last_name" => $this->lastname , "email1" => $this->emailid ,"emp_num" => $this->emp_num ,
    "date_format" => "yyyy-mm-dd" , "time_zone" => "America/Denver" , "default_record_view" => "Summary" , "internal_mailer" => "" , "language" => "en_us");

    if($this->isadmin == "true"){
      $createData["roleid"]="H2";
      $createData["is_admin"]="on";
    }else{
      $createData["roleid"]="H5";
      $createData["is_admin"]="off";
    }

    $query = "SELECT * FROM Users where user_name='$this->crmusername';";
    $searchDetails=$this->qryobj->crm_Query($query);

    if($searchDetails == "Noresult"){
      //Create New User In CRM : Call function from another class
      $createDetails=$this->qryobj->crm_createUser($createData);

      if(!empty($createDetails['result'])){
        $crmuserArr=$createDetails['result']['id'];
        $crmuserArr=explode("x",$crmuserArr);
        $crm_userid=$crmuserArr[1];
      }else{
        $this->sendEmail("Can't able to create new user!".$createDetails['error']['message']);
        $crm_userid="Error";
      }
    }else{
      $crm_userid="user exist";
    }

    echo $crm_userid;

  }  // Create lead function End


  function sendEmail($msg){  // Email function Start

      $this->mailObj->CharSet = "UTF-8";
      $this->mailObj->isSMTP();                                      // Set mailer to use SMTP
      $this->mailObj->Host = 'yyyyyyy';  // Specify main and backup SMTP servers
      $this->mailObj->SMTPAuth = true;                               // Enable SMTP authentication
      $this->mailObj->Username = 'yyyyyyy';    // SMTP username
      $this->mailObj->Password = 'vvvvvvvv';                           // SMTP password
      $this->mailObj->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
      $this->mailObj->Port = 25;
  		$this->mailObj->Subject ="CRM User Create Error";
      // TCP port to connect to
      $this->mailObj->setFrom($this->emailid);

      $msgBody = "<table><tr>";
    	$msgBody = "<td>";
    	$msgBody = "";
    	$msgBody .= '<STRONG style="color:#F00">Webservice Error : CRM User Create</STRONG><br>';
      $msgBody .= '<font > ! Problem While creating new user </font><br>';
    	$msgBody .= "<table>";
    	$msgBody .= '<STRONG><u>Contact Information:</u></STRONG><table>';
    	$msgBody .= "<tr><td><span><b>date: </b></span></td><td> <font color='#0000CC'>".date('Y-m-d h:i:s')."</font></td></tr>";
    	$msgBody .= "<tr><td><span><b>User Name: </b></span></td><td> <font color='#0000CC'>".$this->crmusername."</font></td></tr>";
    	$msgBody .= "<tr><td><span><b>User Password: </b></span></td><td> <font color='#0000CC'>".$this->userpassword."</font></td></tr>";
    	$msgBody .= "<tr><td><span><b>Confirm Password: </b></span></td><td> <font color='#0000CC'>".$this->confirmpassword."</font></td></tr>";
    	$msgBody .= "<tr><td><span><b>First Name: </b></span></td><td> <font color='#0000CC'>" .$this->firstname. "</font></td></tr>";
    	$msgBody .= "<tr><td><span><b>Last Name: </b></span></td><td> <font color='#0000CC'>" . $this->lastname. "</font></td></tr>";
    	$msgBody .= "<tr><td><span><b>Email Address: </b></span></td><td> <font color='#0000CC'>". $this->emailid ."</font></td></tr>";
      $msgBody .= "<tr><td><span><b>Employee Number: </b></span></td><td> <font color='#0000CC'>".$this->emp_num ."</font></td></tr>";
      $msgBody .= "<tr><td><span><b>Is Admin: </b></span></td><td> <font color='#0000CC'>".$this->isadmin ."</font></td></tr>";
      $msgBody .= "<tr><td><span><b></b></span></td><td> <font color='#0000CC'></font></td></tr>";
      $msgBody .= "<tr><td><span><b>Webservice Location: </b></span></td><td> <font color='#0000CC'>CRM User Create  (createUser_CRM_Webservice.php)</font></td></tr>";
      $msgBody .= "<tr><td><span><b>Error Msg: </b></span></td><td> <font color='#0000CC'>".$msg."</font></td></tr>";
      $msgBody .= "<tr><td><span><b></b></span></td><td> <font color='#0000CC'></font></td></tr>";
      $msgBody.="</table>";

    	$this->mailObj->isHTML(true);
    	$this->mailObj->Body = $msgBody;
    	$this->mailObj->addAddress('test@test.com');
    	$this->mailObj->send();

  }  //Email function End



}  // Class End

?>
