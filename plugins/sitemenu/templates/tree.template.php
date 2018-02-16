<?if(!defined('ALLOW_ACCESS')) exit;?>
<?
/*	$Color_over		= '#C7C7C7';
	$Color_out		= '#F8F8F8';
	$Color_click	= '#ffcc99';*/

	$Color_over		= '#C7C7C7';
	$Color_out		= '#F8F8F8';
	$Color_click	= 'rgb(255, 204, 153)';
    
    
?>

<script language="JavaScript" type="text/javascript">
	
	window.onload=ViewPCMSMenu;	
    Color_over		= '#C7C7C7';
	Color_out		= '#F8F8F8';

	if(navigator.userAgent.indexOf("MSIE")>0&&navigator.userAgent.indexOf("Opera")<0)
	   Color_click	= 'rgb(255,204,153)';
    else
      Color_click	= 'rgb(255, 204, 153)';		
    function setClickColor(obj){
    	   color = obj.style.backgroundColor;
           if ( color.replace(/\s/,'')!= Color_click.replace(/\s/,'')) {
                 obj.style.backgroundColor=Color_click;
             } else {obj.style.backgroundColor=Color_out;} 
      }
</script>	

<div align="right" style="width:98%; margin: 15px 0 15px 0; border: 0px solid #000000;">
	<a class="basic" href="<?=$SELF?>&tab=<?=$TMPL_tab?>&action=add">
		<img src="images/icos16/add.gif" width="16" height="16" border="0" align="absmiddle" alt="Make root menu"> <?=$TEXT['sitemenu']['new_menu']?>
	</a>
</div>
<table width="100%" cellspacing="0" cellpadding="0" border="0"> 
	<?=$TMPL_tree?>
</table>