<?if(!defined('ALLOW_ACCESS')) exit;?>
<script language="javascript" type="text/javascript" src="./js/categories_manager.js"></script>
<script language="javascript" type="text/javascript" src="./js/wz_dragdrop.js"></script>
<script language="javascript" type="text/javascript" src="./tiny_mce/plugins/imagemanager/js/mcimagemanager.js"></script>
<script>
	var cManager = new catManager();
	cManager.textAdd  = "  <?=$TEXT['global']['add']?> ";
	cManager.textEdit = "  <?=$TEXT['global']['edit']?>  ";
	cManager.plugin   = "<?=$_GET['m']?>&tab=categories";
</script>
<div id="addcat_div" style="position:absolute; width:400px; height:21px; background:#fff; display:block; left:-500px; border:0px Solid red;">
  <table border="0" style="width:402px; height:21px; cursor:move;" class="win_header" cellpadding="0" cellspacing="0">
	<tr>
	  <td style="width:6px; background-image:url(./images/line_grad1.gif);"><img src="./images/win_corn1.gif" width="6" height="21" border="0"></td>
	  <td style="background-image:url(./images/line_grad1.gif);" align="right" valign="middle"><a href="javascript:void(0)" onclick="cManager.hide()"><img src="./images/icos16/close.gif" width="16" height="16" border="0" style="border:1px solid #87B7E8" onmouseover="this.style.border='1px outset ButtonHighlight'" onmouseout="this.style.border='1px solid #87B7E8'" onmousedown="this.style.border='1px inset ButtonHighlight'"></a></td>
	  <td style="width:6px; background-image:url(./images/line_grad1.gif);"><img src="./images/win_corn2.gif" width="6" height="21" border="0"></td>
	</tr>
  </table>

  <form action="" id="addcat_form" method="post" enctype="multipart/form-data" onsubmit="document.getElementById('addcat_button').disabled=true" style="position:absolute; width:100%;">
	<div style="width:100%; background:#F3F3F3" class="FLOAT_main">
	<table width="350" style="margin: 20px" border="0" cellspacing="0" cellpadding="2" class="text1">
	  <tr><td class="err" id="addcat_err"><?=$TMPL_errors?></td></tr>
	  
	  
	  <? for($i=0;$i<count($TMPL_lang);$i++){ ?> 
	  <tr>
		<td><input type="text" name="name[<?=$TMPL_lang[$i]?>]" id="name[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_name[$TMPL_lang[$i]]?>" class="formField1" style="width:90%">&nbsp;(<?=$TMPL_lang[$i]?>)</td>
	  </tr>
	  <?}?>
	  
	  <!-- image //-->
	  <tr><td class="err" style="cursor:pointer;" onCLick="showHide_image('sub_1')">Image</td></tr>
	  <tr>
	  	<td>
	  	  <table border="0" cellspacing="0" cellpadding="0" id="sub_1" style="display:none;">
	  	  <tr><td>
		  	  <table border="0" cellspacing="0" cellpadding="0" class="text1">
		  	  	<tr>
			  		<td><input type="text" name="image" readonly="readonly" id="image" value="<?=$TMPL_name[$TMPL_lang[$i]]?>" class="formField1" style="width:100%" onChange="javascript:cManager.showPreviewImage_cat(this.value,'imagePreview')"></td>
			  		<td style="width:1%;"><a href="javascript:mcImageManager.open('addcat_form','image','','',{relative_urls : true});"><img src="images/browse.gif" style="cursor:pointer; border:1px Solid #f2f2f2;" onMouseOver="this.style.border='1px Solid Blue'" onMouseOut="this.style.border='1px Solid #f2f2f2'" ></a></td>
			  		<td style="width:1%;"><a href="javascript: void(0);" onclick="javascript:document.getElementById('imagePreview').innerHTML = ''; document.getElementById('image').value = '';"><img src="images/icos16/delete.gif" style="cursor:pointer; border:1px Solid #f2f2f2;" onMouseOver="this.style.border='1px Solid Blue'" onMouseOut="this.style.border='1px Solid #f2f2f2'" ></a></td>
		  		</tr>
	  	  	  </tr>
	  	  	  </table>
	  	  </td></tr>
	  	  
	  	  <tr><td>
	  	  	  <div id="imagePreview" style="width:340px; height:100px; border:1px Solid Silver; overflow:auto">&nbsp;</div>
	  	  </td></tr>

  	  	  </table>
		</td>
  	  </tr>
	  <!-- image //-->
	  
	  <!-- image2 //-->
	  <tr><td class="err" style="cursor:pointer;" onCLick="showHide_image('sub_2')">Image2</td></tr>
	  <tr>
	  	<td>
	  	  
	  	  <table border="0" cellspacing="0" cellpadding="0" id="sub_2" style="display:none;">
	  	  <tr><td>	  	  
	  	  
	  	  <table border="0" cellspacing="0" cellpadding="0" class="text1">
	  	  	<tr>
		  		<td><input type="text" name="image2" readonly="readonly" id="image2" value="<?=$TMPL_name[$TMPL_lang[$i]]?>" class="formField1" style="width:100%" onChange="javascript:cManager.showPreviewImage_cat(this.value,'imagePreview2')"></td>
		  		<td style="width:1%;"><a href="javascript:mcImageManager.open('addcat_form','image2','','',{relative_urls : true});"><img src="images/browse.gif" style="cursor:pointer; border:1px Solid #f2f2f2;" onMouseOver="this.style.border='1px Solid Blue'" onMouseOut="this.style.border='1px Solid #f2f2f2'" ></a></td>
		  		<td style="width:1%;"><a href="javascript: void(0);" onclick="javascript:document.getElementById('imagePreview2').innerHTML = ''; document.getElementById('image2').value = '';"><img src="images/icos16/delete.gif" style="cursor:pointer; border:1px Solid #f2f2f2;" onMouseOver="this.style.border='1px Solid Blue'" onMouseOut="this.style.border='1px Solid #f2f2f2'" ></a></td>
	  		</tr>
  	  	  </tr>
  	  	  </table>
  	  	  
	  	  </td></tr>
	  	  
	  	  <tr><td>
  	  	  <div id="imagePreview2" style="width:340px; height:100px; border:1px Solid Silver; overflow:auto">&nbsp;</div>
	  	  </td></tr>

  	  	  </table>
  	  	  
		</td>
  	  </tr>
	  <!-- image2 //-->
	  
	  
	  <tr>
		<td style="padding: 0px 0px 0px 245px;" nowrap>
			<input type="button" id="addcat_button" onclick="this.form.submit()" value="  <?=$TMPL_button?>  " class="formButton1">&nbsp;&nbsp;
			<input type="button" onclick="cManager.hide()" value="<?=$TEXT['global']['cancel']?>" class="formButton4">
		</td>
	  </tr>
	</table>
	</div>
  <input type="hidden" name="cid" id="cid" value="<?=$TMPL_cid?>"> 
  <input type="hidden" name="action" id="action" value="<?=$TMPL_action?>">
  </form>
