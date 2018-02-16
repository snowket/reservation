<?

$TABLE = $_CONF['db']['prefix'].'_room_prices';


/**ADD**/
if ($_POST['action']=='add'){
	$type_id=(int)$_POST['type_id'];
	$block_id=(int)$_POST['block_id'];
	
	//strtotime posted dates
	$checkin=$_POST['start_date'];
	$checkout=$_POST['end_date'];
	
	if (!$checkin || !$checkout || !$type_id || !$block_id) {
		$errors='fill all fields';
		$TMPL->addVar('TMPL_errors',$errors);
	}
	
	if (!$errors) {
		$query		= "SELECT * FROM {$TABLE}
					  WHERE type_id={$type_id}
					  AND block_id={$block_id} AND
					  (date BETWEEN '{$checkin}' AND '{$checkout}')";
		$result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$data		= $result->GetRows();
		
		if (!empty($data)) {
			$errors='exists';
			$TMPL->addVar('TMPL_errors',$errors);
		}
	}
	
	if (!$errors) {	
		
		$endDate = strtotime($checkout);
		
		//Select MAX rec_id 
		$query		= "SELECT MAX(rec_id) AS rec_id FROM {$TABLE}";
		$result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$data		= $result->fields;
		$MAXrec_id	= $data['rec_id']+1;
		$roomArr=$_POST['roomArr'];
		$capacityArr=$_POST['capacityArr'];
		
		for ($i=0; $i < count($roomArr); $i++) { 
			$common_id=$roomArr[$i];
			$capacity_id=$capacityArr[$i];
			$startDate = strtotime($checkin);
			while ($startDate <= $endDate) {
				$dw = date( "D", $startDate);
				$date = date("Y-m-d",$startDate);
				$price=$_POST['day'][$common_id][$dw];
				
				$query = "INSERT INTO {$TABLE} SET 
						  rec_id='{$MAXrec_id}',
						  type_id={$type_id},
						  block_id={$block_id},
						  capacity_id={$capacity_id},
						  day='{$dw}',  
						  mk_date='{$startDate}',
						  date='{$date}',
						  common_id={$common_id},
						  price={$price}";
				$CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
				
				$startDate = strtotime('+1 day', $startDate);
			}
			
		}
		$FUNC->Redirect($SELF.'&type_id='.$type_id.'&block_id='.$block_id);
	}
}
/**UPDATE**/
elseif($_POST['action']=='update'){
	$common_id=(int)$_POST['common_id'];
	$rec_id=(int)$_POST['rec_id'];
	$type_id=(int)$_POST['type_id'];
	$capacity_id=(int)$_POST['capacity_id'];
	$block_id=(int)$_POST['block_id'];
	
	$query		= "SELECT * FROM {$TABLE} WHERE common_id={$common_id} AND rec_id={$rec_id}";
	$result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data		= $result->GetRows();
	
	foreach ($_POST['day'][$common_id] as $key => $value) {
		$price=(double)$value;
		if (empty($data)) {
		$query = "INSERT INTO {$TABLE} SET 
			 	 price={$price}, common_id={$common_id},
			 	 day='{$key}', type_id={$type_id},
			 	 block_id={$block_id},
				 capacity_id={$capacity_id},
			 	 rec_id={$rec_id}";	
		}else{
		$query = "UPDATE {$TABLE} SET 
			 	 price={$price}
			 	 WHERE common_id={$common_id} AND day='{$key}' AND rec_id={$rec_id}";
		}
		$CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	}
	$FUNC->Redirect($SELF.'&type_id='.$type_id.'&block_id='.$block_id);
	
}
/**DELETE**/
elseif($_POST['action']=='delete'){
	$rec_id=(int)$_POST['rec_id'];
	$query = "DELETE FROM {$TABLE} 
		 	 WHERE rec_id={$rec_id}";
	$CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF.'&type_id='.(int)$_GET['type_id'].'&block_id='.(int)$_GET['block_id']);
}
/**LIST**/
if ($_GET['type_id'] && $_GET['block_id']) {
	$type_id=(int)$_GET['type_id'];
	$block_id=(int)$_GET['block_id'];
	$query = "SELECT *
			FROM {$_CONF['db']['prefix']}_rooms_manager
		  	WHERE 
		  	type_id={$type_id}
		  	AND block_id={$block_id}
		  	GROUP by id 
		  	ORDER BY sort_id ASC, id DESC";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data=$result->GetRows();
	
	$query = "SELECT *,date_format(date,'%d/%M/%Y') as date FROM {$TABLE} 
		  	  WHERE type_id={$type_id} 
		  	  ORDER BY rec_id desc,date asc";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$room_prices=$result->GetRows();
	
	if (!empty($room_prices)) {
		foreach ($room_prices as $key => $value) {
			$roomPriceArr[$value['rec_id']][$value['common_id']][$value['day']]=$value['price'];
			$dateArr[$value['rec_id']][]=$value['date'];
			$rec_id[$value['rec_id']]=$value['rec_id'];
		}
	}
	
	$roomCapacity=GetRoomCapacity();
	
	foreach ($roomCapacity as $key => $value) {
		$rcArr[$value['id']]=$value['title'];
	}
	$TMPL->addVar('TMPL_capacity',$rcArr);
}

$TMPL->addVar('TMPL_blocks',GetBlocks());
$TMPL->addVar('TMPL_categories',GetRoomTypes());
$TMPL->addVar('TMPL_data',$data);
$TMPL->addVar('TMPL_rid',$rec_id);
$TMPL->addVar('TMPL_prices',$roomPriceArr);
$TMPL->addVar('TMPL_dr',$dateArr);
$TMPL->addVar('weekDays',$weekDays);
$TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
$TMPL->ParseIntoVar($_CENTER,"discounts");