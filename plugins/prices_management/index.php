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
		case 'price_manager': 	$tab = 'price_manager'; require_once($ROOT."/files/price_manager.php");break;
        case 'discounts': 		$tab = 'discounts';  	require_once($ROOT."/files/discounts.php");break;
		case 'parameters':	  	$tab = 'parameters';       require_once($ROOT."/files/parameters.php");break;
		default:			  	$tab = 'items';		   require_once($ROOT."/files/price_manager.php");break;
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
	$tabs = array();
    $tabs['price_manager'] = $TEXT['tab']['price'];
	$tabs['discounts']	= $TEXT['tab']['discounts'];
    //$tabs['parameters']	= $TEXT['tab']['parameters'];
	$_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
}


function GetServices($type='default') {
	global $_CONF,$CONN,$FUNC;
	if (!in_array($type,array('default','extra'))) {
		return false;
	}	
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_room_services
				WHERE publish=1 AND type='{$type}'";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	$data=$result->GetRows();	
	foreach ($data as $key => $value) {
		$data[$key]['title']=$FUNC->unpackData($value['title'],LANG);
	}	
	return $data;
}


function GetRoomCapacity() {
	global $_CONF,$CONN,$FUNC;
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity
				WHERE publish=1";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	$data=$result->GetRows();
	foreach ($data as $key => $value) {
		$data[$key]['title']=$FUNC->unpackData($value['title'],LANG);
        $modified[$value['id']]=$data[$key];
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
        $modified[$value['id']]=$data[$key];
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
	global $_CONF,$CONN, $FUNC;
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

function modifyArrayBy($data,$by='id')
{
    foreach ($data as $key => $value) {
        $modified[$value[$by]] = $data[$key];
    }
    return $modified;
}