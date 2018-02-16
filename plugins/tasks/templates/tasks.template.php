<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['tasks']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="creator_id"><?= $TEXT['tasks']['creator'] ?></label>
                </td>
                <td>
                     <select id="creator_id" name="creator_id">
                        <option value="0"><?=$TEXT['tasks']['filter_modal']['all']?></option>
                        <? foreach ($TMPL_users as $creator) { ?>
                            <option
                                value="<?= $creator['id'] ?>" <? if ((int)$_GET['creator_id'] == $creator['id']) {
                                echo "selected";
                            } ?>><?= $creator['firstname'] ?> <?= $creator['lastname'] ?></option>
                        <? } ?>
                    </select>
                </td>
                <td align="right">
                    <label for="start_date"><?= $TEXT['tasks']['deadline'] ?></label>
                </td>
                <td>
                    <input type="text" class="calendar-icon" id="start_deadline" name="start_deadline" placeholder="<?=$TEXT['tasks']['filter_modal']['from']?>" value="<?=(isset($_GET['start_deadline']) && $_GET['start_deadline']!=='')?$_GET['start_deadline']:''?>"/>
                    <input type="text" class="calendar-icon" id="end_deadline" name="end_deadline" placeholder="<?=$TEXT['tasks']['filter_modal']['to']?>" value="<?=(isset($_GET['end_deadline']) && $_GET['end_deadline']!=='')?$_GET['end_deadline']:''?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="executor_id"><?= $TEXT['tasks']['executor'] ?></label>
                </td>
                <td>
                     <select id="executor_id" name="executor_id">
                        <option value="0"><?=$TEXT['tasks']['filter_modal']['all']?></option>
                        <? foreach ($TMPL_users as $executor) { ?>
                            <option
                                value="<?= $executor['id'] ?>" <? if ((int)$_GET['executor_id'] == $executor['id']) {
                                echo "selected";
                            } ?>><?= $executor['firstname'] ?> <?= $executor['lastname'] ?></option>
                        <? } ?>
                    </select>
                </td>
                <td align="right">
                    <label for="start_date"><?= $TEXT['tasks']['executed_at'] ?></label>
                </td>
                <td>
                    <input type="text" class="calendar-icon" placeholder="<?=$TEXT['tasks']['filter_modal']['from']?>" id="start_executed" name="start_executed" value="<?=(isset($_GET['start_executed']) && $_GET['start_executed']!=='')?$_GET['start_executed']:''?>"/>
                    <input type="text" class="calendar-icon" placeholder="<?=$TEXT['tasks']['filter_modal']['to']?>" id="end_executed" name="end_executed" value="<?=(isset($_GET['end_executed']) && $_GET['end_executed']!=='')?$_GET['end_executed']:''?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="in_task"><?= $TEXT['tasks']['filter_modal']['in_task'] ?></label>
                </td>
                <td>
                    <input type="text" class=" " id="in_task" name="in_task" value="<?=$_GET['in_task']?>"/>
                </td>
                <td align="right">
                    <label for="status"><?= $TEXT['tasks']['status']?></label>
                </td>
                <td>
                    <select id="status" name="status">
                        <option value="" <? if (!isset($_GET['status'])||$_GET['status']=='') {
                            echo "selected";
                        } ?>><?=$TEXT['tasks']['filter_modal']['all']?>
                        </option>
                        <option value="1" <? if (isset($_GET['status']) && $_GET['status']!='' && $_GET['status'] == 1) {
                            echo "selected";
                        } ?>><?=$TEXT['tasks']['executed']?>
                        </option>
                        <option value="0" <? if (isset($_GET['status']) && $_GET['status']!='' && $_GET['status'] == 0) {
                            echo "selected";
                        } ?>><?=$TEXT['tasks']['unexecuted']?>
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="4">
                    <br>
                    <input class="formButton2" type="submit"
                           value="<?= $TEXT['tasks']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>

<div style="height: 10px;">
</div>

