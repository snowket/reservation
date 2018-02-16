<form action="" method="post" enctype="multipart/form-data">
<table class="table-table" cellspacing="0" cellpadding="2">
<tr>
<td class="table-th">Type</td>
<td class="table-th"></td>
</tr>
    <? foreach($TMPL_items as $k=>$v){ ?>
    <tr>
    <td class="table-td"><?=$k?></td>
    <td class="table-td" >
        <table border="0" width="100%" cellspacing="0" cellpadding="5" class="text_black border_gray2">
          <? for($i=0,$n=count($v); $i<$n; $i++){?>
            <tr
                <? if($v[$i]['promo']) { ?>
                    onmouseover="this.style.background='#FFA4BD'"  onmouseout="this.style.background='#FFDBE5'" style="cursor:pointer; background:#FFDBE5;"
                <? } else {?>
                     onmouseover="this.style.background='#ECECEC'" onmouseout="this.style.background='#F8F8F8'" style="cursor:pointer;"
                <? }?>
             >
                <td width="100" height="90" valign="top" class="border_gray1">
                <?if($v[$i]['img']){?>
                    <img src="<?=$TMPL_imgdir?>/thumb_<?=$v[$i]['img']?>" border="0" vspace="4" width="90">
                <?}else{?>
                <?}?>
                </td>
                <td valign="top" style="padding-right:20px;" class="border_gray1">
                    <a class="basic" href="<?=$SELF?>&action=edit&id=<?=$v[$i]['id']?>">
                        <b><?=$v[$i]['title']?></b>
                    </a><br>
                    <?=htmlspecialchars_decode($v[$i]['intro'])?>
                </td>
                <td align="right" valign="top" style="width:1%;" class="border_gray1" nowrap>
                    <table cellspacing="0" cellpadding="0">
                    </table>


                    <a href="index.php?m=room_management&tab=items&action=change_status&id=<?=$v[$i]['id']?>"><img src="./images/icos16/<?=($v[$i]['publish']==1?'protect-green':'protect-red')?>.gif" width="16" height="16" border="0" align="top" title="Enable/Disable Product"></a>

                    <a href="<?=$SELF?>&action=edit&id=<?=$v[$i]['id']?>"><img src="./images/icos16/edit.gif" width="16" height="16" border="0" align="top" title="Edit Product"></a>
                    <?if($v[$i]['blocked']){?>
                        <img src="./images/icos16/blocked.gif" width="16" height="16" border="0" align="top">
                    <? }else{ ?>
                        <a onclick="return confirm('are you sure?')" href="index.php?m=room_management&tab=items&action=delete&id=<?=$v[$i]['id']?>"><img src="./images/icos16/delete.gif" width="16" height="16" border="0" align="top" title="Delete Product"></a>
                    <?}?>
                </td>
            </tr>
            <?}?>
          </table>
      </td>
    </tr>
    <?}?>
</table>
</form>
