<?php

class crm_crudoperation    // Class Start
{

  // variable declaration
  protected $_url='';
  protected $_sessionID='';

  //constructor to set variable value
  public function __construct($endpointUrl,$sessionid){

    $this->_url=$endpointUrl;
    //echo " URL : ".$this->_url."<br />";
    $this->_sessionID=$sessionid;
    //echo " Session : ".$this->_sessionID."<br />";
  }

  // Using this function we are updating the crm values
  // performing two operations : query and update
  // operation query : fetch the records from crm DB
  // operation update : give the field and vlaue to update in crm DB
  // Caaling Pages: driveFasterLeadUpdate.php , creditCanadaLeadUpdate.php
  public function crm_update($query,$updated_value){  // update Function implementation

      $returndata="";
      $queryParam = urldecode($query);
      $param=array("operation" => "query", "sessionName" => $this->_sessionID, 'query' => $queryParam);
      $getUserDetail=crm_webservice::curl_execution($this->_url,$param);  // call the static function from another class
      //print_r($getUserDetail);
      if (!empty($getUserDetail['result'])) {
          // records got from query operation
          //foreach($getUserDetail['result'] as $key => $retrievedObjects);
          $retrievedObjects=$getUserDetail['result'][0];

          if(count($getUserDetail['result']) > 1){

            $crmInsert=1;
            foreach($getUserDetail['result'] as $updateArr){

             $assignedUserid=explode('x',$updateArr['assigned_user_id']);
             $groupArray=array(2,3,4,6,138);

            if($updateArr['leadstatus'] !== 'Bad Information - Junk' || $updateArr['leadstatus'] !== 'Bad Information - Duplicate'){

                if(!in_array($assignedUserid[1],$groupArray) ){
                    $retrievedObjects=$updateArr;
                    $crmInsert=2;
                    break;
                }
            }
            }

            if($crmInsert == 1){
              $retrievedObjects=$getUserDetail['result'][0];
            }
          }

          // include the fields and values into the records variable to update
          foreach($updated_value as $key => $value){
            $retrievedObjects[$key] =$value;
          }
          //echo "<pre>";
          //print_r($retrievedObjects);
          //$returndata=$retrievedObjects;
          // encode the array values
          $objectJson=json_encode($retrievedObjects);
          $param=array("operation" => "update", "sessionName" => $this->_sessionID, 'element' => $objectJson);
          $UpdatedDetail=crm_webservice::curl_execution($this->_url,$param,"POST");  // call the static function from another class

          if (!empty($getUserDetail['result'])) {
                $returndata=$UpdatedDetail;
          }else{
            $returndata="Error" ;
          }

      }else{
        $returndata="NoResult" ;
      }

      return $returndata;

  } // update Function implementation End

  // Using this function we are creating new entry in crm
  // performing two operations : query and create
  // operation query : to idendify the user exist in crm
  // operation create : new entry in crm DB
  public function crm_create($createData){  // create Function implementation

      $returndata="";
      /*$firstname=$createData['firstname'];
      $lastname=$createData['lastname'];
      $email=$createData['email'];

      $query = "SELECT * FROM Leads where firstname='$firstname' and lastname='$lastname' and email='$email';";
      $queryParam = urldecode($query);
      $param=array("operation" => "query", "sessionName" => $this->_sessionID, 'query' => $queryParam);
      $getUserDetail = crm_webservice::curl_execution($this->_url,$param);

      if (!empty($getUserDetail['result'])) {

          $returndata=" Lead already exist !!!";
      }else{ */
          $createDataJson = json_encode($createData);
          $param=array("operation" => "create", "sessionName" => $this->_sessionID, "element" => $createDataJson, "elementType" => "Leads");
          $dataDetails = crm_webservice::curl_execution($this->_url,$param,"POST");
          $returndata=$dataDetails;

      //}
      return $returndata;

  } // create Function implementation End


