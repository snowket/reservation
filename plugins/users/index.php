<?php
if(!defined('ALLOW_ACCESS')) exit;

$ROOT = dirname(__FILE__);
$SELF = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];

require_once($ROOT."/lang/".LANG.".php");
$TMPL->setRoot($ROOT);

if(isset($_POST['action'])){

 //***************************************************************************//
 //*** Adding/Editing user group  ********************************************//
 if($_POST['action']=="addgroup"||$_POST['action']=="editgroup")
    {
       $VALIDATOR->validateLength($_POST['title'],$TEXT['users']['group_title'],3,255);
       $errors = $VALIDATOR->passErrors();
       #if(empty($_POST['id'])) $errors .= $TEXT['users']['choose_perm'];
       if(!empty($errors))
         {
           $TMPL->addVar("TMPL_errors",$errors);
         }
       else{
             $title = $VALIDATOR->ConvertSpecialChars($_POST['title']);
             $restricted = array();
             for($i=0;$i<count($_POST['id']);$i++)
               {
                 $pid[$i] = StringConvertor::toNatural($_POST['id'][$i]);
                 if($_POST['perm_'.$pid[$i]]=="self")
                   $restricted[] = $pid[$i];
               }
             //*** Adding new group  **************//
             if($_POST['action']=="addgroup")
              {
                $query ="insert into {$_CONF['db']['prefix']}_groups set title='{$title}',
                       permitions='".implode(',',$pid)."',restricted='".implode(',',$restricted)."'";
                $result  = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
              }
            //*** Editing group settings **********//
            elseif($_POST['action']=="editgroup")
              {
                $gid = StringConvertor::toNatural($_POST['gid']);
                if($gid>1)
                  {
                    $query ="update {$_CONF['db']['prefix']}_groups set title='{$title}',
                         permitions='".implode(',',$pid)."',restricted='".implode(',',$restricted)."'
                         where id='{$gid}'";
                    $result  = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
                  }
              }
           $FUNC->Redirect($_SERVER['PHP_SELF']."?m=users");
         }
    }

 //***********************************************************************//
 //*** Adding  users  ****************************************************//
 elseif($_POST['action']=="addmember"){
    $POST = $VALIDATOR->ConvertSpecialChars($_POST);
    $VALIDATOR->validateString($POST['login'],'LATIN_EXTENDED',$TEXT['users']['login'],4,16);
    $VALIDATOR->validateString($POST['passw'],'LATIN_EXTENDED',$TEXT['users']['pass'],5,255);
    $VALIDATOR->compareValues($POST['passw'],$POST['passw2'],$TEXT['users']['pass']);
    $VALIDATOR->validateLength($POST['name'],$TEXT['users']['name'],1,255);
    $VALIDATOR->validateLength($POST['lname'],$TEXT['users']['name'],1,255);
    $VALIDATOR->validateString($POST['email'],'EMAIL',$TEXT['users']['email'],5,255);
    $VALIDATOR->validateString($POST['gid'],'DIGIT',$TEXT['users']['group'],1);
    $errors=$VALIDATOR->passErrors();
    $query ="select * from {$_CONF['db']['prefix']}_users where login='{$POST['login']}'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    if($result->RecordCount()>0)
      $errors .= $TEXT['users']['user_exists'];
    if(!empty($errors))
      {
         $TMPL->addVar("TMPL_errors",$errors);
         $TMPL->addVar("TMPL_login",$POST['login']);
         $TMPL->addVar("TMPL_name",$POST['name']);
         $TMPL->addVar("TMPL_lname",$POST['lname']);
         $TMPL->addVar("TMPL_email",$POST['email']);
      }
    else{
        $passhash = $VALIDATOR->RandString("!@#$%^&*+/-_|",5);
        $password = $FUNC->CompiledPass($POST['passw'],$passhash);
        $query = "INSERT into {$_CONF['db']['prefix']}_users set login='{$POST['login']}',
               firstname = '".$POST['name']."',lastname = '".$POST['lname']."',email='{$POST['email']}',
               password='{$password}', passhash ='{$passhash}',group_id='{$POST['gid']}',joined=NOW(),publish='1'";
        $result  = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
        $FUNC->Redirect($SELF);
      }
  }
 //***********************************************************************//
 //*** Editing users  ****************************************************//
  elseif($_POST['action']=="editmember"){
    $POST = $VALIDATOR->ConvertSpecialChars($_POST);
    if(!empty($POST['passw'])){
       $VALIDATOR->validateString($_POST['passw'],'LATIN_EXTENDED',$TEXT['users']['pass'],5,255);
       $VALIDATOR->compareValues($_POST['passw'],$_POST['passw2'],$TEXT['users']['pass']);
       $passhash = $VALIDATOR->RandString("!@#$%^&*+/-_|",5);
       $password = $FUNC->CompiledPass($POST['passw'],$passhash);
       $addquery = "password='{$password}', passhash ='{$passhash}',";
     }
    $VALIDATOR->validateLength($POST['name'],$TEXT['users']['name'],1,255);
    $VALIDATOR->validateLength($POST['lname'],$TEXT['users']['name'],1,255);
    $VALIDATOR->validateString($POST['email'],'EMAIL',$TEXT['users']['email'],5,255);
    $VALIDATOR->validateString($POST['gid'],'DIGIT',$TEXT['users']['group'],1);
    $errors=$VALIDATOR->passErrors();
    if(!empty($errors))
      $TMPL->addVar("TMPL_errors",$errors);
    else{
        $password = $FUNC->CompiledPass($_POST['passw'],$passhash);
        $query = "update {$_CONF['db']['prefix']}_users set firstname = '{$POST['name']}',lastname = '".$POST['lname']."',
               email='{$POST['email']}',{$addquery} group_id='{$POST['gid']}' where id='{$POST['uid']}'";
        $result  = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
        $FUNC->Redirect($SELF."&action=edit_user&uid=".$POST['uid']);
      }
  }
}


