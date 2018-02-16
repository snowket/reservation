<?if(!defined('ALLOW_ACCESS')) exit;?>
<div style="margin-bottom:10px" class="sectionName">
  <?=$TEXT['users']['add_user']?>
</div>
<form action="" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="text1">
   <?if($TMPL_errors){?>
     <tr>
        <td>&nbsp;</td>
        <td class="err"><?=$TMPL_errors?></td>
     </tr>
   <?}?>
   <tr>
     <td width="150"><b>User name</b></td>
     <td><input type="text" name="login" value="<?=$TMPL_login?>" class="formField1"></td>
   </tr>
   <tr>
     <td><b><?=$TEXT['users']['pass']?></b></td>
     <td><input type="password" name="passw" value="" class="formField1"></td>
   </tr>
   <tr>
     <td><b><?=$TEXT['users']['pass_retype']?></b></td>
     <td><input type="password" name="passw2" value="" class="formField1"></td>
   </tr>
   <tr>
     <td><b>First Name</b></td>
     <td><input type="text" name="name" value="<?=$TMPL_name?>" class="formField1"></td>
   </tr>
   <tr>
     <td><b>Last Name</b></td>
     <td><input type="text" name="lname" value="<?=$TMPL_lname?>" class="formField1"></td>
   </tr>
   <tr>
     <td><b><?=$TEXT['users']['email']?></b></td>
     <td><input type="text" name="email" value="<?=$TMPL_email?>" class="formField1"></td>
   </tr>
   <tr>
     <td><b><?=$TEXT['users']['select_group']?></b></td>
     <td>
       <select size="1" name="gid" class="formField1">
         <option></option>
         <?=$TMPL_gropts?>
       </select>
     </td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td><input type="submit" value="   <?=$TEXT['global']['add']?>   " class="formButton2"></td>
   </tr>
  </table>
  <input type="hidden" name="action" value="addmember">
</form>
