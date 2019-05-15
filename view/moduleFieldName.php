<?php


include('constant.inc');
include(ROOT_PATH.'controller/classes/crm_webservice.php');
include(ROOT_PATH.'controller/auth.php');
include(ROOT_PATH.'controller/classes/query.php');


// Get the url and sessionid from auth.php
// create instance for class
$qryobj=new crm_crudoperation($endpointUrl,$sessionid);

//$describeData = call($endpointUrl, array("operation" => "describe", "sessionName" => $sessionid,"elementType"=>"Leads"));
$elementType="Contacts";
$describeData=$qryobj->crm_getfieldname($elementType);

$obj = json_decode (json_encode ($describeData), FALSE);
$name=$obj->result->name;
$fields=$obj->result->fields;

$html="<table width=60% border=1 cellpadding=0 cellspacing=0 >
     <tr height=40 ><th colspan='3' >".$name."<th></tr>
     <tr height=40 >
     <th>Name</th>
     <th>Label</th>
     <th>Value</th>
     </tr>
";

foreach($fields as $key => $value) {
        $mandatory=$fields[$key]->mandatory;
        $st="";
        if($mandatory == 1){
            $st="style=color:red";
        }
    $html.="<tr height=40 ".$st." >
        <td>".$fields[$key]->label."</td>
        <td>".$fields[$key]->name."</td>
        <td>";
        $datatype=$fields[$key]->type->name;
        if($fields[$key]->type->picklistValues){
            $picklistValues=$fields[$key]->type->picklistValues;
            $html.="<div style='50%;height:100px;overflow-y:scroll'>";
            foreach($fields as $key => $value) {
                $html.="<p>".$picklistValues[$key]->value."</p>";

            }
            $html.="</div>";
        }else{
            $html.=$datatype;
        }


        $html.="</td>
        </tr>";
}

$html.="</table>";

echo $html;
echo "<br /><hr /><br />";

$elementType="Leads";
$describeData=$qryobj->crm_getfieldname($elementType);

$obj = json_decode (json_encode ($describeData), FALSE);
$name=$obj->result->name;
$fields=$obj->result->fields;

$html="<table width=60% border=1 cellpadding=0 cellspacing=0 >
     <tr height=40 ><th colspan='3' >".$name."<th></tr>
     <tr height=40 >
     <th>Name</th>
     <th>Label</th>
     <th>Value</th>
     </tr>
";

foreach($fields as $key => $value) {
        $mandatory=$fields[$key]->mandatory;
        $st="";
        if($mandatory == 1){
            $st="style=color:red";
        }
    $html.="<tr height=40 ".$st." >
        <td>".$fields[$key]->label."</td>
        <td>".$fields[$key]->name."</td>
        <td>";
        $datatype=$fields[$key]->type->name;
        if($fields[$key]->type->picklistValues){
            $picklistValues=$fields[$key]->type->picklistValues;
            $html.="<div style='50%;height:100px;overflow-y:scroll'>";
            foreach($fields as $key => $value) {
                $html.="<p>".$picklistValues[$key]->value."</p>";

            }
            $html.="</div>";
        }else{
            $html.=$datatype;
        }


        $html.="</td>
        </tr>";
}

$html.="</table>";

echo $html;
?>
