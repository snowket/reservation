<?if(!defined('ALLOW_ACCESS')) exit;?>
<?
	
$out  = '<form name="thisform" action="'.$SELF.'" method="post" enctype="multipart/form-data">';
$out .= '<table width="600" align="center" cellspacing="0" cellpadding="5" border="0">';

for ($i=0;$i<count($data);$i++) {
	if ($data[$i]['type']=='' || $data[$i]['type']=='text') {
		$out .= '<tr>';
		$out .= '<td valign="middle" class="tdrow2" nowrap width="45%"><span class="text1">'.$data[$i]['title'].':</span></td>';
		$out .= '<td valign="middle" class="tdrow3"><input class="formField1" type="text" name="'.$data[$i]['input_name'].'" '.$data[$i]['input_param'].' value="'.$data[$i]['value'].'">&nbsp;<span style="color:red;">*</span>';
		$out .= '</td>';
		$out .= '</tr>';
	}
	if ($data[$i]['type']=='select') {
		$default_values = explode(",",$data[$i]['default']); 
		
		$out .= '<tr>';
		$out .= '<td valign="middle" class="tdrow2"><span class="text1">'.$data[$i]['title'].':</span></td>';
		$out .= '<td valign="middle" class="tdrow3">';
		$out .= '<select size="1" name="'.$data[$i]['input_name'].'" '.$data[$i]['input_param'].' class="formField2">';
		
	   	   foreach ($default_values as $m=>$n) {
		     $selected=($n==$data[$i]['value'])?"selected":"";
		     $out.='<option value="'.$n.'" '.$selected.'>'.$n.'</option>'; 
	   	   }
		   	   
		$out .= '</select>&nbsp;<span style="color:red;">*</span>';
		$out .= '</td>';
		$out .= '</tr>';
	}
}

$out .= '<tr>';
$out .= '<td colspan="2" align="center" valign="top" height="20">';
$out .= '<input class="formButton1" type="submit" name="update" value="   save   "></td>';
$out .= '<input type="hidden" name="action" value="submit">';
$out .= '</tr>';

$out .= '</table>';
$out .= '</form>';

echo $out;

?>