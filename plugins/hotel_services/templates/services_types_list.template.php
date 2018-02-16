<table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
 <? foreach($TMPL_data AS $services_type){?>
    <tr>
       <td >
           <?=$services_type['title']?>
       </td>
       <td width="25">
          <a href="<?=$SELF?>&action=edit&pid=<?=$services_type['id']?>&mid=<?=intval($_GET['mid'])?>">
            <img src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0"> 
          </a> 
       </td>
       <td width="25">
         <? if($services_type['blocked']){ ?>
            <img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
         <?}else{?>
            <a href="<?=$SELF?>&action=delete&pid=<?=$services_type['id']?>">
              <img src="./images/icos16/delete.gif" width="16" height="16" alt="delete" border="0"> 
            </a> 
         <?}?>  
      </td> 
    </tr>
 <?}?>
</table>
 
<div align="center"><?=$TMPL_navbar?></div>