<?
if(!defined('ALLOW_ACCESS')) exit;

if ($_POST['with_cats'])	$pub = 1;
else						$pub = 0;

$query = "UPDATE {$_CONF['db']['prefix']}_sitemenu_blocks SET publish='".$pub."' WHERE name='news_categories'";
$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

?>