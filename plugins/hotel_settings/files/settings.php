<?php
if(!defined('ALLOW_ACCESS')) exit;

//**************************************************************//
//****** Sending a mail list ***********************************//
if (isset($_POST['action']) && $_POST['action'] == "submit") {
    ignore_user_abort(true);
    set_time_limit(0);

    unset($_POST['update']);
    unset($_POST['action']);

    $_POST = $VALIDATOR->ConvertSpecialChars($_POST);
    $logo=saveImage('logo','../uploads','logo',220,96);
    if($logo){
        $query = "UPDATE {$_CONF['db']['prefix']}_hotel_settings SET value = '$logo' WHERE input_name = 'logo'";
        $res = $CONN->_Query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }

    $signature=saveImage('signature','../uploads','signature',228,92);
    if($signature){
        $query = "UPDATE {$_CONF['db']['prefix']}_hotel_settings SET value = '$signature' WHERE input_name = 'signature'";
        $res = $CONN->_Query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }

    $stamp=saveImage('stamp','../uploads','stamp',92,92);
    if($stamp){
        $query = "UPDATE {$_CONF['db']['prefix']}_hotel_settings SET value = '$stamp' WHERE input_name = 'stamp'";
        $res = $CONN->_Query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }


    foreach ($_POST as $k => $v) {
        $query = "UPDATE {$_CONF['db']['prefix']}_hotel_settings SET value = '$v' WHERE input_name = '$k'";
        $res = $CONN->_Query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }
    //$FUNC->Redirect($SELF);
    $FUNC->Redirect('../buildCache.php');
}

//**************************************************************//
//****** Default output  ***************************************//
else {
    /*
    $TMPL->ParseIntoVar($_CENTER,'header');
    */
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_settings WHERE publish='1' AND pluginid='' ORDER BY orderid,id";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    $TMPL->addVar("data", $data);
    $TMPL->ParseIntoVar($_CENTER, 'settings');
}


function saveImage($image, $path, $new_name, $width, $height)
{

    $SET['img_width'] = $width;
    $SET['img_height'] = $height;

    if ($_FILES[$image]['name']!='' && $_FILES[$image]['size']!=0) {
        $ext=strtolower(substr($_FILES[$image]['name'],1+strrpos($_FILES[$image]['name'],".")));
        $IMG = new ImageGD($path);
        if ($img = $IMG->uploadImage($image)) {
            $IMG->resizeImage($img, $width, $height,$new_name.'.'.$ext,false);
        }
        if ($errors = $IMG->passErrors()) {
            @unlink($path . '/' . $new_name.'.'.$ext);
            return false;
        }else{
            return $new_name.'.'.$ext;
        }
    } else {
        return false;
    }
}