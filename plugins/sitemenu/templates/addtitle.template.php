<?if(!defined('ALLOW_ACCESS')) exit;?>

<script language="JavaScript" src="js/prototype_1.5.1.1.js" type="text/javascript"></script>

<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/mce_init.js"></script>
<script type="text/javascript" src="./js/overlay.js"></script>
<script type="text/javascript" src="./js/window.js"></script>
<script type="text/javascript" src="./plugins/sitemenu/js/functions.js"></script>
<script type="text/javascript" src="./js/F_toolTip.js"></script>
<script type="text/javascript" src="./js/wz_dragdrop.js"></script>
<script type="text/javascript" src="./tiny_mce/plugins/imagemanager/js/mcimagemanager.js"></script>
<script type="text/javascript">
<!--

function designImageName() {
	img_src = '<?=$TMPL_designdir?>' + document.getElementById('design').value + '.jpg';
	return img_src;
}

function toolTip2(msg) {
	if(toolTip2.arguments.length < 1) { // hide
		if(ns4) toolTipSTYLE.visibility = "hidden";
		else toolTipSTYLE.display = "none";
	}
	else { // show
		var content = '<table border="1" cellspacing="0" cellpadding="5" style="border: 1px Dashed Silver; background-color:#f3f3f3;"><tr><td>'+
		'<img src=' + msg + ' >' + '</td></tr></table>';

		if(ns4) {
			toolTipSTYLE.document.write(content);
			toolTipSTYLE.document.close();
			toolTipSTYLE.visibility = "visible";
		}
	
		if(ns6) {
		  document.getElementById("toolTipLayer").innerHTML = content;
		  toolTipSTYLE.display='block';
		}
	
		if(ie4) {
		  document.all("toolTipLayer").innerHTML=content;
		  toolTipSTYLE.display='block'
		}
	}
}

var blocks = Array('<?=implode("','",array_keys($TMPL_blocks))?>'); 
var mBuilder = new menuBuilder(blocks);
//-->
</script>

<div id="toolTipLayer" style="position:absolute; visibility: hidden;"></div>

<form id="addEdit_sitemenu" action="" method="post" enctype="multipart/form-data" onsubmit="mBuilder.selectItems()">
<!-- MENU && TITLE NAME//-->
<table border="0" style="width:100%;" cellspacing="2" cellpadding="0" class="text1">
	<tr>
		<td class="border_gray1"><b><?=$TEXT['sitemenu']['mtitle']?></b></td>
		<td class="border_gray1"><b>Text</b></td>
	</tr>
  <?if(isset($TMPL_errors)){?>
	<tr>
		<td class="err"><?=$TMPL_errors?></td>
	</tr>
  <?}?>
  <? for($i=0;$i<count($TMPL_lang);$i++){ ?> 
	<tr>
		<td style="width:30%;" nowrap><input type="text" id="lang<?=($i+1)?>" name="name[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_name[$TMPL_lang[$i]]?>" class="formField1" style="width:85%" tabindex="<?=($i+1)?>">(<?=$TMPL_lang[$i]?>)</td>
		<td><input type="text" name="title[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_title[$TMPL_lang[$i]]?>" class="formField1" style="width:70%" tabindex="<?=($i+1+20)?>">(<?=$TMPL_lang[$i]?>)</td>
	</tr>
  <?}?>
	<tr>
		<td class="border_gray1">&nbsp;</td>
		<td class="border_gray1">&nbsp;</td>
	</tr>
</table>
<!-- MENU && TITLE NAME//-->

<div style="cursor:pointer; background-color:#EAEAEA;" onClick="switch_options()">
	<table style="width:100%;" border="0"  border="0" cellspacing="0" class="text1"><tr>
		<td id="td_options" align="right">+</td>
		<td width="1px"><?=$TEXT['sitemenu']['options']?></td>
	</tr></table>
</div>


