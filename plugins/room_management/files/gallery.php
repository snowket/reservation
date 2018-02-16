<?
$rec_id = StringConvertor::toNatural($_GET['rec_id']);
$restrict = false;

$query = "select creator from {$_CONF['db']['prefix']}_rooms_manager 
			where id='{$rec_id}'";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
//p($result);
if(!$result->fields)
   $FUNC->Redirect($SELF);
$restrict = ($LOADED_PLUGIN['restricted']&&$result->fields['creator']!=$$_SESSION['pcms_user_id'])?true:false;

$imgDIR .= "/$rec_id";
if(!file_exists($imgDIR)){
    mkdir($imgDIR,0777);
    chmod($imgDIR,0777);
}


if(!$restrict){
 if($_POST['action']=="add"){
 
  if($_FILES['image']['name']&&$_FILES['image']['size'])
    {
       $IMG = new ImageGD($imgDIR);
       if($img = $IMG->uploadImage("image"))
         {
           $IMG->resizeImage($img,$SETTINGS['img_width'],$SETTINGS['img_height'],"thumb2_".$img);
           //*** Making thumbnail  ************************//
           if($SETTINGS['g_method']=='c')
              $IMG->cropImage($img,$SETTINGS['g_th_width'],$SETTINGS['g_th_height'],"thumb_".$img);
           else
              $IMG->resizeImage($img,$SETTINGS['g_th_width'],$SETTINGS['g_th_height'],false, "thumb_".$img);
         }
       $errors = $IMG->passErrors();
     }

   if(empty($errors)){
        $query ="insert into {$_CONF['db']['prefix']}_rooms_gal set
                rec_id ={$rec_id},
                img ='{$img}',
                access='".StringConvertor::toNatural($_POST['access'])."'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
        $FUNC->Redirect($SELF."&tab=gallery&rec_id=".$rec_id);
      }  
   }
  else{
 

    @unlink($imgDIR.'/'.$img); 
    @unlink($imgDIR.'/thumb2_'.$img);
    @unlink($imgDIR.'/thumb_'.$img);

    if($_GET['action']=="ch_access"){
        $iid = StringConvertor::toNatural($_GET['iid']); 
        $query = "update {$_CONF['db']['prefix']}_rooms_gal set access=if(access=1,0,1) where id= '{$iid}'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
        $FUNC->Redirect($SELF."&tab=gallery&rec_id=".$rec_id);       
      } 
    elseif($_GET['action']=="delete"){
       $iid = StringConvertor::toNatural($_GET['iid']);
       $query = "select img from {$_CONF['db']['prefix']}_rooms_gal where id='{$iid}' and rec_id='{$rec_id}'";
       $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
       if($result->fields){
            @unlink($imgDIR."/thumb_".$result->fields['img']);
            @unlink($imgDIR."/thumb2_".$result->fields['img']);
            @unlink($imgDIR."/".$result->fields['img']);
            $query = "delete from {$_CONF['db']['prefix']}_rooms_gal where id='{$iid}'";
            $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
          }
       $FUNC->Redirect($SELF."&tab=gallery&rec_id=".$rec_id);
     } 
  }
}

$query = "select* from {$_CONF['db']['prefix']}_rooms_gal where rec_id='{$rec_id}'";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
while($row = $result->FetchRow()){
     $TMPL_images[]  = array(
           'id'      =>  $row['id'],
           'img'     =>  $imgDIR."/thumb_".$row['img'],
           'access'  =>  $row['access']
      );
  
 } 
 
$TMPL->addVar("TMPL_rec_id",$rec_id);  
$TMPL->addVar("TMPL_width",$SETTINGS['g_th_width']);
$TMPL->addVar("TMPL_height",$SETTINGS['g_th_height']);
$TMPL->addVar("TMPL_images",$TMPL_images); 
$TMPL->ParseIntoVar($_CENTER,'gallery');

