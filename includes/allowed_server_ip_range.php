<?php
if(!defined('ALLOW_ACCESS')) exit;


$ranges=array(
    '91.208.144.0/255',
    '185.163.200.0/23',
    '91.212.213.0/255',
    '91.239.206.0/255',
    '91.239.207.0/255',
    '212.58.116.64/95'
);

$djc9d8g8gd8d8d90=0;
foreach($ranges as $range){
    if(ip_in_range( $_SERVER['SERVER_ADDR'], $range )){
        $djc9d8g8gd8d8d90++;
    }
}

function ip_in_range_old( $ip, $range ) {
    $tmp=explode('/',$range);
    $tmp2=explode('.',$tmp[0]);
    echo ip2long($tmp[0]) < ip2long($ip) && ip2long($ip) < ip2long($tmp2[0].'.'.$tmp2[1].'.'.$tmp2[2].'.'.$tmp[1]);
    return (ip2long($tmp[0]) < ip2long($ip) && ip2long($ip) < ip2long($tmp2[0].'.'.$tmp2[1].'.'.$tmp2[2].'.'.$tmp[1]));
}

function ip_in_range( $ip, $range ) {
    if ( strpos( $range, '/' ) == false ) {
        $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list( $range, $netmask ) = explode( '/', $range, 2 );
    $range_decimal = ip2long( $range );
    $ip_decimal = ip2long( $ip );
    $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
    $netmask_decimal = ~ $wildcard_decimal;
    return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
}