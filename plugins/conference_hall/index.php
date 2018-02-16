<?php
if(!defined('ALLOW_ACCESS')) exit;
require_once("classes/mpdf/mpdf.php");
$ROOT = dirname(__FILE__);
$SELF = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$SELF_TABS = $_SERVER['PHP_SELF'] . "?m=" . $LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];
$TABLE_ITEMS='cms_ch_items';
$TABLE_SERVICES='cms_ch_services';


require_once($ROOT."/lang/".LANG.".php");
$TMPL->setRoot($ROOT);
$TMPL->addVar("SELF", $SELF);

if (isset($_GET['excel'])) {
	require_once($ROOT."/download.php");
}

if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
    require_once($ROOT . "/files/" . $tab . ".php");
} else {
    reset($TEXT['tab']);         //Moves array pointer to first record
    $tab = key($TEXT['tab']);     //Returns current key
   header("Location: index.php?m=conference_hall&tab=" . $tab);
}

if (!$LOADED_PLUGIN['restricted']) {
    //$_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
    $_CENTER = pcmsInterface::drawModernTabs("{$SELF_TABS}&tab=", $TEXT['tab'], $tab) . $_CENTER;
}
$TMPL->ParseIntoVar($_RIGHT,'search');

function addBooking($guest_id,$general_price,$custom_price,$check_in,$check_out,$room_ids,$from_calendar,$to_calendar,$adult_num,$booking_comment,$services){
    global $CONN, $FUNC, $_CONF;
        if($custom_price!=0){
            $price=$custom_price;
        }else{
            $price=$general_price;
        }

        $query="INSERT INTO cms_ch_booking SET guest_id=".$guest_id." ,accommodation_price=".$price
        .",check_in='".date('Y-m-d',strtotime($check_in))." ".date('H:m:s',strtotime($from_calendar))."
        ',check_out='".date('Y-m-d',strtotime($check_out))." ".date('H:m:s',strtotime($to_calendar))."'
        ,adult_num=".$adult_num.",comment='".$booking_comment."',administrator_id=".$_SESSION['pcms_user_id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $inserted_id=$CONN->Insert_ID();
				$tmpService=array();
				foreach ($services as $key => $value) {
					$tmpService[$value]=$adult_num;
				}
        foreach($room_ids as $id){
              $query="INSERT INTO cms_ch_booking_info SET room_id=".$id.",booking_id=".$inserted_id.",price=".$price.",services='".serialize($tmpService)."'" ;
              $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
          }
    return $inserted_id;

}
function getGuestByID($guest_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
                FROM {$_CONF['db']['prefix']}_guests
                WHERE id={$guest_id}";
    $guest = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $guest;
}
function getBookings(){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT t1.* FROM cms_ch_booking t1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as $key=> $booking){
        $result[$key]['rooms']=getBookingRooms($booking['id']);
        $result[$key]['guest']=getBookingGuest($booking['guest_id']);
        $price=0;
        foreach( $result[$key]['rooms'] as $room){
            $price+=$room['price'];
        }
        $result[$key]['price']=$price;
    }
    return $result;
}
function getBooking($id){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT t1.* FROM cms_ch_booking t1 WHERE t1.id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as $key=> $booking){
        $result[$key]['rooms']=getBookingRooms($booking['id']);
        $result[$key]['guest']=getBookingGuest($booking['guest_id']);
        $result[$key]['info']=getBookingInfo($booking['id']);
    }
    return $result[0];
}
function getBookingbyQuery($where){
    global $CONN, $FUNC, $_CONF;
    $bwhere=getBookingquery($where);
    $rwhere=getBookingGuestquery($where);
    $query="SELECT t1.* FROM cms_ch_booking t1 WHERE 1=1".$bwhere;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as $key=> $booking){
        $result[$key]['rooms']=getBookingRooms($booking['id']);
        $result[$key]['guest']=getBookingGuest($booking['guest_id']);
    }
    return $result;
}
function getBookingGuestquery($where){
    $return='';
    if($where['guest_name']){
        $return.="AND name like '%".$where['guest_name']."%'";
    }
    return $return;
}
function getBookingquery($where){
    $return='';
    if($where['booking_id'] && is_int((int)$where['booking_id'])){
        $return.=' AND id='.$where['booking_id'];
    }
    if($where['in_start_date'] && $where['in_end_date']){
        $return.=" AND check_in between '".$where['in_start_date']."' AND '".$where['in_end_date']."'";
    }
    if($where['guest_id'] && is_int($where['guest_id']) ){
        $return.=' AND guest_id='.$where['guest_id'];
    }
    return $return;
}
function getBookingInfo($id){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT * FROM cms_ch_booking_info WHERE booking_id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->fields;

    return $result;
}
function getBookingGuest($id){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT CONCAT(first_name,' ',last_name) as name FROM cms_guests WHERE id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->fields;

    return $result;
}
function getAllCountries()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_country WHERE publish =1 ORDER BY sort_id DESC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $result->GetRows();
}
function getBookingRooms($id){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT t1.*,t2.name,t2.type_id FROM cms_ch_booking_info t1
     LEFT JOIN cms_ch_rooms t2 ON t1.room_id=t2.id
     WHERE booking_id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as $key=>$value){
        $result[$key]['name']=$FUNC->unpackData($value['name'],LANG);
        $result[$key]['services']=$FUNC->unpackData($value['services']);
        $result[$key]['types']=getRoomType($FUNC->unpackData($value['type_id']));
    }

    return $result;
}
function getRoomType($id){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_rooms_types WHERE id in (".implode(',',$id).")";
  #  dd($query);
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $result->getRows();
}
function setCH_service($name,$price,$type){
    global $CONN, $FUNC, $_CONF;
    $query="INSERT INTO {$_CONF['db']['prefix']}_ch_services SET name='{$name}',price={$price},type_id='{$type}'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return true;
}
function setCh_prices($items){
    global $CONN, $FUNC, $_CONF;
    $from=$items['start_date'].":00";
    $to=$items['end_date'].":00";
    $ch_room_id=$items['room_id'];
    foreach($items['callender'] as $key=> $item){
        $day=$key;
        $price=$item['price'];
        $query="INSERT INTO cms_ch_prices SET from_time='".$from."',to_time='".$to."',price=".$price.",day='".$day."',ch_room_id=".$ch_room_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    }
    if($CONN->Affected_Rows()){
        return true;
    }else{
        return false;
    }

}
function setCH_room($name,$type){
    global $CONN, $FUNC, $_CONF;
    $name=serialize($name);

    $type=serialize(array_values($type));
    $query="INSERT INTO {$_CONF['db']['prefix']}_ch_rooms SET name='{$name}',type_id='{$type}'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if($CONN->Affected_Rows()){
        return true;
    }else{
        return false;
    }
}
function setCH_service_type($name,$type){
    global $CONN, $FUNC, $_CONF;
    $query="INSERT INTO {$_CONF['db']['prefix']}_ch_services_types SET name='{$name}' , type_of=".$type;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if($CONN->Affected_Rows()){
        return true;
    }else{
        return false;
    }
}
function setCH_room_type($name,$capacity){
    global $CONN, $FUNC, $_CONF;
    $query="INSERT INTO {$_CONF['db']['prefix']}_ch_rooms_types SET name='{$name}' , capacity={$capacity}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if($CONN->Affected_Rows()){
        return true;
    }else{
        return false;
    }
}

