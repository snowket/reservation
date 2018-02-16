<?
//**************************************************************//
//**************************************************************//
//                PRODUCTS PCMS PLUGIN                          // 
//**************************************************************//
//**************************************************************//
if(!defined('ALLOW_ACCESS')) exit;

$mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
#error_reporting(E_ALL);
$ROOT   = dirname(__FILE__);
$g_tab=$_GET['tab']?'&tab='.$_GET['tab']:'';
$SELF   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'].$g_tab;
$SELF_TABS   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];
require_once($ROOT."/lang/".LANG.".php");

$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("PLUGIN", $LOADED_PLUGIN['plugin']);
$TMPL->setRoot($ROOT);

$weekDays = 
array(
  'Mon',
  'Tue',
  'Wed',
  'Thu',
  'Fri',
  'Sat',
  'Sun',
);


	//*** Central side   ***********************************//
	// $tab = isset($_GET['tab'])?$_GET['tab']:'';
	
	switch($_GET['tab']){ 
		case 'items':		  	$tab = 'items';		   require_once($ROOT."/files/rooms.php");break;
		case 'room_types':	  	$tab = 'room_types'; 	   require_once($ROOT."/files/room_types.php"); break;
		case 'capacity':	  	$tab = 'capacity'; 	   require_once($ROOT."/files/capacity.php"); break;
		case 'extra_services':	$tab = 'extra_services'; require_once($ROOT."/files/extra_services.php"); break;
		case 'default_services':$tab = 'default_services'; require_once($ROOT."/files/default_services.php"); break;
        case 'services_types':  $tab = 'services_types'; require_once($ROOT."/files/services_types.php"); break;
		case 'blocks':		    $tab = 'blocks'; 		   require_once($ROOT."/files/blocks.php"); break;
		case 'gallery':		    $tab = 'items';		   require_once($ROOT."/files/gallery.php");break;
		case 'existed_rooms':   $tab = 'existed_rooms';       require_once($ROOT."/files/existed_rooms.php");break;
        case 'all_rooms':       $tab = 'all_rooms';       require_once($ROOT."/files/all_rooms.php");break;
		default:			  	$tab = 'blocks';		   require_once($ROOT."/files/blocks.php");break;
	} 

	$query = "SELECT type_id, count(*) AS num FROM {$_CONF['db']['prefix']}_rooms_manager 
			  GROUP BY type_id";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	while($row = $result->FetchRow()){
		$counts[$row['type_id']] = $row['num'];
	}

//*** DRAW TABS ***************************************//
if(!$LOADED_PLUGIN['restricted']){
	$tabs['blocks']	= $TEXT['tab']['blocks'];
    $tabs['room_types'] = $TEXT['tab']['room_types'];
    $tabs['capacity'] = $TEXT['tab']['room_cap'];
    $tabs['items']= $TEXT['tab']['rooms'];
    $tabs['existed_rooms']= $TEXT['tab']['existed_rooms'];
    $tabs['all_rooms']= $TEXT['tab']['all_rooms'];
	$_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
}



function GetCommon($id='') {
    global $_CONF,$CONN,$FUNC;
    $q_add=($id)?' AND id='.(int)$id:'';
    $query  = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager
				WHERE publish=1".$q_add;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $data=$result->getAssoc();
    return $data;
}

function GetServices() {
	global $_CONF,$CONN,$FUNC;
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_room_services
				WHERE publish=1
				ORDER BY price ASC";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	$data=$result->GetRows();	
	foreach ($data as $key => $value) {
		$data[$key]['title']=$FUNC->unpackData($value['title'],LANG);
	}	
	return $data;
}

function GetServicesTypes() {
    global $_CONF,$CONN, $FUNC;
    $query  = "SELECT * FROM {$_CONF['db']['prefix']}_room_services_types
				WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $data=$result->GetRows();
    foreach ($data as $services_type) {
        $services_types[$services_type['id']]=$services_type;
        $services_types[$services_type['id']]['title']=$FUNC->unpackData($services_type['title'],LANG);
    }
    return $services_types;
}

function GetRoomCapacity() {
	global $_CONF,$CONN,$FUNC;
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity
				WHERE publish=1";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	$data=$result->GetRows();
	
	foreach ($data as $key => $value) {
        $tmp=$value;
        $tmp['title']=$FUNC->unpackData($value['title'],LANG);
		$mapped[$tmp['id']]=$tmp;
	}	
	return $mapped;
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
	foreach ($data as $value) {
        $mapped[$value['id']]=$value;
        $mapped[$value['id']]['title']=$FUNC->unpackData($value['title'],LANG);
	}	
	return $mapped;
}

function GetRooms($id='') {
	global $_CONF,$CONN,$FUNC;
	if ($id) {
		$q_add=' AND common_id='.(int)$id;
	}	
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
				WHERE publish=1 {$q_add}
				ORDER BY floor ";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	$data=$result->GetRows();	
	return $data;
}

function GetRoomTypes($id='') {
	global $_CONF,$CONN,$FUNC;
	if ($id) {
		$q_add=' AND id='.(int)$id;
	}	
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_room_types
				WHERE publish=1 {$q_add}";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	$data=$result->GetRows();	
	foreach ($data as $key => $value) {
        $tmp=$value;
        $tmp['title']=$FUNC->unpackData($value['title'],LANG);
        $mapped[$tmp['id']]=$tmp;
	}
	return $mapped;

}

