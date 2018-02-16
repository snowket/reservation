<?php

$whereclouse="WHERE 1=1";
if(isset($_GET['keyword']) && $_GET['keyword'] !=''){
    $keyword=mysql_real_escape_string($_GET['keyword']);
    $whereclouse.=" AND CONCAT(`first_name`, `last_name`) like '%{$keyword}%'";
}



if(isset($_GET['start_date']) && $_GET['start_date'] !=''){
    $whereclouse.=" AND end_date>='{$_GET['start_date']}'";
}else{
    $whereclouse.=" AND end_date>='".date('y-d-m')."'";
}
if(isset($_GET['end_date']) && $_GET['end_date'] !=''){
    $whereclouse.=" AND end_date<='{$_GET['end_date']}'";
}
else{
    $whereclouse.=" AND end_date<='".date('y-d-m')."'";
}


$TMPL->addVar("TMPL_logs", getTransactionLogs($whereclouse));
$TMPL->ParseIntoVar($_CENTER, "transaction_log");