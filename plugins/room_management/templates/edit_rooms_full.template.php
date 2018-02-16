<script language="javascript" src="./tiny_mce/tiny_mce.js"></script>
<script language="javascript" src="./js/mce_init.js"></script>
<table border="0" width="100%" cellpadding="0" cellspacing="2" style="font-size:11px;">
    <tr>
        <? for ($i = 0; $i < count($TMPL_lang); $i++) { ?>
            <td class="<?= ($TMPL_lang[$i] == $TMPL_item['lang']) ? 'tab_active' : 'tab_inactive' ?>">
                <a href="<?= $SELF ?>&action=edit&id=<?= $TMPL_item['id'] ?>&lang=<?= $TMPL_lang[$i] ?>"
                   class="text1"><?= $TMPL_lang[$i] ?></a>
            </td>
        <? } ?>
        <td width="100%" class="tab_devider">&nbsp;</td>
    </tr>
</table>


<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="lang" value="<?= $TMPL_item['lang'] ?>">
    <input type="hidden" name="id" value="<?= $TMPL_item['id'] ?>">
    <input type="hidden" name="action" value="save">

    <table border="0" style="width:100%;" cellspacing="0" cellpadding="2" class="text1">
        <? if ($TMPL_errors) { ?>
            <tr>
            <td colspan="2" class="err" align="center"><?= $TMPL_errors ?></td></tr><? } ?>
        <tr>
            <td colspan="2">
                <div style="font-weight:bold; padding:5px 0;"><?= $TEXT['prod']['intro'] ?></div>
                <textarea name="intro" class="formField1 mceEditor"
                          style="width:100%;height:80px"><?= $TMPL_item['intro'] ?></textarea>
            </td>
        </tr>
    </table>

    <div style="width:100%;padding-top:4px;text-align:right">
        <input type="submit" value="<?= $TEXT['global']['edit'] ?>" class="formButton2">
    </div>
</form>


<form name="rooms" action="<?= $SELF ?>" method="post" enctype="multipart/form-data">
    <table border="0" cellpadding="3" cellspacing="0">
        <tr>
            <td class="text1"><?= $TEXT['number'] ?></td>
            <td class="text1"><?= $TEXT['floor'] ?></td>
            <td class="text1"><?= $TEXT['for_web'] ?></td>
        </tr>
        <? foreach ($TMPL_rooms as $key => $value) { ?>
            <tr>
                <td>
                    <input type="text" name="room[<?= $value['id'] ?>]" value="<?= $value['name'] ?>"
                           style="width:80px;"/>
                </td>
                <td>
                    <select name="floor[<?= $value['id'] ?>]" id="" style="width:80px;">
                        <?for ($i = 1; $i <= $TMPL_block['floors']; $i++) {
                            $selected = '';
                            $selected = $value['floor'] == $i ? "selected" : "";
                            ?>
                            <option value="<?= $i ?>" <?= $selected ?>><?= $i ?></option>
                        <? } ?>
                    </select>
                </td>
                <td>
                    <select name="for_web[<?= $value['id'] ?>]" id="" style="width:80px;">
                        <option value="1" <?= ($value['for_web']==1)?"selected":'' ?>>yes</option>
                        <option value="0" <?= ($value['for_web']==0)?"selected":'' ?>>no</option>
                    </select>
                </td>
            </tr>

        <? } ?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right"><input type="submit" value=" <?= $TEXT['global']['edit'] ?> " class="formButton2">
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?= $TMPL_item['id'] ?>">
    <input type="hidden" name="action" value="update_rooms">
</form>
