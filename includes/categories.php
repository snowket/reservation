<?
if (!defined('ALLOW_ACCESS')) {die('.');}

//if(check_structure_categories($ACTIVE['structure'])){
	$site_categories = array();	
	
	$query	= "SELECT * FROM {$_CONF['db']['prefix']}_categories WHERE publish=1 ORDER by cat_left";
	$result	= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
$categories_array=array();
	while($r = $result->FetchRow()){
		$pid = $r['pid'];
		$parentid = $r['parent_id'];
		$id  = $r['cat_id'];
		$categories_array[$pid][]= array(
								'cat_id'	=> $r['cat_id'],
								'parent_id'	=> $r['parent_id'],
								'name'		=> trim($FUNC->unpackData($r['name'],LANG)),
								'level'		=> $r['cat_level'],
								'publish'	=> $r['publish'],
								'image'		=> $r['image'],
								'post_count'=> $r['post_count'],
							);
		$site_categories[$pid][$parentid][$id] = array(
											'cat_id'	=> $r['cat_id'],
											'parent_id'	=> $r['parent_id'],
											'name'		=> trim($FUNC->unpackData($r['name'],LANG)),
											'level'		=> $r['cat_level'],
											'publish'	=> $r['publish'],
											'image'		=> $r['image'],
											'post_count'=> $r['post_count'],
										);
	}
	$registry = Registry::getInstance();
	$registry->add('site_categories', $site_categories);
//}

