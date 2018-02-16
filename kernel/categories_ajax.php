<?
error_reporting(1);
if($_GET['action']=="editcat"){
	$cid	= StringConvertor::toNatural($_GET['cid']);
	$query  = "SELECT * FROM {$_CONF['db']['prefix']}_categories WHERE cat_id='$cid'";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data	= $result->fields;
	
	
	header("Content-type: text/xml; charset=UTF-8");
	print "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">";
	print "<data>";
	if(count($data)>0) {
		$title = $FUNC->unpackData($data['name']);
		foreach($title as $k=>$v) {
			print "<{$k}>{$v}</{$k}>";
		}
	}
	print "<image>{$data['image']}</image>";
	print "<image2>{$data['image2']}</image2>";
	print "<option>{$data['options']}</option>";
	if ($_GET['m']=='receipts' || $_GET['m']=='advices') {
		$query_m  = "SELECT * FROM {$_CONF['db']['prefix']}_sitemenu WHERE cat_id > 1 ORDER BY cat_left";
		$result_m = $CONN->Execute($query_m) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		while($o_m = $result_m->FetchNextObject()){
			$category_m[$o_m->CAT_ID] = str_repeat('&nbsp;',($o_m->CAT_LEVEL-1)*3).$FUNC->unpackData($o_m->NAME,'eng');
		}
		$categories_m  = '<select id="mid" name="mid[]" class="formField1" style="width:340px; height:100px;" multiple><option></option>';
		$categories_m .= $INTERFACE->drawOptions($category_m,unserialize($data['mid']),_ASSOC_);
		$categories_m .= '</select>';
		#p($categories_m);
		print "<mid><![CDATA[{$categories_m}]]></mid>";
	}
	print "</data>";
}
?>