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
        case '2':  $tab = '2'; require_once($ROOT."/files/services.php"); break;
        case '3':  $tab = '3'; require_once($ROOT."/files/services.php"); break;
        case '4':  $tab = '4'; require_once($ROOT."/files/services.php"); break;
        case '6':  $tab = '6'; require_once($ROOT."/files/services.php"); break;
        case '7':  $tab = '7'; require_once($ROOT."/files/services.php"); break;
        case '8':  $tab = '8'; require_once($ROOT."/files/services.php"); break;
        case '9':  $tab = '9'; require_once($ROOT."/files/services.php"); break;
        case '10': $tab = '10';require_once($ROOT."/files/services.php"); break;
		default:   $tab = '2'; require_once($ROOT."/files/services.php"); break;
	}

	$query = "SELECT type_id, count(*) AS num FROM {$_CONF['db']['prefix']}_rooms_manager 
			  GROUP BY type_id";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	while($row = $result->FetchRow()){
		$counts[$row['type_id']] = $row['num'];
	}
	//*** Right side - Categories Tree  *******************//
	/*$TMPL->ParseIntoVar($_RIGHT,'search');
	$TMPL->addVar('TMPL_counts',$counts);
	$TMPL->addVar('TMPL_cats',GetRoomTypes());
	$TMPL->ParseIntoVar($_RIGHT,'categories_right');*/

//*****************************************************//
//*** DRAW TABS ***************************************//
if(!$LOADED_PLUGIN['restricted']){
    $tabs['2']	= $TEXT['tab']['2'];
    $tabs['3']	= $TEXT['tab']['3'];
    $tabs['7']	= $TEXT['tab']['7'];
    $tabs['4']	= $TEXT['tab']['4'];
    $tabs['6']	= $TEXT['tab']['6'];
    $tabs['9']	= $TEXT['tab']['9'];
    $tabs['8']	= $TEXT['tab']['8'];
    $tabs['10']	= $TEXT['tab']['10'];
	$_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
}


function GetServices($type=0) {
	global $_CONF,$CONN,$FUNC;
    $where_clause =($type!=0)?" type_id=" . $type:"";
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_room_services
				WHERE ".$where_clause;
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
        $services_types[$services_type['id']]['title']=$services_type['title_'.LANG];
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
		$data[$key]['title']=$FUNC->unpackData($value['title'],LANG);
	}	
	return $data;
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

function GetRooms($id='') {
	global $_CONF,$CONN,$FUNC;
	if ($id) {
		$q_add=' AND common_id='.(int)$id;
	}	
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
				WHERE publish=1 {$q_add}";
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
		$data[$key]['title']=$FUNC->unpackData($value['title'],LANG);
	}	
	return $data;
}
