 <table border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
 <? for($i=0;$i<count($TMPL_data);$i++){?>
    <tr>
       <td style="width:200px;"><?=$TMPL_data[$i]['title']?></td>
       <td width="25">
          <a href="<?=$SELF?>&action=edit&pid=<?=$TMPL_data[$i]['id']?>">
            <img src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0"> 
          </a> 
       </td>
       <td width="16" style="padding-left:5px;">    
			<a href="<?=$SELF?>&action=change_status&pid=<?=$TMPL_data[$i]['id']?>"><img src="./images/icos16/<?=($TMPL_data[$i]['publish']==1?'protect-green':'protect-red')?>.gif" width="16" height="16" border="0" alt="Switch visibility"></a>
	   </td>
       <td width="25">
         <?if($TMPL_data[$i]['blocked']){?>
            <img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
         <?}else{?>
            <a href="<?=$SELF?>&action=delete&pid=<?=$TMPL_data[$i]['id']?>">
              <img src="./images/icos16/delete.gif" width="16" height="16" alt="delete" border="0"> 
            </a> 
         <?}?>  
      </td> 
    </tr>
 <?}?>
</table>
 
<div align="center"><?=$TMPL_navbar?></div>