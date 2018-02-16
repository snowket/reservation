<?

$POST = $VALIDATOR->ConvertSpecialChars($_POST);

if($POST['action']=='update'){
    $lpd=stripslashes($_POST['lpd']);

    //$lpd=json_decode(stripslashes($_POST['lpd']),true);
	$pay_now_discount=(int)$POST['pay_now_discount'];
    $one_person_discount=(int)$POST['one_person_discount'];
    $child_price=(float)$POST['child_price'];
    $common_id=(int)$POST['common_id'];
    $payments_method=(int)$POST['payments_method'];
    if($payments_method==1){
        $pay_now_discount=0;
    }
    if($common_id>0){
        $query = "UPDATE {$_CONF['db']['prefix']}_rooms_manager SET
                  pay_now_discount={$pay_now_discount},
                  lpd='{$lpd}',
                  payments_method={$payments_method},
                  child_price={$child_price}
                  WHERE id={$common_id}";
        $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    }
	$FUNC->Redirect($SELF);
	
}


	$type_id=(int)$_GET['type_id'];
	$block_id=(int)$_GET['block_id'];

    $blocks=modifyArrayBy(GetBlocks());
    $room_types=modifyArrayBy(GetRoomTypes());
    $roomCapacity=modifyArrayBy(GetRoomCapacity());



    $query = "SELECT *
			FROM {$_CONF['db']['prefix']}_rooms_manager
			WHERE publish=1";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$commons=$result->GetRows();
    foreach ($commons as $common){
        $commons_array[$blocks[$common['block_id']]['title']][$room_types[$common['type_id']]['title']][$roomCapacity[$common['capacity_id']]['title']]=$common;
        }

$TMPL->addVar('TMPL_commons',$commons_array);
$TMPL->addVar('TMPL_capacity',$roomCapacity);
$TMPL->addVar('TMPL_tbc_payments_method',$_CONF['tbc_payments_method']);
$TMPL->ParseIntoVar($_CENTER,"discounts");