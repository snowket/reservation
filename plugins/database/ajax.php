<?
if($_GET['tab'] == 'categories'){
	require_once("./kernel/categories_ajax.php");
}
else{
	$calendar = new CALENDAR($SETTINGS['cal_min'],$SETTINGS['cal_max']);
	$calendar->getActiveDays($CONN,$_CONF['db']['prefix']."_news");

	header("Content-type: text/xml; charset=UTF-8");
	print "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">";
	print "<data>";
	print "<calendar><![CDATA[";
	print $calendar->printCalendar("index.php?m=".$LOADED_PLUGIN['plugin']."&",LANG,"admin");
	print "]]></calendar>"; 
	print "</data>";
}
?> 