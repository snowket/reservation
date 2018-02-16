 <table border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
 <? for($i=0;$i<count($TMPL_data);$i++){?>
    <tr>
       <td style="width:200px;"><?=$TMPL_data[$i]['title']?></td>
       <td width="25">
          <a href="?m=room_management&tab=items&action=view&cid=<?=$TMPL_data[$i]['id']?>">
            <img src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0"> 
          </a> 
       </td>

    </tr>
 <?}?>
</table>
 
<div align="center"><?=$TMPL_navbar?></div>