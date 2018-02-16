<?
$query  = "SELECT cat_id FROM {$_CONF['db']['prefix']}_sitemenu 
          WHERE structure LIKE '%\"news\"%' LIMIT 1";
$res1     = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
$data   = $res1->fields;           
          
$query    = "SELECT * FROM {$_CONF['db']['prefix']}_news WHERE TO_DAYS(date)-TO_DAYS(NOW())<7 ORDER BY date DESC";         
$res2 = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

while($o = $res2->FetchNextObject())
{	
   $text[$o->LANG] .= "<a href=\"".$_CONF['path']['url']."?m=".$data['cat_id']."&newsid=".$o->ID."\" target=\"_blank\">".$o->TITLE."</a><br><br>\n";
}
?>