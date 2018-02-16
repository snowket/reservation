<? if(!defined('ALLOW_ACCESS')) exit;
$SE="index.php?m=conference_hall&tab=services";
if(isset($_GET['delete']) && $_GET['delete']=='delete'){

}
elseif($_POST['action']=='add') {
   setCH_service(serialize($_POST['name']),$_POST['price'],$_POST['service_type']);
   header('location:'.$SE);
}elseif($_POST['action']=='edit') {
  updateCH_service(serialize($_POST['name']),$_POST['price'],$_POST['service_type'],$_POST['service_id']);
   header('location:'.$SE);
}else{
   $services=getCH_service();
   $service_types=getCH_service_type();
}
$TMPL_lang=$_CONF['langs_publish'];

$TMPL->addVar("services", $services);
$TMPL->addVar("TMPL_lang", $TMPL_lang);
$TMPL->addVar("TMPL_services_types", $service_types);
$TMPL->ParseIntoVar($_CENTER,"services");