<?php

function sorting_array($un_array,$sort_with=false) {
	$tmp_items = array();
	
	if ($sort_with) {
		for($i=0,$n=count($un_array); $i<$n; $i++){
			$tmp_items[$i] = $un_array[$i][$sort_with];
		}
		sort($tmp_items);
		
		for ($i=0,$n=count($tmp_items); $i<$n; $i++){
			for ($j=0,$m=count($un_array); $j<$m; $j++){
				if (array_search($tmp_items[$i],$un_array[$j])) {
					$sorted_array[$i] = $un_array[$j]; break;
				}
			}
		}
	}
	else {
		asort($un_array);
		$sorted_array = $un_array;
	}
	
	return $sorted_array;
}

// some product functions _ used for user registration ad products delivery.
// returns assoc array - (id => title,delivery_price)
function common_prod_cities_users($all=false) {
	global $FUNC,$CONN,$TEXT,$_CONF;
	$query_add = ($all==true)?'':"WHERE publish=1";
	$query = "SELECT * FROM {$_CONF['db']['prefix']}_prod_cities ".$query_add;  
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	
	while($row = $result->FetchRow()){
		$title = unserialize($row['title']);
		$items[$row['id']] =  $title[LANG];
	}
	
	$items = sorting_array($items);
	#p($items);
	#asort($items);
	#p($items);
	
	if(count($items)>0)
		return $items;
	else
		return false;
}

function common_prod_cities() {
	global $FUNC,$CONN,$TEXT,$_CONF;
	$query = "SELECT * FROM {$_CONF['db']['prefix']}_prod_cities";  
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	
	while($row = $result->FetchRow()){
		if ($row['publish'] != '1') continue;
		$title = unserialize($row['title']);
		$items[] = array(
				'id'				=>  $row['id'],
				'title'				=>  $title[C_LANG],
				'delivery_price'	=>  $row['delivery_price'],
				'publish'			=>  $row['publish'],
			);
	}
	
	if(count($items)>0)
		return $items;
	else
		return false;
}


function common_HtmlHeader($title,$pipka="",$url="",$HTML="",$bg="") {
	if ($url) {
		$style	= 'cursor:pointer;';
		$onClick= 'onClick="top.location.href=\''.$url.'\'"';
	}
	if ($HTML) {
		$plus = '<td align="right" style="width:1%; padding: 0px 15px 0px 5px;">'.$HTML.'</td>';
	}
	
	$pipka	= $pipka?'<td style="width:12px; padding: 0px 7px 0px 7px;  '.$style.'" '.$onClick.'><img src="images/'.$pipka.'" width="12" height="12" alt=""></td>':'';
	
	$bg = false;
	$bg = $bg?$bg:'background:#75777C;';
	
	$out = '
	<!-- Prod HEADER //-->
	<tr><td style="height:28px;">

		<table border="0" style="width:100%; height:100%; '.$bg.' border-bottom:1px Solid White;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:3px; height:3px;"><img src="images/gr_lt.png" width="3" height="3" alt="" style="filter:expression(transparent(this));"></td>
			<td><img src="images/spacer.gif" width="2" height="3" alt=""></td>
			<td style="width:3px; height:3px;"><img src="images/gr_rt.png" width="3" height="3" alt="" style="filter:expression(transparent(this));"></td>
		</tr>
		<tr>
			<td style="width:3px;"><img src="images/spacer.gif" width="3" height="3" alt=""></td>
			<td >
				<table border="0" style="width:100%; height:100%;" cellpadding="0" cellspacing="0"><tr>
					'.$pipka.'
					<td style="color:white; padding: 0px 3px 0px 0px; '.$style.'" '.$onClick.'><b>'.$title.'</b></td>
					'.$plus.'
				</tr></table>
			</td>
			<td style="width:3px;"><img src="images/spacer.gif" width="3" height="3" alt=""></td>
		</tr>
		<tr>
			<td style="width:3px; height:3px;"><img src="images/gr_lb.png" width="3" height="3" alt="" style="filter:expression(transparent(this));"></td>
			<td><img src="images/spacer.gif" width="2" height="3" alt=""></td>
			<td style="width:3px; height:3px;"><img src="images/gr_rb.png" width="3" height="3" alt="" style="filter:expression(transparent(this));"></td>
		</tr>
		</table>

	</td></tr>
	<!-- Prod HEADER //-->
';
	
	return $out;
 
}


?>