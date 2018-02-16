<?
session_start();
error_reporting(E_ALL && ~E_NOTICE);
//*******************************************************//
//*** Authorization  ************************************//
header('Content-Type: text/html; charset=utf-8');
if (!isset($_SESSION['pcms_user_id']) || !isset($_SESSION['pcms_user_group'])) {
    echo "ACCESS DENIED!";
    exit;
}


define("ALLOW_ACCESS", true);
//*******************************************************//
//*** Including base classes ****************************//
require_once("./config.php");
require_once("./common.php");
$FUNC = new CommonFunc();

//*******************************************************//
//*** Setting site language  ****************************//
DEFINE('LANG', $FUNC->SetLang("langs_pcms"));
require_once("lang/" . LANG . ".php");

require_once("./classes/adodb/adodb.inc.php");
require_once("./classes/datavalidator/validator.class.php");
require_once("./classes/pcmsInterface.php");
require_once("./classes/pcmsTemplate/pcmsTemplate.php");


$TMPL = new pcmsTemplate();
$VALIDATOR = new Validator();
$INTERFACE = new pcmsInterface();

$TMPL->importVars("TEXT");

//*******************************************************//
//*** Establishing connection with database *************//
$CONN = &ADONewCONNection($_CONF['db']['type']);
$CONN->connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass'], $_CONF['db']['name']);
$CONN->debug = 0;
$CONN->setFetchMode(ADODB_FETCH_ASSOC);

$query = "SET NAMES utf8 COLLATE utf8_general_ci";
$CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

$POST = $VALIDATOR->ConvertSpecialChars($_POST);
require_once("./models/Model.php");
require_once("./models/ActivityLog.model.php");
$ALOG = new ActivityLog();


if (!isset($_SESSION['pcms_user_id']) || !isset($_SESSION['pcms_user_group'])) {
    $FUNC->Redirect("auth.php");
}
if ($_SESSION['pcms_user_group'] == 1) {
    define("IS_SUPER_ADMIN", true);
} else {
    define("IS_SUPER_ADMIN", false);
}

if ($_POST['action'] == 'change_current_date' && isset($_POST['current_date']) && $_POST['current_date'] != '') {
    $_SESSION['server_date'] = $_POST['current_date'];
}

if (isset($_SESSION['server_date']) && $_SESSION['server_date'] != '') {
    define("CURRENT_DATE", $_SESSION['server_date']);
} else {
    define("CURRENT_DATE", date('Y-m-d',strtotime('-1day')));
}


//******************************************************//
//*** Reading available plugins ************************//
if ($_SESSION['pcms_user_group'] > 1) {
    $query = "select permitions,restricted from {$_CONF['db']['prefix']}_groups
             where id='{$_SESSION['pcms_user_group']}'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    $restriction = " where id in({$data[0]['permitions']})";
} else $restriction = "";

$query = "SELECT * FROM {$_CONF['db']['prefix']}_pcms_plugins {$restriction}";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$PLUGINS = $result->GetRows();

//*****************************************************//
//*** Selecting active module *************************//
if (isset($_GET['m'])) {
    if ($LOADED_PLUGIN = $FUNC->arraySearch($PLUGINS, 'plugin', $_GET['m'])) {
        if (!empty($data[0]['restricted']))
            $LOADED_PLUGIN['restricted'] = (in_array($LOADED_PLUGIN['id'], explode(",", $data[0]['restricted']))) ? true : false;
        if (!empty($LOADED_PLUGIN['settings'])) {
            $SETTINGS = $FUNC->unpackData($LOADED_PLUGIN['settings']);
        }
        if (file_exists("./plugins/{$LOADED_PLUGIN['plugin']}/ajax.php")) {
            $TMPL->setRoot("./plugins/{$LOADED_PLUGIN['plugin']}");
            require_once("./plugins/{$LOADED_PLUGIN['plugin']}/ajax.php");
        }
    }
}

