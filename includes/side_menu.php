<?
//Second  Level Sitemenu	
if (!defined('ALLOW_ACCESS')) {die('.');}


$side_menu.='<div class="side_menu">
<ul >';

	foreach ($siteMenu['left_menu'][1] as $key => $value) {
		$title = $value['name'];
		$url=check_structure($value['structure'])?'index.php?m='.$value['cat_id']:'#';
		$url=$value['redirect']?$value['redirect']:$url;
		$active_li=$ACTIVE['cat_id']==$value['cat_id']?'active':'';
			$side_menu.='<li class="'.$active_li.'" onclick="top.location.href=\''.$url.'\'"><a class="BPGNinoMtavruliBold" href="'.$url.'">'.$title.'</a></li>'."\n";
	}
$side_menu.='</ul></div>';
