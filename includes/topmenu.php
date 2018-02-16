<?
//Second  Level Sitemenu	
if (!defined('ALLOW_ACCESS')) {die('.');}

$topmenu.='<div id="myjquerymenu" class="jquerycssmenu"><ul>';

	foreach ($siteMenu['main'][1] as $key => $value) {
		$title = $value['name'];
		$url=check_structure($value['structure'])?'index.php?m='.$value['cat_id']:'#';
		$url=$value['redirect']?$value['redirect']:$url;
		if($value['cat_id']==$ACTIVE['cat_id'] || $value['cat_id']==$ACTIVE['parent_id']){
			$topmenu.='<li class="active first-level"><a class="firstlvl BPGNinoMtavruliBold" href="'.$url.'" >'.$title.'</a>'.get_childs($key).'</li>'."\n";
		}else{
			$topmenu.='<li class="first-level"><a class="firstlvl BPGNinoMtavruliBold" href="'.$url.'">'.$title.'</a>'.get_childs($key).'</li>'."\n";
		}
		$topmenu.='<li class="divider"><img src="images/spacer.gif" alt="" /></li>'."\n";
	}
$topmenu.='</ul>
		  <br class="clear" /></div>';


function get_childs ($parent_id){
	global $_CONF,$CONN,$FUNC,$siteMenu;	
	
	$data=$siteMenu['main'][$parent_id];
	
	$has_subcats = false;
	
	$out.='<ul>';
		foreach ($data as $key => $value) {
			$has_subcats = true;
			$title	= $value['name'];
			$url=check_structure($value['structure'])?'index.php?m='.$value['cat_id']:'#';
			$url=$value['redirect']?$value['redirect']:$url;
			$out .= '<li><a href="'.$url.'">'.$title.'</a>'.get_childs($key).'</li>'."\n";
		}
	$out.='</ul>';
	
	return ($has_subcats)?$out:false;
}