<!-- OPTIONS //-->
<table id="table_options" style="width:100%; display:none;" border="0" cellspacing="0" cellpadding="2" class="text1">
  <tr>
	<td valign="top">
	  <table border="0" cellspacing="0" cellpadding="2" class="text1">
		<!-- MENU TYPE //-->
		<? if ($TMPL_types) { ?>
		<tr>
			<td width="150" nowrap class="tdrow2"><b><?=$TEXT['sitemenu']['type']?></b></td>
			<td class="tdrow3"><select name="type" class="formField1" style="width:180px"><?=$TMPL_types?></select></td>
		</tr>
		<? } ?>
		<!-- MENU TYPE //-->
		
		<!-- VIEW TYPE //-->
		<thead style="display:none;">
		<? if ($TMPL_viewtypes) { ?>
		<tr>
			<td width="150" nowrap class="tdrow2"><b>View type</b></td>
			<td class="tdrow3"><select name="viewtype" class="formField1" style="width:180px"><?=$TMPL_viewtypes?></select></td>
		</tr>
		<? } ?>
		</thead>
		<!-- VIEW TYPE //-->
		
		<!-- REDIRECT //-->
		  <tr>
			<td nowrap class="tdrow2"><b><?=$TEXT['sitemenu']['redirect']?></b></td>
			<td class="tdrow3"><input type="text" name="redirect" value="<?=$TMPL_redirect?>" class="formField1" style="width:180px"></td>
		  </tr>
		<!-- REDIRECT //-->
		<!-- BANNER IMAGE //-->
		<tr>
		  <td nowrap class="tdrow2"><b>Header Images List</b></td>
		  <td class="tdrow3">
			<select name="image" class="formField1" style="width:180px;" onchange="mBuilder.showPreview(this,'<?=$TMPL_imagedir?>','imagePreview'); mBuilder.switchTabs_img('imagePreview','designPreview','bgimagePreview')" >
				<option style="width:160px;" value="">&nbsp;</option>
				<?=$TMPL_allimages?>
			</select>
		  </td>
		</tr>
		<tr>
		  <td nowrap class="tdrow2"><b>Header Image</b>(1005px &times; 205px)</td>
		  <td class="tdrow3"><input type="file" name="newimage" class="formField1" style="width:180px"></td>
		</tr>
		<!-- BANNER IMAGE //-->
		<!-- DESIGN IMAGE //-->
		<tr>
		  <td nowrap class="tdrow2"><b><?=$TEXT['sitemenu']['design']?></b></td>
		  <td class="tdrow3">
            <select id="design" name="design" class="formField1" style="width:180px" onchange="mBuilder.showPreview(this,'<?=$TMPL_designdir?>','designPreview','.gif',true); if (design_selectedIndex != this.selectedIndex) {showHide('blocks','hide'); showHide('update','show');} else {showHide('blocks','show'); showHide('update','hide');} mBuilder.switchTabs_img('designPreview','imagePreview','bgimagePreview')">
              <?=$TMPL_alldesigns?>
            </select>
		  </td>
		</tr>
		<script> design_selectedIndex = document.getElementById('design').selectedIndex; </script>
		<!-- DESIGN IMAGE //-->
		<!-- BACKGROUND //-->
		<tr>
		  <td nowrap class="tdrow2">
		  	<b>Popular Menu Bg </b> <br/>(width:120px &times; height:120px;)
		  	<br/>
		  	(<a onclick="document.getElementById('bgimage').value=''; return false;" style="cursor:pointer" href="Clear">Clear</a>)
		  </td>
		  <td class="tdrow3">
	        <!-- <img src="images/img.gif" width="16" height="16" border="0" alt="Edit image" title="Edit image" align="absbottom" onClick="showHide('addimg_div')" style="cursor:pointer;"></a>&nbsp;&nbsp;
			<br>
   			<div id="addimg_div" align="left" class="FLOAT_main" style="position:absolute; display:none; width:200px; height:21px; background:#F3F3F3; left_:-500px; border:0px Solid red;"> //-->
				<table border="0" style="width:100%; margin:1px;" cellspacing="0" cellpadding="1" >
				  <!-- image //-->
				  <tr>
				  	<td><input type="text" id="bgimage" name="bgimage" readonly="readonly" value="<?=$TMPL_bgimage?>" class="formField1" style="width:100%;" onChange="mBuilder.showPreview_bgImage(this.value,'bgimagePreview',true,this.value); mBuilder.switchTabs_img('bgimagePreview','imagePreview','designPreview');" ></td>
				  	<td style="width:1%;"><a href="javascript:mcImageManager.open('addEdit_sitemenu','bgimage','','',{relative_urls:true, rootpath:'../../../../uploads_script/sitemenu/popular/'});"><img src="images/browse.gif" border="0" style="cursor:pointer; " onMouseOver="this.style.border='1px Solid Blue'" onMouseOut="this.style.border='0px'" alt="Browse image" title="Browse image" ></a></td>
				  </tr>
				  <!-- image //-->
				</table>
			<!-- </div> //-->
			
	 	  </td>
		</tr>
		<!-- BACKGROUND //-->
		
		<!-- ICONS //-->
		<tr>
		  <td nowrap class="tdrow2"><b>Quick Menu Bg</b>
		  <br/>(width:31px &times; height:31px;)</b> 
		  	<br/>
		  (<a onclick="document.getElementById('bgimage').value=''; return false;" style="cursor:pointer" href="Clear">Clear</a>)
		  </td>
		  <td class="tdrow3">
	        <!-- <img src="images/img.gif" width="16" height="16" border="0" alt="Edit image" title="Edit image" align="absbottom" onClick="showHide('addimg_div')" style="cursor:pointer;"></a>&nbsp;&nbsp;
			<br>
   			<div id="addimg_div" align="left" class="FLOAT_main" style="position:absolute; display:none; width:200px; height:21px; background:#F3F3F3; left_:-500px; border:0px Solid red;"> //-->
				 <!--<table border="0" style="width:100%; margin:1px;" cellspacing="0" cellpadding="1" >
				  image
				  <tr>
				  	<td><input type="text" id="icons" name="icons" readonly="readonly" value="<?=$TMPL_icons?>" class="formField1" style="width:100%;"></td>
				  	<td style="width:1%;"><a href="javascript:mcImageManager.open('addEdit_sitemenu','icons','','',{relative_urls:true, rootpath:'../../../../uploads_script/sitemenu/quick/'});"><img src="images/browse.gif" border="0" style="cursor:pointer; " onMouseOver="this.style.border='1px Solid Blue'" onMouseOut="this.style.border='0px'" alt="Browse image" title="Browse image" ></a></td>
				  </tr> //-->
				  <!-- image 
				</table>//-->
			<!-- </div> //-->
			
	 	  </td>
		</tr>
		<!-- ICONS //-->
		
		
		<!-- PERMISSIONS //-->
		<thead style="display:none;">
		<tr>
		  <td nowrap class="tdrow2" valign="top"><b>Permission to Access</b></td>
		  <td class="tdrow3">
		    	<select name="groups[]" style="width:100%; font-size:11px; font-family:verdana;" size="7" multiple>
		    		<option value="none">[None]</option>
		    	<?
					foreach ($TMPL_groups AS $v) {
						if ($TMPL_action=='add') {
							$selected = 'selected';
						}
						else {
							$selected = in_array($v['id'], $TMPL_groups_selected) ? 'selected' : '';
						}
						echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['title'].'</option>';
					}
		    	?>
		    	</select>
	 	  </td>
		</tr>
		<!-- PERMISSIONS //-->
		
		
		<!-- PERMISSIONS //-->
		<tr>
		  <td nowrap class="tdrow2" valign="top"><b>Permission to View</b></td>
		  <td class="tdrow3">
		    	<select name="groups_view[]" style="width:100%; font-size:11px; font-family:verdana;" size="7" multiple>
		    		<option value="none">[None]</option>
		    	<?
					foreach ($TMPL_groups AS $v) {
						if ($TMPL_action=='add') {
							$selected = 'selected';
						}
						else {
							$selected = in_array($v['id'], $TMPL_groups_view_selected) ? 'selected' : '';
						}
						echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['title'].'</option>';
					}
		    	?>
		    	</select>
	 	  </td>
		</tr>
		</thead>
		<!-- PERMISSIONS //-->
		
		<!-- blank //-- >
		<tr>
		  <td nowrap class="tdrow2"><b>&nbsp;</b></td>
		  <td class="tdrow3">&nbsp;</td>
		</tr>
		<!-- blank //-->
	  </table>

	</td>
    <td valign="top" style="width:100%;">
      
	<!-- Banner image && Design //-->
		<table width="100%" border="0" cellpadding="0" cellspacing="2">
		  <!-- TABS //-->
	      <tr>
		      <td class="tab_inactive" style="width:1%; cursor:pointer;" id="tab_imagePreview" onclick="mBuilder.switchTabs_img('imagePreview','designPreview','bgimagePreview')"><span class="text1">Header Image</span></td>
		      <td class="tab_active"   style="width:1%; cursor:pointer;" id="tab_designPreview" onclick="mBuilder.switchTabs_img('designPreview','imagePreview','bgimagePreview')"><span class="text1">Site Design</span></td>
		      <td class="tab_inactive" style="width:1%; cursor:pointer;" id="tab_bgimagePreview" onclick="mBuilder.switchTabs_img('bgimagePreview','imagePreview','designPreview')"><span class="text1">Menu Bg</span></td>
			  <td class="tab_devider">&nbsp;</td>
	      </tr>
		  <!-- TABS //-->
		  <tr><td colspan="3">
			<!-- DIVS //-->
			  <div id="imagePreview" style="display:none; width:220px; height:115px; overflow:hidden; text-align:center;">
				  <?if(isset($TMPL_image)&&!empty($TMPL_image)){?>
					  <img src="<?=$TMPL_imagedir.$TMPL_image?>" width="200" border="0">
					  <a href="<?=$SELF?>&action=delpic&pic=<?=$TMPL_image?>"><img src ="./images/icos16/delete.gif" align="top" alt="delete" border="0"></a>
				  <?}?>
			  </div> 
			  <div id="designPreview" style="display:block; width:220px; height:115px; overflow:hidden; text-align:center;">
					<?if(isset($TMPL_design)&&!empty($TMPL_design)){?><img src="<?=$TMPL_designdir.$TMPL_design?>.gif" width="200" border="0" onMouseOver="toolTip2(designImageName())" onMouseOut="toolTip()"><?}?>
			  </div>
			  <div id="bgimagePreview" style="display:none; width:220px; height:115px; overflow:hidden; text-align:center;">
					<?if(isset($TMPL_bgimage)&&!empty($TMPL_bgimage)){?><img src="<?=$TMPL_bgimage?>" width="200" border="0" onMouseOver="toolTip2('<?=$TMPL_bgimage?>');" onMouseOut="toolTip();"><?}?>
			  </div>
			  
			<!-- DIVS //-->
		</td></tr>
	    </table>
	<!-- Banner image && Design //-->
    </td>
  </tr>
