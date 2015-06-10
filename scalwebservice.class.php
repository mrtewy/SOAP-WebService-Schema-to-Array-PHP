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
    public function _GetProductInfo($Type = 1, $CustomerId, $ContractNo, $SessionId = 'SOFTCONTROL')
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
    public function _CustomerInfo($Type, $CustomerId, $SessionId = 'SOFTCONTROL', $TelephoneNo = ' ')
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

    public function _GennerateOTP($CustomerId, $TelephoneNo)
    {
        $params = array(
            'CustomerId'    =>  $CustomerId,
            'SessionId'     =>  ' ',
            'TelephoneNo'   =>  $TelephoneNo,
            'OTP'           =>  ' ',
            );

        $respond = $this->soapCall("GennerateOTP", $params);
        if($respond->GennerateOTPResult){
            return $respond;
        }
        return $respond->GennerateOTPResult;
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


    function array_to_table($data, $args=false) {
        if (!is_array($args)) { $args = array(); }
        foreach (array('class','column_widths','custom_headers','format_functions','nowrap_head','nowrap_body','capitalize_headers') as $key) {
            if (array_key_exists($key,$args)) { $$key = $args[$key]; } else { $$key = false; }
        }
        if ($class) { $class = ' class="'.$class.'"'; } else { $class = ''; }
        if (!is_array($column_widths)) { $column_widths = array(); }

        //get rid of headers row, if it exists (headers should exist as keys)
        if (array_key_exists('headers',$data)) { unset($data['headers']); }

        $t = '<table'.$class.'>';
        $i = 0;
        foreach ($data as $row) {
            $i++;
            //display headers
            if ($i == 1) { 
                foreach ($row as $key => $value) {
                    if (array_key_exists($key,$column_widths)) { $style = ' style="width:'.$column_widths[$key].'px;"'; } else { $style = ''; }
                    $t .= '<col'.$style.' />';
                }
                $t .= '<thead><tr>';
                foreach ($row as $key => $value) {
                    if (is_array($custom_headers) && array_key_exists($key,$custom_headers) && ($custom_headers[$key])) { $header = $custom_headers[$key]; }
                    elseif ($capitalize_headers) { $header = ucwords($key); }
                    else { $header = $key; }
                    if ($nowrap_head) { $nowrap = ' nowrap'; } else { $nowrap = ''; }
                    $t .= '<td'.$nowrap.'>'.$header.'</td>';
                }
                $t .= '</tr></thead>';
            }

            //display values
            if ($i == 1) { $t .= '<tbody>'; }
            $t .= '<tr>';
            foreach ($row as $key => $value) {
                if (is_array($format_functions) && array_key_exists($key,$format_functions) && ($format_functions[$key])) {
                    $function = $format_functions[$key];
                    if (!function_exists($function)) { custom_die('Data format function does not exist: '.htmlspecialchars($function)); }
                    $value = $function($value);
                }
                if ($nowrap_body) { $nowrap = ' nowrap'; } else { $nowrap = ''; }
                $t .= '<td'.$nowrap.'>'.$value.'</td>';
            }
            $t .= '</tr>';
        }
        $t .= '</tbody>';
        $t .= '</table>';
        return $t;
    }

  
}

?>