if ($_GET['cmd'] == "get_guest_info") {
    if (isset($_POST['guest_id'])) {
        $query = "SELECT id, id_number,tax,type,birth_day,first_name,last_name,company_co,ind_discount, id_scan,extra_doc,country,address,telephone,email,comment FROM {$_CONF['db']['prefix']}_guests WHERE id=" . (int)$_POST['guest_id'];
        $result = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        echo json_encode($result);
    } else {
        echo "invalid guest id";
    }
}
else if($_GET['cmd']=='delete_dbl_booking_room'){
  $id=$POST['el_id'];
  if(deleteDbl_room($id)){
    echo json_encode(array('msg'=>'success','code'=>'1'));
  }else{
    echo json_encode(array('msg'=>'with errors','code'=>'2'));
  }
}
else if ($_GET['cmd'] == "get_guest_suggestions") {
    if (isset($_GET['term'])) {
        //CONCAT(G.first_name, ' ', G.last_name ) LIKE '%"
        $query = "SELECT id, id_number,first_name,last_name,email
                    FROM {$_CONF['db']['prefix']}_guests
                    WHERE id_number LIKE '%" . $_GET['term'] . "%' OR
                          CONCAT(first_name, ' ', last_name )  LIKE '%" . $_GET['term'] . "%' OR
                          email LIKE '%" . $_GET['term'] . "%'
                          LIMIT 10";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $guests = $result->GetRows();
        $structurized_list[] = array('id' => 0, 'label' => 'Add New Guest', 'value' => 'Add New Guest');
        foreach ($guests AS $guest) {
            $tmp['id'] = $guest['id'];
            $tmp['label'] = $guest['first_name'] . " " . $guest['last_name'];
            $tmp['value'] = $guest['first_name'] . " " . $guest['last_name'];
            $structurized_list[] = $tmp;
        }
        echo json_encode($structurized_list);
    } else {
        echo "invalid term";
    }
}elseif($_GET['cmd']=='get_room_price'){
  $room_id=$_GET['room_id'];
  $day=$_GET['day'];
  $qs="SELECT * FROM cms_rooms_manager WHERE id=".$room_id;
  $qs = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
  $qs = $qs->fields;
  $query="SELECT * FROM cms_room_prices WHERE type_id=".$qs['type_id']." AND common_id=".$room_id." AND date like '".date('Y-m-d',strtotime($day))."'";
  $query = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
  $query = $query->fields;
  echo json_encode($query);
}
else if ($_GET['cmd'] == "get_guest_suggestions_dbl") {
    if (isset($_GET['term'])) {
        //CONCAT(G.first_name, ' ', G.last_name ) LIKE '%"
        $query = "SELECT *,NULL as password
                    FROM {$_CONF['db']['prefix']}_guests
                    WHERE id_number LIKE '%" . $_GET['term'] . "%' OR
                          CONCAT(first_name, ' ', last_name )  LIKE '%" . $_GET['term'] . "%' OR
                          email LIKE '%" . $_GET['term'] . "%'
                          LIMIT 10";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $guests = $result->GetRows();
        $structurized_list[] = array('id' => 0, 'label' => 'Add New Guest', 'value' => 'Add New Guest');
        foreach ($guests AS $guest) {
            $tmp['id'] = $guest['id'];
            $tmp['label'] = $guest['first_name'] . " " . $guest['last_name'];
            $tmp['value'] = $guest['first_name'] . " " . $guest['last_name'];
            $structurized_list[] = $tmp;
        }
        echo json_encode($structurized_list);
    } else {
        echo "invalid term";
    }
} else if ($_GET['cmd'] == "get_affiliate_suggestions") {
    if (isset($_GET['term'])) {
        //CONCAT(G.first_name, ' ', G.last_name ) LIKE '%"
        $query = "SELECT id, id_number,first_name,last_name,email
                    FROM {$_CONF['db']['prefix']}_guests
                    WHERE type='tour-company' AND (
                          id_number LIKE '%" . $_GET['term'] . "%' OR
                          CONCAT(first_name, ' ', last_name )  LIKE '%" . $_GET['term'] . "%' OR
                          email LIKE '%" . $_GET['term'] . "%')
                          LIMIT 10";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $guests = $result->GetRows();
        $structurized_list[] = array('id' => 0, 'label' => 'Add New Guest', 'value' => 'Add New Guest');
        foreach ($guests AS $guest) {
            $tmp['id'] = $guest['id'];
            $tmp['label'] = $guest['first_name'] . " " . $guest['last_name'];
            $tmp['value'] = $guest['first_name'] . " " . $guest['last_name'];
            $structurized_list[] = $tmp;
        }
        echo json_encode($structurized_list);
    } else {
        echo "invalid term";
    }
} else if ($_GET['cmd'] == "get_service_info") {
    if (isset($_POST['service_id'])) {
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services WHERE id=" . (int)$_POST['service_id'];
        $result = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result['title'] = $FUNC->unpackData($result['title']);
        echo json_encode($result);
    } else {
        echo "invalid service id";
    }
} else if ($_GET['cmd'] == "add_guest") {
    $guest_id = (int)$_POST['guest_id'];
    if ($guest_id == 0) {
        $guests_count = 0;
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests
                      WHERE id<>{$POST['guest_id']} AND (
                      (id_number='{$_POST['id_number']}' AND id_number<>'') OR  (email='{$_POST['email']}' AND email<>'')
                      )";

        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $guests_count = $result->RecordCount();

        if ($guests_count > 0) {
            $msg['errors'][] = 'user_exists';
        } else {
            $id_number = $_POST['id_number'];
            $guest_type = $_POST['guest_type'];
            $guest_tax = (int)$_POST['tax'];
            if ($POST['first_name'] == '') {
                $first_name = "TEMP GUEST";
            } else {
                $first_name = $_POST['first_name'];
            }
            $last_name = $_POST['last_name'];

            $id_scan = (isset($_FILES['id_scan'])) ? saveImage('id_scan') : "";

            $country = $_POST['country'];
            $address = $_POST['address'];
            $telephone = $_POST['telephone'];
            $email = $_POST['email'];
            $birth_day = $_POST['birth_day']?$_POST['birth_day']:date('Y-m-d');
            $comment = $_POST['comment'];
            $co_name=$_POST['company_co']?$_POST['company_co']:'';
            $guest_ind_discount = $_POST['guest_ind_discount'];
            $guest_id = addNewGuest($id_number, $guest_type, $guest_tax, $guest_ind_discount, $first_name, $last_name, $id_scan, $country, $address, $telephone, $email, $comment,$co_name,$birth_day);
            if ($first_name == 'TEMP GUEST') {
                $query = "UPDATE {$_CONF['db']['prefix']}_guests SET
					  first_name = 'TEMP GUEST {$guest_id}',
					  updated_at=NOW()
					  WHERE id={$guest_id}";

                $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            }
            $query = "SELECT id, id_number,tax,type,first_name,birth_day,company_co,last_name,ind_discount, id_scan,extra_doc,country,address,telephone,email,comment
                            FROM {$_CONF['db']['prefix']}_guests
                            WHERE id=" . $guest_id;

            $result = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $msg['guest'] = $result;
        }

    } else {
        $msg['errors'][] = 'cmd=add_guest and guest_id!=0';
    }
    echo json_encode($msg);
} else if($_GET['cmd'] == 'get_guest_counts'){
    $booking_id=$_POST['current_room_id'];
    $query="SELECT * FROM {$_CONF['db']['prefix']}_booking WHERE id=".$booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
  $book=$result->fields;
    echo json_encode(array('adult'=>$book['adult_num'],'child'=>$book['child_num']));
}else if($_GET['cmd'] == 'get_ch_service_price'){
    $id=$_POST['id'];
    $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_services WHERE id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $book=$result->fields;
    echo json_encode($book);
}else if($_GET['cmd'] == 'add_ch_service_booking'){
    $booking_id=$_POST['booking_id'];
    $service=$_POST['service'];
    $count=$_POST['count'];
    $service_price=$_POST['price'];
    $query="SELECT * FROM {$_CONF['db']['prefix']}_ch_booking_info WHERE booking_id=".$booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $book=$result->fields;
    $book['services']=unserialize($book['services']);
    $book['services'][$service]=array($count,$service_price);
    $tmps=0;
    foreach ($book['services'] as $key => $value) {
      $tmps+=$value[1];
    }
    $query="UPDATE {$_CONF['db']['prefix']}_ch_booking_info SET service_price=".$tmps.", services='".serialize($book['services'])."' WHERE id=".$book['id'];
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

      if($CONN->Affected_Rows()){
          $qr="SELECT * FROM cms_ch_booking WHERE id=".$booking_id;
          $result = $CONN->Execute($qr) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
          GeneratePriceEOF($result->fields);
          echo json_encode(array('status'=>'OK'));
      }else{
          echo json_encode(array('status'=>'NO'));
      }
}else if ($_GET['cmd'] == "edit_guest") {
    $guest_id = (int)$_POST['guest_id'];

    if ($guest_id != 0) {
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests
                      WHERE id<>{$POST['guest_id']} AND (
                      (id_number='{$POST['id_number']}' AND id_number<>'') OR  (email='{$POST['email']}' AND email<>'')
                      )";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        if ($result->RecordCount() > 0) {
            $msg['errors'][] = 'Guest Duplication by email or ID Number';
        }


        $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests WHERE id='{$guest_id}'";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        if ($result->RecordCount() != 1) {
            $msg['errors'][] = 'User_Not_Exists';
        }
        $results=$result->fields;
        if($guest_id==$results['id']){

        }

        if (empty($msg['errors'])) {
            $id_number = $_POST['id_number'];
            $guest_type = $_POST['guest_type'];
            $guest_tax = (int)$_POST['tax'];
            $first_name = $_POST['first_name'];
            if ($first_name == "") {
                $first_name = "TEMP GUEST " . $guest_id;
            }
            $last_name = $_POST['last_name'];
            $id_scan = (isset($_FILES['id_scan'])) ? saveImage('id_scan') : "";
            $co_name=$_POST['company_co']?$_POST['company_co']:'';
            $country = (int)$_POST['country'];
            $address = $_POST['address'];
            $telephone = $_POST['telephone'];
            $email = $_POST['email'];
            $comment = $_POST['comment'];
            $guest_ind_discount = (int)$_POST['guest_ind_discount'];

            $query = "UPDATE {$_CONF['db']['prefix']}_guests SET
                      id_number = '{$id_number}',
					  first_name = '{$first_name}',
                      last_name='{$last_name}',
					  company_co='{$co_name}',
					  birth_day='{$POST['birth_day']}',
					  type='{$guest_type}',
                      tax=" . $guest_tax . ",
                      ind_discount=" . $guest_ind_discount . ",
					  telephone='{$telephone}',
					  email='{$email}',
					  country={$country},
					  address='{$address}',
					  id_scan='{$id_scan}',
                      comment='{$comment}',
					  group_id=5,
					  updated_at=NOW()
					  WHERE id={$guest_id}";

            $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

            /*
            $guest_id=addNewGuest($id_number,$guest_type,$guest_tax,$guest_ind_discount,$first_name,$last_name,$id_scan,$country,$address,$telephone,$email,$comment);
*/
            $query = "SELECT id, id_number,tax,type,company_co,first_name,last_name,ind_discount, id_scan,extra_doc,country,address,telephone,email,comment
                            FROM {$_CONF['db']['prefix']}_guests
                            WHERE id=" . $guest_id;

            $result = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $msg['guest'] = $result;
        }
    } else {
        $msg['errors'][] = 'cmd=edit_guest and guest_id=0';
    }
    echo json_encode($msg);
} elseif ($_GET['cmd'] == "get_room_booking_days") {
    $room_id = (int)$_POST['room_id'];
    $booking_id = (int)$_POST['booking_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    $query = "SELECT *
                FROM {$_CONF['db']['prefix']}_booking
                WHERE active=1 AND room_id=" . $room_id . " AND id<>" . $booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $bookings = $result->GetRows();
    $booking_ids = array(0);
    foreach ($bookings AS $booking) {
        if (!in_array($booking['id'], $booking_ids)) {
            $booking_ids[] = $booking['id'];
        }
    }
    $query = "SELECT *
                FROM {$_CONF['db']['prefix']}_booking_daily
                WHERE active=1 AND booking_id IN(" . implode(',', $booking_ids) . ") AND DATE(date) >= DATE(NOW())";
    //p($query);
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $booking_days = $result->GetRows();
    $final_result = array();
    foreach ($booking_days AS $booking_day) {
        if ($booking_day['type'] == 'check_in') {
            $final_result['check_in'][] = $booking_day['date'];
        } elseif ($booking_day['type'] == 'check_out') {
            $final_result['check_out'][] = $booking_day['date'];
        } elseif ($booking_day['type'] == 'in_use') {
            $final_result['in_use'][] = $booking_day['date'];
        } else {

        }
    }
    echo json_encode($final_result);
} elseif ($_GET['cmd'] == "get_room_restrictions") {
    $room_id = (int)$_POST['room_id'];
    $type = $_POST['type'];
    $restrictions = getRoomRestrictions($room_id, $type);
    echo json_encode(array('restrictions' => $restrictions));
} elseif ($_GET['cmd'] == "delete_room_restriction") {
    $restriction_id = (int)$_POST['restriction_id'];
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_restrictions
              WHERE id=".$restriction_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $restrictions = $result->GetRows();
    if(count($restrictions)==1){
        $query = "DELETE FROM {$_CONF['db']['prefix']}_room_restrictions
              WHERE id=".$restriction_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }else{
        echo json_encode(array('error' => 1,'message'=>'No Restriction found with this ID'));
    }
    echo json_encode(array('error'=>0,'room_id' => $restrictions[0]['room_id'],'type'=>$restrictions[0]['type']));
    $ALOG->addActivityLog(
        "Delete Room Restriction",
        "Administrator Removed room[{$restrictions[0]['room_id']}] restriction[{$restriction_id}] ",
        $_SESSION['pcms_user_id'],
        serialize($restrictions[0])
    );
}
elseif ($_GET['cmd'] == "get_ch_service_info") {
    $service_id=$_POST['service_id'];
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_ch_services WHERE id=".$service_id;

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result = $result->fields;
    $result['name']=$FUNC->unpackData($result['name']);
echo json_encode($result);

}
elseif ($_GET['cmd'] == "get_ch_service_type_info") {
    $service_id=$_POST['service_id'];
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_ch_services_types WHERE id=".$service_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result = $result->fields;
#    p($result);
    echo json_encode($result);

}  else if ($_GET['cmd'] == "get_ch_room_type_info") {
    $room_type_id=$_POST['service_id'];
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_ch_rooms_types
			  WHERE id=" . $room_type_id;
    $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    echo json_encode($room);
}
else if ($_GET['cmd'] == "get_ch_room_info") {
    $room_type_id=$_POST['service_id'];
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_ch_rooms
			  WHERE id=" . $room_type_id;
    $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $room['name']=$FUNC->unpackData( $room['name']);
    $room['type_id']=$FUNC->unpackData( $room['type_id']);


    echo json_encode($room);
}
else if ($_GET['cmd'] == "get_room_types") {
  $id=$_POST['id'];
  $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_types WHERE id=".$id;
  $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
  $o=$result->fields;
  $caps_name = array(
         'id'      	  =>  $o['id'],
         'title'   	  =>  $FUNC->unpackData($o['title']),
     	 'publish'    =>  $o['publish'],
         'blocked' 	  =>  ($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$o['creator'])?true:false
   );
  echo json_encode($caps_name);
}
elseif ($_GET['cmd'] == "add_room_restriction") {

    $room_id = (int)$_POST['room_id'];
    $type = $_POST['type'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    if ($room_id != 0 && $type != '' && $from_date != '' && $to_date != '' && $to_date > $from_date) {
        $query = "INSERT INTO {$_CONF['db']['prefix']}_room_restrictions set
                  room_id={$room_id},
				  type='{$type}',
				  from_date='{$from_date}',
				  to_date='{$to_date}'";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $restriction_id = $CONN->Insert_ID();
        echo json_encode(array('error' => 0, 'room_id' => $room_id, 'type' => $type,'restriction_id'=>$restriction_id));
        $ALOG->addActivityLog(
            "Add Room Restriction",
            "Administrator Added room[{$room_id}] restriction[{$restriction_id}] type[{$type}] from[{$from_date}] to[{$to_date}]",
            $_SESSION['pcms_user_id'],
            serialize($restrictions[0])
        );
    } else {
        echo json_encode(array('error' => 1, 'message' => 'Unexpected Parameters'));
    }

} elseif ($_GET['cmd'] == "get_free_rooms") {
    $block_id = (int)$_POST['block_id'];
    $common_id = (int)$_POST['common_id'];
    $floor = (int)$_POST['floor'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $current_room_id = (int)$_POST['current_room_id'];
    if ($check_in != '' && $check_out != '') {
        $query = "SELECT *
                            FROM {$_CONF['db']['prefix']}_booking
                            WHERE ((check_in>='" . $check_in . "' AND check_in<'" . $check_out . "') OR
                                  (check_out>'" . $check_in . "' AND check_out<='" . $check_out . "') OR
                                  (check_in<='" . $check_in . "' AND check_out>='" . $check_out . "')) AND active=1 AND room_id<>" . $current_room_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $matched_bookings = $result->getRows();
        $used_rooms_list = array();
        foreach ($matched_bookings AS $matched_booking) {
            if (!in_array($matched_booking['room_id'], $used_rooms_list)) {
                $used_rooms_list[] = $matched_booking['room_id'];
            }
        }
        if (count($used_rooms_list) > 0) {
            $where_clause = " AND id NOT IN (" . implode(",", $used_rooms_list) . ")";
        } else {
            $where_clause = "";
        }
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms WHERE publish=1" . $where_clause . " ORDER BY floor ASC";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $free_rooms_list = $result->getRows();

        $free_rooms_common_ids = array();
        foreach ($free_rooms_list AS $free_room) {
            if (!in_array($free_room['common_id'], $free_rooms_common_ids)) {
                $free_rooms_common_ids[] = $free_room['common_id'];
            }
            $free_rooms[$free_room['id']] = array('id' => $free_room['id'], 'common_id' => $free_room['common_id'], 'name' => $free_room['name'], 'floor' => $free_room['floor']);
        }

        if ($common_id != 0) {
            $where_clause = " AND id=" . $common_id;
        } else {
            if (count($free_rooms_common_ids) > 0) {
                $where_clause = " AND id IN (" . implode(",", $free_rooms_common_ids) . ")";
            } else {
                $where_clause = "";
            }
        }

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager WHERE publish=1" . $where_clause;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $free_commons = $result->getRows();
        foreach ($free_commons AS $free_common) {
            $free_commons_list[$free_common['id']] = $free_common;
        }

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_types WHERE publish=1";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $room_types = $result->getRows();
        foreach ($room_types AS $room_type) {
            $room_types_list[$room_type['id']] = array('id' => $room_type['id'], 'title' => $FUNC->unpackData($room_type['title'], LANG));
        }

        if ($block_id != 0) {
            $all_blocks[$block_id] = getBlockByID($block_id);
        } else {
            $all_blocks = getAllBlocks();
        }

        foreach ($free_rooms AS $free_room) {
            $r_common_id = $free_room['common_id'];
            $r_floor = $free_room['floor'];
            $r_block_id = $free_commons_list[$r_common_id]['block_id'];
            $block_title = $all_blocks[$r_block_id]['title'];
            $room_type_title = $room_types_list[$free_commons_list[$r_common_id]['type_id']]['title'];
            $room_title = $free_room['name'];
            $list[$block_title][$room_type_title][] = $free_room;
        }
        //p($list);
        echo json_encode($list);
    }
}
elseif ($_GET['cmd'] == "get_free_rooms_dbl") {
    $block_id = (int)$_POST['block_id'];
    $common_id = (int)$_POST['common_id'];
    $floor = (int)$_POST['floor'];
    $booking_array=(array)$_POST['b_id'];
    $check_in = date('Y-m-d',strtotime($_POST['check_in']));
    $check_out = date('Y-m-d',strtotime($_POST['check_out']));
    $current_room_id = (int)$_POST['current_room_id'];
    if ($check_in != '' && $check_out != '') {
        $query = "SELECT *
                            FROM {$_CONF['db']['prefix']}_booking
                            WHERE ((check_in>='" . $check_in . "' AND check_in<'" . $check_out . "') OR
                                  (check_out>'" . $check_in . "' AND check_out<='" . $check_out . "') OR
                                  (check_in<='" . $check_in . "' AND check_out>='" . $check_out . "')) AND active=1 AND id NOT in ( '" . implode($booking_array, "', '") . "' )";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $matched_bookings = $result->getRows();
        $used_rooms_list = array();
        foreach ($matched_bookings AS $matched_booking) {
            if (!in_array($matched_booking['room_id'], $used_rooms_list)) {
                $used_rooms_list[] = $matched_booking['room_id'];
            }
        }
        if (count($used_rooms_list) > 0) {
            $where_clause = " AND id NOT IN (" . implode(",", $used_rooms_list) . ")";
        } else {
            $where_clause = "";
        }
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms WHERE publish=1" . $where_clause . " ORDER BY floor ASC";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $free_rooms_list = $result->getRows();

        $free_rooms_common_ids = array();
        foreach ($free_rooms_list AS $free_room) {
            if (!in_array($free_room['common_id'], $free_rooms_common_ids)) {
                $free_rooms_common_ids[] = $free_room['common_id'];
            }
            $free_rooms[$free_room['id']] = array('id' => $free_room['id'], 'common_id' => $free_room['common_id'], 'name' => $free_room['name'], 'floor' => $free_room['floor']);
        }

        if ($common_id != 0) {
            $where_clause = " AND id=" . $common_id;
        } else {
            if (count($free_rooms_common_ids) > 0) {
                $where_clause = " AND id IN (" . implode(",", $free_rooms_common_ids) . ")";
            } else {
                $where_clause = "";
            }
        }

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager WHERE publish=1" . $where_clause;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $free_commons = $result->getRows();
        foreach ($free_commons AS $free_common) {
            $free_commons_list[$free_common['id']] = $free_common;
        }

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_types WHERE publish=1";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $room_types = $result->getRows();
        foreach ($room_types AS $room_type) {
            $room_types_list[$room_type['id']] = array('id' => $room_type['id'], 'title' => $FUNC->unpackData($room_type['title'], LANG));
        }

        if ($block_id != 0) {
            $all_blocks[$block_id] = getBlockByID($block_id);
        } else {
            $all_blocks = getAllBlocks();
        }

        foreach ($free_rooms AS $free_room) {
            $r_common_id = $free_room['common_id'];
            $r_floor = $free_room['floor'];
            $r_block_id = $free_commons_list[$r_common_id]['block_id'];
            $block_title = $all_blocks[$r_block_id]['title'];
            $room_type_title = $room_types_list[$free_commons_list[$r_common_id]['type_id']]['title'];
            $room_title = $free_room['name'];
            $list[$block_title][$room_type_title][] = $free_room;
        }
        echo json_encode($list);
    }
} elseif ($_GET['cmd'] == "change_booking_acc_price") {
    if (!($_SESSION['pcms_user_group']<=2)) {
        echo json_encode(array('error' => 1, 'error_message' => 'You have no permission. Please contact to administrator.'));
        exit;
    }
    $booking_id = (int)$_POST['booking_id'];
    $new_total_price = (float)$_POST['accomodation_price'];
    $booking = getBookingById($booking_id);
    if ($booking) {
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily WHERE active=1 AND booking_id=" . $booking['id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $booking_days = $result->getRows();

        $old_total_acc_price = 0;
        foreach ($booking_days AS $booking_day) {
            $bdays[] = $booking_day['id'];
            $old_total_acc_price += $booking_day['price'];
        }

        $query = "SELECT SUM(service_price) as total_price FROM {$_CONF['db']['prefix']}_booking_daily_services
                  WHERE active=1 AND booking_daily_id IN (" . implode(',', $bdays) . ") AND (service_type_id=9 OR service_type_id=2)";
        $services_total_price = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $new_total_acc_price = $new_total_price - (float)$services_total_price['total_price'];
        $counter = 0;
        $tmp_total = 0;
        foreach ($booking_days AS $booking_day) {
            if ($booking_day['type'] == 'check_out') {
                continue;
            } else {
                $counter++;
            }
            $old_price = $booking_day['price'];

            
            $new_price = $new_total_acc_price / (count($booking_days) - 1);
            // if ($old_total_acc_price == 0) {
            //     $new_price = $new_total_acc_price / (count($booking_days) - 1);
            // } else {
            //     $new_price = ($old_price * $new_total_acc_price) / $old_total_acc_price;
            // }
            $new_price = number_format($new_price, 2, '.', '');
            $tmp_total += $new_price;
            //start tetrebshi ro ar moxdes cdomileba
            if ($counter == (count($booking_days) - 1) && $tmp_total != $new_total_acc_price) {
                $new_price += ($new_total_acc_price - $tmp_total);
            }
            //end tetrebshi ro ar moxdes cdomileba
            $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
                  price = {$new_price}
                  WHERE id=" . $booking_day['id'];
            $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

            $bdz[$booking_day['id']] = $new_price;

        }
        updateBookingFinances($booking_id);
        $ALOG->addActivityLog("change_booking_acc_price", "Administrator Changed booking[" . $booking['id'] . "] Acc AccPrice=" . $new_total_price, $_SESSION['pcms_user_id'], serialize($_REQUEST));
        echo json_encode(array('booking_id' => $booking_id, 'bdz' => $bdz, 'new_total_price' => $new_total_price));
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} elseif ($_GET['cmd'] == "change_booking_status") {
    $booking_id = (int)$_POST['booking_id'];
    $status_id = (int)$_POST['status_id'];
    if ($booking_id != 0) {
        $old_booking = getBookingById($booking_id);
        if ($old_booking['status_id'] < $status_id || $_SESSION['pcms_user_group']<=2) {
                if($old_booking['check_in']>date('Y-m-d') && $status_id==5){
                    echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data (new_status_id<old status_id)'));
                }else {
                    $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
                  status_id = {$status_id},
                  updated_at=NOW()
                  WHERE id={$booking_id}";
                    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
                    $statuses = getAllStatuses();

                    $msg['success'][] = "booking room changed successfully!";
                    $msg['booking_id'] = $booking_id;
                    $msg['status_id'] = $status_id;
                    $msg['title'] = $statuses[$status_id]['title'];
                    $msg['color'] = $statuses[$status_id]['color'];
                    $ALOG->addActivityLog("change_booking_status", "Administrator Changed booking status=" . $status_id, $_SESSION['pcms_user_id'], serialize($_REQUEST));
                    echo json_encode($msg);
                }
        } else {
            echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data (new_status_id<old status_id)'));
        }
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} elseif ($_GET['cmd'] == "get_food_ids") {
   $result=GetServices(6);
    echo json_encode($result);

}
elseif ($_GET['cmd'] == "validate_ch_room_for_booking") {

    $check_in=$_POST['formData']['check_in'];
    $from_calendar=$_POST['formData']['from_calendar'];
    $check_out=$_POST['formData']['check_out'];
    $to_calendar=$_POST['formData']['to_calendar'];
    $room_ids=$_POST['formData']['room_id'];

    $result_tmp=array();
     foreach($room_ids as $room_id){

         $query = "SELECT * FROM {$_CONF['db']['prefix']}_ch_booking_info CHBI
                  LEFT JOIN {$_CONF['db']['prefix']}_ch_booking CHB ON CHB.id=CHBI.booking_id
                  WHERE CHBI.room_id=" . $room_id."
                   AND (CHB.check_in BETWEEN '".date('Y-m-d',strtotime($check_in))." ".$from_calendar."'
                   AND'".date('Y-m-d',strtotime($check_in))." ".$from_calendar."')
                   OR (CHB.check_out BETWEEN '".date('Y-m-d',strtotime($check_out))." ".$to_calendar."'
                   AND '".date('Y-m-d',strtotime($check_out))." ".$to_calendar."')";

         $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

         $tt=$result->GetRows();
         if(!empty($tt)){
             $result_tmp[]=$tt;
         }
     }

    if(!empty($result_tmp)){
        echo json_encode(array('error'=>'1','text'=>'შეუძლებელია დაჯავშნვის გაკეთება ( ოთახი დაკავებულია)','bookings'=>$result_tmp));
    }else{
        $price=GeneratePrice($_POST['formData']);
        echo json_encode(array('error'=>'0','text'=>$price));
    }


}
elseif ($_GET['cmd'] == "booking_room_info_save"){
   if( ProceedRoomInfoSave($_POST)){
       echo json_encode(array('error'=>'0','text'=>'მონაცემები შეცვლილია წარმატებით'));
   }else{
       echo json_encode(array('error'=>'1','text'=>'მონაცემების შეცვლა ვერ მოხერხდა'));
   }

}elseif ($_GET['cmd'] == "drag_booking") {
    //echo json_encode($_POST);
    //exit;
    $booking_id = (int)$_POST['booking_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $room_id = (int)$_POST['room_id'];
    $recalculate_price = ($_POST['recalculate_price'] == "true") ? true : false;

    $old_booking = getBookingById($booking_id);
    if ($old_booking['room_id'] == $room_id) {
        //nothing to do
    } else {
        //fasis sheucvlelad gadayavs sxva otaxshi
        $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
                      room_id = {$room_id},
					  updated_at=NOW()
					  WHERE id={$booking_id}";
        $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $msg['success'][] = "booking room changed successfully!";
    }

    if ($old_booking['check_in'] != $check_in) {
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily
			  WHERE active=1 AND booking_id=" . $booking_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $old_booking_days = $result->GetRows();

        $old_check_in_mk = mktime(1, 0, 0, substr($old_booking['check_in'], 5, 2), substr($old_booking['check_in'], 8, 2), substr($old_booking['check_in'], 0, 4));
        $new_check_in_mk = mktime(1, 0, 0, substr($check_in, 5, 2), substr($check_in, 8, 2), substr($check_in, 0, 4));

        $diff = $new_check_in_mk - $old_check_in_mk;
        foreach ($old_booking_days as $old_booking_day) {
            $old_booking_day_check_in_mk = mktime(1, 0, 0, substr($old_booking_day['date'], 5, 2), substr($old_booking_day['date'], 8, 2), substr($old_booking_day['date'], 0, 4));
            //$old_booking_day_check_in_mk =$old_booking_day['mk_time'];
            $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
                                 date='" . date('Y-m-d', ($old_booking_day_check_in_mk + $diff)) . "'
                                 WHERE id=" . $old_booking_day['id'];
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        }

        $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
                                 check_in='" . $check_in . "',
                                 check_out='" . $check_out . "'
                                 WHERE id=" . $booking_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $msg['success'][] = "booking dates changed successfully!";
    }

    if ($recalculate_price) {
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily
			  WHERE active=1 AND booking_id=" . $booking_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $booking_days = $result->GetRows();

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
			  WHERE id=" . $room_id;
        $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_prices
			  WHERE common_id=" . $room['common_id'] . " AND date>='" . $check_in . "' AND date<='" . $check_out . "'";

        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $room_prices = $result->GetRows();
        $room_prices = mapArrayByProp($room_prices, 'date');


        $common_obj = $CONN->GetRow("SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager WHERE id=" . $room['common_id']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $guest_obj = $CONN->GetRow("SELECT * FROM {$_CONF['db']['prefix']}_guests WHERE id=" . $old_booking['guest_id']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $food_id = $old_booking['food_id'];
        $food_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_room_services where id={$food_id}");


        $daily_discount = $old_booking['daily_ind_discount'];
        $adult_num = (int)$old_booking['adult_num'];
        $child_num = (int)$old_booking['child_num'];
        $food_price = (double)$food_obj['price'] * ($adult_num + $child_num);
        $one_person_discount = 0;
        if ($adult_num == 1) {
            $one_person_discount = $common_obj['one_person_discount'];
        }
        $guest_ind_discount = $guest_obj['ind_discount'];
        if ($guest_obj['tax'] == 0) {
            $guest_tax_discount = 18;
        }

        foreach ($booking_days as $booking_day) {

            $a = $room_prices[$booking_day['date']]['price'];
            $b = $room_prices[$booking_day['date']]['discount'];
            $pay_now_discount = 0;
            if ($booking_day['type'] != 'check_out') {
                $new_price = calculateNetPrice($a, $food_price, $b, $one_person_discount, $pay_now_discount, $guest_ind_discount, $daily_discount, $guest_tax_discount);
                $msg['progress'][] = $booking_day['date'] . "->" . $a;
                $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
                                 price=" . $new_price . "
                                 WHERE id=" . $booking_day['id'];
                $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            }
        }
        updateBookingFinances($booking_id);
    }
    echo json_encode($msg);
} elseif ($_GET['cmd'] == "resize_booking") {
    $booking_id = (int)$_POST['booking_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $recalculate_price = ($_POST['recalculate_price'] == "true") ? true : false;

    $msg['success'] = changeCheckinCheckout($booking_id, $check_in, $check_out);

    echo json_encode($msg);
}
elseif ($_GET['cmd'] == "get_ch_booking_ajax") {
    $bookings=getChBookings($_POST['room_id'],$_POST['date']);
    echo json_encode($bookings);
}elseif ($_GET['cmd'] == "split_booking") {

    $booking_id = (int)$_POST['booking_id'];
    $date = $_POST['date'];
    $msg['success'] = split_booking($booking_id, $date);
    echo json_encode($msg);
} else if ($_GET['cmd'] == "change_room_housekeeping_status") {
    $room_id = (int)$_POST['room_id'];
    $status = $_POST['status'];
    $status_title = $_POST['status_title'];
    $query = "UPDATE {$_CONF['db']['prefix']}_rooms SET
                                 housekeeping_status='" . $status . "',
                                 hs_updated_at='" . date("Y-m-d H:i:s") . "'
                                 WHERE id=" . $room_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $msg['success'] = 'ok';
    $msg['room_id'] = $room_id;
    $msg['status'] = $status;
    $msg['status_title'] = $status_title;
    echo json_encode($msg);
} else if ($_GET['cmd'] == "add_sp") {
    if ((int)$_POST['room_id'] != 0 && (int)$_POST['service_id'] != 0 && (int)$_POST['service_type_id'] != 0 && (int)$_POST['sp_count'] != 0) {
        $sp['room_id'] = (int)$_POST['room_id'];
        $sp['service_id'] = (int)$_POST['service_id'];
        $sp['service_type_id'] = (int)$_POST['service_type_id'];
        $sp['sp_count'] = (int)$_POST['sp_count'];
        $sp_ids;
        for ($i = 0; $i < $sp['sp_count']; $i++) {
            $sp_ids[] = addSp($sp);
        }
        echo json_encode(GetSpendingMaterials($sp['room_id'], $sp['service_type_id']));
    } else {
        echo json_encode('invalid POST data');
    }
}
else if ($_GET['cmd'] == "del_sp") {
    if ((int)$_POST['room_id'] != 0 && (int)$_POST['service_id'] != 0 && (int)$_POST['service_type_id'] != 0) {
        $sp['room_id'] = (int)$_POST['room_id'];
        $sp['service_id'] = (int)$_POST['service_id'];
        $sp['service_type_id'] = (int)$_POST['service_type_id'];
        deleteSp($sp);
        echo json_encode(GetSpendingMaterials($sp['room_id'], $sp['service_type_id']));
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} else if ($_GET['cmd'] == "change_room_restriction_status") {
    if ((int)$_POST['room_id'] != 0) {
        $room_id = (int)$_POST['room_id'];
        $type=$_POST['type'];
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
			  WHERE id=" . $room_id;
        $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        if ($room) {
            if($type=='dnr_online'){
                $msg['value'] = $for_web = 1 - (int)$room['for_web'];
                $for_local = $room['for_web'];
            }else{
                $for_web = $room['for_web'];
                $msg['value'] =  $for_local = 1 - (int)$room['for_local'];
            }

            $query = "UPDATE {$_CONF['db']['prefix']}_rooms SET
                     for_web=" . $for_web . ",
                     for_local=" . $for_local . "
                     WHERE id=" . $room['id'];
            $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $msg['error'] = 0;
            $msg['room_id'] = $room['id'];
            $msg['type'] = $type;

            $ALOG->addActivityLog("Edit Room Restriction Status", "Administrator Changed room restriction ".$type."=" . $msg['value'], $_SESSION['pcms_user_id'], serialize($_REQUEST));
            echo json_encode($msg);
        } else {
            echo json_encode(array('error' => 1, 'error_message' => 'Room not exists'));
        }
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} else if ($_GET['cmd'] == "add_room") {
    $common_id = (int)$_POST['common_id'];
    $floor = (int)$_POST['floor'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $for_web = (int)$_POST['for_web'];
    $for_local = (int)$_POST['for_local'];
    if ($common_id != 0) {
        $query = "INSERT INTO {$_CONF['db']['prefix']}_rooms set
                  common_id={$common_id},
				  name='{$name}',
				  description='{$description}',
				  floor={$floor},
				  for_web ={$for_web},
				  for_local ={$for_local}";
        $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $room_id = $CONN->Insert_ID();

        $msg['success'] = 'ok';
        $msg['room_id'] = $room_id;
        $msg['common_id'] = $common_id;
        $msg['floor'] = $floor;
        $msg['name'] = $name;
        $msg['description'] = $description;
        $msg['for_web'] = $for_web;
        $msg['for_local'] = $for_local;
        $ALOG->addActivityLog("Add Room", "Administrator added room =" . $room_id, $_SESSION['pcms_user_id'], serialize($_REQUEST));
        echo json_encode($msg);
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} else if ($_GET['cmd'] == "edit_room") {
    if ((int)$_POST['room_id'] != 0) {
        $room_id = (int)$_POST['room_id'];

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
			  WHERE id=" . $room_id;
        $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        if ($room) {
            $for_web = 1 - (int)$room['for_web'];
            $query = "UPDATE {$_CONF['db']['prefix']}_rooms SET
                     name='" . $_POST['name'] . "',
                     description='" . $_POST['description'] . "',
                     common_id=" . $_POST['common_id'] . ",
                     floor=" . $_POST['floor'] . "
                     WHERE id=" . $room_id;
            $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

            $msg['success'] = 'ok';
            $msg['room_id'] = $room['id'];
            $msg['common_id'] = $_POST['common_id'];
            $msg['floor'] = $_POST['floor'];
            $msg['name'] = $_POST['name'];
            $msg['description'] = $_POST['description'];
            $msg['for_web'] = $room['for_web'];
            $ALOG->addActivityLog("Edit Room", "Administrator edited room =" . $_POST['room_id'], $_SESSION['pcms_user_id'], serialize($_REQUEST));
            echo json_encode($msg);
        } else {
            echo json_encode(array('error' => 1, 'error_message' => 'unexpected room_id'));
        }
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} else if ($_GET['cmd'] == "edit_transaction") {
    $transaction_id = (int)$_POST['edit_transaction_id'];
    if ($transaction_id != 0) {
        $date = $_POST['edit_date'] . ' ' . date('H:i:s', time());
        //$date=date('YY-mm-dd H:i:s', $date);
        $amount = (float)$_POST['edit_amount_in'] - (float)$_POST['edit_amount_out'];

        $query = "UPDATE {$_CONF['db']['prefix']}_booking_transactions SET
                     amount=" . $amount . ",
                     payment_method_id=" . (int)$_POST['edit_payment_method_id'] . ",
                     end_date='" . $date . "',
                     guest_tax=" . (int)$_POST['edit_tax'] . "
                     WHERE id=" . $transaction_id;
        $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());


        $query = "SELECT id,guest_id,booking_id,amount,payment_method_id,guest_tax, end_date  FROM {$_CONF['db']['prefix']}_booking_transactions
			  WHERE id=" . $transaction_id;
        $new_transaction = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        if ((int)$new_transaction['booking_id']) {
            updateBookingFinances($new_transaction['booking_id']);
        }
        $payment_methods = getAllPaymentMethods();
        $new_transaction['payment_method'] = $payment_methods[$new_transaction['payment_method_id']];
        $new_transaction['end_date'] = date('Y-m-d', strtotime($new_transaction['end_date']));
        echo json_encode($new_transaction);
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} else if ($_GET['cmd'] == "delete_transaction") {
    $transaction_id = (int)$_POST['delete_transaction_id'];
    if ($transaction_id != 0) {

        $query = "SELECT id,booking_id  FROM {$_CONF['db']['prefix']}_booking_transactions
			  WHERE id=" . $transaction_id;
        $transaction = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        deleteTransaction($transaction['id']);
        if ((int)$transaction['booking_id']) {
            updateBookingFinances($transaction['booking_id']);
        }
        echo json_encode($transaction);
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} else if ($_GET['cmd'] == "get_booking_tooltip") {

    $booking_id = (int)$_POST['booking_id'];
    if ($booking_id != 0) {
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking
			  WHERE active=1 AND id=" . $booking_id;
        $booking = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $food_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_room_services where id={$booking['food_id']}");

        $query = "SELECT id,first_name,last_name, id_number,email,type FROM {$_CONF['db']['prefix']}_guests
			  WHERE id IN(" . $booking['guest_id'] . "," . $booking['affiliate_id'] . "," . $booking['responsive_guest_id'] . ")";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $guests = $result->getAssoc();

        $query = "SELECT BDS.service_title
              FROM {$_CONF['db']['prefix']}_booking_daily AS BD
              LEFT JOIN {$_CONF['db']['prefix']}_booking_daily_services AS BDS
              ON BD.id=BDS.booking_daily_id
              WHERE BD.active=1 AND BDS.service_type_id=9 AND BD.booking_id=" . $booking_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $services = $result->GetRows();
        if($booking['dbl_res_id']==1 || $booking['dbl_res_id']>1){
                if($booking['dbl_res_id']==1){
                    $booking_reo_id=$booking['id']." OR id=".$booking['id'];
                }
                else{
                    $booking_reo_id=$booking['dbl_res_id'];
                }
             $query = "SELECT id
              FROM {$_CONF['db']['prefix']}_booking WHERE dbl_res_id=" . $booking_reo_id;
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $booking_res = $result->GetRows();

        }
        $statuses = getAllStatuses();
        $return['id'] = $booking['id'];
        $return['dbl_res'] = $booking['dbl_res'];
        $return['dbl_res_id'] = $booking['dbl_res_id'];
        $return['dbl_res_id_ens'] = $booking_res;
        $return['adult_num'] = $booking['adult_num'];
        $return['child_num'] = $booking['child_num'];
        $return['status'] = $statuses[$booking['status_id']];
        $return['guest']['name'] = $guests[$booking['guest_id']]['first_name'] . ' ' . $guests[$booking['guest_id']]['last_name'];
        $return['guest']['id_number'] = $guests[$booking['guest_id']]['id_number'];
        $return['guest']['email'] = $guests[$booking['guest_id']]['email'];
        $return['affiliate']['id']=$booking['affiliate_id'];
        $return['affiliate']['name']= $guests[$booking['affiliate_id']]['first_name'] . ' ' . $guests[$booking['affiliate_id']]['last_name'];

        $return['responsive_guest']['name'] = $guests[$booking['responsive_guest_id']]['first_name'] . ' ' . $guests[$booking['responsive_guest_id']]['last_name'];
        $return['responsive_guest']['id_number'] = $guests[$booking['responsive_guest']]['id_number'];
        $return['responsive_guest']['email'] = $guests[$booking['responsive_guest']]['email'];

        $return['services'] = $services;
        $return['food'] = $FUNC->unpackData($food_obj['title'], LANG);

        $return['check_in'] = $booking['check_in'];
        $return['check_out'] = $booking['check_out'];
        $return['price'] = (float)$booking['accommodation_price'] + (float)$booking['services_price'];
        $return['paid_amount'] = (float)$booking['paid_amount'] + (float)$booking['services_paid_amount'];
        $return['comment'] = $booking['comment'];
        echo json_encode($return);

    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }


} else if ($_GET['cmd'] == "delete_room") {
    if ((int)$_POST['room_id'] != 0) {
        $room_id = (int)$_POST['room_id'];

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
			  WHERE id=" . $room_id;
        $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        if ($room) {
            $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking
			  WHERE room_id=" . $room_id;
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $bookings = $result->GetRows();
            if (count($bookings) == 0) {
                if (deleteRoom($room_id)) {
                    $ALOG->addActivityLog("Delete Room", "Administrator Deleted Room=" . $room_id, $_SESSION['pcms_user_id'], serialize($room));
                    $msg['room_id'] = $room_id;
                    echo json_encode($msg);
                } else {
                    echo json_encode(array('error' => 1, 'error_message' => 'db error'));
                }
            } else {
                echo json_encode(array('error' => 1, 'error_message' => 'assigned bookings to the room'));
            }
        } else {
            echo json_encode(array('error' => 1, 'error_message' => 'Room not exists'));
        }
    } else {
        echo json_encode(array('error' => 1, 'error_message' => 'invalid POST data'));
    }
} else {
    echo json_encode('invalid cmd');
}


function getAllBlocks()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $bloks = $result->getRows();
    foreach ($bloks AS $block) {
        $bloks_list[$block['id']] = array('id' => $block['id'], 'title' => $FUNC->unpackData($block['title'], LANG), 'floors' => $block['floors']);
    }
    return $bloks_list;
}

function getAllPaymentMethods()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_payment_methods WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $results = $result->getRows();
    foreach ($results AS $method) {
        $methods[$method['id']] = $FUNC->unpackData($method['title'], LANG);
    }
    return $methods;
}

function getBlockByID($id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks WHERE id=" . $id;
    $block = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $block;
}

function getBookingById($booking_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking WHERE id=" . $booking_id;
    $booking = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $booking;
}


function addNewGuest($id_number, $guest_type, $tax, $guest_ind_discount, $first_name, $last_name, $id_scan, $country, $address, $telephone, $email, $comment,$co_name,$birth_day)
{
    global $CONN, $VALIDATOR, $FUNC, $_CONF, $ALOG;
    $POST['password'] = $VALIDATOR->RandString("1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm!@#$%^&*+/-_|", 6);
    $passhash = $VALIDATOR->RandString("!@#$%^&*+/-_|", 5);
    $password = $FUNC->CompiledPass($POST['password'], $passhash);
    $query = "INSERT INTO {$_CONF['db']['prefix']}_guests set
			      id_number = '{$id_number}',
			      type = '{$guest_type}',
			      tax = {$tax},
			      ind_discount = {$guest_ind_discount},
				  first_name = '{$first_name}',
                  last_name='{$last_name}',
				  company_co='{$co_name}',
				  telephone='{$telephone}',
				  email='{$email}',
          birth_day='$birth_day',
				  country={$country},
				  address='{$address}',
				  id_scan='{$id_scan}',
				  password='{$password}',
				  passhash ='{$passhash}',
				  group_id=5,
				  comment ='{$comment}',
				  created_at=NOW(),
				  publish=1";

    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $guest_id = $CONN->Insert_ID();
    $ALOG->addActivityLog("Add New Guest", "Administrator Added New Guest[" . $guest_id . "]", $_SESSION['pcms_user_id'], mysql_real_escape_string($query));
    return $guest_id;
}

function addSp($sp)
{

    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_spending_materials set
			      room_id = {$sp['room_id']},
			      service_id = {$sp['service_id']},
			      service_type_id = {$sp['service_type_id']},
				  date = CURDATE()";
    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $sp_id = $CONN->Insert_ID();
    $ALOG->addActivityLog("Add Spending Material", "Administrator Added Spending Material[" . $sp_id . "]", $_SESSION['pcms_user_id'], mysql_real_escape_string($query));
    return $sp_id;
}

function deleteSp($sp)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "DELETE FROM {$_CONF['db']['prefix']}_spending_materials
              WHERE room_id = {$sp['room_id']} AND service_id = {$sp['service_id']} AND date = CURDATE()";
    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $ALOG->addActivityLog("Delete Spending Material", "Administrator Deleted Spending Material[" . $sp['id'] . "]", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($sp)));
    return ($result) ? true : false;
}

function deleteTransaction($transaction_id)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_transactions WHERE id=" . $transaction_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $results = $result->getRows();
    if (count($results) != 1) {
        return false;
    } else {
        $transaction = $results[0];
        $query = "DELETE FROM {$_CONF['db']['prefix']}_booking_transactions
                  WHERE id =" . $transaction_id;
        $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $ALOG->addActivityLog("Delete Transaction", "Administrator Deleted transaction[" . $transaction_id . "]", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($transaction)));
        return ($result) ? true : false;
    }
}

function deleteRoom($room_id)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms WHERE id=" . $room_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $results = $result->getRows();
    if (count($results) != 1) {
        return false;
    } else {
        $room = $results[0];
        $query = "DELETE FROM {$_CONF['db']['prefix']}_rooms
              WHERE id = {$room_id}";
        $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $ALOG->addActivityLog("Delete Room", "Administrator Deleted Room[" . $room_id . "]", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($room)));
        return ($result) ? true : false;
    }
}

function GetSpendingMaterials($room_id, $service_type_id)
{
    global $_CONF, $CONN, $FUNC;
    $date = date('Y-m-d', time());
    $room_clause = ($room_id == 0) ? "" : " AND room_id=" . $room_id;
    $type_clause = ($service_type_id == 0) ? "" : " AND service_type_id=" . $service_type_id;
    $services = GetServices();
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_spending_materials
              WHERE date='" . $date . "'" . $room_clause . $type_clause;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $spending_materials = $result->GetRows();
    $mappedSpendingMaterials[$room_id] = array();
    foreach ($spending_materials as $spending_material) {
        $mappedSpendingMaterials[$spending_material['room_id']][$services[$spending_material['service_id']]['title']][] = $spending_material;

    }
    return $mappedSpendingMaterials;
}

function GetServices($type = 0)
{
    global $_CONF, $CONN, $FUNC;
    $where_clause = ($type != 0) ? " WHERE type_id=" . $type : "";
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services" . $where_clause;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    $tmp_data = array();
    foreach ($data as $key => $value) {
        $value['title'] = $FUNC->unpackData($value['title'], LANG);
        $tmp_data[$value['id']] = $value;

    }
    return $tmp_data;
}


function deleteBookingDailyService($booking_daily_service_id)
{
    global $CONN, $FUNC, $_CONF, $ALOG;

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily_services
              WHERE id=" . $booking_daily_service_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $daily_services = $result->GetRows();

    if (count($daily_services) == 1) {
        $daily_service = $daily_services[0];
        $query = "DELETE FROM {$_CONF['db']['prefix']}_booking_daily_services
                  WHERE id=" . $booking_daily_service_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        if ($result) {
            $title = "Delete Booking Daily Service";
            $desc = "Administrator Deleted BookingDay[" . $daily_service['booking_daily_id'] . "] Service[" . $booking_daily_service_id . "]";
            $ALOG->addActivityLog($title, $desc, $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($daily_service)));
        }
    }
}

function deleteBookingDayWithServices($booking_day_id)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily_services
              WHERE booking_daily_id=" . $booking_day_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $daily_services = $result->GetRows();
    foreach ($daily_services as $daily_service) {
        deleteBookingDailyService($daily_service['id']);
    }

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily
              WHERE id=" . $booking_day_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $booking_days = $result->GetRows();

    if (count($booking_days) == 1) {
        $booking_day = $booking_days[0];
        $booking_day['services'] = $daily_services;
        $query = "DELETE FROM {$_CONF['db']['prefix']}_booking_daily
              WHERE id={$booking_day_id}";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $ALOG->addActivityLog("Delete Booking Day", "Administrator Deleted BookingDay[" . $booking_day_id . "]", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($booking_day)));
        return true;
    } else {
        return false;
    }
}

function createDateRangeArray($strDateFrom, $strDateTo)
{
    $aryRange = array();
    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function addNewBookingDay($booking_id, $date, $type, $price, $comment)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_booking_daily set
			      booking_id = {$booking_id},
				  date = '{$date}',
				  mk_time='" . mktime() . "',
				  type='{$type}',
				  price={$price},
				  comment='{$comment}'";
    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $booking_day_id = (int)$CONN->Insert_ID();
    //tanmdevi procesia da araa sachiro amis logireba
    //$ALOG->addActivityLog("Add Booking Day", "Administrator Added BookingDay[".$booking_day_id."] to booking[".$booking_id."]", $_SESSION['pcms_user_id'], mysql_real_escape_string($query));
    return $booking_day_id;
}

function calculateNetPrice($a, $food_price, $b, $c, $d, $f, $e, $g)
{
    //A-B(%)-C(%)-D(%)-F(%)-E-G(%)+FOOD
    $daily_price = $a;
    $daily_price = $daily_price - $daily_price / 100 * $b;
    $daily_price = $daily_price - $daily_price / 100 * $c;
    $daily_price = $daily_price - $daily_price / 100 * $d;
    $daily_price = $daily_price - $daily_price / 100 * $f;
    $daily_price = $daily_price - $e;
    $daily_price = $daily_price - $daily_price / (100 + $g) * $g;
    $daily_price += $food_price;
    $daily_price = round($daily_price * 100) / 100;
    return $daily_price;
}
function saveImage($image)
{
    require_once 'classes/imageGD/imageGD.class.php';
    $imgDIR = "../uploads_script/guests";
    $SET['img_width'] = 480;
    $SET['img_height'] = 640;
    $SET['th_width'] = 340;
    $SET['th_height'] = 260;
    $SET['th_method'] = 'r';
    if ($_FILES[$image]['name'] && $_FILES[$image]['size']) {
        $IMG = new ImageGD($imgDIR);
        if ($img = $IMG->uploadImage($image)) {
            $IMG->resizeImage($img, $SET['img_width'], $SET['img_height'], 100, false, $img);

            if ($SET['th_method'] == 'c') {
                $IMG->cropImage($img, $SET['th_width'], $SET['th_height'], 100, 'thumb_' . $img);
            } else {
                $IMG->resizeImage($img, $SET['th_width'], $SET['th_height'], 100, false, 'thumb_' . $img);
            }
        }
        if ($errors = $IMG->passErrors()) {
            @unlink($imgDIR . '/' . $img);
            @unlink($imgDIR . '/thumb_' . $img);
            @unlink($imgDIR . '/thumb2_' . $img);
        }
    }
    return $img;
}
function mapArrayByProp($arr, $prop, $only_props = false)
{
    $mappedArray = array();
    foreach ($arr AS $item) {
        if ($only_props) {
            if (!in_array($item[$prop], $mappedArray)) {
                $mappedArray[] = $item[$prop];
            }

        } else {
            $mappedArray[$item[$prop]] = $item;
        }

    }
    return $mappedArray;
}

function updateBookingFinances($booking_id)
{
    global $CONN, $FUNC, $_CONF;
    $booking_accommodation_price = 0;
    $booking_services_price = 0;
    $paid_amount = 0;
    $services_paid_amount = 0;

    $query = "SELECT id, price
                FROM {$_CONF['db']['prefix']}_booking_daily
                WHERE booking_id=" . $booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $booking_days = $result->GetRows();

    foreach ($booking_days AS $booking_day) {
        $booking_accommodation_price += (float)$booking_day['price'];
        $days_ids[] = $booking_day['id'];
    }

    $tmp_arr = implode(", ", $days_ids);
    if (!empty($days_ids)) {
        $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking_daily_services
			  	WHERE booking_daily_id IN ($tmp_arr)";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $daily_services = $result->GetRows();
    }

    //p($daily_services); exit;
    foreach ($daily_services AS $service) {
        if (in_array($service['service_type_id'], array(2, 9))) {
            $booking_accommodation_price += (float)$service['service_price'];
        } else {
            $booking_services_price += (float)$service['service_price'];
        }
    }


    $query = "SELECT *
                FROM {$_CONF['db']['prefix']}_booking_transactions
                WHERE booking_id=" . $booking_id . " AND result='OK'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();

    foreach ($transactions AS $transaction) {
        if ($transaction['destination'] == 'accommodation') {
            $paid_amount += (float)$transaction['amount'];
        } elseif ($transaction['destination'] == 'extra-service') {
            $services_paid_amount += (float)$transaction['amount'];
        } else {

        }
    }

    $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
			 	 paid_amount={$paid_amount},
			 	 services_paid_amount={$services_paid_amount},
			 	 accommodation_price={$booking_accommodation_price},
			 	 services_price={$booking_services_price}
			 	 WHERE id={$booking_id}";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
}

function split_booking($booking_id, $date)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $booking = getBookingById($booking_id);
    if (!$booking || $booking['check_in'] >= $date || $booking['check_out'] <= $date) {
        return false;
    }
    $fields = "guest_id,responsive_guest_id,foreign_guest_ids,affiliate_id,room_id,administrator_id,first_day_price,fixed_discount,daily_ind_discount,check_in,check_out,adult_num,child_num,food_id,method,online_payment_type,reservation_guarantee,online_is_paid,status_id,paid_amount,services_paid_amount,accommodation_price,services_price,currency_coef,currency_type,currency_rates,created_at,updated_at,comment,log";
    //Insert New Booking
    $sql = "INSERT INTO {$_CONF['db']['prefix']}_booking (" . $fields . ")
          SELECT " . $fields . " FROM {$_CONF['db']['prefix']}_booking
          WHERE id=" . $booking_id;
    $CONN->Execute($sql) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $new_booking_id = $CONN->Insert_ID();

    //Change check_in to new booking
    $sql = "UPDATE {$_CONF['db']['prefix']}_booking
          SET check_in='" . $date . "',parent_id=" . $booking_id . ", child_id=" . $booking['child_id'] . "
          WHERE id=" . $new_booking_id;
    $CONN->Execute($sql) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());


    //Change check_out to old booking
    $sql = "UPDATE {$_CONF['db']['prefix']}_booking
          SET check_out='" . $date . "', child_id=" . $new_booking_id . "
          WHERE id=" . $booking_id;
    $CONN->Execute($sql) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    //jachvi ro ar dairgves
    $sql = "UPDATE {$_CONF['db']['prefix']}_booking
          SET  parent_id=" . $new_booking_id . "
          WHERE parent_id=" . $booking_id . " AND id<>" . $new_booking_id;
    $CONN->Execute($sql) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());


    //change booking_id to old booking_day
    $sql = "UPDATE {$_CONF['db']['prefix']}_booking_daily
          SET booking_id=" . $new_booking_id . "
          WHERE booking_id=" . $booking_id . " AND date>='" . $date . "'";
    $CONN->Execute($sql) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    //change type as check_in to new booking first day
    $sql = "UPDATE {$_CONF['db']['prefix']}_booking_daily
          SET type='check_in'
          WHERE booking_id=" . $new_booking_id . " AND date='" . $date . "'";
    $CONN->Execute($sql) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    //Create chek_out day to old booking
    $sql = "INSERT INTO {$_CONF['db']['prefix']}_booking_daily (booking_id,date,mk_time,type,price,comment)
          SELECT " . $booking_id . " AS booking_id,date,mk_time,'check_out' AS type,0 price,'' comment
          FROM {$_CONF['db']['prefix']}_booking_daily
          WHERE booking_id=" . $new_booking_id . " AND date='" . $date . "'";
    $CONN->Execute($sql) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    updateBookingFinances($booking_id);
    updateBookingFinances($new_booking_id);

    $ALOG->addActivityLog("Split Booking", "Administrator Splited Booking[" . $booking_id . "] new booking[" . $new_booking_id . "] date[" . $date . "]", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($booking)));
    return true;
}