</div>






<div align="right" style="width:98%; margin-bottom:10px; border: 0px solid #000000;">
  <a class="basic" href="javascript:void(0);" onclick="cManager.showAddForm('1')">
    <img src="images/icos16/add.gif" width="16" height="16" border="0" align="absmiddle" title="Make root category"> <?=$TEXT['global']['root_cat']?>
  </a>
</div>
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="text1">
 <? for($i=0;$i<count($TMPL_cat);$i++){?>
    <? 
    	if ($_GET['m']=='receipts') $span = '<span style="cursor:pointer" onClick="window.location.href=\''.$SELF.'&date=view_all&cid='.$TMPL_cat[$i]['cat_id'].'\'">'; 
    	else $span = '';

    ?>
    
     <tr onmouseover="this.style.background='#F2F2F2'" onmouseout="this.style.background='#F8F8F8'">
       <td>
          <? echo $span.$TMPL_cat[$i]['spacer'].$TMPL_cat[$i]['name']?></span>
       </td>
       <td width="140">
         <a href="<?=$SELF?>&tab=categories&action=up&cid=<?=$TMPL_cat[$i]['cat_id']?>">
           <img src="./images/icos16/up.gif" width="16" height="16" border="0" alt="Move up" align="absbottom"></a>
         <a href="<?=$SELF?>&tab=categories&action=down&cid=<?=$TMPL_cat[$i]['cat_id']?>">
           <img src="./images/icos16/down.gif" width="16" height="16" border="0" alt="Move down" align="absbottom"></a>&nbsp;&nbsp;
         <a href="javascript:void(0)" onclick="cManager.showAddForm('<?=$TMPL_cat[$i]['cat_id']?>')">
           <img src="images/icos16/add.gif" width="16" height="16" border="0" alt="Make submenu" align="absbottom"></a>
         <a  href="javascript:cManager.showEditForm('<?=$TMPL_cat[$i]['cat_id']?>')">
           <img src="images/icos16/edit.gif" width="16" height="16" border="0" alt="Edit submenu" align="absbottom"></a>&nbsp;&nbsp;
         <a href="<?=$SELF?>&tab=categories&action=change_status&cid=<?=$TMPL_cat[$i]['cat_id']?>">
           <img src="./images/icos16/<?=($TMPL_cat[$i]['publish']==1?'protect-green':'protect-red')?>.gif" width="16" height="16" border="0" alt="Switch visibility" align="absbottom"></a>
         <a href="<?=$SELF?>&tab=categories&action=delete&cid=<?=$TMPL_cat[$i]['cat_id']?>">
           <img src="./images/icos16/delete.gif" width="16" height="16" border="0" alt="Delete" align="absbottom"></a>
       </td>
      </tr>
 <?}?>
</table>

<script type="text/javascript" language="javascript">
<!---->
SET_DHTML("addcat_div");
//dd.elements.addcat_div.show();
dd.elements.addcat_div.moveTo(dd.getWndW()/2-dd.elements.addcat_div.w/2,dd.getWndH()*0.2);
dd.elements.addcat_div.hide();
//document.getElementById('addcat_div').style.display = '<?=$TMPL_display?>';

td_sub_1  = document.getElementById('sub_1');
td_sub_2  = document.getElementById('sub_2');

function showHide_image(elmID) {
	if ( eval("td_"+elmID).style.display == 'block' ) {
		showHide(elmID);
		writeCookie(elmID,'0', 31104000);
	}
	else {
		showHide(elmID);
		writeCookie(elmID,'1', 31104000);
	}
}

if (readCookie('sub_1') == '1')
	document.getElementById('sub_1').style.display = "block";
if (readCookie('sub_2') == '1')
	document.getElementById('sub_2').style.display = "block";
</script>