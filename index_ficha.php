<?
$ficha  = '';
$ficha .= '
	<script language="javascript">
	function transparent(element){
		if(/MSIE (5\.5|6).+Win/.test(navigator.userAgent)) {
			var src = element.src;
			element.src = "./images/spacer.gif"; 
			if (src) element.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'" + src + "\',sizingMethod=\'scale\')";
		}
	}
	</script>
';


$k = 0;
$ficha .= '
	<table border="1" style="width:100%; border:1px Solid White; background:#F2F2F2; margin-bottom:20px; border-collapse:collapse;" cellspacing="0" cellpadding="3" align="center">
	<tr>';

	for($i=0, $n=count($PLUGINS); $i<$n; $i++) {
		$k++;
		$ficha .= ($i%4==0)?'<tr onmouseover="this.style.backgroundColor=\'#e7e7e7\';" onmouseout="this.style.backgroundColor=\'transparent\';">':'';
		$img = file_exists('images/icos_plugins/'.$PLUGINS[$i]['plugin'].'.png')?'<div style="width:48px; height:48px;"><center><img src="images/icos_plugins/'.$PLUGINS[$i]['plugin'].'.png" width="48" height="48" style="filter:expression(transparent(this));"></center></div>':'<div style="width:48px; height:48px;">&nbsp;</div>';
		$ficha .= '<td align="center" style="cursor:pointer; height:80px;" onmouseover="this.style.backgroundColor=\'#347BC9\';" onmouseout="this.style.backgroundColor=\'transparent\';" onclick="top.location.href=\''.$_SERVER['PHP_SELF'].'?m='.$PLUGINS[$i]['plugin'].'\'">'.$img.str_replace('Manager of','',$PLUGINS[$i]['title']).'</td>';
		$ficha .=  ($i%4==3)?'</tr>':'';
	}

$ficha .= '
	</tr>
	</table>
';
$ficha .= '<table border="1" style="width:100%; border:1px Solid Silver; border-collapse:collapse; margin-bottom:10px;" cellspacing="0" cellpadding="3" >

<tr><td class="tdrow2">Date:</td><td class="tdrow3">'.date("r").'</td></tr>
<tr><td class="tdrow2">TimeZone:</td><td class="tdrow3">'.date_default_timezone_get().'</td></tr>';


	// Reg users
	$users_count		= $FUNC->db_query_getRows('_users','COUNT(*) as count','',__FILE__,__LINE__);
	$reg_users_count	= $FUNC->db_query_getRows('_users','COUNT(*) as count','group_id=5',__FILE__,__LINE__);
	$reg_users_blocked	= $FUNC->db_query_getRows('_users','COUNT(*) as count','group_id=5 and publish=0',__FILE__,__LINE__);
	$ficha .= '
	<tr><td class="tdrow2" width="30%">&nbsp;</td><td class="tdrow3">&nbsp;</td></tr>
	<tr><td class="tdrow2"><a href="?m=users_registered"><b>Users</b></a> :</td><td class="tdrow3">'.$users_count[0]['count'].'</td></tr>
	<tr><td class="tdrow2">Users registered:</td><td class="tdrow3">'.$reg_users_count[0]['count'].'</td></tr>
	<tr><td class="tdrow2">Users blocked:</td><td class="tdrow3">'.$reg_users_blocked[0]['count'].'</td></tr>
	<tr><td class="tdrow2">Users registration options:</td><td class="tdrow3"><a href="?m=users_registered&tab=parameters">Edit</a></td></tr>';
	
	// Products
