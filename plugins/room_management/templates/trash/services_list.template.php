 <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
     <thead>
         <th>Title</th>
         <th>Type</th>
         <th>Price</th>
         <th>Edit</th>
         <th>Visible</th>
         <th>Block</th>
         <th>Delete</th>
     </thead>
    <tbody>
     <? foreach($TMPL_data AS $service){?>
        <tr>
           <td width="250"><?=$service['title']?></td>
            <td><?=$TMPL_services_types[$service['type_id']]['title']?></td>
           <td><?=$service['price']?></td>
           <td width="25">
              <a href="<?=$SELF?>&action=edit&pid=<?=$service['id']?>&mid=<?=intval($_GET['mid'])?>">
                <img src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0">
              </a>
           </td>

           <td width="16" style="padding-left:5px;">
                <a href="<?=$SELF?>&action=change_visibility&pid=<?=$service['id']?>"><img src="./images/icos16/<?=($service['in_use']==1?'visible':'unvisible')?>.png" width="16" height="16" border="0" alt="Switch visibility"></a>
           </td>
           <td width="16" style="padding-left:5px;">
                <a href="<?=$SELF?>&action=change_status&pid=<?=$service['id']?>"><img src="./images/icos16/<?=($service['publish']==1?'protect-green':'protect-red')?>.gif" width="16" height="16" border="0" alt="Switch visibility"></a>
           </td>
           <td width="25">
             <? if($service['blocked']){ ?>
                <img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
             <?}else{?>
                <a href="<?=$SELF?>&action=delete&pid=<?=$service['id']?>">
                  <img src="./images/icos16/delete.gif" width="16" height="16" alt="delete" border="0">
                </a>
             <?}?>
          </td>
        </tr>
     <?}?>
    </tbody>
</table>
 
<div align="center"><?=$TMPL_navbar?></div>