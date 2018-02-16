<?
$service_type=(int)$_GET['tab'];
if($service_type==0){
    $service_type=2;
}
### ADD PICTURE
if($_POST['action']=="add"){
    //p($_POST); exit;
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
	### Image UPLOADED? - ADD TO DB
	if(!$errors) {
        if($_FILES['image']['name'] && $_FILES['image']['size']){
            $img=saveImage('image');
            $i= "img='".$img."',";
        }
		$query = "INSERT INTO {$_CONF['db']['prefix']}_room_services SET
				  title='".serialize($POST['title'])."',
          ".$i."
				  price='".$POST['price']."',
				  food_count='".$POST['food_count']."',
				  type_id=".$POST['service_type'];
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$FUNC->Redirect($SELF);
	}

	### If ERRORS
	else{
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_services',$POST['services']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->ParseIntoVar($_CENTER,"services");
	}
}
### EDIT PICTURE
elseif($_POST['action']=="edit"){
    //p($_POST); exit;
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);

	### No errors - save changes
	if(!$errors) {
        if($_FILES['image']['name'] && $_FILES['image']['size']){
            $img=saveImage('image');
            $i= "img='".$img."',";
        }

		$query = "UPDATE {$_CONF['db']['prefix']}_room_services SET
				  title ='".serialize($POST['title'])."',
				  ".$i."
				  price='".$POST['price']."',
                  type_id='".$POST['service_type']."',
                  food_count='".$POST['food_count']."'
				  WHERE id=".(int)$POST['service_id'];
		$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$FUNC->Redirect($SELF);
	}
	### If ERRORS
	else {
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_id',$POST['pid']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_price',$POST['price']);
		$TMPL->ParseIntoVar($_CENTER,"services");
    }

}
### Delete PICTURE
elseif($_GET['action']=='delete'){
	$pid   = StringConvertor::toNatural($_GET['pid']);

	$query = "DELETE FROM {$_CONF['db']['prefix']}_room_services WHERE id='{$pid}'";
	$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF);
}
elseif($_GET['action']=='edit') {
	$pid   = StringConvertor::toNatural($_GET['pid']);
	$query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services WHERE id='{$pid}'";
	$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data = $result->GetRows();
    $data[0]['title']=$FUNC->unpackData($data[0]['title']);
	//$data[0]['title']=$FUNC->unpackData($TMPL_service['title'],'eng');
	$TMPL->addVar('TMPL_service',$data[0]);
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
    $TMPL->addVar('TMPL_services_types',GetServicesTypes());
    $TMPL->ParseIntoVar($_CENTER,"services");
}
elseif($_GET['action']=="change_status" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_services SET publish=if(publish=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
elseif($_GET['action']=="change_visibility" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_services SET in_use=if(in_use=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### DEFAULT
else {


    $TMPL->addVar('TMPL_services_types',GetServicesTypes());
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->ParseIntoVar($_CENTER,"services_".$service_type);
}

$TMPL->addVar('TMPL_services_types',GetServicesTypes());
$TMPL->addVar('TMPL_data',GetServices($service_type));
$TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
$TMPL->ParseIntoVar($_CENTER,"services_list");


function saveImage($image){
    global $imgDIR;
    $SET['th_width']=32;
    $SET['th_height']=32;
    $SET['th_method']='c';

    if($_FILES[$image]['name'] && $_FILES[$image]['size']){
        $IMG = new ImageGD($imgDIR);
        if($img = $IMG->uploadImage($image)) {
            //imagealphablending( $img, false );
            //imagesavealpha( $img, true );
            //*** Making thumbnail  ************************//
            if($SET['th_method']=='c') {
                $IMG->cropImage($img,$SET['th_width'],$SET['th_height'],'thumb_'.$img);
            }
            else {
                $IMG->resizeImage($img,$SET['th_width'],$SET['th_height'],'thumb_'.$img);
            }
            //*** Making thumbnail  ************************//
        }

        if($errors = $IMG->passErrors()){
            p($IMG->passErrors());
            @unlink($imgDIR.'/'.$img);
            @unlink($imgDIR.'/thumb_'.$img);
        }else{
            @unlink($imgDIR.'/'.$img);
        }
    }
    return  'thumb_'.$img;
}
