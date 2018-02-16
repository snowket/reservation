

<div width="100%" style="border:1px Solid #F2F2F2; margin-top:25px;">
	<div align="center" class="text1" style="padding:5px;"><b><?=$TEXT['tab']['room_types']?></b></div>
	<? for($i=0; $i<count($TMPL_cats);$i++){ ?>
		<? $style = 'font-weight:bold;'; ?>
		<a class="text1" style="display:block; padding:2px; cursor:pointer; <?=$style?>" onmouseover="this.style.background='#F8F8F8'" onmouseout="this.style.background='#FFF'" href="<?=$SELF?>&action=view&cid=<?=$TMPL_cats[$i]['id']?>" title="<?=$TMPL_cats[$i]['id']?>">
			<?=$TMPL_cats[$i]['title']?>
			<? echo '&nbsp;('.$TMPL_counts[$TMPL_cats[$i]['id']].')'; ?>
		</a>
	<?}?> 
</div>