/*	$prod_count			= $FUNC->db_query_getRows('_prod','COUNT(*) as count','',__FILE__,__LINE__);
	$prod_count_publish	= $FUNC->db_query_getRows('_prod','COUNT(*) as count','publish=1',__FILE__,__LINE__);
	$prod_count_promo	= $FUNC->db_query_getRows('_prod','COUNT(*) as count','promo=1',__FILE__,__LINE__);
	$ficha .= '
	<tr><td class="tdrow2">&nbsp;</td><td class="tdrow3">&nbsp;</td></tr>
	<tr><td class="tdrow2"><a href="?m=products"><b>Products</b></a> :</td><td class="tdrow3">'.$prod_count[0]['count'].'</td></tr>
	<tr><td class="tdrow2">Products published:</td><td class="tdrow3">'.$prod_count_publish[0]['count'].'</td></tr>
	<tr><td class="tdrow2">Products promo:</td><td class="tdrow3">'.$prod_count_promo[0]['count'].'</td></tr>
	<tr><td class="tdrow2">Products options:</td><td class="tdrow3"><a href="?m=products&tab=parameters">Edit</td></tr>';
*/
$ficha .= '</table>';
/*
$ficha .= '<table border="1" style="width:100%; border:1px Solid Silver; border-collapse:collapse;" cellspacing="0" cellpadding="3">';

	// Orders
	$orders_count		= $FUNC->db_query_getRows('_orders','COUNT(*) as count','',__FILE__,__LINE__);
	$orders_added		= $FUNC->db_query_getRows('_orders','COUNT(*) as count','date_paid=0',__FILE__,__LINE__);
	$orders_paid		= $FUNC->db_query_getRows('_orders','COUNT(*) as count','date_paid<>0 AND date_delivered=0',__FILE__,__LINE__);
	$orders_delivered	= $FUNC->db_query_getRows('_orders','COUNT(*) as count','date_paid<>0 AND date_delivered<>0',__FILE__,__LINE__);
	$ficha .= '
	<tr><td class="tdrow2" width="30%"><a href="?m=orders"><b>Orders</b></a> :</td><td class="tdrow3">'.$orders_count[0]['count'].'</td></tr>
	<tr><td class="tdrow2"><a href="?m=orders"><b>Orders</b> ( confirmed/not paid )</a>:</td><td class="tdrow3">'.$orders_added[0]['count'].'</td></tr>
	<tr><td class="tdrow2"><a href="?m=orders&tab=paid"><b>Orders</b> ( paid )</a>:</td><td class="tdrow3">'.$orders_paid[0]['count'].'</td></tr>
	<tr><td class="tdrow2"><a href="?m=orders&tab=delivered"><b>Orders</b> ( delivered )</a>:</td><td class="tdrow3">'.$orders_delivered[0]['count'].'</td></tr>
	<tr><td class="tdrow2">Orders options:</td><td class="tdrow3"><a href="?m=orders&tab=parameters">Edit</a></td></tr>
	<tr><td class="tdrow2">Orders summary:</td><td class="tdrow3"><a href="?m=orders&tab=stats&action=preview&date_from=&date_to='.date("Y-m-d").'&submit=Previews">View</a> / <a href="?m=orders&tab=stats&action=preview&date_from=&date_to='.date("Y-m-d").'&submit=Previews&print=xls">Export to Excel</a></td></tr>';
	


$ficha .= '</table>';
*/

$ficha_r  = '';
/*
$ficha_r .= '
<pre>
Pro-Service design studio.

Phone: 233 005;

BOSS:  Voland
BOSS2: Zeonito

Web-Design: Chipa
PHP Developers:
    Elrian, Alex,
    GR и 2 навички
Flasher: Sergio

</pre>
';
*/
$ficha_r .= '
<table border="1" style="width:100%; font-family:arial;" cellspacing="0" cellpadding="3">
	<tr><td class="tdrow1" nowrap>
		Pro-Service - 
		<br> &nbsp; &nbsp; Web-Design Studio 
		<br> &nbsp; &nbsp; HOST Provider 
		<br> &nbsp; &nbsp; &nbsp; &nbsp; (OS Win/Nix/BSD)
	</td></tr>
	<tr><td class="tdrow3" style="font-family:verdana;" nowrap>
		<b style="color:red; font-family:verdana; font-size:13px;">
			&nbsp; &nbsp; 233-005
			<br> &nbsp; &nbsp; 508-333
			<br> &nbsp; &nbsp; 260-555
		</b>
		<br><a href="http://proservice.ge" target="_blank">www.proservice.ge</a>
		<br><a href="mailto:contact@proservice.ge">contact@proservice.ge</a>
		<br> <br> Abashidze str., 42
	</td></tr>
	<tr><td class="tdrow2">&nbsp;</td>
	<tr><td class="tdrow3" style="font-family:verdana;" nowrap><a href="http://proservice.ge/hosting.html" target="_blank">Hosting prices</a></td></tr>
	<tr><td class="tdrow3" style="font-family:verdana;" nowrap><a href="http://proservice.ge/ns.php" target="_blank">CHECK DOMAINS</a></td></tr>
</table>
';



$_CENTER	= $ficha;
$_RIGHT		= $ficha_r;