  public function create_LeadToContacts($createData){  // create contact Function implementation

      $returndata="";
      $createDataJson = json_encode($createData);
      $param=array("operation" => "create", "sessionName" => $this->_sessionID, "element" => $createDataJson, "elementType" => "Contacts");
      $dataDetails = crm_webservice::curl_execution($this->_url,$param,"POST");
      $returndata=$dataDetails;


      return $returndata;

  } // create contact Function implementation End
  public function update_LeadToContacts($retrievedObjects){  // update Function implementation

        $objectJson=json_encode($retrievedObjects);
        $param=array("operation" => "update", "sessionName" => $this->_sessionID, 'element' => $objectJson);
        $UpdatedDetail=crm_webservice::curl_execution($this->_url,$param,"POST");  // call the static function from another class
/*
        if (!empty($getUserDetail['result'])) {
              $returndata=$UpdatedDetail;
        }else{
          $returndata="Error" ;
        }
*/
      return $UpdatedDetail;

  } // update Function implementation End


  public function crm_comments_create($createData){  // Comments create Function implementation

      $returndata="Error";

      $createDataJson = json_encode($createData);
      $param=array("operation" => "create", "sessionName" => $this->_sessionID, "element" => $createDataJson, "elementType" => "ModComments");
      $dataDetails = crm_webservice::curl_execution($this->_url,$param,"POST");

      if (!empty($dataDetails['result'])) {
         $returndata="success";
      }else{
         $returndata=$dataDetails['error']['message'];
      }

      return $returndata;

  } // Comments create Function implementation End


  // Using this function we are deleting entry in crm
  // performing two operations : query and delete
  // operation query : to idendify the user exist in crm and get the user id
  // operation delete : delete entry in crm DB
  public function crm_delete($dataArr){ // Delete Function implementation

      $returndata="";
      $whereCond="where";
      foreach($dataArr as $key=>$value){
          $whereCond.=" ".$key."="."'$value'"." and";
      }
      $whereCond=substr($whereCond,0,-3);

      $query = "SELECT * FROM Leads $whereCond;";
      $queryParam = urldecode($query);
      $param=array("operation" => "query", "sessionName" => $this->_sessionID, 'query' => $queryParam);
      $getUserDetail = crm_webservice::curl_execution($this->_url,$param);

      if (!empty($getUserDetail['result'])) {

          foreach($getUserDetail['result'] as $key => $value){
              $id=$value['id'];
              $param=array("operation" => "delete", "sessionName" => $this->_sessionID, "id" => $id);
              $deleteData = crm_webservice::curl_execution($this->_url,$param,"POST");

              $deleteStatus=$deleteData['result']['status'];
              if($deleteStatus == "successful"){
                  $returndata=" Deleted operation is successful !!";
              }else{
                  $returndata=" Delete operation is not successful !!";
              }

          }


      }else{
          $returndata= " No Result !!!";
      }
      return $returndata;

  } // Delete Function implementation End

  public function crm_getfieldname($elementType){  // describe Function implementation

    $param=array("operation" => "describe", "sessionName" => $this->_sessionID, 'elementType' => $elementType);
    $getfieldDetail = crm_webservice::curl_execution($this->_url,$param);

    return $getfieldDetail;

  } // describe Function implementation End


  public function crm_Query($query){  // Query Function implementation

      $returndata="Noresult";
      $queryParam = urldecode($query);
      $param=array("operation" => "query", "sessionName" => $this->_sessionID, 'query' => $queryParam);
      $getUserDetail = crm_webservice::curl_execution($this->_url,$param);

      if (!empty($getUserDetail['result'])) {

        $returndata=$getUserDetail;
      }
      return $returndata;

  } // Query Function implementation End

