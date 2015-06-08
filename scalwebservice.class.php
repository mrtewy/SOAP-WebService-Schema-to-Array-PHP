<?php

/**
 * Class ScalWebService
 * @package SummitService
 * @author 
 */

class ScalWebService
{
    private $_client;

    var $params     = array();
 
    public function __construct()
    {
        try {           
            if(!extension_loaded("soap")){
                die("Please enable php_soap.dll");
            } else {
                $this->_client = new SoapClient('http://223.27.241.101/SummitService/SummitSvc.svc/basic?wsdl');
            }
        } catch (Exception $e) {
            throw new Exception("Error Processing Request". $e->getMessage(), 1);
        }
    }

    /**
    * Description: return all functions in Serverside
    * @return array 
    */
    public function getFunctions()
    {
        return $this->_client->__getFunctions();
    }

    /**
    * Description: Do the action, call function from the Server-side
    * @return array 
    */
    public function soapCall($function, $params)
    {
        return $this->_client->__soapCall($function, array($params));
    }

    public function _respond($type, $obj, $input = 1)
    {
        if($obj == 'GetProductInfoResult')
        {
            switch ($type) {
                case 1:
                die("Data not found");
                    break;
                case 2:
                die("Unable to connect database");
                    break;
            }
        } else {
            if($input == 1)
            {
                switch ($type) {
                    case 1:
                    die("Data not found");
                        break;
                    case 2:
                    die("Mobile Phone Not Found");
                        break;
                    case 3:
                    die("Unable to connect database");
                        break;
                }
            } else {
                switch ($type) {
                    case 1:
                    die("Data not found");
                        break;
                    case 2:
                    die("Unable to connect database");
                        break;
                }
            }
        }
    }

    /**
    * Usage:    _GetProductInfo('$CustomerId','$ContractNo')
    * @param    $Type = Type of products
    *           $Type = '1' Payment
    *           $Type = '2' Loan
    *           $Type = '3' Taxes
    *
    * @return   array of requested data
    *           result of return:
    *           result=0     Data founded
    *           result=1     Data Not Found
    *           result=2     Unable to connect database
    */
    public function _GetProductInfo($CustomerId, $ContractNo, $Type = 1, $SessionId = 'SOFTCONTROL')
    {
        $params = array(
            'CustomerId'    =>  $CustomerId,
            'Type'          =>  $Type,
            'SessionId'     =>  $SessionId,
            'ContractNo'    =>  $ContractNo,
            'Name'          =>  ' ',
            'LastName'      =>  ' ',
            'DataInfo'      =>  ''
            );

        $respond = $this->soapCall("GetProductInfo", $params);
        if($respond->GetProductInfoResult == 0){
            return $respond;
        }
        return $this->_respond($respond->GetProductInfoResult, 'GetProductInfoResult');
    }

    /**
    * Usage:    _CustomerInfo('$CustomerId','$Type')
    * @param    $Type = Type of Information
    *           $Type = '1'  Register
    *           $Type = '2'  Login
    *           $Type = '3'  Changing
    * @return   array of requested data
    *           result of return:
    *           $Type '1'
    *           result=0     Data founded
    *           result=1     ID_Card Not Found
    *           result=2     Mobile Phone Not Found
    *           result=3     Unable to connect database
    *           =====================================================
    *           $Type = '2' AND $Type = '3'
    *           result=0     Data founded
    *           result=1     Data not found
    *           result=2     Unable to connect database
    * 
    */
    public function _CustomerInfo($CustomerId, $Type, $SessionId = 'SOFTCONTROL', $TelephoneNo = ' ')
    {
        $params = array(
            'CustomerId'    =>  $CustomerId,
            'Type'          =>  $Type,
            'SessionId'     =>  $SessionId,
            'TelephoneNo'   =>  $TelephoneNo,
            'Name'          =>  ' ',
            'LastName'      =>  ' ',
            'Email'         =>  ' ',
            'PersonType'    =>  ' ',
            );

        $respond = $this->soapCall("GetCustomerInfo", $params);
        if($respond->GetCustomerInfoResult == 0){
            return $respond;
        }
        return $this->_respond($respond->GetCustomerInfoResult, 'GetCustomerInfoResult');
    }
 
    /**
    * Usage:    _GetSchema($_Function->DataInfo)
    * @param    $obj_params
    * Return:   Array 
    */
    public function _GetSchema($obj_params)
    {
        $schema_info = array_values((array)$obj_params);
        $schema_info_xml = '<root>' . $schema_info[0] . '</root>';

        $doc = new DOMDocument();
        $doc->loadXML($schema_info_xml);
        $xpath = new DOMXpath($doc);

        $data = array();
        foreach ($xpath->query('//myTable') as $item) {
            $row = array();
            foreach ($item->childNodes as $node) {
                $row[$node->nodeName] = $node->nodeValue;
            }
            $data []= $row;
        }
        return $data;
    }
  
}

?>