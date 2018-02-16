<?php

if(!defined('ALLOW_ACCESS')) exit;

$TABLE  = $_CONF['db']['prefix'].'_parcels';
$TABLE2 = $_CONF['db']['prefix'].'_parcel_items';
#p($SETTINGS);

// get full list of users
$query	= "SELECT t1.id, CONCAT(t1.login, ' - ', t1.lastname, ' ', t1.firstname, ' (', t2.title, ')',   repeat('.', 70 - char_length(CONCAT(t1.login, ' - ', t1.lastname, ' ', t1.firstname, ' (', t2.title, ')'))),   t1.balance) AS name
				FROM ".$_CONF['db']['prefix']."_users t1
				LEFT JOIN ".$_CONF['db']['prefix']."_groups t2 ON t2.id = t1.group_id
				ORDER BY id DESC";
				//ORDER BY group_id ASC, login DESC";
$result	= $CONN->Execute($query) OR $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
$usersList	= $result->GetAssoc();

$TMPL->addVar('usersList', $usersList);



// parcel tabs
$pTabs = array(
				'parcel/edit'				=>	'Parcel',
				'parcel/edit/items'			=>	'Parcel Items',
				'parcel/edit/statuses'		=>	'Parcel Statuses',
			);
if (@$pTabs[$_GET['action']])
	$_CENTER = pcmsInterface::drawTabs($SELF . '&tab=' . $_GET['tab'] . '&id=' . $_GET['id'] . '&action=', $pTabs, $_GET['action']) . $_CENTER;

$error	= false;
$errorMessages	= array();