function changeCheckinCheckout($booking_id, $new_checkin, $new_checkout)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $booking = getBookingById($booking_id);
    if ($booking['check_in'] < CURRENT_DATE && $booking['check_in'] != $new_checkin) {
        return false;
    }
    if ($booking['check_out'] < CURRENT_DATE && $booking['check_out'] != $new_checkout) {
        return false;
    }
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking
			  	WHERE room_id={$booking['room_id']} AND active=1 AND id<>" . $booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $other_bookings = $result->GetRows();
    foreach ($other_bookings as $other_booking) {
        if ($other_booking['check_in'] == $new_checkin) {
            return false;
        }
        if ($other_booking['check_in'] < $new_checkin && $other_booking['check_out'] > $new_checkin) {
            return false;
        }
        if ($other_booking['check_out'] == $new_checkout) {
            return false;
        }
        if ($other_booking['check_in'] < $new_checkout && $other_booking['check_out'] > $new_checkout) {
            return false;
        }
        if ($other_booking['check_in'] > $new_checkin && $other_booking['check_out'] < $new_checkout) {
            return false;
        }

    }
    $room = getRoomByID($booking['room_id']);
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_room_prices
			  	WHERE common_id=" . $room['common_id'] . " AND date>='" . $new_checkin . "' AND date<='" . $new_checkout . "'";

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $new_days_prices = mapArrayByProp($result->GetRows(), 'date');

    $current_days = createDateRangeArray($booking['check_in'], $booking['check_out']);
    $new_days = createDateRangeArray($new_checkin, $new_checkout);


    if (count($new_days_prices) != count($new_days)) {
        //fasebi araa yvela dgeze sheyvanili
        return false;
    }
    for ($i = 0; $i < count($current_days); $i++) {
        if (!in_array($current_days[$i], $new_days)) {
            $days_to_remove[] = $current_days[$i];
        }
    }

    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking_daily
			  	WHERE booking_id=" . $booking['id'];
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $booking_current_days = $result->GetRows();

    foreach ($booking_current_days AS $booking_current_day) {
        if (in_array($booking_current_day['date'], $days_to_remove)) {
            deleteBookingDayWithServices($booking_current_day['id']);
        }
    }
    $mapped_booking_current_days = mapArrayByProp($booking_current_days, 'date');
    $mapped_booking_current_days_dates = mapArrayByProp($booking_current_days, 'date', true);
    for ($i = 0; $i < count($new_days); $i++) {
        $daily_price = $new_days_prices[$new_days[$i]]['price'] - (($new_days_prices[$new_days[$i]]['price'] / 100) * $new_days_prices[$new_days[$i]]['discount']);

        if ($new_days[$i] == $new_checkin) {
            $type = 'check_in';
        } elseif ($new_days[$i] == $new_checkout) {
            $type = 'check_out';
            $daily_price = 0;
        } else {
            $type = 'in_use';
        }

        if (!in_array($new_days[$i], $mapped_booking_current_days_dates)) {
            addNewBookingDay($booking['id'], $new_days[$i], $type, $daily_price, '');
        } else {
            //update
            if ($mapped_booking_current_days[$new_days[$i]]['type'] == 'check_out') {
                $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
			 	 type='" . $type . "',
			 	 price=" . $daily_price . "
			 	 WHERE id=" . $mapped_booking_current_days[$new_days[$i]]['id'];
            } else {
                $daily_price = ($type == 'check_out') ? 0 : $mapped_booking_current_days[$new_days[$i]]['price'];
                $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
			 	 type='" . $type . "',
			 	 price=" . $daily_price . "
			 	 WHERE id=" . $mapped_booking_current_days[$new_days[$i]]['id'];
            }
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        }
    }
    $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
			 	 check_in='" . $new_checkin . "',
			 	 check_out='" . $new_checkout . "'
			 	 WHERE id=" . $booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    updateBookingFinances($booking_id);
    $ALOG->addActivityLog("Change Booking Checkin Checkout", "Administrator Changed Booking[" . $booking_id . "] check_in[" . $new_checkin . "] check_out[" . $new_checkout . "]", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($booking)));
    return true;
}
function deleteDbl_room($id){
    global $CONN, $FUNC, $_CONF,$ALOG;
    $query="DELETE FROM cms_booking WHERE id=".$id;
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $ids=$CONN->Affected_Rows();
    $query="DELETE FROM cms_booking_daily WHERE booking_id=".$id;
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $idss=$CONN->Affected_Rows();
    if($ids && $idss){
      return true;
    }
    else{
      return false;
    }
}
function getRoomByID($id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_rooms
			  	WHERE id={$id}";
    $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $room;
}

