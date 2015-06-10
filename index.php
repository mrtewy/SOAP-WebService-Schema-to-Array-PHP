<?php
session_start();
include_once 'scalwebservice.class.php';

ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
ini_set("soap.wsdl_cache_enabled", "0");

if(!extension_loaded("soap")){
  dl("php_soap.dll");
}
 
$Service 			= 	new ScalWebService();
$GetProductInfo 	= 	$Service->_GetProductInfo("1", "3850100163634", "0501130108139");
$GetDataInfo 		= 	$Service->_GetSchema($GetProductInfo->DataInfo);

$customs_args = array(
  'class'=>'Table1',
  'column_widths'=>array(
    'seq_no'=>100,
    'period'=>50,
    'pay_date'=>50,
    'due_date'=>100,
    'recv_date'=>100,
    'recv_amt'=>100,
    'pay_type'=>100,
    'recv_type'=>150,
    'transaction_desc'=>100,
    'installment'=>100,
    'contract_balance'=>100,
    'account_date'=>100,
  ),
  'custom_headers'=>array(
    'seq_no'=>'Sequence No.',
    'period'=>'',
    'pay_date'=>'',
    'due_date'=>'',
    'recv_date'=>'',
    'recv_amt'=>'',
    'pay_type'=>'',
    'recv_type'=>'',
    'transaction_desc'=>'',
    'installment'=>'',
    'contract_balance'=>'',
    'account_date'=>'',
  )
);
 
echo $Service->array_to_table($GetDataInfo, $customs_args);

?>

 
 


  
 