if ($_GET['action'] == 'parcel/edit') {
	$readonly	= false;
	$action		= 'add';
	$record 	= array();
	$id			= intval(@$_GET['id']);
	
	/*
	$query	= "SELECT * FROM {$TABLE} WHERE id='".$id."'";
	$result	= $CONN->Execute($query) OR $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$record = $result->fields;
	*/
	
	$records = CORE::$MODULES->PARCEL->GetRecords(array('conditions' => array('t1.id = '.$id)));
	if ($records) {
		$record = $records[0];
		$action = 'edit';
		
		if (intval($record['status_delivery']) >= 3 || $record['status_paid']) {
			$readonly = true;
		}
	}
	
	
	
	if ($_POST['action']) {
		#$record = $_POST[o];
		#p2($_POST);
		
		$recordFilteredByTrackIF = array();
		$trackID = trim($_POST['o']['trackid']);
		if (strlen($trackID)) {
			// get data if exists
			$tmpOptions = array('conditions' => array(
						't1.trackid = '.$CONN->qstr($trackID)
				)
			);
			
			if ($action == 'edit') {
				$tmpOptions['conditions'][] = 't1.id <> '.$id;
			}
			
			#p2($tmpOptions);
			$tmpRecords = CORE::$MODULES->PARCEL->GetRecords($tmpOptions);
			#p2($tmpRecords);
			
			if ($tmpRecords) {
				$error = true;
				$errorMessages[] = '<a href="'.$SELF.'&action='.$_GET['action'].'&id='.$tmpRecords[0]['id'].'" target="_blank">tracking code</a> already exists!';
			}
		}
		
		// save data
		if (!$error) {
			$saveData = $_POST['o'];
			
			if ($action == 'edit') {
				$saveData['updated'] 	= date('Y-m-d H:i:s');
				
				$updateStatus = CORE::$MODULES->PARCEL->UpdateRecord($saveData, 'id = ' . $id);
				$FUNC->Redirect($_SERVER['REQUEST_URI']);
			}
			else {
				$saveData['status_delivery']	= 1;
				$saveData['created'] 			= date('Y-m-d H:i:s');
				$saveData['status_delivery_1'] 	= date('Y-m-d H:i:s');
				$saveData['creatorid']			= $_SESSION['pcms_user_id'];
				
				$insertID = CORE::$MODULES->PARCEL->AddRecord($saveData);
				$FUNC->Redirect($SELF . '&action='.$_GET['action'].'&id=' . $insertID);
			}
		}
		else {
			if ($action == 'add') {
				$record = $_POST['o'];
			}
		}
	}
	
	// $action
	{
		$TMPL->addVar('readonly', $readonly);
		
		$TMPL->addVar('error', $error);
		$TMPL->addVar('errorMessages', $errorMessages);
		$TMPL->addVar('action', $action);
		$TMPL->addVar('record', $record);
		$TMPL->ParseIntoVar($_CENTER, 'addEdit');
	}
}
else if ($_GET['action'] == 'parcel/edit/items') {
	// get/add/edit parcel items
	
	$readonly	= false;
	$action		= 'add';
	$record 	= array();
	$parcelID	= intval(@$_GET['id']);
	
	$records = CORE::$MODULES->PARCEL->GetRecords(array('conditions' => array('t1.id = '.$parcelID)));
	if ($records) {
		$record 	= $records[0];
		$action = 'edit';
	}
	else {
		$FUNC->Redirect($SELF . '&action=parcel/edit');
	}
	
	// items must not be able to edit
	if ($record['userid'] == $record['creatorid'] || (intval($record['status_delivery']) >= 3)) {
		$readonly = true;
	}
	
	
	// get record items
	$recordItems = CORE::$MODULES->PARCEL->GetRecordItems('recordid = '.$parcelID, 'id,recordid,title,cost');
	$items = $recordItems[$parcelID];
	#p2($items);
	
	if ($_POST && $_POST['action'] == 'update' && !$readonly) {
		#p2($_POST);
		
		$updateItems = array();
		if (is_array($_POST['o']['items'])) {
			foreach ($_POST['o']['items']['name'] AS $i => $name) {
				$name	= trim($_POST['o']['items']['name'][$i]);
				$cost	= floatval(str_replace(',', '.', $_POST['o']['items']['cost'][$i]));
				
				if (!$name) continue;
				
				$r = array();
				$r['recordid']	= $record['id'];
				$r['title']		= $name;
				$r['cost']		= $cost;
				$r['currency']	= CORE::$FRONT->settings['parcel_item_default_currency'];
				$r['creatorid']	= $_SESSION['pcms_user_id'];
				$r['created']	= date('Y-m-d H:i:s');
				
				$updateItems[] = $r;
			}
		}
		
		$insertUpdateItemsStatus = CORE::$MODULES->PARCEL->InsertUpdateRecordItems($parcelID, $updateItems);
		$FUNC->Redirect($_SERVER['REQUEST_URI']);
	}

	$items = $FUNC->htmlspecialchars_array((array)$items);
	{
		$TMPL->addVar('readonly', $readonly);
		
		$TMPL->addVar('error', $error);
		$TMPL->addVar('errorMessages', $errorMessages);
		$TMPL->addVar('action', $action);
		$TMPL->addVar('record', $record);
		$TMPL->addVar('items', $items);
		$TMPL->ParseIntoVar($_CENTER, 'addEditItems');
	}
}
else if ($_GET['action'] == 'parcel/edit/statuses') {
	$record = array();
	$id		= intval(@$_GET['id']);
	
	$records = CORE::$MODULES->PARCEL->GetRecords(array('conditions' => array('t1.id = '.$id)));
	if ($records) {
		$record 	= $records[0];
	}
	else {
		$FUNC->Redirect($SELF . '&action=parcel/edit');
	}
	
	if ($_POST['action'] == 'update') {
		// if setting new status_delivery
		if ($_POST['set_status']) {
			// new status delivery
			$nsd = key($_POST['set_status']);
			
			if (!CORE::$MODULES->PARCEL->parcelStatuses[$nsd]) {
				$error = true;
				$errorMessages[] = 'Invalid status_delivery requested!!!';
			}
			else if ($nsd == 3 && $record['delivery_cost'] <= 0) {
				$error = true;
				$errorMessages[] = 'FIRST - Enter Delivery Cost!';
			}
			else {
				$updateData	= array();
				$updateData['status_delivery']			= $nsd;
				$updateData['status_delivery_' . $nsd] 	= date('Y-m-d H:i:s');
				$updateData['updated'] 					= date('Y-m-d H:i:s');
				
				#p($updateData);
				$updateStatus = CORE::$MODULES->PARCEL->UpdateRecord($updateData, 'id = ' . $id);
				$FUNC->Redirect($_SERVER['REQUEST_URI']);
			}
		}
		// if trying to reset status_delivery - to the old one
		else if ($_POST['reset_status']) {
			// new status delivery
			$nsd = intval(key($_POST['reset_status']));
			
			if ($record['status_delivery'] > 3 || $record['status_paid']) {
				$error = true;
				$errorMessages[] = 'Invalid request!';
			}
			else if (!CORE::$MODULES->PARCEL->parcelStatuses[$nsd]) {
				$error = true;
				$errorMessages[] = 'Invalid status_delivery requested!!!';
			}
			else {
				$updateData	= array();
				$updateData['status_delivery']			= $nsd;
				$updateData['status_delivery_' . $nsd] 	= date('Y-m-d H:i:s');
				
				#p($record['status_email_delivery']);
				if ($nsd < $record['status_email_delivery']) {
					$updateData['status_email_delivery'] = $nsd - 1;
				}
				
				foreach (CORE::$MODULES->PARCEL->parcelStatuses AS $iStatus => $statusTitle) {
					if ($iStatus > $nsd)
						$updateData['status_delivery_' . $iStatus] 	= NULL;
				}
				$updateData['updated'] 					= date('Y-m-d H:i:s');
				
				#p($updateData);
				$updateStatus = CORE::$MODULES->PARCEL->UpdateRecord($updateData, 'id = ' . $id);
				$FUNC->Redirect($_SERVER['REQUEST_URI']);
			}
		}
		// if trying to send email for concrete status_delivery
		else if ($_POST['send_email']) {
			$emSD = key($_POST['send_email']);
			
			if (CORE::$MODULES->PARCEL->parcelStatuses[$emSD]) {
				$options = array(
					'cmd'				=> 'delivery',
					'parcel'			=> $record,
					'asStatusDelivery'	=> $emSD
				);
				CORE::$MODULES->PARCEL->NotifyParcelOwner($options);
				
				if (CORE::$MODULES->PARCEL->error) {
					$error = true;
					$errorMessages[] = CORE::$MODULES->PARCEL->errorMessage;
				}
				else {
					// update mail send datetime
					#$updateData['updated'] 					= date('Y-m-d H:i:s');
					$updateData['email_date_sd_' . $emSD] 		= date('Y-m-d H:i:s');
					
					$updateStatus = CORE::$MODULES->PARCEL->UpdateRecord($updateData, 'id = ' . $id);
					$FUNC->Redirect($_SERVER['REQUEST_URI']);
				}
			}
		}
	}
	
	{
		$TMPL->addVar('error', $error);
		$TMPL->addVar('errorMessages', $errorMessages);
		$TMPL->addVar('action', $action);
		$TMPL->addVar('record', $record);
		$TMPL->ParseIntoVar($_CENTER, 'editStatus');
	}
}
else if (isset($_POST['action'])) {
	$_POST = $VALIDATOR->ConvertSpecialChars($_POST);
	
	$save_post_keys = array('type','number_alia','number_qronika','months','cost');
	foreach ($save_post_keys AS $pk) {
		$q_adds[] = $pk."='".mysql_real_escape_string($_POST[$pk])."' ";
	}
	$q_add = implode(',',$q_adds);
	
	if ($_POST['action']=="add") {
		$query  = "INSERT INTO {$TABLE} SET {$q_add}, publish=1";
		$result = $CONN->_Query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	}
	elseif ($_POST['action']=="edit") {
		$query  = "UPDATE {$TABLE} SET {$q_add} WHERE id='".intval($_POST['id'])."'";
		$result = $CONN->_Query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	}

	$FUNC->Redirect($SELF);
}
### DELETE
else if($_GET['action']=="delete" && isset($_GET['rec_id'])) {
	$rec_id = StringConvertor::toNatural($_GET['rec_id']);
	$query = "DELETE FROM {$TABLE} WHERE id='{$rec_id}'";
	$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF);
}
else if($_GET['action']=="change_status" && isset($_GET['rec_id'])) {
	$rec_id = StringConvertor::toNatural($_GET['rec_id']);
	$query="UPDATE {$TABLE} SET publish=if(publish=1,0,1) WHERE id=".$rec_id;
	$res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF);
}
### DEFAULT
else{
	/*$query	= "SELECT * FROM {$_CONF['db']['prefix']}_settings WHERE pluginid='{$LOADED_PLUGIN['id']}' ORDER BY orderid ASC";
	$result	= $CONN->Execute($query)or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$settings = $result->GetRows();
	foreach ($settings AS $v) {
		$settings_[$v['input_name']] = $v['value'];
	}
	#p($settings_);*/
	
	#p2($_GET[s]);
	
	$sOpt = array();
	$pageBarUrlAddon = '';
	if ($_GET['action'] == 'parcel/filter') {
		$pageBarUrlAddon .= '&action=' . $_GET['action'];
		
		// filter by userid/owner
		if (intval($_GET['s']['userid'])) {
			$sOpt[] = 't1.userid = ' . intval($_GET['s']['userid']);
			$pageBarUrlAddon .= '&s[userid]=' . intval($_GET['s']['userid']);
		}
		
		// filter by creatorid/creator
		if (intval($_GET['s']['creatorid'])) {
			$sOpt[] = 't1.creatorid = ' . intval($_GET['s']['creatorid']);
			$pageBarUrlAddon .= '&s[creatorid]=' . intval($_GET['s']['creatorid']);
		}
		
		// filter by trackid/tracking code
		if (strlen(trim(($_GET['s']['trackid']))) && $args = CORE::$MODULES->PARCEL->pcmsSearchFiltrationLikeArgs[$_GET['s']['trackid_opt']]) {
			$sOpt[] = 't1.trackid ' . $args[0] . ' ' .$CONN->qstr($args[1] . trim($_GET['s']['trackid']) . $args[2]);
			$pageBarUrlAddon .= '&s[trackid]=' . $_GET['s']['trackid'];
			$pageBarUrlAddon .= '&s[trackid_opt]=' . intval($_GET['s']['trackid_opt']);
		}
		
		// filter by flight
		if (strlen(trim(($_GET['s']['flight']))) && $args = CORE::$MODULES->PARCEL->pcmsSearchFiltrationLikeArgs[$_GET['s']['flight_opt']]) {
			$sOpt[] = 't1.flight ' . $args[0] . ' ' .$CONN->qstr($args[1] . trim($_GET['s']['flight']) . $args[2]);
			$pageBarUrlAddon .= '&s[flight]=' . $_GET['s']['flight'];
			$pageBarUrlAddon .= '&s[flight_opt]=' . intval($_GET['s']['flight_opt']);
		}
		
		// filter by sender/shop/web page
		if (strlen(trim(($_GET['s']['sender']))) && $args = CORE::$MODULES->PARCEL->pcmsSearchFiltrationLikeArgs[$_GET['s']['sender_opt']]) {
			$sOpt[] = 't1.sender ' . $args[0] . ' ' .$CONN->qstr($args[1] . trim($_GET['s']['sender']) . $args[2]);
			$pageBarUrlAddon .= '&s[sender]=' . $_GET['s']['sender'];
			$pageBarUrlAddon .= '&s[sender_opt]=' . intval($_GET['s']['sender_opt']);
		}
		
		// filter by status delivery
		if (is_array($_GET['s']['sd'])) {
			$sdFiltered = array();
			foreach ($_GET['s']['sd'] AS $sd) {
				$sdFiltered[] = intval($sd);
				$pageBarUrlAddon .= '&s[sd][]=' . intval($sd);
			}

			if (count($sdFiltered))
				$sOpt[] = 't1.status_delivery IN ('.implode(',', $sdFiltered).') ';
		}
	}
	#p2($sOpt);
	
	$query	= "SELECT t1.*,
					if(t1.items_count > 0, t1.items_count, COUNT(t2.id)) AS items_count, if(t1.items_cost > 0, t1.items_cost, SUM(t2.cost)) AS items_cost,
					t3.login AS user_login, CONCAT(t3.lastname, ' ', t3.firstname) AS user_name, t3.email AS user_email,
					t4.login AS creator_login, CONCAT(t4.lastname, ' ', t4.firstname) AS creator_name, t4.email AS creator_email
				FROM ".$TABLE." t1
				LEFT JOIN ".$TABLE2." t2 ON t2.recordid = t1.id
				LEFT JOIN ".$_CONF['db']['prefix']."_users t3 ON t3.id = t1.userid
				LEFT JOIN ".$_CONF['db']['prefix']."_users t4 ON t4.id = t1.creatorid
				".(count($sOpt) ? 'WHERE ' . implode(' AND ', $sOpt) : '')."
				GROUP BY t1.id
				ORDER BY t1.created DESC";
	#p($query);
	$result	= $CONN->PageExecute($query, $SETTINGS['per_page'] ? $SETTINGS['per_page']:10 , $_GET['p']) OR $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$records= $result->GetRows();
	
	$TMPL->addVar('records', $records);
	$TMPL->addVar('TMPL_navbar', $FUNC->DrawPageBar($SELF.'&tab='.$_GET['tab'].$pageBarUrlAddon.'&p=', $result));
	#$TMPL->addVar('settings', $settings_);
	$TMPL->addVar('LOADED_PLUGIN', $LOADED_PLUGIN);
	$TMPL->addVar('SETTINGS', $SETTINGS);
	$TMPL->addVar('langs_all', $_CONF['langs_all']);
	$TMPL->ParseIntoVar($_CENTER, 'list');
}










