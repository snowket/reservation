<?
//Second  Level Sitemenu	
if (!defined('ALLOW_ACCESS')) {die('.');}

$bottom_menu.='<ul>';
	$i=0;
	$n=(count($siteMenu['main'][1])-1);
	foreach ($siteMenu['main'][1] as $key => $value) {
		$title = $value['name'];
		$url=check_structure($value['structure'])?'index.php?m='.$value['cat_id']:'#';
		$url=$value['redirect']?$value['redirect']:$url;
		$limargins=$i==$n?'':' limargins';
			$bottom_menu.='<li class="parents '.$limargins.'"><a class="BPGNinoMtavruliBold" href="'.$url.'">'.$title.'</a>'.get_childs_bottom($key).'</li>'."\n";
		$i++;
	}
$bottom_menu.='</ul>';


function get_childs_bottom ($parent_id){
	global $_CONF,$CONN,$FUNC,$siteMenu;	
	
	$data=$siteMenu['main'][$parent_id];
	
	$has_subcats = false;
	
	$out.='<ul class="childs">';
		foreach ($data as $key => $value) {
			$has_subcats = true;
			$title	= $value['name'];
			$url=check_structure($value['structure'])?'index.php?m='.$value['cat_id']:'#';
			$url=$value['redirect']?$value['redirect']:$url;
			$out .= '<li><a class="" href="'.$url.'">'.$title.'</a>'.get_childs($key).'</li>'."\n";
		}
	$out.='</ul>';
	
	return ($has_subcats)?$out:false;
}
