<!--
<form action="index.php" method="get" id="searchform">
	<input type="hidden" name="m"		value="products">
	<input type="hidden" name="action"	value="view">
	<input type="hidden" name="tab"		value="<? if ($_GET['tab']=='view') echo 'view'; else ''; ?>">
	
	<div align="center" class="text1"><b><?=$TEXT['global']['search']?></b></div>
	<input type="text" name="kw" value="<?=$TEXT['global']['keywords']?>" style="width:150px" class ="formField1" 
		onfocus="if (this.value == '<?=$TEXT['global']['keywords']?>') this.value='';" onblur="if (this.value == '') this.value='<?=$TEXT['global']['keywords']?>';"
	>

	<table width="150" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td>
				<select size="1" name="param" class="formField1" style="width:130px;">
					<option value="title"><?=$TEXT['prod']['title']?></option>
					<option value="intro"><?=$TEXT['prod']['intro']?></option>
					<option value="code"><?=$TEXT['prod']['prodID']?></option>
				</select>    
			</td>
			<td style="border:1px solid #fff;width:20px" onmouseover="this.style.borderColor='#F2F2F2'" onmouseout="this.style.borderColor='#fff'">
				<img src="./images/icos16/browse.gif" width="16" height="16" border="0" style="cursor:pointer" onclick="document.getElementById('searchform').submit()"><br>
			</td>
		</tr>
	</table>
</form>//-->