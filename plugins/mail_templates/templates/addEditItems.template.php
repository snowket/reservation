<?php #p2($items); ?>

<style>
	#addEditForm {}
	#addEditForm table.main {border-spacing:1px;}
	#addEditForm table.main th {padding:5px; text-align:right; vertical-align:middle;}
	#addEditForm table.main td {padding:1px; margin:0;}
	#addEditForm table.main .currency {text-transform:uppercase; font-style:italic; color:#ccc; font-weight:bold;}
	
	#addEditForm .main {}
	#addEditForm .main .name input {padding:3px; border:0; width:450px;}
	#addEditForm .main .cost input {padding:3px; border:0; width:55px; text-align:center;}
	#addEditForm .main img {width:22px;}
	
	#addEditForm .currency {color:#f9f9f9;}
	
	#errorMessages {margin:10px 0;}
	#errorMessages .row {/*float:left;*/ padding:5px; border-bottom:0px solid #ff0000; background:#FDBABA;}
	#errorMessages .row .title {color:#ff0000; font-weight:bold;}
	#errorMessages .row .msg {/*color:#ff0000;*/}
</style>

<?php
	if ($error) {
		echo '<div id="errorMessages">';
		
		foreach ($errorMessages AS $errorMessage) {
			echo '<div class="row"><b class="title">Error:</b> <span class="msg">'.$errorMessage.'</span></div><div class="clear"></div>';
		}
		
		echo '</div>'; 
	}
	
	#p($record);
?>

<form id="addEditForm" name="addEditForm" action="" method="post" enctype="multipart/form-data">
	<?php if (!$readonly) { ?>
	<input type="hidden" name="action" value="update" />
	<?php } ?>
	
	<table class="main">
		<thead>
		<tr>
			<th class="tdrow2">Name</th>
			<th class="tdrow2">Cost</th>
			<th class="tdrow2">
				<?php if (!$readonly) { ?>
				<a href="javascript:ParcelItemAppend();"><img src="../images/_fromusaus/icons/plus.png" alt="<?php echo $TEXT['global']['add']?>" title="<?php echo $TEXT['global']['add']?>" /></a>
				<?php } else { echo '&nbsp;'; } ?>
			</th>
		</tr>
		</thead>
		
		<tbody>
		</tbody>
		
		<?php if (!$readonly) { ?>
		<tfoot>
		<tr>
			<th class="tdrow2" colspan="3"><input type="submit" name="" value="update" class="formButton2" /></th>
		</tr>
		</tfoot>
		<?php } ?>
	</table>
</form>

<script type="text/javascript" charset="utf-8">
	var parcelItemsReadonly = '<?php echo $readonly ?>';
	var parcelItemsCount = 0;
	var parcelItemsIndex = 0;
	var parcelItemHtmlTemplate = ''
			+ '<tr>'
			+ '	<td class="tdrow2 name"><input type="text" name="o[items][name][]" value="{{NAME}}" /></td>'
			+ '	<td class="tdrow2 cost"><input type="text" name="o[items][cost][]" value="{{COST}}" /> <span class="currency"><?php echo CORE::$MODULES->USER->currencies[CORE::$FRONT->settings["parcel_item_default_currency"]] ?></span></td>'
			+ '	<td class="tdrow2"><a href="javascript:void(0);" onclick="$(this).parent().parent(\'tr\').fadeOut().remove(); --parcelItemsCount;"><img src="../images/_fromusaus/icons/minus.png" alt="<?php echo $TEXT["global"]["delete"]?>" title="<?php echo $TEXT["global"]["delete"]?>" /></a></td>'
			+ '</tr>';
			
	var parcelItemHtmlTemplateReadonly = ''
		+ '<tr>'
		+ '	<td class="tdrow2 name" style="padding:5px;">{{NAME}}</td>'
		+ '	<td class="tdrow2 cost" style="padding:5px;">{{COST}} <span class="currency"><?php echo CORE::$MODULES->USER->currencies[CORE::$FRONT->settings["parcel_item_default_currency"]] ?></span></td>'
		+ '	<td class="tdrow2" style="padding:5px;">&nbsp;</td>'
		+ '</tr>';
	
	function ParcelItemAppend(dataObj) {
		var _newItemHtml = parcelItemsReadonly ? parcelItemHtmlTemplateReadonly : parcelItemHtmlTemplate;

		if (parcelItemsReadonly && !dataObj) {
			return;
		}

		var dataObj = dataObj || {"name":"", "cost":""}
		
		var _keyFields = {
			"title":"{{NAME}}",
			"cost":"{{COST}}"
		}
		//console.log(_keyFields);
		//console.log((dataObj));
		//console.log($.parseJSON(dataObj));
		
		try {
			for (_kf in _keyFields) {
				//console.log(_keyFields[_kf]);
				
				_newItemHtml = _newItemHtml.replace(_keyFields[_kf], (dataObj[_kf] || ""));
			}
			
			$("#addEditForm table.main").prepend(_newItemHtml).find("input[type=text]:eq(0)").focus();
			
			++parcelItemsIndex;
			++parcelItemsCount;
		}
		catch(e) {alert(e.message);}
	}
	
	<?php
		if (is_array($items)) {
			foreach ($items AS $i => $item) {
				echo 'ParcelItemAppend('.json_encode($item).');'."\r\n";
			}
		}
		
		if (!$items)
			echo 'ParcelItemAppend();';
	?>
</script>

<script>$('td:empty').html('&nbsp;');</script>