</table>
<!-- OPTIONS //-->



<script>
	siteBlock_optionsTa = document.getElementById('table_options');
	siteBlock_optionsTd = document.getElementById('td_options');
		
	function switch_options() {
		if ( siteBlock_optionsTa.style.display == 'block') {
			siteBlock_optionsTa.style.display = 'none';
			siteBlock_optionsTd.innerHTML = '+';
			writeCookie('siteBlock_options','0', 31104000);
		}
		else {
			siteBlock_optionsTa.style.display = 'block';
			siteBlock_optionsTd.innerHTML = '-';
			writeCookie('siteBlock_options','1', 31104000);
		}
	}
	
	if(readCookie('siteBlock_options')==1) {
		siteBlock_optionsTa.style.display = 'block';
		siteBlock_optionsTd.innerHTML = '-';
	}
	else {
		siteBlock_optionsTa.style.display = 'none';
		siteBlock_optionsTd.innerHTML = '+';
	}
</script>
<div style="font-size:5px;">&nbsp;</div>


<div id="blocks" style="border: 1px solid #E3E3E3; padding:10px 0px 0px 10px">
  <table width="100%" border="0" cellpadding="0" cellspacing="2"><tr>
    <td width="100%">
		<select id="mod_all" name="mod_all"  multiple style="width:100%; height:150px; background:#fafafa; font-size:11px; border:1px Solid Silver;">
	 	<optgroup label=" <?=$TEXT['sitemenu']['pmoduls']?>" style="color:#aa0000;">
			<?=$TMPL_pblocks?>
		</optgroup>
		<optgroup label=" <?=$TEXT['sitemenu']['cmoduls']?>" style="color:#3073BA;" id="group_content">   
			<?=$TMPL_cblocks?>   
		</optgroup>
		</select>
	</td>
	<td valign="top" style="padding: 0px 5px 0px 5px;">
		<div style="padding: 0px 0px 5px 0px;"><input type="button" value="-" onClick="update_wh('mod_all',-20)" style="width: 20px; height:20px; border: 1px Solid Silver; background-color:#EAEAEA; cursor:pointer;"></div>
		<div><input type="button" value="+" onClick="update_wh('mod_all',20)"  style="width: 20px; height:20px; border: 1px Solid Silver; background-color:#EAEAEA; cursor:pointer;"></div>
	</td>
  </tr></table>


