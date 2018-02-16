<?
set_time_limit(0);
#error_reporting(1);
#p($_POST);

### UPDATE Prices FROM file.dbf
/*
if($_POST['action']=='update_prices_dbf'){
	if ($_FILES['dbf'] && $_FILES['dbf']['name']) {
		$parseFilename = $FUNC->parseFilename($_FILES['dbf']['name']);
		if ($parseFilename[1]!='dbf') echo '<script>alert("wrong filetype");</script>';
		else {
			$db = dbase_open($_FILES['dbf']['tmp_name'], 0);
			if ($db) {
				#p($_POST);
				#p($_FILES);
				$COL1 = $_POST['column_name1']; // Related	column name	- KOD_MED
				$COL2 = $_POST['column_name2']; // Related	column name	- KOD_MANUF
				$COL3 = $_POST['column_name3']; // Price	column name	- PRICE_USD
				$COEF = (float)$_POST['coefficient'];
				# Check column names
				$db_info = dbase_get_header_info($db);
				if (!$FUNC->arraySearch($db_info,'name',$COL1) || !$FUNC->arraySearch($db_info,'name',$COL2) || !$FUNC->arraySearch($db_info,'name',$COL3)) {
					echo '<script>alert("wrong columns names");</script>';
				}
				else {
					$record_numbers = dbase_numrecords($db);
					for ($i=1; $i<=$record_numbers; $i++) {
						$row = dbase_get_record_with_names($db, $i);
						if ($row[$COL1] && $row[$COL1]) {
							$query = "UPDATE {$_CONF['db']['prefix']}_prod SET price='".((float)$row[$COL3]*$COEF)."' WHERE code='".trim($row[$COL1])."' AND mcode='".trim($row[$COL2])."'";
							$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
							#echo '<br>';
						}
					}
				}
				dbase_close($db);
				$FUNC->Redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}
	else
		$FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
*/
### ElitEl v1
/*
$delimiter = ',';
if($_POST['action']=='update_prices'){
	if ($_FILES['price_file'] && $_FILES['price_file']['name']) {
		$parseFilename = $FUNC->parseFilename($_FILES['price_file']['name']);
		if ($parseFilename[1]!='csv') echo '<script>alert("wrong filetype");</script>';
		else {
			$dbf = fopen ($_FILES['price_file']['tmp_name'],"r");
			while (!feof ($dbf)) {
				$data	= fgetcsv($dbf, 1000, $delimiter);
				$code	= $data[0];
				$price	= floatval($data[1]);
				$quantity = intval($data[2]);
				
				if (!$code) continue;
				
				$query = "UPDATE {$_CONF['db']['prefix']}_prod SET ".($_POST['update_price_old']?"price_old=price":"").", price=$price, quantity='{$quantity}' WHERE code='{$code}'";
				#p($query);
				$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
			}
			fclose ($dbf);
			$FUNC->Redirect($_SERVER['HTTP_REFERER']);
		}
	}
	else {
		$FUNC->Redirect($_SERVER['HTTP_REFERER']);
	}
}
*/

### ElitEl v1
#$delimiter = ',';
$delimiter = ';';
if($_POST['action']=='update_prices'){
	if ($_FILES['price_file'] && $_FILES['price_file']['name']) {
		$parseFilename = $FUNC->parseFilename($_FILES['price_file']['name']);
		if ($parseFilename[1]!='csv') echo '<script>alert("wrong filetype");</script>';
		else {
			$dbf = fopen ($_FILES['price_file']['tmp_name'],"r");
			while (!feof ($dbf)) {
				$data	= fgetcsv ($dbf, 1000, $delimiter);
				#$code	= trim($data[0]).";".trim($data[1]);
				$code	= mysql_real_escape_string(trim($data[0]).";".trim($data[1]));
				$quantity = intval($data[2]);
				$price	= floatval($data[3]);
				
				if (!$code) continue;
				
				$query = "UPDATE {$_CONF['db']['prefix']}_prod SET ".($_POST['update_price_old']?"price_old=price,":"")." price=$price, quantity='{$quantity}' WHERE code='{$code}'";
				$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
			}
			fclose ($dbf);
			$FUNC->Redirect($_SERVER['HTTP_REFERER']);
		}
	}
	else {
		$FUNC->Redirect($_SERVER['HTTP_REFERER']);
	}
}