function updateCH_service($name,$price,$type,$id){
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query="UPDATE {$_CONF['db']['prefix']}_ch_services SET name='{$name}',price={$price},type_id='{$type}' WHERE id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return true;
}
function updateCH_service_type($id,$name,$type){
    global $CONN, $FUNC, $_CONF;
    $query="UPDATE {$_CONF['db']['prefix']}_ch_services_types SET
        name='".$name."' ,
        type_of=".$type."
        WHERE id=".$id;

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if($CONN->Affected_Rows()){
        return true;
    }else{
        return false;
    }
}

function updateCH_room_type($id,$name,$capacity){
    global $CONN, $FUNC, $_CONF;
    $query="UPDATE {$_CONF['db']['prefix']}_ch_rooms_types SET name='".$name."',capacity=".$capacity." WHERE id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
  if($CONN->Affected_Rows()){
      return true;
  }else{
      return false;
  }
}

function updateCH_room($id,$name,$type){
    global $CONN, $FUNC, $_CONF;
    $name=serialize($name);
    $type=serialize(array_values($type));
    $query="UPDATE {$_CONF['db']['prefix']}_ch_rooms SET name='".$name."',type_id='".$type."' WHERE id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if($CONN->Affected_Rows()){
        return true;
    }else{
        return false;
    }
}