<div style="font-size:5px;">&nbsp;</div>

<!-- SITEBLOCKS TABS //-->
<table width="100%" border="0" cellpadding="0" cellspacing="2">
  <tr>
  <? $firsttab = each($TMPL_blocks);?>
	<td class="tab_active" id="tab_<?=$firsttab[0]?>" onclick="mBuilder.switchTabs('<?=$firsttab[0]?>')"><a href="javascript:void(0)" class="text1"><?=$firsttab[1]?></a></td>
  <? while(list($k,$v) = each($TMPL_blocks)){ ?>
	<td class="tab_inactive" id="tab_<?=$k?>" onclick="mBuilder.switchTabs('<?=$k?>')"><a href="javascript:void(0)" class="text1"><?=$v?></a></td>
  <?}?>
	<td style="width:100%;" class="tab_devider">&nbsp;</td>
  </tr> 
</table>
<!-- SITEBLOCKS TABS //-->

<div style="width:100%; border-top:1px solid #E3E3E3; ">
  <?foreach($TMPL_blocks as $k=>$v){?>
  <div style="display:none; margin:5px 0px 2px 0px;" id="div_<?=$k?>">
	<table width="100%" cellpadding="1" cellspacing="0" border="0">
	  <tr>
	  	<td>&nbsp;</td>
	  	<td width="20"><img src="images/icos16/document_plain.gif" style="width:16px; height:16px; cursor:pointer;" alt="New Content" onclick="mBuilder.contentAddWindow()"></td>
		<td width="40"><img src="images/icos16/edit.gif" style="width:16px; height:16px; cursor:pointer;" alt="Edit" onclick="mBuilder.contentEditWindow()"></td>
		<td width="20"><img src="images/icos16/add.gif" style="width:16px; height:16px; cursor:pointer;" alt="Add" onclick="mBuilder.addItems()"></td>
		<td width="30"><img src="images/icos16/delete.gif" style="width:16px; height:16px; cursor:pointer;" alt="Remove" onclick="mBuilder.removeItems()"></td>
		<td width="20"><img src="images/icos16/down.gif" style="width:16px; height:16px; cursor:pointer;" alt="Move down" onclick="mBuilder.moveItem('down')"></td>
		<td width="40"><img src="images/icos16/up.gif" style="width:16px; height:16px; cursor:pointer;" alt="Move up" onclick="mBuilder.moveItem('up')"></td>
	  </tr>
	</table>
	
	<table width="100%" border="0" cellpadding="0" cellspacing="2">
	  <tr>
	    <td style="padding: 0px 15px 0px 0px;">
		  <select id="block_<?=$k?>" name="block_<?=$k?>[]" style="width:100%; height:175px; color:#555; font-size:11px; border:1px Solid Silver;" multiple>
			<?=$TMPL_options[$k]?>
		  </select>
	    </td>
	    <!--	  
           <td valign="top" style="padding: 0px 5px 0px 5px;">
		   <br>
		   	
		   	<div style="padding: 0px 0px 5px 0px;"><input type="button" value="-" onClick="update_wh('block_<?=$k?>',-20)" style="width: 20px; height:20px; border: 1px Solid Silver; background-color:#EAEAEA; cursor:pointer;"></div>
		   		<div><input type="button" value="+" onClick="update_wh('block_<?=$k?>',20)"  style="width: 20px; height:20px; border: 1px Solid Silver; background-color:#EAEAEA; cursor:pointer;"></div>
		   
		   </td>
		-->
	  </tr> 
	</table>	
	
  </div> 
  <? } ?>
 </div>
