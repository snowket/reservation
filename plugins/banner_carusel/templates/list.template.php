 <?if(!defined('ALLOW_ACCESS')) exit;?>
 <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
 <? for($i=0;$i<count($TMPL_images);$i++){?>
    <tr>
       <td  width="70"><img src="<?=$TMPL_images[$i]['img']?>" height="65"></td>
       <td><?=$TMPL_images[$i]['title']?></td>
       <td width="25">
          <a href="<?=$SELF?>&action=edit&pid=<?=$TMPL_images[$i]['id']?>&mid=<?=intval($_GET['mid'])?>">
            <img src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0"> 
          </a> 
       </td>
       <td width="25">
         <? if($TMPL_images[$i]['blocked']){ ?>
            <img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
         <?}else{?>
            <a href="<?=$SELF?>&action=delete&pid=<?=$TMPL_images[$i]['id']?>">
              <img src="./images/icos16/delete.gif" width="16" height="16" alt="delete" border="0"> 
            </a> 
         <?}?>  
      </td> 
    </tr>
 <?}?>
</table>
 
<div align="center"><?=$TMPL_navbar?></div>