<table class="table-table" cellpadding="2" cellspacing="0">
    <tr>
        <td class="table-th">&#8470;</td>
        <td class="table-th">
            <?= $TEXT['tasks']['creator'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['tasks']['deadline'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['tasks']['executor'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['tasks']['executed_at'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['tasks']['task'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['tasks']['status'] ?>
        </td>
        <td class="table-th"><?= $TEXT['tasks']['action'] ?></td>
    </tr>
    <?
    foreach ($TMPL_tasks AS $task) { ?>
        <tr class="table-tr">
            <td class="table-td">
                <b><? $p = ((int)($_GET['p'])!=0) ? (int)($_GET['p']) : 1;
                    echo ($p - 1) * intval($TMPL_rowsPerPage) + $counter + 1;?></b>
            </td>
            <td class="table-td" title="<?=$TMPL_users[$task['creator_id']]['firstname']?> <?=$TMPL_users[$task['creator_id']]['lastname']?> (<?= date('Y-m-d H:i:s', strtotime($task['created_at'])) ?>)">
               <?=$TMPL_users[$task['creator_id']]['login']?>
            </td>
            <td class="table-td">
                <div  class="date-filter basic" title="<?= date('Y-m-d', strtotime($task['deadline_at'])) ?>">
                    <?= date('Y-m-d H:i:s', strtotime($task['deadline_at'])) ?>
                </div>
            </td>
            <td class="table-td">
                <div title="<?=$TMPL_users[$task['executor_id']]['firstname']?> <?=$TMPL_users[$task['executor_id']]['lastname']?>">
                    <?=($TMPL_users[$task['executor_id']]['login']=='')?$TEXT['tasks']['undefined']:$TMPL_users[$task['executor_id']]['login']?>
                </div>
            </td>
            <td class="table-td">
                <div >
                    <?=($task['executed_at']=='')?$TEXT['tasks']['undefined']:date('Y-m-d H:i:s', strtotime($task['executed_at'])) ?>
                </div>
            </td>
            <td class="table-td">
                <div  id="task_<?=$task['id']?>"><?= $task['task']?></div>
            </td>
            <td class="table-td" style=" <?=($task['status']==1)?'background-color:#83FA00; color:#000000':'background-color:#FF0000; color:FFFFFF'?>">
                <div class="change_task_status_trigger" task_id="<?=$task['id']?>" style="cursor: pointer">
                    <?= ($task['status']==1)?$TEXT['tasks']['executed']:$TEXT['tasks']['unexecuted']?>
                </div>
            </td>
            <td class="table-td">
                <div class="edit_task" task_id="<?=$task['id']?>" deadline_date="<?=date('Y-m-d', strtotime($task['deadline_at']))?>" deadline_time="<?=date('H:i:s', strtotime($task['deadline_at']))?>" style="float:left; cursor: pointer">
                    <img src="./images/icos16/edit.gif" width="16" height="16" border="0" align="middle" alt="<?=$TEXT['tasks']['edit_task']?>"  title="<?=$TEXT['tasks']['edit_task']?>">
                </div>
                <div class="delete_task" task_id="<?=$task['id']?>" style="float:left; margin-left: 10px; cursor: pointer" >
                    <img src="./images/icos16/delete.gif" width="16" height="16" border="0" align="middle" alt="<?=$TEXT['tasks']['delete_task']?>"  title="<?=$TEXT['tasks']['delete_task']?>">
                </div>
                <?if($task['type']=='booking'){?>
                    <div style="float:left; margin-left: 10px; cursor: pointer" >
                        <a href="index.php?m=booking_management&tab=booking_list&action=view&booking_id=<?=$task['booking_id']?>">
                            <img src="./images/icos16/booking_bell.png" width="16" height="16" border="0" align="middle" alt="<?=$TEXT['tasks']['view_booking']?>"  title="<?=$TEXT['tasks']['view_booking']?>">
                        </a>
                    </div>
                <?}?>
            </td>
        </tr>
    <? $counter++;} ?>
</table>
<div style="float: right">
    <div id="add_new_task_modal_trigger" class="formButton2" style="margin:6px; cursor:pointer; padding-left:4px; width:140px;"><?=$TEXT['tasks']['add_new_task']?></div>
    <div id="excel_downloader" class="download-excel">Download</div>
</div>
<div style="margin-top: 10px">
    <center><?= $TMPL_navbar ?></center>
</div>



<div id="add_new_task_modal" style="display:none" title="<?= $TEXT['tasks']['add_new_task'] ?>">
  <form id="add_new_task_form" method="post">
      <input type="hidden" name="action" value="add_new_task">
      <input type="hidden" name="task_id" value="0">
      <table width="100%" border="0">
          <tr>
              <td width="50%">
                  <label for="service_price"><?= $TEXT['tasks']['date'] ?>Date: </label>
                    <input type="text" id="deadline_date" name="deadline_date" value="<?=CURRENT_DATE?>" class="calendar-icon" autocomplete="off" />
               </td>
              <td width="50%">
                  <label for="service_price"><?= $TEXT['tasks']['date'] ?>Time: </label>
                   <select id="deadline_time" name="deadline_time">
                   <?for($i=0;$i<24;$i++){?>
                        <option value="<?=($i>9)?$i:'0'.$i?>:00:00"><?=($i>9)?$i:'0'.$i?>:00:00</option>
                        <option value="<?=($i>9)?$i:'0'.$i?>:30:00"><?=($i>9)?$i:'0'.$i?>:30:00</option>
                   <?}?>
                   </select>
              </td>
          <tr>
          </tr>
              <td colspan="2">
                <textarea id="task" class="formField3" style="width:100%" name="task"></textarea>
              </td>
          </tr>
      </table>
  </form>
</div>

<form id="change_task_status_form" method="post">
    <input type="hidden" name="action" value="change_task_status">
    <input type="hidden" name="task_id" id="task_id" value="0">
</form>

<form id="delete_task_form" method="post">
    <input type="hidden" name="action" value="delete_task">
    <input type="hidden" name="task_id" id="task_id" value="0">
</form>

<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>

<script>
$( document ).ready(function() {
    $('#excel_downloader').click(function () {
        $('#excel_download_form').submit();
    });

    $(".delete_task").click(function () {
        if(confirm('are you sure?')){
            $('#delete_task_form input[name=task_id]').val($(this).attr('task_id'));
            $('#delete_task_form').submit();
        }
    });

    $(".change_task_status_trigger").click(function () {
        $('#change_task_status_form input[name=task_id]').val($(this).attr('task_id'));
        $('#change_task_status_form').submit();
    });

    $("#add_new_task_modal_trigger").click(function () {
        $("#add_new_task_modal").attr('title','<?=$TEXT['tasks']['add_new_task']?>');
        $("#add_new_task_form input[name='action']").val("add_new_task");
        $("#add_new_task_form textarea[name='task']").val("");
        $("#add_new_task_form input[name='deadline_date']").val('<?=date('Y-m-d',mktime())?>');
        $("#add_new_task_form select[name='deadline_time']").val('00:00:00');
        $("#add_new_task_modal").dialog({
            resizable: false,
            width: 400,
            modal: true,
            buttons: {
                "<?= $TEXT['tasks']['but_add'] ?>": function () {
                    $("#add_new_task_form").submit();
                    $(this).dialog("close");
                },
                "<?= $TEXT['tasks']['but_cancel'] ?>": function () {
                    $(this).dialog("close");
                }
            }
        });
        $("#add_new_task_modal").dialog('option', 'title', '<?=$TEXT['tasks']['add_new_task']?>');
        $("#deadline_date").datepicker('hide');
    });

    $(".edit_task").click(function () {
        $("#add_new_task_modal").attr('title','<?=$TEXT['tasks']['edit_task']?>');
        $("#add_new_task_form input[name='action']").val("edit_task");
        $("#add_new_task_form input[name='task_id']").val($(this).attr('task_id'));
        $("#add_new_task_form textarea[name='task']").val($('#task_'+$(this).attr('task_id')).text());
        $("#add_new_task_form input[name='deadline_date']").val($(this).attr('deadline_date'));
        $("#add_new_task_form select[name='deadline_time']").val($(this).attr('deadline_time'));
        $("#add_new_task_modal").dialog({
            resizable: false,
            width: 400,
            modal: true,
            buttons: {
                "<?= $TEXT['tasks']['but_edit'] ?>": function () {
                    $("#add_new_task_form").submit();
                    $(this).dialog("close");
                },
                "<?= $TEXT['tasks']['but_cancel'] ?>": function () {
                    $(this).dialog("close");
                }
            }
        });
        $("#add_new_task_modal").dialog('option', 'title', '<?=$TEXT['tasks']['edit_task']?>');
        $("#deadline_date").datepicker('hide');
    });

    $("#deadline_date").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        minDate: 'today',
        modal: true,
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
        }
    });

    $( "#start_deadline" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() + (1000*60*60*24));
            $( "#end_deadline" ).datepicker( "option", "minDate",date );
        }
    });

    $( "#end_deadline" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000*60*60*24));
            $( "#start_deadline" ).datepicker( "option", "maxDate", date );
        }
    });

    $( "#start_executed" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() + (1000*60*60*24));
            $( "#end_executed" ).datepicker( "option", "minDate",date );
        }
    });

    $( "#end_executed" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000*60*60*24));
            $( "#start_executed" ).datepicker( "option", "maxDate", date );
        }
    });

});
</script>