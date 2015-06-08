<?php

include_once 'scalwebservice.class.php';

ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
ini_set("soap.wsdl_cache_enabled", "0");

if(!extension_loaded("soap")){
  dl("php_soap.dll");
}
 
$Service 			= new ScalWebService();
$GetCustomerInfo 	= $Service->_CustomerInfo('3770100148466', '3','032811226');
$GetSchema 	 		= $Service->_GetSchema($GetCustomerInfo->AddressInfo);
 

// var_dump($GetCustomerInfo);
// var_dump($GetSchema);
 

?>