if(isset($_GET['action'])){

 //*************************************************************//
 //*** Form for creating new group  ****************************//
 if($_GET['action']=='new_group')
   {
     $TMPL->importVars("PLUGINS");
     $TMPL->addVar("TMPL_button",$TEXT['global']['add']);
     $TMPL->addVar("TMPL_action","addgroup");
     $TMPL->ParseIntoVar($_CENTER,"addgroup");
   }
 //*****************************************************//
 //*** Form for editing group settings *****************//
 elseif($_GET['action']=='edit_group')
   {
     $gid = StringConvertor::toNatural($_GET['gid']);
     if($gid>1)
       {
         $query  = "select* from {$_CONF['db']['prefix']}_groups where id='{$gid}'";
         $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
         $data   = $result->GetRows();
         if(count($data)==0)
           $FUNC->Redirect($_SERVER['PHP_SELF']."?m=users");

         $TMPL->importVars("PLUGINS");
         $TMPL->addVar("TMPL_gid",$gid);
         $TMPL->addVar("TMPL_grtitle",$data[0]['title']);
         $TMPL->addVar("TMPL_checked",explode(',',$data[0]['permitions']));
         $TMPL->addVar("TMPL_restricted",explode(',',$data[0]['restricted']));
         $TMPL->addVar("TMPL_button",$TEXT['global']['edit']);
         $TMPL->addVar("TMPL_action","editgroup");
         $TMPL->ParseIntoVar($_CENTER,"addgroup");
       }

   }
 //*****************************************************//
 //*** Changing user status ****************************//
 elseif($_GET['action']=="change_status")
   {
      $uid   = StringConvertor::toNatural($_GET['uid']);
      $query = "update {$_CONF['db']['prefix']}_users set
                publish=if(publish=0,1,0) where id='{$uid}'";
      $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
      $FUNC->Redirect($_SERVER['HTTP_REFERER']);
   }

 //*****************************************************//
 //*** Users list in selected group ********************//
  elseif($_GET['action']=="show_users")
   {
     $gid     = StringConvertor::toNatural($_GET['gid']);
     $query   = "select* from {$_CONF['db']['prefix']}_groups where id='{$gid}'";
     $result  = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
     $gr_info = $result->GetRows();
     if(count($gr_info)==0)
       $FUNC->Redirect($SELF);
     $query = "select* from {$_CONF['db']['prefix']}_users
               where group_id='{$gid }'";
     $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

     $TMPL->addVar("TMPL_grname",$gr_info[0]['title']);
     $TMPL->addVar("TMPL_gid",$gr_info[0]['id']);
     $TMPL->addVar("TMPL_users", $result->GetRows());
     $TMPL->ParseIntoVar($_CENTER,"userlist");
   }

 //*****************************************************//
 //*** Form for creating new member ********************//
 elseif($_GET['action']=="new_member")
   {
     $query ="select id,title from {$_CONF['db']['prefix']}_groups order by id";
     $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
     while($o = $result->FetchNextObject())
	   $groups[$o->ID] = $o->TITLE;
     $TMPL->addVar("TMPL_gropts",pcmsInterface::drawOptions($groups,intval($_GET['gid']),_ASSOC_));
     $TMPL->ParseIntoVar($_CENTER,"addmember");
   }

 //*****************************************************//
 //*** Form for editing a member ***********************//
 elseif($_GET['action']=="edit_user")
   {
     $uid = StringConvertor::toNatural($_GET['uid']);
     $query ="select* from {$_CONF['db']['prefix']}_users where id='{$uid}'";
     $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
     if(!$data = $result->fields)
       $FUNC->Redirect($SELF);

     $query ="select id,title from {$_CONF['db']['prefix']}_groups order by id";
     $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
     while($o = $result->FetchNextObject())
	   $groups[$o->ID] = $o->TITLE;
	 $TMPL->addVar("TMPL_login",$data['login']);
     $TMPL->addVar("TMPL_name",$data['firstname']);
     $TMPL->addVar("TMPL_lname",$data['lastname']);
     $TMPL->addVar("TMPL_email",$data['email']);
     $TMPL->addVar("TMPL_uid",$data['id']);
     $TMPL->addVar("TMPL_gropts",pcmsInterface::drawOptions($groups,$data['group_id'],_ASSOC_));
     $TMPL->addVar("TMPL_action","editmember");
     $TMPL->ParseIntoVar($_CENTER,"editmember");
   }
 //*****************************************************//
 //*** Deleting entire group ***************************//
 elseif($_GET['action']=="del_group")
   {
     $gid = StringConvertor::toNatural($_GET['gid']);
     if($gid>1)
       {
         $query = "delete from {$_CONF['db']['prefix']}_groups where id={$gid}";
         $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
         $query = "delete from {$_CONF['db']['prefix']}_users where group_id={$gid}";
         $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
       }
     $FUNC->Redirect($SELF);
   }

 //*****************************************************//
 //*** Deleting single user ****************************//
 elseif($_GET['action']=="del_user")
   {
     $gid = StringConvertor::toNatural($_GET['gid']);
     $uid = StringConvertor::toNatural($_GET['uid']);
     if($uid>1)
       {
         $query = "delete from {$_CONF['db']['prefix']}_users where id={$uid}";
         $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
       }
     $FUNC->Redirect($_SERVER['PHP_SELF']."?m=users&action=show_users&gid=".$gid);
   }
}

//******************************************************//
//*** Default output     *******************************//
//*** Retrieving list of existing user groups  *********//
else{

 $query = "select id, title,(select count(*) from {$_CONF['db']['prefix']}_users as users where users.group_id= gr.id) num
          from {$_CONF['db']['prefix']}_groups as gr order by gr.id";

 $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

 $TMPL->addVar("TMPL_groups",$result->GetRows());
 $TMPL->ParseIntoVar($_CENTER,"groups");
}

?>
