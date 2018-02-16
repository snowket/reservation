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

			foreach($data as $day){

				$price=(double)$_POST['day'][$day['common_id']][$day['day']];
				$query = "UPDATE {$TABLE} SET
				 	 price={$price}
				 	 WHERE common_id={$day['common_id']} AND day='{$day['day']}' AND id={$day['id']}";

				$CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
			}
			$FUNC->Redirect($SELF.'&type_id='.$type_id.'&block_id='.$block_id);
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
	
	$query		= "SELECT * FROM {$TABLE} WHERE common_id={$common_id} AND rec_id={$rec_id} AND block_id={$block_id}";
	$result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data		= $result->GetRows();
	if (empty($data)) {
			$checkin=$_POST['start_date'];
			$checkout=$_POST['end_date'];
			$startDate = strtotime($checkin);
			$endDate = strtotime($checkout);
			
			while ($startDate <= $endDate) {
				$dw = date( "D", $startDate);
				$date = date("Y-m-d",$startDate);
				$price=$_POST['day'][$common_id][$dw];
				
				$query = "INSERT INTO {$TABLE} SET 
						  rec_id='{$rec_id}',
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
	else{
		foreach ($_POST['day'][$common_id] as $key => $value) {
			$price=(double)$value;
			
			$query = "UPDATE {$TABLE} SET 
				 	 price={$price}
				 	 WHERE common_id={$common_id} AND day='{$key}' AND rec_id={$rec_id}";
			$CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		}
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
		  	ORDER BY capacity_id asc";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data=$result->GetRows();
	$query = "SELECT *,date_format(date,'%d/%M/%Y') as _date
			  FROM {$TABLE} 
		  	  WHERE type_id={$type_id} AND block_id={$block_id}
		  	  ORDER BY rec_id desc,date asc,capacity_id asc";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$room_prices=$result->GetRows();
	if (!empty($room_prices)) {
		foreach ($room_prices as $key => $value) {
			$roomPriceArr[$value['rec_id']][$value['common_id']][$value['day']]=$value['price'];
         	$roomPriceDaily[$value['id']]=array('price'=>$value['price'],'discount'=>$value['discount']);
			$dateArr[$value['rec_id']][]=$value['_date'];
			$date_range[$value['rec_id']][]=$value['date'];
			$dateArr2[$value['rec_id']][$value['date']][$value['capacity_id']]=$value['id'];
			$rec_id[$value['rec_id']]=$value['rec_id'];
		}
	}
	
	$roomCapacity=GetRoomCapacity();
	foreach ($roomCapacity as $key => $value) {
		$rcArr[$value['id']]=$value['title'];
	}
	$TMPL->addVar('TMPL_capacity',$rcArr);	
	foreach ($dateArr2 as $key => $value) {
		foreach ($value as $k => $v) {
			$date=explode('-',$k);
			$y=$date[0];
			$m=$date[1];
			$d=$date[2];
			$dates[$key][$y][$m][$d]=$v;
		}
	}	
	if ($_POST['action']=='update_price') {
		foreach ($_POST['price'] as $key => $value) {
			if ($value!=$roomPriceDaily[$key]['price'] || $_POST['discount'][$key]!=$roomPriceDaily[$key]['discount']) {
				$price=(double)$value;
				$discount=(double)$_POST['discount'][$key];
				$id=(int)$key;
				$query = "UPDATE {$TABLE} SET 
			 	 price={$price}, discount={$discount}
			 	 WHERE id={$id}";
				$CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
			}
		}
		$FUNC->Redirect($SELF.'&type_id='.(int)$_GET['type_id'].'&block_id='.(int)$_GET['block_id']);
	}
}


$TMPL->addVar('TMPL_CONF', $_CONF);
$TMPL->addVar('TMPL_blocks',modifyArrayBy(GetBlocks()));
$TMPL->addVar('TMPL_categories',modifyArrayBy(GetRoomTypes()));
$TMPL->addVar('TMPL_data',$data);
$TMPL->addVar('TMPL_rid',$rec_id);
$TMPL->addVar('TMPL_prices',$roomPriceArr);
$TMPL->addVar('daily_price',$roomPriceDaily);
$TMPL->addVar('TMPL_dr',$dateArr);
$TMPL->addVar('TMPL_dates',$dates);
$TMPL->addVar('date_range',$date_range);
$TMPL->addVar('weekDays',$weekDays);
$TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
$TMPL->ParseIntoVar($_CENTER,"price_list");