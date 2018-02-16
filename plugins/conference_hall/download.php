<?	

require_once($ROOT."/excelwriter.inc.php");

$excel=new ExcelWriter($ROOT."/guests.xls");

if($excel==false)    echo $excel->error;

$where_clause=" WHERE 1=1";
$where_clause.=(isset($_GET['filter_guest_id_number'])&& $_GET['filter_guest_id_number']!="")?" AND id_number LIKE '%".$_GET['filter_guest_id_number']."%'":"";
$where_clause.=(isset($_GET['start_date'])&& $_GET['start_date']!="")?" AND DATE(created_at)>='".$_GET['start_date']."'":" AND DATE(created_at)>='".date('Y-m-01')."'";
$where_clause.=(isset($_GET['end_date'])&& $_GET['end_date']!="")?" AND DATE(created_at)<='".$_GET['end_date']."'":" AND DATE(created_at)<='".date('Y-m-d')."'";
$where_clause.=(isset($_GET['guest_name'])&& $_GET['guest_name']!="")?" AND CONCAT(first_name, ' ',last_name) LIKE '%".$_GET['guest_name']."%'":"";
$where_clause.=(isset($_GET['publish'])&& $_GET['publish']!="")?" AND publish=".$_GET['publish']:"";
$where_clause.=(isset($_GET['guest_type'])&& $_GET['guest_type']!="")?" AND type='".$_GET['guest_type']."'":"";
$where_clause.=(isset($_GET['tax'])&& $_GET['tax']!=2)?" AND type=".$_GET['tax']:"";

$query = "SELECT * FROM {$_CONF['db']['prefix']}_guests".$where_clause;
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
$data=$result->GetRows();

$myArr=array(
    $TEXT['app_firstname'],
    $TEXT['app_lastname'],
    $TEXT['app_birthday'],
    $TEXT['app_passport_id'],
    $TEXT['app_phone'],
    $TEXT['app_mobile'],
    $TEXT['app_street'],
    $TEXT['app_email'],
    $TEXT['app_ubfirstname'],
    $TEXT['app_ublastname'],
    $TEXT['app_ubbirthday'],
    $TEXT['app_gender']
);
$excel->writeLine($myArr);

for($i=0;$i<count($data); $i++){
    $firstname=$data[$i]['first_name'];
    $lastname=$data[$i]['last_name'];
    $birthday=$data[$i]['birth_day'];
    $passport_id=$data[$i]['passport_id'];
    $phone=$data[$i]['phone'];
    $mobile=$data[$i]['mobile'];
    $street=$data[$i]['street'];
    $email=$data[$i]['email'];
    $ubfirstname=$data[$i]['ubfirstname'];
    $ublastname=$data[$i]['ublastname'];
    $ubbirthday=$data[$i]['ubbirthday'];
    $gender=$data[$i]['gender'];
    $myArr=array($firstname,$lastname,$birthday,$passport_id,$phone,$mobile,$street,$email,$ubfirstname,$ublastname,$ubbirthday,$gender);
   // $excel->writeLine($myArr);
}


$excel->close();
header('Content-Description: File Transfer');
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename= guests.xls");
readfile($ROOT."/guests.xls");
//$FUNC->Redirect($SELF.$url);

