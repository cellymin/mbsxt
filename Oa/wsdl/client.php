<meta charset="utf-8"/>
<?php 
ini_set("error_reporting","E_ALL & ~E_NOTICE"); 
 $client = new SoapClient('http://120.redcrossfy.com/oa/wsdl/server.php?wsdl');
$aaa =  ($client->qubiao('2016-05-02','2016-05-03','1','fjdkls39cmxdu3rjcxcDFDS23D')); 
//$aaa =  ($client->daozhen('271','fjdkls39cmxdu3rjcxcDFDS23D')); 
//$aaa =  ($client->yymd('','王敏',''));
//echo "<pre>";
//echo  $aaa;
//print_r(json_decode($aaa));
echo $aaa;
?> 