<?


if ($_POST['action'] == "add" || $_POST['action'] == "edit") {

    $POST = $VALIDATOR->ConvertSpecialChars($_POST);

    unset($POST['action']);
    foreach ($POST as $key => $value) {
        if (in_array($key, array('one_person_discount', 'extra_services','food_services', 'default_services', 'intro', 'lang'))) {
            continue;
        }
        $VALIDATOR->validateString($value, 'DIGIT', $TEXT[$key], 1);
    }

    foreach ($POST['extra_services'] as $key => $value) {
        $esArr[] = (int)$value;
    }

    $es = implode(',', $esArr);
    if ($es) {
        $q_es = " extra_services='" . $es . "' , ";
    }

    foreach ($POST['default_services'] as $key => $value) {
        $dsArr[] = (int)$value;
    }

    $ds = implode(',', $dsArr);
    if ($ds) {
        $q_ds = " default_services='" . $ds . "' , ";
    }

    foreach ($POST['food_services'] as $key => $value) {
        $foodArr[] = (int)$value;
    }

    $fs = implode(',', $foodArr);
    $q_fs = " food_services='" . $fs . "' , ";

    //*** Uploading intro image  *******************************//
    $query_img = "";
    //$imgDIR=getcwd().str_replace('.','',$imgDIR);
    //p($imgDIR);
    if (!$errors = $VALIDATOR->passErrors()) {
        if ($_FILES['image']['name'] && $_FILES['image']['size']) {
            $query_img = "introimg ='',";
            $IMG = new ImageGD($imgDIR);
            if ($img = $IMG->uploadImage("image")) {
                $IMG->resizeImage($img, $SETTINGS['img_width'], $SETTINGS['img_height'], 'thumb2_' . $img, false);

                //*** Making thumbnail  ************************//
                if ($SETTINGS['th_method'] == 'c') {

                    $IMG->cropImage($img, $SETTINGS['th_width'], $SETTINGS['th_height'], 'thumb_' . $img);
                } else {
                    $IMG->resizeImage($img, $SETTINGS['th_width'], $SETTINGS['th_height'], 'thumb_' . $img, false);
                }
                $query_img = "introimg ='{$img}',";
            }
            if ($errors = $IMG->passErrors()) {
                @unlink($imgDIR . '/' . $img);
                @unlink($imgDIR . '/thumb_' . $img);
                @unlink($imgDIR . '/thumb2_' . $img);
            }
        }
    }

    //*** Action is Add  ****************************//
    if ($_POST['action'] == 'add') {
        //*** Report if errors are found  ************//
        if (!empty($errors)) {
            drawAddForm($POST, $errors);
        } //*** No errors, save it *********************//
        else {
            $query = "INSERT INTO {$_CONF['db']['prefix']}_rooms_manager SET
						block_id={$POST['block_id']},
						type_id={$POST['type_id']},
						capacity_id={$POST['capacity_id']},
						{$q_es}
						{$q_ds}
						{$q_fs}
						{$query_img}
						creator='{$_SESSION['pcms_user_id']}'";
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $common_id = $CONN->Insert_ID();

            foreach ($_CONF['langs_all'] as $v => $k) {
                $query = "INSERT INTO {$_CONF['db']['prefix']}_rooms_info
							SET common_id ={$common_id}, lang='{$k}',
							intro = '{$POST['intro'][$k]}'";
                $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            }

            for ($i = 0; $i < $POST['quantity']; $i++) {
                $query = "INSERT INTO {$_CONF['db']['prefix']}_rooms
							SET common_id ={$common_id}";
                $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            }

            $FUNC->Redirect($SELF . '&action=edit&id=' . $common_id);
        }
    } //*** Action is Edit  ****************************//
    else {
        $id = StringConvertor::toNatural($_POST['id']);

        //*** Printing only top part of edit form ***//
        if (!empty($errors)) {
            $query = "SELECT introimg FROM {$_CONF['db']['prefix']}_rooms_manager WHERE id= '{$id}'";
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            if ($result->fields['introimg'])
                $POST['introimg'] = $imgDIR . "/thumb_" . $result->fields['introimg'];
            $TMPL->addVar("TMPL_errors", $errors);
            $TMPL->addVar('TMPL_catbox', $FIELD);
            // Added
            $TMPL->addVar('TMPL_es', GetServices('extra'));
            $TMPL->addVar('TMPL_ds', GetServices('default'));
            $TMPL->addVar('TMPL_rc', GetRoomCapacity());
            $TMPL->addVar('TMPL_blocks', GetBlocks());
            $TMPL->addVar('TMPL_types', GetRoomTypes());
            $TMPL->addVar("TMPL_item", $POST);
            $TMPL->ParseIntoVar($_CENTER, 'edit_rooms_head');
        } //*** No errors, save it *********************//
        else {
            if ($LOADED_PLUGIN['restricted']) {
                $strictquery = " and creator ='{$_SESSION['pcms_user_id']}'";
            }

            $query = "UPDATE {$_CONF['db']['prefix']}_rooms_manager SET block_id={$POST['block_id']},
						type_id={$POST['type_id']},
						{$q_es}
						{$q_ds}
						{$q_fs}
						{$query_img}
						capacity_id={$POST['capacity_id']}
						WHERE id='{$id}' {$strictquery}";
            //p($query);
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $FUNC->Redirect($SELF . "&action=edit&id=" . $id);
        }
    }
}
//*********************************************************************//
//***  Editing rooms full information  ******************************//
elseif ($_POST['action'] == 'update_rooms') {
    $POST['id'] = StringConvertor::toNatural($_POST['id']);

    foreach ($_POST['room'] as $key => $value) {
        $query = "UPDATE {$_CONF['db']['prefix']}_rooms SET
				  	name='" . mysql_real_escape_string($value) . "',
				  	for_web=" . (int)($_POST['for_web'][$key]) . ",
				  	floor=" . (int)($_POST['floor'][$key]) . "
				   WHERE id=" . (int)$key . "
				  ";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }
    $FUNC->Redirect($SELF . "&action=edit&id=" . $POST['id']);
}
//*********************************************************************//
//***  Editing rooms full information  ******************************//
elseif ($_POST['action'] == 'save') {
    $POST['id'] = StringConvertor::toNatural($_POST['id']);
    if ($LOADED_PLUGIN['restricted']) {
        $query = "SELECT creator FROM {$_CONF['db']['prefix']}_rooms_manager
					WHERE id='{$POST['id']}'";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        if ($result->fields['creator'] != $_SESSION['pcms_user_id'])
            $FUNC->Redirect($SELF);
    }
    $POST['lang'] = $VALIDATOR->ConvertSpecialChars($_POST['lang']);
    $POST['title'] = $VALIDATOR->ConvertSpecialChars($_POST['title']);
    $POST['intro'] = $VALIDATOR->ConvertSpecialChars($_POST['intro']);
    //$VALIDATOR->validateLength($POST['title'],$TEXT['prod']['title'],1);

    if ($errors = $VALIDATOR->passErrors()) {
        $TMPL->addVar("TMPL_settings", $SETTINGS);
        $TMPL->addVar("TMPL_errors", $errors);
        $TMPL->addVar("TMPL_item", $POST);
        $TMPL->AddVar("TMPL_lang", $_CONF['langs_all']);
        $TMPL->ParseIntoVar($_CENTER, 'edit_rooms_full');
    } else {
        $query = "UPDATE {$_CONF['db']['prefix']}_rooms_info
		         set
		           title='{$POST['title']}',
				   intro='{$POST['intro']}'
				WHERE common_id='{$POST['id']}'
				and lang='{$POST['lang']}'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $FUNC->Redirect($SELF . "&action=edit&lang={$POST['lang']}&id=" . $POST['id']);
    }
}
//*********************************************************************//
//***  UPDATE COLUMN sort_id IN DB ************************************//
elseif ($_POST['action'] == 'update_sort_id') {
    foreach ($_POST['sort_id'] as $k => $v) {
        $query = "UPDATE {$_CONF['db']['prefix']}_rooms_manager SET sort_id='{$v}' WHERE id='{$k}'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }

    foreach ($_POST['price_id'] as $k => $v) {
        $query = "UPDATE {$_CONF['db']['prefix']}_rooms_manager SET price='{$v}' WHERE id='{$k}'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }

    foreach ($_POST['price_old'] as $k => $v) {
        $query = "UPDATE {$_CONF['db']['prefix']}_rooms_manager SET price_old='{$v}' WHERE id='{$k}'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }
    $FUNC->Redirect($SELF . "&action=view&cid=" . $_GET['cid']);
} elseif ($_GET['action'] == "view") {
    $cid = StringConvertor::toNatural($_GET['cid']);
    $errors = '';

    $query_end = "t1.type_id = '{$cid}' and t2.lang ='" . C_LANG . "'";
    $pagenary_uri = "cid={$cid}";

    if (empty($errors)) {
        $query = "SELECT t1.*, t2.title, t2.intro FROM {$_CONF['db']['prefix']}_rooms_manager t1
				  LEFT JOIN {$_CONF['db']['prefix']}_rooms_info
				  	 t2 ON t1.id = t2.common_id
				  WHERE {$query_end} GROUP by t1.id ORDER BY t1.sort_id ASC, t1.id DESC";
        #p($query);
        $result = $CONN->PageExecute($query, 20, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        function substring($string, $i, $n)
        {
            return function_exists('mb_substr') ? mb_substr($string, $i, $n, "UTF-8") : substr($string, $i, $n);
        }

        while ($row = $result->FetchRow()) {

            $items[] = array(
                'id' => $row['id'],
                'sort_id' => $row['sort_id'],
                'img' => $row['introimg'],
                'publish' => $row['publish'],
                'blocked' => ($LOADED_PLUGIN['restricted'] && $row['creator'] != $_SESSION['pcms_user_id']) ? true : false,
                'intro' => substring($row['intro'], 0, 200),
            );
        }
        if (count($items) > 0) {
            $TMPL->addVar('TMPL_imgdir', $imgDIR);
            $TMPL->addVar('TMPL_items', $items);
            $TMPL->addVar('TMPL_pagebar', $FUNC->DrawPageBar("index.php?m={$_GET['m']}&action=view&{$pagenary_uri}&p=", $result));
            $TMPL->parseIntoVar($_CENTER, 'roomslist');
        } else {
            $errors = $TEXT['global']['no_info'];
        }
    }
    if ($errors != '') {
        $TMPL->addVar('TMPL_error', $errors);
        $TMPL->parseIntoVar($_CENTER, 'error');
    }
} elseif ($_GET['action'] == "edit") {
    $id = StringConvertor::toNatural($_GET['id']);
    $lang = $FUNC->validLang($_GET['lang'], 'langs');
    $query = "select t1.*, t2.* from {$_CONF['db']['prefix']}_rooms_manager as t1,
			  {$_CONF['db']['prefix']}_rooms_info as t2 where t1.id='{$id}'
			  and t2.common_id ='{$id}' and t2.lang ='{$lang}'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    if (!$result->fields) {
        $FUNC->Redirect($SELF);
    }

    $data = $result->fields;


    if ($data['introimg']) {
        $data['introimg'] = $imgDIR . "/thumb_" . $data['introimg'];
    }


    $blocks = GetBlocks();
    foreach ($blocks AS $block) {
        $mapped_bloks[$block['id']] = $block;
    }

    $rooms = GetRooms($data['id']);
    //$blocks=GetBlocks($data['block_id']);
    //$rooms=GetRooms($data['id']);

    $query = "select * from {$_CONF['db']['prefix']}_rooms_manager
              WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $rooms_managers = $result->getRows();

    //get

    foreach ($rooms_managers AS $rooms_manager) {
        $tmp = array();
        $tmp['id'] = $rooms_manager['id'];
        $tmp['title'] = $mapped_bloks[$rooms_manager['block_id']] . " - ";

    }

    $TMPL->addVar("TMPL_item", $data);
    $TMPL->addVar("TMPL_block", $mapped_bloks[$data['block_id']]);
    $TMPL->addVar("TMPL_rooms", $rooms);
    $TMPL->addVar("TMPL_rooms_managers", $rooms_managers);

    if ($LOADED_PLUGIN['restricted'] && ($data['creator'] != $_SESSION['pcms_user_id'])) {
        $TMPL->ParseIntoVar($_CENTER, 'content_restricted');
    } else {
        // Added

        $TMPL->addVar('TMPL_es', GetServices());
        $TMPL->addVar('TMPL_rc', GetRoomCapacity());
        $TMPL->addVar('TMPL_blocks', GetBlocks());
        $TMPL->addVar('TMPL_types', GetRoomTypes());
        $TMPL->addVar("TMPL_settings", $SETTINGS);
        $TMPL->addVar("TMPL_lang", $_CONF['langs_all']);
        $TMPL->ParseIntoVar($_CENTER, 'edit_rooms_head');
        $TMPL->ParseIntoVar($_CENTER, 'edit_rooms_full');
    }
} elseif ($_GET['action'] == 'delimg') {
    $id = StringConvertor::toNatural($_GET['id']);
    $query = "select introimg, creator from {$_CONF['db']['prefix']}_rooms_manager where id='$id'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if (!$result->fields) {
        $FUNC->Redirect($SELF);
    }
    if (!$LOADED_PLUGIN['restricted'] || ($result->fields['creator'] == $_SESSION['pcms_user_id'])) {
        @unlink($imgDIR . "/thumb_" . $result->fields['introimg']);
        @unlink($imgDIR . "/thumb2_" . $result->fields['introimg']);
        @unlink($imgDIR . "/" . $result->fields['introimg']);
        $query = "update {$_CONF['db']['prefix']}_rooms_manager set introimg ='' where id='$id'";
        $CONN->_query($query) OR $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }
    $FUNC->Redirect($SELF . "&action=edit&id=" . $id);
} elseif ($_GET['action'] == 'delete') {
    $id = StringConvertor::toNatural($_GET['id']);
    $query = "SELECT introimg, creator from {$_CONF['db']['prefix']}_rooms_manager
				where id='$id'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if (!$result->fields) {
        $FUNC->Redirect($SELF);
    }
    if (!$LOADED_PLUGIN['restricted'] || ($result->fields['creator'] == $_SESSION['pcms_user_id'])) {
        //*** deleting gallery if exists    *****//
        if (file_exists($imgDIR . '/' . $id)) {
            $FM = new FileManager($imgDIR . '/' . $id);
            if (!$FM->RemoveDir($imgDIR . '/' . $id)) {
                $FUNC->ServerError(__FILE__, __LINE__, "Failed to delete gallery for rooms {$id}");
            }

            $query = "DELETE from {$_CONF['db']['prefix']}_rooms_gal where rec_id='$id'";
            $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        }

        //*** deleting intro image if exists  ***//
        @unlink($imgDIR . "/thumb_" . $result->fields['introimg']);
        @unlink($imgDIR . "/thumb2_" . $result->fields['introimg']);
        @unlink($imgDIR . "/" . $result->fields['introimg']);

        //*** deleting rooms options   ********//
        $query = "delete from {$_CONF['db']['prefix']}_rooms_info  where common_id ='$id'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $query = "delete from {$_CONF['db']['prefix']}_rooms  where common_id ='$id'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $query = "delete from {$_CONF['db']['prefix']}_rooms_manager  where id ='$id'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $query = "delete from {$_CONF['db']['prefix']}_room_prices where common_id ='$id'";
        $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }
    $FUNC->Redirect('index.php?m=room_management&tab=existed_rooms');
} elseif ($_GET['action'] == "change_status" && isset($_GET['id'])) {
    $id = StringConvertor::toNatural($_GET['id']);
    $query = "UPDATE {$_CONF['db']['prefix']}_rooms_manager
			  SET publish=if(publish=1,0,1) WHERE id ='{$id}'";
    $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $FUNC->Redirect('index.php?m=room_management&tab=existed_rooms');
} elseif ($_GET['action'] == "change_status2" && isset($_GET['id'])) {
    $id = StringConvertor::toNatural($_GET['id']);
    $query = "UPDATE {$_CONF['db']['prefix']}_rooms_manager
			SET publish_basket=if(publish_basket=1,0,1) WHERE id ='{$id}'";
    $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
} elseif ($_GET['action'] == "turngall") {
    $id = StringConvertor::toNatural($_GET['id']);
    if ($LOADED_PLUGIN['restricted'])
        $strictquery = " AND creator ='{$_SESSION['pcms_user_id']}'";
    $query = "UPDATE {$_CONF['db']['prefix']}_rooms_manager SET gallery = if(gallery=1,0,1) WHERE id ='{$id}' {$strictquery}";
    $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $FUNC->Redirect($SELF . "&action=edit&id=" . $id);
} else {


    $query = "SELECT t1.*, t2.title, t2.intro, t3.title as type FROM {$_CONF['db']['prefix']}_rooms_manager t1
				  LEFT JOIN {$_CONF['db']['prefix']}_rooms_info t2
				  ON t1.id = t2.common_id
				  LEFT JOIN {$_CONF['db']['prefix']}_room_types t3
                  ON t1.type_id = t3.id
                  WHERE t2.lang='".LANG."'
				  GROUP BY t1.id
				  ORDER BY t1.sort_id ASC, t1.id DESC";

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    while ($row = $result->FetchRow()) {
        $items[$FUNC->unpackData($row['type'], LANG)][] = array(
            'id' => $row['id'],
            'sort_id' => $row['sort_id'],
            'img' => $row['introimg'],
            'publish' => $row['publish'],
            'blocked' => ($LOADED_PLUGIN['restricted'] && $row['creator'] != $_SESSION['pcms_user_id']) ? true : false,
            'intro' => substring($row['intro'], 0, 200),
        );
    }

    $TMPL->addVar('TMPL_imgdir', $imgDIR);
    $TMPL->addVar('TMPL_items', $items);
    $TMPL->parseIntoVar($_CENTER, 'existed_rooms');

}
//----------------------------Functions

function substring($string, $i, $n)
{
    return function_exists('mb_substr') ? mb_substr($string, $i, $n, "UTF-8") : substr($string, $i, $n);
}