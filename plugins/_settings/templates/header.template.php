
<?if(!defined('ALLOW_ACCESS')) exit;?>
<? 
 if(isset($_GET['users'])){
    $class1 = "tab_inactive";
    $class2 = "tab_active"; 
  }
 else{
    $class1 = "tab_active";
    $class2 = "tab_inactive"; 
  }
?>
<table width="100%" border="0" cellspacing="2" cellpadding="0" style="margin-bottom:20px">
 <tr>
  <td nowrap class="<?=$class1?>">
    <a href="<?=$SELF?>" class="text1">
      <?=$TEXT['mlisting']['newsletter']?>
    </a>  
  </td>
  <td nowrap class="<?=$class2?>">
    <a href="<?=$SELF?>&users" class="text1">
      <?=$TEXT['mlisting']['users']?>
    </a>  
  </td>
  <td width="100%" class="tab_devider">&nbsp;</td>
 </tr>
</table>