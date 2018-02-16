<?if(!defined('ALLOW_ACCESS')) exit;?>

<h3><?=$TEXT['rule_1']?></h3>
<div style="margin-top:4px; margin-bottom:4px; font-weight:normal; padding:2px; cursor: pointer; text-align: centerl; width: 160px; float:right" ip_type="allow" class="add_ip_modal_trigger formButton2" >
    + <?=$TEXT['add_allowed_ip']?>
</div>
<table class="table-table" cellpadding="2" cellspacing="0">
    <tr>
        <td class="table-th" width="30">&#8470;</td>
        <td class="table-th" width="150"><?=$TEXT['list_table']['ip']?></td>
        <td class="table-th"><?=$TEXT['list_table']['description']?></td>
        <td class="table-th" width="20"><?=$TEXT['list_table']['action']?></td>
    </tr>
    <? $counter=0; foreach ($TMPL_allow_ips AS $k=>$v) {$counter++?>
        <tr class="table-tr">
            <td class="table-td">
                <b><?=$counter?></b>
            </td>
            <td class="table-td">
                <?=$k?>
            </td>
            <td class="table-td">
                <?=$v?>
            </td>
            <td class="table-td">
                <a href="index.php?m=security&tab=ips&action=delete&ip_type=allow&ip=<?=$k?>">
                    <img src="./images/icos16/delete.gif">
                </a>
            </td>
        </tr>
    <? } ?>
</table>
<br>
<h3><?=$TEXT['rule_2']?></h3>
<div style="margin-top:4px; margin-bottom:4px; font-weight:normal; padding:2px; cursor: pointer; text-align: centerl; width: 160px; float:right" ip_type="deny"  class="add_ip_modal_trigger formButton2" >
    + <?=$TEXT['add_denied_ip']?>
</div>
<table class="table-table" cellpadding="2" cellspacing="0">
    <tr>
        <td class="table-th" width="30">&#8470;</td>
        <td class="table-th" width="150"><?=$TEXT['list_table']['ip']?></td>
        <td class="table-th"><?=$TEXT['list_table']['description']?></td>
        <td class="table-th" width="20"><?=$TEXT['list_table']['action']?></td>
    </tr>
    <? $counter=0; foreach ($TMPL_denay_ips AS $k=>$v) {$counter++?>
        <tr class="table-tr">
            <td class="table-td">
                <b><?=$counter?></b>
            </td>
            <td class="table-td">
                <?=$k?>
            </td>
            <td class="table-td">
                <?=$v?>
            </td>
            <td class="table-td">
                <a href="index.php?m=security&tab=ips&action=delete&ip_type=deny&ip=<?=$k?>">
                    <img src="./images/icos16/delete.gif">
                </a>
            </td>
        </tr>
    <? } ?>
</table>

<div id="ip_modal"  style="display: none;" title="fddd" class="ip_modal">
    <form id="add_ip_form" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="action" name="action" value="add_ip">
    <input type="hidden" id="ip_type" name="ip_type" value="">
    <table width="100%">
        <tr>
            <td><label for="ip"><?=$TEXT['list_table']['ip']?></label></td>
            <td >
                <input name="ip" id="ip" type="text" placeholder="221.221.221.221" style="width:100%" value="">
            </td>
        </tr>
        <tr>
            <td><label for="desc"><?=$TEXT['list_table']['description']?></label></td>
            <td >
                <textarea name="desc" id="desc" rows="5" style="width:100%"></textarea>
            </td>
        </tr>
    </table>
    </form>
</div>




<script type="text/javascript">
$(document).ready(function () {
    $(".add_ip_modal_trigger").click(function () {
        var modal_title='';
        console.log('ip_type',$(this).attr('ip_type'));
        $('#ip').val('');
        if($(this).attr('ip_type')=="allow"){
            $('#ip_type').val('allow');
            modal_title='<?=$TEXT['add_allowed_ip']?>';
        }else if($(this).attr('ip_type')=="deny"){
            $('#ip_type').val('deny');
            modal_title='<?=$TEXT['add_denied_ip']?>';
        }else {
           return;
        }

        var btns={};
        btns['<?=$TEXT['add_ip_modal']['add']?>']= function () {
        if(ValidateIPaddress($('#ip').val())){
            $('#add_ip_form').submit();
        }else{
            alert("Invalid IP");
        }

        };
        btns["<?=$TEXT['add_ip_modal']['cancel']?>"]= function () {
            $(this).dialog("close");
        };

        $("#ip_modal").dialog({
            title: modal_title,
            resizable: false,
            width: 300,
            modal: true,
            buttons: btns
        });
    })
});
function ValidateIPaddress(ip)
 {console.log('ValidateIPaddress('+ip+')')
     var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
     if(ip.match(ipformat))
     {
     return true;
     }
     else
     {
     return false;
     }
 }
</script>