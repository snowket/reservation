<?php
/**
 * Booking engine wrapper.
 *
 * PHP versions 5
 *
 * WuBook  (http://www.wubook.net)
 * Copyright 2009, WuBook  (http://www.wubook.net)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         v 1.0
 * @version       v 1.0
 * @author    Marco Pergola (marco.pergola at gmail dot com)
 * @lastmodified  19/01/2010
 */

/**
 * Included libs
 * XML-RPC for PHP v 3.0.0beta (http://phpxmlrpc.sourceforge.net/)
 */
include "xmlrpc.inc";

class WuBook {

    /**
     * List of errors
     *
     * @var array
     * @access public
     */
    public $errors = array();

    /**
     * Name of Wubook account
     *
     * @var string
     * @access public
     */
    public $account;

    /**
     * Pkey
     *
     * @var string
     * @access public
     */
    public $Pkey;

    /**
     * Password of Wubook account
     *
     * @var string
     * @access public
     */
    public $password;

    /**
     * Token released from Wubook server
     *
     * @var string
     * @access public
     */
    public $token = null;

    /**
     * Xml- rpc Client
     *
     * @var object
     * @access private
     */
    private $xmlrpc_client = null;

    /**
     * Xml- rpc Client Debug
     *
     * @var object
     * @access private
     */
    private $xmlrpc_debug = 2;

    /**
     * Credit Cards information
     *
     * @var array
     * @access public
     */
    public $cc_family = array(
        1 => array('name' => 'Visa', 'cvv' => 1),
        2 => array('name' => 'MasterCard', 'cvv' => 1),
        4 => array('name' => 'Discover', 'cvv' => 1),
        8 => array('name' => 'American Express', 'cvv' => 1),
        16 => array('name' => 'Enroute', 'cvv' => 0),
        32 => array('name' => 'Jcb', 'cvv' => 0),
        64 => array('name' => 'Diners', 'cvv' => 0),
        128 => array('name' => 'Unknown', 'cvv' => 0),
        256 => array('name' => 'Maestro', 'cvv' => 0),
        512 => array('name' => 'Carte Blanche', 'cvv' => 0),
        1024 => array('name' => 'Australian BankCard', 'cvv' => 0),
        2048 => array('name' => 'Virtual Credit Card', 'cvv' => 0),
    );

    /**
     * Constructor. Initialize xml-rpc client, accoutn e password
     *
     * @param string $account Account name
     * @param string $password Account password
     */
    public function __construct($account, $password = null, $Pkey) {

        $this->xmlrpc_client = new xmlrpc_client('/xrws/', 'wubook.net', '443', 'https');
        $this->xmlrpc_client->setSSLVerifyPeer(0);
        $this->xmlrpc_client->setDebug(0);
        $this->xmlrpc_client->setSSLVerifyHost(2);

        $this->account = $account;
        $this->Pkey = $Pkey;
        $this->password = $password;
        $token_params = array(
            new xmlrpcval($this->account, 'string'),
            new xmlrpcval($this->password, 'string'),
            new xmlrpcval($this->Pkey, 'string'),
        );

        $xmlrpc_message = new xmlrpcmsg('acquire_token', $token_params);
        $xmlrpc_response = $this->xmlrpc_client->send($xmlrpc_message);
        $this->token = $this->validateResponse($xmlrpc_response);
        unset($xmlrpc_message);
        unset($xmlrpc_response);
    }

    /**
     * Call API method
     *
     * @param string $name Name of the method to call
     * @param string $params Parameters for the method.
     * @return array The response from API method
     * @access public
     */
    public function callMethod($name, $params) {

        $this->errors = array();
        if (!$this->token) {
            dd($xmlrpc_message);
            dd($xmlrpc_response);
            exit;
        }
        $params = array_merge(array($this->token), $params);

        foreach ($params as $param) {
            $xmlrpc_val[] = php_xmlrpc_encode($param);
        }

        $xmlrpc_message = new xmlrpcmsg($name, $xmlrpc_val);

        $xmlrpc_response = $this->xmlrpc_client->send($xmlrpc_message);

        $validate_response = $this->validateResponse($xmlrpc_response);

        if (!$validate_response) {
            $this->errors['xmlrpc_message'] = $xmlrpc_message;
        }

        return $validate_response;

    }

    /**
     * Check if XML-RPC response has error
     *
     * @param object $xmlrpc_response
     * @return mixed On success an array with data, on failure false
     * @access private
     */
    private function validateResponse($xmlrpc_response) {

        if (!$xmlrpc_response->faultCode()) {

            $xmlrpc_response = php_xmlrpc_decode($xmlrpc_response->value());
            if ($xmlrpc_response[0]) {
                $this->errors[] = array('code' => $xmlrpc_response[0], 'message' => $xmlrpc_response[1]);
                return false;
            } else {
                return $xmlrpc_response[1];
            }

        } else {
            $this->errors[] = array('code' => $xmlrpc_response->faultCode(), 'message' => $xmlrpc_response->faultString());
            return false;
        }

    }