  public function crm_new_update($retrievedObjects){  // update  Function implementation

      $objectJson=json_encode($retrievedObjects);
      $param=array("operation" => "update", "sessionName" => $this->_sessionID, 'element' => $objectJson);
      $UpdatedDetail=crm_webservice::curl_execution($this->_url,$param,"POST");  // call the static function from another class

      if (!empty($UpdatedDetail['result'])) {
            $returndata=$UpdatedDetail;

      }else{
        $returndata="Error" ;
      }
  }

  public function crm_contact_product_update($query,$updated_value){  // update Function implementation

      $returndata="";
      $queryParam = urldecode($query);
      $param=array("operation" => "query", "sessionName" => $this->_sessionID, 'query' => $queryParam);
      $getUserDetail=crm_webservice::curl_execution($this->_url,$param);  // call the static function from another class

      //print_r($getUserDetail);

      if (!empty($getUserDetail['result'])) {

          $retrievedObjects=$getUserDetail['result'][0];
          $crmInsert=1;
          $crmInsert_1=1;
          $retrievedObjects_1="";
          $leadstatusArr=array("Interested",
          "CAT CARD ONLY","1st Attempt","2nd Attempt",
          "3rd Attempt","4th Attempt","Not Interested - Payments Too High",
          "Not Interested - Set Up Too High");

          if(count($getUserDetail['result']) > 1){
            foreach($getUserDetail['result'] as $updateArr){

              if($updateArr['cf_857'] == "Application Received"){
                $retrievedObjects=$updateArr;
                $crmInsert=2;
                break;
              }
              if(in_array($updateArr['cf_857'],$leadstatusArr)){
                  $crmInsert_1=2;
                  $retrievedObjects_1=$updateArr;
              }
            }
          }
          if($crmInsert != 2 && $crmInsert_1 == 2 ){
            $retrievedObjects=$retrievedObjects_1;
          }
          // include the fields and values into the records variable to update
          foreach($updated_value as $key => $value){
            $retrievedObjects[$key] =$value;
          }
          //  print_r($retrievedObjects);
          // encode the array values

          $objectJson=json_encode($retrievedObjects);
          $param=array("operation" => "update", "sessionName" => $this->_sessionID, 'element' => $objectJson);
          $UpdatedDetail=crm_webservice::curl_execution($this->_url,$param,"POST");  // call the static function from another class

          //print_r($UpdatedDetail);
          if (!empty($UpdatedDetail['result'])) {
                $returndata=$UpdatedDetail;
          }else{
            $returndata="Error" ;
          }

      }else{
        $returndata="NoResult" ;
      }

      return $returndata;

  } // update Function implementation End


  public function crm_createUser($createData){  // user create Function implementation

    $createDataJson = json_encode($createData);
    $param=array("operation" => "create", "sessionName" => $this->_sessionID, "element" => $createDataJson, "elementType" => "Users");
    $dataDetails = crm_webservice::curl_execution($this->_url,$param,"POST");
    $returndata=$dataDetails;


    return $returndata;

  } // user create Function implementation End


  public function crm_logout(){  // Session Logout Function implementation


    $param=array("operation" => "logout", "sessionName" => $this->_sessionID);
    $dataDetails = crm_webservice::curl_execution($this->_url,$param,"GET");


  } // Session Logout Function implementation End


  public function crm_update_operation($update_userinfo){ // used for credit canada lead update : dont remove : Most Important function

    $objectJson=json_encode($update_userinfo);
    $param=array("operation" => "update", "sessionName" => $this->_sessionID, 'element' => $objectJson);
    $UpdatedDetail=crm_webservice::curl_execution($this->_url,$param,"POST");  // call the static function from another class

    return $UpdatedDetail;

  }

  public function convert_lead_to_contact($update_data){

    $objectJson=json_encode($update_data);
    $param=array("operation" => "convertlead", "sessionName" => $this->_sessionID, 'element' => $objectJson);
    $UpdatedDetail=crm_webservice::curl_execution($this->_url,$param,"POST");
    return $UpdatedDetail;
  }

}  // Class End
 ?>
