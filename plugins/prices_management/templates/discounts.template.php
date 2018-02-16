
<form id="update_form" method="post">
    <input type="hidden" name="action" value="update">
    <input type="hidden" id="common_id" name="common_id" value="0">
    <input type="hidden" id="lpd" name="lpd" value="">
    <input type="hidden" id="pay_now_discount" name="pay_now_discount" value="0">
    <input type="hidden" id="child_price" name="child_price" value="0">
    <input type="hidden" id="payments_method" name="payments_method" value="0">
</form>

<? foreach ($TMPL_commons as $b=> $block) {
if(count($block)>0){?>
<table border="1" style="border-collapse: collapse; width:100%"  cellspacing="2" >
    <tr style="background-color: #3a82cc; color:#ffffff">
        <td colspan="2" ><?=($b!='')?$b:'No Block Title'?></td>
    </tr>
    <? foreach ($block as $t=> $type) {
        if(count($type)>0){?>

    <tr>
        <td style="width: 100px; "><div style="width: 100px"><?=($t!='')?$t:'No Title'?></div></td>
        <td>
            <table border="1" style="border-collapse: collapse; width:100%" cellspacing="2" cellpadding="2">
                <tr style="background-color: #2172C6; color:#ffffff">
                    <td>img</td>
                    <td><?=$TEXT['room_capacity']?></td>
                    <td><?=$TEXT['payment_general_method']?></td>
                    <td><?=$TEXT['pay_now_discount']?></td>
                    <td><?=$TEXT['child_price']?></td>
                    <td><?=$TEXT['one_person_discount']?></td>
                    <td><?=$TEXT['action']?></td>
                </tr>
                <?foreach ($type as $c=> $capacity) {
                if(count($capacity)>0){?>
                    <tr>
                        <td width="30"><img src="../uploads_script/room_management/<?=$capacity['introimg']?>" width="30" height="20" ></td>
                        <td width="140"><div style="width: 140px"><?=$c?></div></td>
                        <td width="120">
                            <select name="payments_method_<?=$capacity['id']?>" id="payments_method_<?=$capacity['id']?>">
                              <?foreach ($TMPL_tbc_payments_method AS $k=>$v) {?>
                              <option value="<?=$k?>" <?=($k==$capacity['payments_method'])?'selected':''?> ><?=$v?></option>
                              <?}?>
                            </select>
                        </td>
                        <td width="100"><input type="number" id="pay_now_discount_<?=$capacity['id']?>" style="width:50px" name="pay_now_discount_<?=$capacity['id']?>" value="<?=$capacity['pay_now_discount']?>" max="99" min="0"  />%</td>
                        <td width="100"> <input type="number" id="child_price_<?=$capacity['id']?>" style="max-width:100px" name="child_price_<?=$capacity['id']?>" value="<?=$capacity['child_price']?>" min="0"  /></td>
                        <td>
                            <table border="1" style="border-collapse: collapse;">
                                <?
                                $lpd=json_decode($capacity['lpd'],true);
                                $max_capacity=$TMPL_capacity[$capacity['capacity_id']]['capacity']-1;
                                $td='<tr>';
                                for($i=$max_capacity; $i>0; $i--){
                                    $td.='<td><div style="width:84px">'.$i.'p=<input type="number" style="width:50px" id="lpd_'.$capacity['id'].'_'.$i.'" value="'.(float)$lpd[$i].'" max="99" min="0" step="0.1" />%</div></td>';
                                }
                                $td.='</tr>';
                                echo $td;
                                ?>
                            </table>
                        </td>
                        <td width="80"><input class="updater formButton2" common_id="<?=$capacity['id']?>" max_capacity="<?=$max_capacity?>" type="submit" value="<?=$TEXT['save']?>" /></td>
                    </tr>
                <?}}?>
            </table>
        </td>
    </tr>
    <?}}?>

</table>
    <br>
<?}}?>


<script type="text/javascript">
$( document ).ready(function() {
    $(".updater").click(function() {
        var common_id=$(this).attr('common_id');
        var max_capacity=$(this).attr('max_capacity');
        var lpd={};
        for(var i=max_capacity; i>0; i--){
           lpd[i]=parseFloat($( "#lpd_"+common_id+"_"+i).val(),10);
        }


        $("#common_id").val(common_id);
        $("#lpd").val(JSON.stringify(lpd));
        $("#pay_now_discount").val($("#pay_now_discount_"+common_id).val());
        $("#child_price").val($("#child_price_"+common_id).val());
        $("#payments_method").val($("#payments_method_"+common_id).val());
        $("#update_form").submit();
    });
});
</script>