function getAllStatuses()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_statuses ORDER BY id ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $statuses = $result->GetRows();
    foreach ($statuses as $status) {
        $tmp['id'] = $status['id'];
        $tmp['color'] = $status['color'];
        $tmp['title'] = $FUNC->unpackData($status['title'], LANG);
        $unpaked_statsuses[$tmp['id']] = $tmp;
    }
    return $unpaked_statsuses;
}

function getRoomRestrictions($room_id, $type)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_restrictions
              WHERE type='" . $type . "' AND room_id=" . $room_id . " AND to_date>='" . CURRENT_DATE . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $restrictions = $result->getRows();
    return $restrictions;
}
function GeneratePrice($POST){
    global $CONN, $FUNC, $_CONF;
    $dda=$POST;

    $price=0;
   if($dda['check_in']==$dda['check_out']){
       $dayName=date('D',strtotime($dda['check_in']));
       $queryDay="day='".$dayName."'";
   }else{
       $queryDay='(';
       $days=getRangeDates($dda['check_in'],$dda['check_out']);
        $dsa=array();
       foreach($days as $day){
           $io=in_array(date('D',strtotime($day)),$dsa);
           if($io){
               continue;
           }
           array_push($dsa,date('D',strtotime($day)));
           $queryDay.="day = '".date('D',strtotime($day))."' OR ";

       }
       $queryDay=substr($queryDay, 0, -3);
       $queryDay=$queryDay.")";




   }
    $rangeTimes=getRangeTimes($dda['from_calendar'],$dda['to_calendar']);

    foreach($dda['room_id'] as $room){
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_ch_prices
              WHERE ch_room_id=".$room." AND ".$queryDay;

        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result=$result->getRows();
        if(!empty($result)){
            $price+=GetPriceSum($result,$rangeTimes,$dsa);
            $price+=getServicePriceSum($dda['services'],$dda['adult_num']);
        }else{

        }
    }
    #
    return $price;
}