### UPDATE COLUMN sort_id IN DB
/*else*/if($_POST['action']=='update_sort_id'){
	foreach ($_POST['price_id'] as $k => $v) {
		$query = "UPDATE {$_CONF['db']['prefix']}_prod SET 
					price='{$v}', code='{$_POST['code'][$k]}', mcode='{$_POST['mcode'][$k]}'
					WHERE id='{$k}'";
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	}
	$FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### Deleting a product  *********************************************//  
elseif($_GET['action']=='delete'){ 
	$id = StringConvertor::toNatural($_GET['id']);
	$query  = "SELECT introimg, creator from {$_CONF['db']['prefix']}_prod WHERE id='$id'";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
	if(!$result->fields)
		$FUNC->Redirect($SELF); 
	if(!$LOADED_PLUGIN['restricted']||($result->fields['creator']==$_SESSION['id'])){
		### deleting gallery if exists
		if(file_exists($imgDIR.'/'.$id)){
			$FM = new FileManager($imgDIR.'/'.$id);
			if(!$FM->RemoveDir($imgDIR.'/'.$id))
				$FUNC->ServerError(__FILE__,__LINE__,"Failed to delete gallery for product {$id}");
			
			$query = "delete from {$_CONF['db']['prefix']}_prod_gal where rec_id='$id'"; 
			$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		}
         
		### deleting intro image if exists
		@unlink($imgDIR."/thumb_".$result->fields['introimg']);
		@unlink($imgDIR."/".$result->fields['introimg']);

		### deleting product options
		//$query = "delete from {$_CONF['db']['prefix']}_prod_options_fill  where pid='$id'";
		//$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

		### deleting  product info
		$query = "delete from {$_CONF['db']['prefix']}_prod_info  where rec_id ='$id'";
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$query = "delete from {$_CONF['db']['prefix']}_prod  where id ='$id'";
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    }
	$FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### Changing product visibility
elseif($_GET['action']=="change_status"&&isset($_GET['id'])){
	$id = StringConvertor::toNatural($_GET['id']);
	$query = "UPDATE {$_CONF['db']['prefix']}_prod SET publish=if(publish=1,0,1) WHERE id ='{$id}'";
	$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### Changing product visibility
elseif($_GET['action']=="change_status2"&&isset($_GET['id'])){
	$id = StringConvertor::toNatural($_GET['id']);
	$query = "UPDATE {$_CONF['db']['prefix']}_prod SET publish_basket=if(publish_basket=1,0,1) WHERE id ='{$id}'";
	$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	$FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### Printing list of added products in selected category
else { //if($_GET['action'] == "view"){
	$cid = StringConvertor::toNatural($_GET['cid']);
	$errors = '';
	
	### Search
	if(isset($_GET['kw'])){
		$_GET['kw'] = urldecode($_GET['kw']);
		$kw = $FUNC->prepareSearch($_GET['kw']);
		if(strlen($kw)>0){
			if($_GET['param']=='code')
				$query_end = "t1.code = '$kw' and t2.lang ='".C_LANG."'";
			elseif($_GET['param']=='intro')  
				$query_end = "t2.intro like '%". str_replace(" ", "%' OR t2.intro LIKE '%", $kw). "%'";
			else 
				#$query_end = "t2.title like '".$kw."%'";
				$query_end = "t2.title like '". str_replace(" ", "%' OR t2.title LIKE '%", $kw). "%'";
		}
		else
			$errors = $TEXT['global']['bad_query']; 
		$pagenary_uri  = "kw={$kw}&param={$_GET['param']}";       
	}
	else if ($_GET['promos']) {
		$query_end = "t1.promo=1";
		$pagenary_uri  = "promos=1";
	}
	### Filtering by categories  
	else{
		$query_end =  "t1.{$FIELD} = '{$cid}' and t2.lang ='".C_LANG."'";
		$pagenary_uri  = "cid={$cid}";  
	}
	
	if(empty($errors)){
		if (isset($_GET['catidIsNull'])) {
			$query = "SELECT t1.* FROM {$_CONF['db']['prefix']}_prod t1
						LEFT JOIN {$_CONF['db']['prefix']}_categories t2 ON t1.cat_id = t2.cat_id
						WHERE t2.cat_id IS NULL"; # OR t2.publish=0
		}
		else {
			$query = "SELECT t1.*, t2.title, t2.intro FROM {$_CONF['db']['prefix']}_prod t1
					  LEFT JOIN {$_CONF['db']['prefix']}_prod_info t2 ON t1.id = t2.rec_id
					  WHERE {$query_end} GROUP by t1.id ORDER BY t1.sort_id ASC, t1.id DESC";
		}
		#p($query);
		$result = $CONN->PageExecute($query,20,$_GET['p']) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    	
    	function substring($string,$i,$n) {
    		return function_exists('mb_substr')?mb_substr($string,$i,$n,"UTF-8"):substr($string,$i,$n);
    	}
    	
		while($row = $result->FetchRow()){
			$items[] = array(
					'id'			=>  $row['id'],
					'sort_id'		=>  $row['sort_id'],
					'code'			=>  $row['code'],
					'mcode'			=>  $row['mcode'],
					'img'			=>  $row['introimg'],
					'promo'			=>  $row['promo'],
					'title'			=>  $row['title'],
					'price'			=>  $row['price'],
					'publish'		=>  $row['publish'],
					'publish_basket'=>  $row['publish_basket'],
					'blocked'		=>  ($LOADED_PLUGIN['restricted']&&$row['creator']!=$_SESSION['id'])?true:false,
					'intro'			=>  substring($row['intro'],0,200),
				); 
		}
		if(count($items)>-1){
			$TMPL->addVar('TMPL_conf',$_CONF);
			$TMPL->addVar('TMPL_imgdir',$imgDIR);
			$TMPL->addVar('TMPL_items',$items);
			$url = '?m='.$_GET['m'].'&tab=view';
			if (isset($_GET['catidIsNull'])) {
				 $url .= '&catidIsNull';
			}
		
		    $TMPL->addVar('TMPL_pagebar', $FUNC->DrawPageBar($url.'&p=',$result));
			$TMPL->parseIntoVar($_CENTER,'view_productlist');
		}   
		else{
			$errors = $TEXT['global']['no_info'];
		}
	}
	if($errors!=''){
		$TMPL->addVar('TMPL_error',$errors);
		$TMPL->parseIntoVar($_CENTER,'error');
	}
}

?>  