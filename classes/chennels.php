<?php
if(!defined('ALLOW_ACCESS')) exit;
error_reporting(E_ALL);
$chanell_settings=getChanellSettings();
function GetConnection() {
    $wubook = new WuBook($chanell_settings['h_name'], $chanell_settings['h_password'], 'a3eedf391d302cea538d9cc1dcb515ff43a5f694a928931e');
    return $wubook;
}
function GetConnectionCor() {
    $wubook = new WuBook('RN045', 'proservice', 'a3eedf391d302cea538d9cc1dcb515ff43a5f694a928931e');
    return $wubook;
}

require 'wiredx_beta.php';