</div>

<div style="font-size:5px;">&nbsp;</div>

<div id="update" style="display:none; color:red; font-size:13px; text-align:center; padding: 20px;"><b>Save changes first</b></div>
<div style="text-align:right"><input type="submit" value="submit" class="formButton5"></div>
<input type="hidden" name="cid" value="<?=$TMPL_cid?>">
<input type="hidden" name="siteblock" value="<?=$TMPL_siteblock?>">
<input type="hidden" name="action" value="<?=$TMPL_action?>">
<input type="hidden" name="type_compare" value="<?=$TMPL_type?>">
</form>
<script type="text/javascript">
<!--
	//SET_DHTML("addcat_div","addimg_div","addopt_div");
	//dd.elements.addimg_div.moveTo(dd.getWndW()/2-dd.elements.addimg_div.w/2,dd.getWndH()*0.2+25);
	//dd.elements.addimg_div.hide();
	
	function update_wh(elmID,H) {
		var obj = document.getElementById(elmID);
		//obj.style.width  = parseInt(obj.style.width) + W;
		setTimeout (function() {obj.style.height = parseInt(obj.style.height) + H; writeCookie(elmID,parseInt(obj.style.height),31104000);},0);
	}
	
	if (readCookie('mod_all'))
		document.getElementById('mod_all').style.height = readCookie('mod_all');
	
	document.getElementById("div_<?=$firsttab[0]?>").style.display = "block";
	document.getElementById('lang1').focus(); 
	initToolTips();
	//document.body.onload = function(){alert('aasd'); document.getElementById('lang1').focus(); }
</script>