    /**
     * Retrieve a list of facilities and their details matching the specified period
     *
     * @param mixed $lcodes Property Identifier
     * @param string $dfrom Arrival date (dates being in european format: 21/12/2012)
     * @param string $dto Departure  date (dates being in european format: 21/12/2012)
     * @return array Bookable room, addons and offers for the selected period.
     * - Available Rooms (with Addons and Reductions)
     * - Available Grouped Rooms
     * - General Addons and Reductions
     * - Special Offers
     * @access public
     */
    public function facilitiesRequest($lcodes, $dfrom, $dto) {

        if (!is_array($lcodes)) {
            $lcodes = array($lcodes);
        }

        $params = array($lcodes, $dfrom, $dto);
        $items = $this->callMethod('facilities_request', $params);

        $facilities = array();

        if (!empty($items)) {
            $i = 0;
            foreach ($items as $key => $item) {

                if ($item[0]) {
                    $this->errors[] = array('code' => $item[0], 'message' => $item[1]);
                } else {
                    $facilities[$i]['id'] = $key;
                    $facilities[$i]['rooms'] = $item[1][0];
                    $facilities[$i]['grouped_rooms'] = $item[1][1];
                    $facilities[$i]['addons'] = $item[1][2];
                    $facilities[$i]['offers'] = $item[1][3];
                    $i++;
                }
            }
        }

        return $facilities;

    }

    /**
     * Retrieve the facility details
     *
     * @param integer $lcode Property Identifier
     * @param string $dfrom Arrival date (dates being in european format: dd/mm/yyyy (21/12/2012))
     * @param string $dto Departure  date (dates being in european format: dd/mm/yyyy (21/12/2012))
     * @return array Bookable rooms, addons and offers for the selected period.
     * - Available Rooms (with Addons and Reductions)
     * - Available Grouped Rooms
     * - General Addons and Reductions
     * - Special Offers
     * @access public
     */
    public function facilityRequest($lcode, $dfrom, $dto) {

        $params = array($lcode, $dfrom, $dto);
        $item = $this->callMethod('facility_request', $params);

        $facility['id'] = $lcode;
        $facility['rooms'] = $item[0];
        $facility['grouped_rooms'] = $item[1];
        $facility['addons'] = $item[2];
        $facility['offers'] = $item[3];

        return $facility;
    }

    /**
     * Select the rooms to book later
     *
     * @param integer $lcode Property Identifier
     * @param array $rooms Rooms you want to book:
     *   int Room id
     *   int Room quantity
     *   (ie: array(111 => 2) where 111 is the room id and 2 is how many rooms you want to book)
     *
     * @return array Associated information for the rooms requested
     * - Daily Rooms Prices
     * - Credit Card Requirements
     * - Discount Type
     * - The Special Offer applied for this request
     * - Rooms Amount
     * - Rooms Addons/Reductions Amount
     * - Generic Addons/Reductions Amount
     * - Clean Amount (with no discount and offer)
     * - Total Amount (the amount with discount and offer applied)
     *
     * @access public
     */
    public function roomsRequest($lcode, $rooms, $opps = array(), $customer_code = null, $iata_code = null) {

        foreach ($rooms as $key => $value) {
            $room_reservations[] = array('number' => $value, 'id' => $key);
        }

        $params = array($lcode, $room_reservations, $opps, $customer_code, $iata_code);
        $room_details = $this->callMethod('rooms_request', $params);

        return $room_details;

    }

    /**
     * Book the last request called by room_request()
     *
     * @param integer $lcode Property Identifier
     * @param array $customer Traveler personal information:
     *   string fname First Name
     *   string lname Last Name
     *   string street Street
     *   string email Email
     *   string country Country code - two chars (it = Italy)
     *   string city City
     *   string phone Phone
     *   string notes Notes
     *   string arrival_hour Time of arrival
     * @param array $credit_card Credit card information:
     *   string ctype Family Id
     *   string cc_number Number
     *   string cc_cvv CVV
     *   string cc_exp_month Expiration Month
     *   string cc_owner Owner Name
     *   string cc_exp_year Expiration Year
     * @param array $iata Iata information:
     *   string iata_name Name
     *   string iata_street Street
     *   string iata_zip ZIP
     *   string iata_city City
     *   string iata_phone Phone
     *   string iata_email Email
     *   string iata_vat VAT
     *   string iata_code Code
     *
     * @return string Html invoice (this invoice is sent also to the customer and to the lodging)
     * @access public
     */
    public function bookLastRequest($customer, $credit_card = array(), $iata = array()) {

        $params = array($customer, $credit_card, $iata);
        return $this->callMethod('book_last_request', $params);

    }

}