function getRangeDates($date_from, $date_to)
{
    $date_from = strtotime($date_from);
    $date_to = strtotime($date_to);
    $range = array();
    for ($i = $date_from; $i <= $date_to; $i += 86400) {
        $range[] = date("Y-m-d", $i);
    }
    return $range;
}
function getRangeTimes($date_from, $date_to)
{
    $date_from = date('H',strtotime($date_from));
    $date_to = date('H',strtotime($date_to));
    $range = array();
    for ($i = $date_from; $i <= $date_to; $i += 1) {
        $range[] = (int)$i;
    }
    return $range;
}

/**
 * @param $result
 * @param $range
 * @return int
 */
function getPriceSum($result, $range,$dsa){
    $timePeriod=0;
    $price=0;
    foreach($result as $time){
        $timePeriod=getRangeTimes($time['from_time'],$time['to_time']);
        $iu=array_intersect($timePeriod,$range);
        if(empty($iu)){
            continue;
        }
        $price+=((count($iu)-1)*$time['price']);
    }
    return $price;
}

/**
 * @param $room_id
 */
function getServicePriceSum($services,$adult_num,$count=array()){
    global $CONN, $FUNC, $_CONF;
    $return=0;

    foreach($services as $key=>$service){
        $query="SELECT * FROM cms_ch_services S
                LEFT JOIN cms_ch_services_types ST ON S.type_id=ST.id
                WHERE S.id=".$service;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result=$result->fields;
        if($result['type_of']==1){
            $return+=$result['price'];
        }elseif($result['type_of']==0){
            if(empty($count)){
              $return+=($result['price'])*$adult_num;
            }else{
              $return+=($result['price'])*$count[$key];
            }
        }
    }
    return $return;
}
function ProceedRoomInfoSave($data){
    global $CONN, $FUNC, $_CONF;
      $booking_id=$data['booking_id'];
      $services=$data['services'];
      $room_type=$data['room_type'];
      $room_id=$data['room_id'];
      $person_cap=$data['person_cap'];
    $query="UPDATE cms_ch_booking_info SET room_type=".$room_type." , person_cap=".$person_cap." WHERE booking_id=".$booking_id." AND room_id=".$room_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());


    $qr="SELECT * FROM cms_ch_booking WHERE id=".$booking_id;
    $result = $CONN->Execute($qr) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    GeneratePriceEOF($result->fields);
    if($CONN->Affected_Rows()){
        return true;
    }else{
        return false;
    }
}
function GeneratePriceEOF($b){
     global $CONN, $FUNC, $_CONF;
     $tmp=0;
     $tmps=0;
     $bookingInfo_query="SELECT * FROM cms_ch_booking_info WHERE booking_id=".$b['id'];
     $bookingInfo = $CONN->Execute($bookingInfo_query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
     $bookingInfo=$bookingInfo->getRows();
     foreach ($bookingInfo as $key => $value) {
        $services=unserialize($value['services']);
        $date_in=$b['check_in'];
        $date_out=$b['check_out'];
        $adult_num=$b['adult_num'];
        foreach ($services as $key => $v) {
          $tmps+=$v[1];
        }
        $tmp+=GenerateRoomPrices($value['room_id'],$b['check_in'],$b['check_out']);
     }
      $query="UPDATE cms_ch_booking_info SET price=".$tmp." WHERE booking_id=".$b['id'];

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
}
function GenerateRoomPrices($room_id,$date_in,$date_out){
    global $CONN, $FUNC, $_CONF;

    $price=0;
   if(date('Y-m-d',strtotime($date_in))==date('Y-m-d',strtotime($date_out))){
       $dayName=date('D',strtotime($date_in));
       $queryDay="day='".$dayName."'";
   }else{
       $queryDay='(';
       $days=getRangeDates($date_in,$date_out);
        $dsa=array();
       foreach($days as $day){
           $io=in_array(date('D',strtotime($day)),$dsa);
           if($io){
               continue;
           }
           array_push($dsa,date('D',strtotime($day)));
           $queryDay.="day = '".date('D',strtotime($day))."' OR ";

       }
       $queryDay=substr($queryDay, 0, -3);
       $queryDay=$queryDay.")";
   }
    $rangeTimes=getRangeTimes($date_in,$date_out);
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_ch_prices
              WHERE ch_room_id=".$room_id." AND ".$queryDay;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result=$result->getRows();
        if(!empty($result)){
            $price+=GetPriceSum($result,$rangeTimes,$dsa);
        }
    return $price;
}
function getChBookings($id,$date){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT CB . * , G.first_name,CR.name room_name FROM cms_ch_booking CB
LEFT JOIN cms_ch_booking_info CBI on CBI.booking_id=CB.id
LEFT JOIN cms_guests G on G.id=CB.guest_id
LEFT JOIN cms_ch_rooms CR on CBI.room_id=CR.id
    WHERE CBI.room_id=".$id." AND DATE_FORMAT(CB.check_in, '%Y %m %d')=DATE_FORMAT('".$date."', '%Y %m %d')";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as &$res){
        $res['room_name']=$FUNC->unpackData($res['room_name'],'geo');
    }
    return $result;
}
