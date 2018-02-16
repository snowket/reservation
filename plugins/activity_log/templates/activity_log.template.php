<?if(!defined('ALLOW_ACCESS')) exit;?>

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['activity_log']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="keyword"><?= $TEXT['activity_log']['filter_modal']['keyword'] ?></label>
                </td>
                <td>
                    <input type="text"  id="keyword" name="keyword" value="<?=$_GET['keyword']?>"/>
                </td>
                <td align="right">
                    <label for="start_date"><?= $TEXT['activity_log']['filter_modal']['date'] ?></label>
                </td>
                <td>
                    <input type="text" class="calendar-icon" id="start_date" name="start_date" value=""/>
                    <input type="text" class="calendar-icon" id="end_date" name="end_date" value=""/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="user_id"><?= $TEXT['activity_log']['filter_modal']['user_id'] ?></label>
                </td>
                <td>
                    <select id="user_id" name="user_id">
                        <option value="-1"><?=$TEXT['activity_log']['filter_modal']['all']?></option>
                        <? foreach ($TMPL_users as $id=>$user) { ?>
                            <option value="<?= $id ?>" <? if (isset($_GET['user_id'])&&(int)$_GET['user_id'] == $id) {echo "selected";} ?>>
                                <?= $user['firstname']." ".$user['lastname'] ?>
                            </option>
                        <? } ?>
                    </select>
                </td>
                <td align="right">
                    <label for="guest_name"><?= $TEXT['activity_log']['filter_modal']['group_id'] ?></label>
                </td>
                <td>
                    <select id="group_id" name="group_id">
                        <option value="-1"><?=$TEXT['activity_log']['filter_modal']['all']?></option>
                        <? foreach ($TMPL_groups as $id=>$group) { ?>
                            <option value="<?= $id ?>" <? if (isset($_GET['group_id'])&&(int)$_GET['group_id'] == $id) {echo "selected";} ?>>
                                <?= $group['title']?>
                            </option>
                        <? } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="4">
                    <br>
                    <input class="formButton2" type="submit"
                           value="<?= $TEXT['activity_log']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>

<div style="height: 10px;">

</div>


<table class="table-table" cellspacing="0" cellpadding="2">
<tr>
    <td class="table-th">&#8470; </td>
    <td class="table-th"><?=$TEXT['list_table']['action']?></td>
    <td class="table-th"><?=$TEXT['list_table']['description']?></td>
    <td class="table-th"><?=$TEXT['list_table']['user']?></td>
    <td class="table-th"><?=$TEXT['list_table']['group']?></td>
    <td class="table-th"><?=$TEXT['list_table']['date']?></td>
    <td class="table-th"><?=$TEXT['list_table']['ip']?></td>
</tr>
<?
$counter=0;
$page=(isset($_GET['p'])&&(int)$_GET['p']>0)?((int)$_GET['p']-1)*50:0;
foreach($TMPL_logs AS $log){?>
<tr class="table-tr">
    <td class="table-td"><?=($page+$counter+1); ?></td>
    <td class="table-td"><?=$log['action']?></td>
    <td class="table-td"><?=$log['description']?></td>
    <td class="table-td"><?=$log['user']?></td>
    <td class="table-td"><?=$log['group']?></td>
    <td class="table-td"><?=$log['date']?></td>
    <td class="table-td"><?=$log['ip']?></td>
</tr>
<?$counter++;}?>
</table>

<div style="margin-top: 10px;">
    <center><?= $TMPL_navbar ?></center>
</div>

<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>
<div id="excel_downloader" class="download-excel" style="float:right">
Download
</div>


<script type="text/javascript">
$(document).ready(function () {

        $("#start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true,
            buttonText: "Select date",
            onSelect: function (selectedDate) {
                var date = $(this).datepicker('getDate');
                date.setTime(date.getTime() + (1000 * 60 * 60 * 24));
                $("#end_date").datepicker("option", "minDate", date);
            }
        });

        $("#end_date").datepicker({
            dateFormat: 'yy-mm-dd',
            defaultDate: new Date(),
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            buttonImageOnly: true,
            buttonText: "Select date",
            onSelect: function (selectedDate) {
                var date = $(this).datepicker('getDate');
                date.setTime(date.getTime() - (1000 * 60 * 60 * 24));
                $("#start_date").datepicker("option", "maxDate", date);
            }
        });

        $("#start_date").datepicker("setDate", '<?=(isset($_GET['start_date'])&&$_GET['start_date']!="")?$_GET['start_date']:date("Y-m-01") ?>');
        $("#end_date").datepicker("setDate", '<?=(isset($_GET['end_date'])&&$_GET['end_date']!="")?$_GET['end_date']:date("Y-m-d") ?>');

        $('#excel_downloader').click(function () {
            $('#excel_download_form').submit();
        });
});
</script>