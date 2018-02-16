<table id="services_table" class="table-table">
<tr>
    <td class="table-th"><?=$TEXT['list_table']['image']?></td>
    <td class="table-th"><?=$TEXT['list_table']['title']?></td>
    <td class="table-th"><?=$TEXT['list_table']['type']?></td>
    <td class="table-th"><?=$TEXT['list_table']['price']?></td>
    <td class="table-th"><?=$TEXT['list_table']['edit']?></td>
    <td class="table-th"><?=$TEXT['list_table']['publish']?></td>
    <td class="table-th"><?=$TEXT['list_table']['delete']?></td>
</tr>
<? foreach($TMPL_data AS $service){?>
<tr>
    <td class="table-td">
        <img src="<?=($service['img']!="")?"../uploads_script/hotel_services/".$service['img']:"../uploads_script/hotel_services/no-image.png" ?>">
    </td>
    <td class="table-td"><?=$service['title']?></td>
    <td class="table-td"><?=$TMPL_services_types[$service['type_id']]['title']?></td>
    <td class="table-td"><?=$service['price']?></td>
    <td class="table-td">
        <a class="add_edit_modal_trigger" href="#" service_id="<?=$service['id']?>">
        <img src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0">
        </a>
    </td>
    <td class="table-td">
        <a href="<?=$SELF?>&action=change_status&pid=<?=$service['id']?>"><img src="./images/icos16/<?=($service['publish']==1?'protect-green':'protect-red')?>.gif" width="16" height="16" border="0" alt="Switch visibility"></a>
    </td>
    <td class="table-td">
     <? if($service['blocked']){ ?>
        <img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
     <?}else{?>
        <a href="<?=$SELF?>&action=delete&pid=<?=$service['id']?>" onclick="return confirm('are you sure?')">
          <img src="./images/icos16/delete.gif" width="16" height="16" alt="delete" border="0">
        </a>
     <?}?>
    </td>
</tr>
<?}?>

</table>

