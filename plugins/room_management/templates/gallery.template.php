<div  class="tdrow1" style="width:100%;">
 <form action="" method="post" enctype="multipart/form-data">
  <table width="450" border="0" cellspacing="0" cellpadding="2" class="text1">
    <?if($TMPL_errors){?>
      <tr>
         <td>&nbsp;</td>
         <td class="err" colspan="2"><?=$TMPL_errors?></td>
      </tr>
    <?}?>
    <tr>  
      <td width="130" style="padding-left:20px"><b><?=$TEXT['prod']['gal_image']?></b></td>
      <td width="170">
         <input type="file" class="formField1" name="image">
      </td>
<!--      <td>
          <select size="1" name="access" class="formField1">
               <option value="0"><?=$TEXT['prod']['private']?></option>
               <option value="1"><?=$TEXT['prod']['public']?></option>
          </select>
      </td> //-->
      <td><input type ="submit" value="  <?=$TEXT['global']['add']?>  " class="formButton2"></td>
    </tr>
  </table> 
  <input type="hidden" name="action" value="add">
 </form>
</div>

<div class="tdrow1" style="width:100%; margin-top:20px;">
  <? for($i=0;$i<count($TMPL_images);$i++){?>
   <div style="display:inline; float:left; width:<?=$TMPL_width?>; height:<?=$TMPL_height+25?>px; margin:10px;">
     <div style="height:<?=$TMPL_height?>px; text-align:center;"><img src="<?=$TMPL_images[$i]['img']?>"></div>
     <div style="height:20px; margin-top:5px; text-align:center;" class="tdrow1_">
       <a href="<?=$SELF?>&tab=gallery&action=delete&rec_id=<?=$TMPL_rec_id?>&iid=<?=$TMPL_images[$i]['id']?>">
         <img src="./images/icos16/delete.gif" width="16" height="16" alt="delete" border="0" align="middle" style="float:right"></a>
       <!-- <a href="<?=$SELF?>&tab=gallery&action=ch_access&rec_id=<?=$TMPL_rec_id?>&iid=<?=$TMPL_images[$i]['id']?>" class="basic">
         <?=$TMPL_images[$i]['access']==1?$TEXT['prod']['public']:$TEXT['prod']['private']?>a
       </a> //-->

     </div>
   </div>
 <?}?>  
</div>