function getCH_service_type(){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_services_types";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as $row){
        $row['name']=$FUNC->unpackData($row['name'],LANG);
    }

    return $result;
}


function getCH_room_type(){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_rooms_types";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as $row){
        $row['name']=$FUNC->unpackData($row['name'],LANG);
    }
    return $result;
}
function getCh_prices($item){
    global $CONN, $FUNC, $_CONF;

    $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_prices WHERE ch_room_id=".$item." ORDER BY from_time ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();

    return $result;
}
function getCH_weekdays(){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_weekdays";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();

    return $result;
}

function getCH_room(){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_rooms";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();

    foreach($result as $key=>$row){
        $result[$key]['name']=$FUNC->unpackData( $result[$key]['name'],LANG);
        $TMP_type_id=$FUNC->unpackData( $result[$key]['type_id']);
        $tmp=implode(",",array_values($TMP_type_id));

        $rs="SELECT * FROM {$_CONF['db']['prefix']}_ch_rooms_types WHERE id in (".$tmp.")";
        $rs = $CONN->Execute($rs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $rs=$rs->getRows();
        $result[$key]['types']=$rs;
    }
    return $result;
}


function getCH_service($type=null,$id=null){
    global $CONN, $FUNC,$_CONF;
    if($type){
        $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_services WHERE type_id={$type}";
    }else{
        if($id){
					$query="SELECT CS.id,CS.name,CST.name as c_name,CS.price FROM {$_CONF['db']['prefix']}_ch_services CS
	                LEFT JOIN {$_CONF['db']['prefix']}_ch_services_types CST ON CST.id=CS.type_id WHERE CS.id=".$id;
				}else{
					$query="SELECT CS.id,CS.name,CST.name as c_name,CS.price FROM {$_CONF['db']['prefix']}_ch_services CS
	                LEFT JOIN {$_CONF['db']['prefix']}_ch_services_types CST ON CST.id=CS.type_id";
				}
    }
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as $key=>$row){
        $result[$key]['name']=$FUNC->unpackData($row['name'],LANG);
    }

    return $result;
}
function deleteCH_service($id){
    global $CONN, $FUNC,$_CONF;


}
function deleteCH_price($id,$start_date){
    global $CONN, $FUNC,$_CONF;

    $query="DELETE FROM cms_ch_prices WHERE ch_room_id=".$id." AND from_time='".$start_date."'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if($CONN->Affected_Rows()){
        return true;
    }else{
        return false;
    }
}
function getAllBookingsSorted($start,$end){
    global $CONN, $FUNC,$_CONF;
    $query="SELECT CB.*,CBI.room_id FROM cms_ch_booking CB
     LEFT JOIN cms_ch_booking_info CBI on CB.id=CBI.booking_id
     WHERE check_in>'".$start."' AND check_out<'".$end."' GROUP BY CB.id";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $bookings=$result->getRows();
    foreach($bookings as $key=>$value){
        $bookings[$value['room_id']][date('Y-m-d',strtotime($value['check_in']))]=$value;
        unset($bookings[$key]);
    }
    #dd($bookings);
    return $bookings;
}

function create_pdf($pdf_path, $html)
{
    global $TEXT, $settings;
    $mpdf = new mPDF('', 'A4', '8', '', 15, 15, 20, 20, 5, 5);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->useSubstitutions = true;
    $mpdf->SetHTMLFooter("<div style='text-align:center; padding-bottom: 4px; background-color: #333333; color:white; width:100%;'>" . $TEXT['invoice']['rule_1'] . "</div><div style='text-align:center; padding-bottom: 4px; background-color: #333333; color:white; width:100%;'>" . $settings['ltd']['value'] . " " . $settings['address']['value'] . " " . $settings['tel']['value'] . " " . $settings['e_mail']['value'] . "</div>");
    $mpdf->WriteHTML($html);
    $mpdf->Output($pdf_path, 'F');
    return $pdf_path;
}
