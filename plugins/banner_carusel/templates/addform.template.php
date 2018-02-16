<?if(!defined('ALLOW_ACCESS')) exit;?>
<script language="javascript" type="text/javascript" src="./tiny_mce/plugins/imagemanager/js/mcimagemanager.js"></script>
<form action="" id="addcat_form" method="post" enctype="multipart/form-data">
    <table width="600" style="margin: 20px; margin-left:<? echo intval($SETTINGS['th_width'])+20 ?>" border="0" cellspacing="0" cellpadding="2" class="text1">
    
		<!-- ERRORS //-->
		<tr>
			<td colspan="2" align="center">
				<table border="0" cellspacing="0" cellpadding="2" class="text1"><tr><td class="err">
				<?=$TMPL_errors?>
				</td></tr></table>
			</td>
		</tr>
		<!-- ERRORS //-->
		
		<tr>
			<td valign="top" onClick="switch_menuopts()" style="cursor:pointer;" nowrap><b>Select menu</b>&nbsp;<span id="gal_span_menuopt">+</span>
			</td>
			<td>
				<div id="gal_div_menuopt" style="display:block;">
				<table border="0" cellpadding="0" cellspacing="0"><tr>
					<td><select id="gal_mid" name="mid[]" class="formField1" style="width:320px; height:100px;" multiple><?=$TMPL_menuopts?></select></td>
					<td valign="top" style="padding: 0px 0px 0px 10px;">
						<div style="padding: 0px 0px 5px 0px;"><input type="button" value="-" onClick="update_wh('gal_mid',-20)" style="width: 20px; height:20px; border: 1px Solid Silver; background-color:#EAEAEA; cursor:pointer;"></div>
						<div><input type="button" value="+" onClick="update_wh('gal_mid',20)"  style="width: 20px; height:20px; border: 1px Solid Silver; background-color:#EAEAEA; cursor:pointer;"></div>
						<div><span style="cursor:pointer; font-size:11px;" onClick="selectMultiple('gal_mid',1)" title="Select all">&nbsp;all</span></div>
					</td>
				</table>
				</div>
			</td>
		</tr>
		
		<? for($i=0;$i<count($TMPL_lang);$i++){ ?>
		<tr>
			<td><b><?=$TEXT['gallery']['img_title']?></b> (<?=$TMPL_lang[$i]?>)</td>
			<td nowrap><input type="text" name="title[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_title[$TMPL_lang[$i]]?>" class="formField1" style="width:320px"></td>
		</tr>
		<?}?>
		<!--URL //-->
		<tr>
			<td><b>URL</b></td>
			<td nowrap><input type="text" name="url" value="<?=$TMPL_url?'http://'.$TMPL_url:'http://'?>" class="formField1" style="width:320px"></td>
		</tr>	
		<!--  URL//-->	
		<tr>
			<td><b><?=$TEXT['gallery']['browse_img']?></b></td>
			<td>
			<input type="text" name="image" value="<?=isset($TMPL_img)?$TMPL_img:''?>" class="formField1" 
				   style="display:inline;width:90%;" >
	           	<a style="display:inline;" href="javascript:mcImageManager.open('addcat_form','image','','',{relative_urls:true, rootpath:'../../../../uploads_script/banner_carusel'});">
		            <img width="20" height="18" src="images/browse.gif" border="0" 
		            style="display:inline;cursor:pointer;border:1px solid white;" align="absmiddle" 
		            onMouseOver="this.style.border='1px solid blue'" onMouseOut="this.style.border='1px solid white'" 
		            alt="Browse Image" title="Browse Image">
	            </a>
            </td>
		</tr>


		<!-- MENU CATEGORIES for SELECT //-->
		<tr>
			<td valign="top">Select Image by menu</td>
			<td>
				<div id="gal_div_menuopt" style="display:block;">
				<table border="0" cellpadding="0" cellspacing="0"><tr>
					<td><select id="gal_mid" name="mid_" class="formField1" style="width:320px;" onChange="top.location.href='<?=$SELF?>&mid='+this.value;" ><?=$TMPL_menuopts_fs?></select></td>
				</tr></table>
				</div>
			</td>
		</tr>
<!-- MENU CATEGORIES for SELECT //-->


		<tr>
			<td>&nbsp;</td>
			<td align="right"><br><input type="submit" id="addcat_button"  value="  <?=$TEXT['global']['add']?>  " class="formButton2"></td>
		</tr>
	</table>
	<input type="hidden" name="action" id="action" value="add">
</form>






<script>

// ##### Menu options
	menuopts_span	= document.getElementById('gal_span_menuopt');
	menuopts_div	= document.getElementById('gal_div_menuopt');
		
	function switch_menuopts() {
		if ( menuopts_div.style.display == 'block') {
			menuopts_div.style.display = 'none';
			menuopts_span.innerHTML = '+';
			writeCookie('gal_div_menuopt','0', 31104000);
		}
		else {
			menuopts_div.style.display = 'block';
			menuopts_span.innerHTML = '-';
			writeCookie('gal_div_menuopt','1', 31104000);
		}
	}

	if(readCookie('gal_div_menuopt')==1) {
		menuopts_div.style.display = 'block';
		menuopts_span.innerHTML = '-';
	}
	else {
		menuopts_div.style.display = 'none';
		menuopts_span.innerHTML = '+';
	}
// ##### Menu options


// ##### Category options
	catopts_span	= document.getElementById('gal_span_catopt')
	catopts_div		= document.getElementById('gal_div_catopt');
		
	function switch_catopts() {
		if ( catopts_div.style.display == 'block') {
			catopts_div.style.display = 'none';
			catopts_span.innerHTML = '+';
			writeCookie('gal_div_catopt','0', 31104000);
		}
		else {
			catopts_div.style.display = 'block';
			catopts_span.innerHTML = '-';
			writeCookie('gal_div_catopt','1', 31104000);
		}
	}

	if(readCookie('gal_div_catopt')==1) {
		if (catopts_div)	catopts_div.style.display = 'block';
		if (catopts_span)	catopts_span.innerHTML = '-';
	}
	else {
		if (catopts_div)	catopts_div.style.display = 'none';
		if (catopts_span)	catopts_span.innerHTML = '+';
	}
// ##### Category options

	if (readCookie('gal_mid') && document.getElementById('gal_mid'))
		document.getElementById('gal_mid').style.height = readCookie('gal_mid');
	if (readCookie('gal_cid') && document.getElementById('gal_cid'))
		document.getElementById('gal_cid').style.height = readCookie('gal_cid');
</script>