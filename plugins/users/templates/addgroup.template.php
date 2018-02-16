<?if(!defined('ALLOW_ACCESS')) exit;?>
<form action="" method="post" enctype="multipart/form-data">
 <table width="100%" border="0" cellspacing="0" cellpadding="2" class="text1">
  <? if(isset($TMPL_errors)){?>
    <tr>
      <td>&nbsp;</td>
      <td class="err"><?=$TMPL_errors?></td>
    </tr>
  <? } ?>
  <tr>
    <td width="150" height="50" valign="top"> 
      <b><?=$TEXT['users']['group_title']?></b>
    </td>
    <td valign="top">
      <input type="text" name="title" value="<?=$TMPL_grtitle?>" class="formField1" style="width:80%">
    </td>
  </tr>
  <tr>
    <td valign="top"><b><?=$TEXT['users']['permitions']?></b></td>
    <td valign="top">
      <table cellpadding="2" cellspacing="0" border="0" class="text1">
        <? for($i=0;$i<count($PLUGINS);$i++){ 
             $checked  = $TMPL_checked&&in_array($PLUGINS[$i]['id'],$TMPL_checked)?"checked":"";
             $selected = $TMPL_restricted&&in_array($PLUGINS[$i]['id'],$TMPL_restricted)?"checked":"";
        ?>
          <tr>
            <td width="200"><?=$PLUGINS[$i]['title_'.LANG]?></td>
            <td width="30"><input type="checkbox" name="id[]" value="<?=$PLUGINS[$i]['id']?>" <?=$checked?>></td>
            <td>
              <? if($PLUGINS[$i]['distributed']==1){ ?>
                 <input type="radio" name="perm_<?=$PLUGINS[$i]['id']?>" value="all">&nbsp;<?=$TEXT['users']['all']?>
                 <input type="radio" name="perm_<?=$PLUGINS[$i]['id']?>" value="self" <?=$selected?>>&nbsp;<?=$TEXT['users']['own']?>
              <?}?>
            </td>
          </tr>
        <? } ?>
      </table> 
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="   <?=$TMPL_button?>   " class="formButton2"></td>
  </tr>
 </table>
 <input type="hidden" name="gid" value="<?=$TMPL_gid?>">
 <input type="hidden" name="action" value="<?=$TMPL_action?>">
</form>
