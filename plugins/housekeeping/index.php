<?php
if(!defined('ALLOW_ACCESS')) exit;

$ROOT = dirname(__FILE__);
$SELF = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];

require_once($ROOT."/lang/".LANG.".php");
$TMPL->setRoot($ROOT);
$TMPL->addVar("SELF", $SELF);


switch($_GET['tab']){
    case 'housekeeping':	    $tab = 'housekeeping';    require_once($ROOT."/files/housekeeping.php");	break;
	default:			$tab = 'housekeeping';   	require_once($ROOT."/files/housekeeping.php");	break;
}

$tabs['housekeeping']		        = 'housekeeping';


$_CENTER= pcmsInterface::drawTabs("{$SELF}&tab=",$tabs,$tab).$_CENTER;


function getJoinedRooms($where_clause = '')
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT R.*, M.type_id , M.capacity_id, M.block_id
              FROM {$_CONF['db']['prefix']}_rooms AS R,{$_CONF['db']['prefix']}_rooms_manager AS M
              WHERE R.common_id=M.id" . $where_clause;
    //p($query);
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $rooms = $result->GetRows();
    $spending_materials=GetSpendingMaterials();
    $miniBarItems=GetMiniBarItems();
    foreach($rooms AS $room){
        $room['spending_materials']=$spending_materials[$room['id']];
        $room['minibar_items']=$miniBarItems[$room['id']];
        $tmp_rooms[$room['id']]=$room;
    }
    return $tmp_rooms;

}

function getRoomsStateByDate($date)
{
    global $CONN, $FUNC, $_CONF;

    $query = "SELECT B.id,B.room_id,D.booking_id, D.date,D.type
              FROM {$_CONF['db']['prefix']}_booking AS B
              LEFT JOIN {$_CONF['db']['prefix']}_booking_daily AS D
              ON B.id=D.booking_id
              WHERE B.active=1 AND D.date='".$date."'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $states = $result->GetRows();
    foreach($states AS $state){
        $roomStates[$state['room_id']][]=$state['type'];
    }
    return $roomStates;
}

function GetSpendingMaterials($date = ''){
    global $_CONF, $CONN, $FUNC;
    if ($date=='') {
        $date=date('Y-m-d',time());
    }
    $service_type_id=10;
    $services=GetServices($service_type_id);
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_spending_materials
              WHERE date='".$date."' AND service_type_id=".$service_type_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $spending_materials = $result->GetRows();
    foreach ($spending_materials as $spending_material) {
        $mappedSpendingMaterials[$spending_material['room_id']][$services[$spending_material['service_id']]['title']][]=$spending_material;

    }
    return $mappedSpendingMaterials;
}

function GetMiniBarItems($date = ''){
    global $_CONF, $CONN, $FUNC;
    $service_type_id=4;
    if ($date=='') {
        $date=date('Y-m-d',time());
    }
    $services=GetServices($service_type_id);
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_spending_materials
              WHERE date='".$date."' AND service_type_id=".$service_type_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $miniBarItems = $result->GetRows();
    foreach ($miniBarItems as $miniBarItem) {
        $mappedMiniBarItems[$miniBarItem['room_id']][$services[$miniBarItem['service_id']]['title']][]=$miniBarItem;

    }
    return $mappedMiniBarItems;
}

function GetServices($type=0) {
    global $_CONF,$CONN,$FUNC;
    $where_clause =($type!=0)?" type_id=" . $type:"";
    $query  = "SELECT * FROM {$_CONF['db']['prefix']}_room_services
				WHERE ".$where_clause;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $data=$result->GetRows();
    $tmp_data=array();
    foreach ($data as $key => $value) {
        $value['title']=$FUNC->unpackData($value['title'],LANG);
        $tmp_data[$value['id']]=$value;

    }
    return $tmp_data;
}

function GetRoomTypes($id = '')
{
    global $_CONF, $CONN, $FUNC;
    if ($id) {
        $q_add = ' AND id=' . (int)$id;
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_types
				WHERE publish=1 {$q_add}";
    //p($query);
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    foreach ($data as $key => $value) {
        $mappedRoomTypes[$value['id']]['id']=$value['id'];
        $mappedRoomTypes[$value['id']]['title']=$FUNC->unpackData($value['title'], LANG);
    }
    return $mappedRoomTypes;
}

function GetBlocks($id='') {
    global $_CONF,$CONN,$FUNC;
    if ($id) {
        $q_add=' AND id='.(int)$id;
    }
    $query  = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks
				WHERE publish=1 {$q_add}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $data=$result->GetRows();
    foreach ($data as $key => $value) {
        $data[$key]['title']=$FUNC->unpackData($value['title'],LANG);
    }
    return $data;
}