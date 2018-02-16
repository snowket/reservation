<?if(!defined('ALLOW_ACCESS')) exit;?>
<form action="" method="post" enctype="multipart/form-data">
 <table width="500" border="0" cellspacing="0" cellpadding="5" class="text1" align="center">
  <tr>
    <td colspan="2" class="border_gray1">
      <b><?=$TMPL_plugin?></b>
    </td>
  </tr>
  <tr>
    <td><?=$TEXT['setts']['frommail']?></td>
    <td>
       <input type="text" name="frommail" value="<?=$TMPL_setts['frommail']?>" class="formField1" style="width:150px">
    </td>
  </tr>

  <tr>
    <td><?=$TEXT['setts']['fromname']?></td>
    <td>
       <input type="text" name="fromname" value="<?=$TMPL_setts['fromname']?>" class="formField1" style="width:150px">
    </td>
  </tr>

  <tr>
    <td><?=$TEXT['setts']['subject']?></td>
    <td>
       <input type="text" name="subject" value="<?=$TMPL_setts['subject']?>" class="formField1" style="width:150px">
    </td>
  </tr>
  
  <tr>
    <td><?=$TEXT['setts']['interval']?></td>
    <td>
       <input type="text" name="interval" value="<?=$TMPL_setts['interval']?>" class="formField1" style="width:50px">
    </td>
  </tr>
  
  <tr>
    <td class="border_gray1">&nbsp;</td>
    <td class="border_gray1"><input type="submit" value="<?=$TEXT['global']['save']?>"  class="formButton2"></td>
  </tr>
 </table>
 <input type="hidden" name="action" value="save"> 
 <input type="hidden" name="pid" value="<?=$TMPL_pid?>">
</form> 
