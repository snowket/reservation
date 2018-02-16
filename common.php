<?
class CommonFunc{


    function backup_tables($backup_file_path,$host,$user,$pass,$name,$tables = '*')
    {

        $link = mysql_connect($host,$user,$pass);
        mysql_select_db($name,$link);

        //get all of the tables
        if($tables == '*')
        {
            $tables = array();
            $result = mysql_query('SHOW TABLES');
            while($row = mysql_fetch_row($result))
            {
                $tables[] = $row[0];
            }
        }
        else
        {
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
        $return="";
        //cycle through
        foreach($tables as $table)
        {
            $result = mysql_query('SELECT * FROM '.$table);
            $num_fields = mysql_num_fields($result);

            $return.= 'DROP TABLE '.$table.';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
            $return.= "\n\n".$row2[1].";\n\n";

            for ($i = 0; $i < $num_fields; $i++)
            {
                while($row = mysql_fetch_row($result))
                {
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j < $num_fields; $j++)
                    {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n","\\n",$row[$j]);
                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                        if ($j < ($num_fields-1)) { $return.= ','; }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }

        //save file
        $handle = fopen($backup_file_path,'w+');
        fwrite($handle,$return);
        fclose($handle);
    }

	function SetLang($langset,$sessName='lng'){
		global $_CONF;
		if(isset($_GET['lng']))
			$lang = $_GET['lng'];
		elseif(isset($_SESSION[$sessName]))
			$lang = $_SESSION[$sessName];
		elseif(isset($_COOKIE[$sessName]))
			$lang = $_COOKIE[$sessName];
		else
			return $_SESSION[$sessName] = $_CONF[$langset.'_def'];
		
		$_SESSION[$sessName] =$lang =$this->validLang($lang,$langset);
		$this->writeCookie($sessName,$lang);
		return $lang;
	}

	function validLang($lang,$langset){
		global $_CONF;
		return in_array($lang,$_CONF[$langset.'_all'])?$lang:$_CONF[$langset.'_def'];
	}

	function writeCookie($name, $value = "", $expires = ""){
		$expires =  time() + ($expires?$expires:60*60*24*365);
		@setcookie($name, $value, $expires,"/","");
	}  

	function deleteCookies($name=""){
		$expires=time()-3600;
		if($name)
			@setcookie ($name,"",$expires,"/","");
		elseif(!empty($_COOKIE)){
			foreach($_COOKIE as $name=>$value)
				@setcookie($name,"",$expires,"/","");
			}
	}

	function Redirect($location){
		header("Location: {$location}");
		exit;
	}

	function CompiledPass($password, $passcode){
		return md5(md5($password).md5($passcode));
	}

	function ServerError($filename,$line,$errmsg=""){
		CommonFunc::logError($filename,$line,$errmsg);
		exit($errmsg);
	}
	
	function logError($filename,$line,$errmsg=""){
        global $_CONF;
		$ErrMessage = "[".date("d.m.Y H:i")."] Error in {$filename} on line {$line} [{$errmsg}]\r\n";
		$fp= fopen($_CONF['path']['error_log'],"a");
		fwrite($fp, $ErrMessage);
		fclose($fp);
	  }

	function unpackData($packed_data,$lang=""){
		$unpacked = unserialize($packed_data);
		return $lang?$unpacked[$lang]:$unpacked;
	}

	function arraySearch($arr,$search_key,$search_val){
		for($i=0;$i<count($arr);$i++){
			if($arr[$i][$search_key]==$search_val)
			return $arr[$i];
		}
		return false;
	}
	
	function arrayIndex($arr,$search_key,$search_val){
		for($i=0;$i<count($arr);$i++){
			if($arr[$i][$search_key]==$search_val)
				return $i;
		}
		return false;
	} 

	function prepareSearch($query){
		$query = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $query);
		$query = trim(preg_replace("/\s(\S{1,2})\s/", " ", ereg_replace(" +", "  ",$query)));
		$query = ereg_replace(" +", " ", $query);
		return $query;
	}


	
	
	function DrawPageBar($URL,$result){
		global $TEXT;
		$str="";
		if ($result->_lastPageNo<2) return;


		$str='<div id="navbar"><div class="navbar_in"><span>';
		if($result->_currentPage!=1)
			$str.="<div style=\"float:left; padding:0px 0px 0px 0px;  width:37px;\"><a href=\"$URL".($result->_currentPage-1)."\" style=\"\"><img src=\"images/navbar/prev.png\" alt=\"Previous\" title=\"Previous\" /></a></div>";
		else $str.="<div style=\"float:left; padding:0px 0px 0px 0px;  width:37px;\"><img src=\"images/navbar/dis_prev.png\" alt=\"Previous\" title=\"Previous\" /></div>";
		

		if($result->_currentPage<5) {
			$sstart =0;
			$send =10;
		}
		else{
			$sstart=$result->_currentPage-5;
			$send  =$result->_currentPage+5;
		}

	if($send*$result->rowsPerPage>$result->_maxRecordCount)
		$send= ceil($result->_maxRecordCount/$result->rowsPerPage);


	// if($result->_lastPageNo%$result->rowsPerPage==0) $add=0;
	// else $add=1;

	for($i=$sstart;$i<$send;$i++) {
		if ($i==$result->_currentPage-1)
			#$str.="<td style=\"font-size:9px; font-weight:bold; font-family:Verdana; text-decoration:underline; color:#3b971b\" align=\"center\">".($i+1)."</td>";
			$str.="<div class=\"navbar_active\" ><div>".($i+1)."</div></div>";
		else $str.="<div onclick=\"top.location.href='$URL".($i+1)."'\" class=\"navbar_switcher\"><div >".($i+1)."</div></div>";
		}

		if($result->_currentPage<$result->_lastPageNo)
			$str.="<div style=\"float:left; width:37px;\"><a href=\"$URL".($result->_currentPage+1)."\" style=\"\"><img src=\"images/navbar/next.png\"  alt=\"Next\" title=\"Next\" /></a></div>";
		else                                      
			$str.="<div style=\"float:left; width:37px;\"><img src=\"images/navbar/dis_next.png\" alt=\"Next\" title=\"Next\" /></div>";

		#$str .= "</tr></table>";
		$str .= "<div style=\"clear:both\"></div></span></div></div>";
		return($str);
	}
	
	
	
	
	function DrawPageBar_($URL,$result){
		$str="";
		if ($result->_lastPageNo<2) return;

		$str ='<table cellpadding="0" cellspacing="0" border="0" class="pagenary"><tr>';

		if($result->_currentPage!=1)
			$str.="<td><a href=\"$URL".($result->_currentPage-1)."\" class=\"link2\">&laquo;</a></td>";
		else $str.="<td>&laquo;</td>";

		if($result->_currentPage<5) {
			$sstart =0;
			$send =10;
		}
		else{
			$sstart=$result->_currentPage-5;
			$send  =$result->_currentPage+5;
		}

	if($send*$result->rowsPerPage>$result->_maxRecordCount)
		$send= ceil($result->_maxRecordCount/$result->rowsPerPage);


	// if($result->_lastPageNo%$result->rowsPerPage==0) $add=0;
	// else $add=1;

	for($i=$sstart;$i<$send;$i++) {
		if ($i==$result->_currentPage-1)
			$str.="<td>".($i+1)."</td>";
		else $str.="<td><a href=\"$URL".($i+1)."\" class=\"link2\">".($i+1)."</a></td>";
		}

		if($result->_currentPage<$result->_lastPageNo)
			$str.="<td><a href=\"$URL".($result->_currentPage+1)."\">&raquo;</a></td>";
		else
			$str.="<td>&raquo;</td>";

		$str .= "</tr></table>";
		return($str);
	}
	
	function DrawPageBar_prod($URL, $result, $total_numbers = 5){
		global $TEXT;
		
		$out = '';
		
		#p($result);
		
		if ($result->_lastPageNo<2) return;
		
		$out ='<table class="pagenary"><tr>';
		
		// page selector
		$out .= '<td><div class="selector-container"><select name="pagejumper" onchange="top.location.href=this.value">';
		for ($i = 1; $i <= $result->_lastPageNo; $i++) {
			$out .= '<option value="'.$URL.$i.'" '.($i==$result->_currentPage ? 'selected' : '').'>'.$i.'</option>';
		}
		$out .= '</select></div></td>';
		
		if($result->_currentPage!=1) {
			$out .= '<td class="previous"><a href="'.$URL.($result->_currentPage-1).'" class="arrow_active">&laquo;</a></td>';
		}
		else {
			$out .= '<td class="previous"><a href="javascript:;" class="arrow_inactive">&laquo;</a></td>';
		}
		
		if($result->_currentPage<$total_numbers) {
			$sstart	= 0;
			$send	= $total_numbers * 2;
		}
		else{
			$sstart=$result->_currentPage - $total_numbers;
			$send  =$result->_currentPage + $total_numbers;
		}

		if($send*$result->rowsPerPage>$result->_maxRecordCount)
			$send= ceil($result->_maxRecordCount/$result->rowsPerPage);

		for($i=$sstart; $i<$send; $i++) {
			if ($i==$result->_currentPage-1) {
				$out .= '<td><a href="javascript:;" class="num active">'.($i+1).'</a></td>';
			}
			else {
				$out .= '<td><a href="'.$URL.($i+1).'" class="num inactive">'.($i+1).'</a></td>';
			}
		}

		if($result->_currentPage<$result->_lastPageNo) {
			$out .= '<td class="next"><a href="'.$URL.($result->_currentPage+1).'" class="arrow_active">&raquo;</a></td>';
		}
		else {
			$out .= '<td class="next"><a href="javascript:;" class="arrow_inactive">&raquo;</a></td>';
		}
		$out .= '</tr></table>';
		
		return $out;
	}
	
	function db_query_getRows($table,$columns,$where=false,$file_name=false,$line_num=false) {
		global $_CONF, $CONN, $FUNC;
		
		$file_name	= $file_name?$file_name:__FILE__;
		$line_num	= $line_num?$line_num:__LINE__;
		
		$where	= $where?' WHERE '.$where:'';
		$query	= 'SELECT '.$columns.' FROM '.$_CONF['db']['prefix'].$table.$where;
		$result = $CONN->Execute($query) or $FUNC->ServerError($file_name,$line_num,$CONN->ErrorMsg());
		$data	= $result->GetRows();
		return $data;
	}
	
	function db_query_setRow($table,$columns,$file_name=false,$line_num=false) {
		global $_CONF, $CONN, $FUNC;
		
		$file_name	= $file_name?$file_name:__FILE__;
		$line_num	= $line_num?$line_num:__LINE__;
		
		$query	= 'INSERT INTO '.$_CONF['db']['prefix'].$table.' SET '.$columns;
		$result = $CONN->Execute($query) or $FUNC->ServerError($file_name,$line_num,$CONN->ErrorMsg());
		return true;
	}
	
	function parseFilename($filename) {
		$extpos = @strrpos($filename,".");
		if($extpos>0&&$extpos<strlen($filename)-2)
			return array(substr($filename,0,$extpos),strtolower(substr($filename,$extpos+1)));
		else
			return false;
	}
	
	function get_user_agent_info() {
	    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
	        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	    } else if (!isset($HTTP_USER_AGENT)) {
	        $HTTP_USER_AGENT = '';
	    }

	    // 1. Platform
	    if (strstr($HTTP_USER_AGENT, 'Win')) {
	        $out['OS'] = 'Win';
	    } else if (strstr($HTTP_USER_AGENT, 'Mac')) {
	        $out['OS'] = 'Mac';
	    } else if (strstr($HTTP_USER_AGENT, 'Linux')) {
	        $out['OS'] = 'Linux';
	    } else if (strstr($HTTP_USER_AGENT, 'Unix')) {
	        $out['OS'] = 'Unix';
	    } else if (strstr($HTTP_USER_AGENT, 'OS/2')) {
	        $out['OS'] = 'OS/2';
	    } else {
	        $out['OS'] = 'Other';
	    }

	    // 2. browser and version
	    // (must check everything else before Mozilla)
	    if (preg_match('@Opera(/| )([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version)) {
	        $out['BROWSER_VER']		= $log_version[2];
	        $out['BROWSER_AGENT']	= 'OPERA';
	    } else if (preg_match('@MSIE ([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version)) {
	        $out['BROWSER_VER']		= $log_version[1];
	        $out['BROWSER_AGENT']	= 'IE';
	    } else if (preg_match('@OmniWeb/([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version)) {
	        $out['BROWSER_VER']		= $log_version[1];
	        $out['BROWSER_AGENT']	= 'OMNIWEB';
	    //} else if (ereg('Konqueror/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
	    // Konqueror 2.2.2 says Konqueror/2.2.2
	    // Konqueror 3.0.3 says Konqueror/3
	    } else if (preg_match('@(Konqueror/)(.*)(;)@', $HTTP_USER_AGENT, $log_version)) {
	        $out['BROWSER_VER']		= $log_version[2];
	        $out['BROWSER_AGENT']	= 'KONQUEROR';
	    } else if (preg_match('@Mozilla/([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version)
	               && preg_match('@Safari/([0-9]*)@', $HTTP_USER_AGENT, $log_version2)) {
	        $out['BROWSER_VER']		= $log_version[1].'.'.$log_version2[1];
	        $out['BROWSER_AGENT']	= 'SAFARI';
	    } else if (preg_match('@Mozilla/([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version)) {
	        $out['BROWSER_VER']		= $log_version[1];
	        $out['BROWSER_AGENT']	= 'MOZILLA';
	    } else {
	        $out['BROWSER_VER']		= 0;
	        $out['BROWSER_AGENT']	= 'OTHER';
	    }
	    
	    return $out;
	}
	
	
	
	

	function Send_Email_u($email, $Subject, $Message, $type="text/plan", $charset="utf-8", $From_name, $From_email, $attachment_input_name=false, $allowattachments=false) {
		define('EMAIL_CRLF', "\r\n");
		if(!empty($_FILES[$attachment_input_name]['tmp_name']) AND $allowattachments) {
			$attachment = true;
			$boundary   = uniqid("");

			// figure our the MIME type of the file, defaulting to 'unknown'
			$MIMEType = $_FILES[$attachment_input_name]['type'] ? $_FILES[$attachment_input_name]['type'] : 'application/unknown';

			if(filesize($_FILES[$attachment_input_name]['tmp_name']) == 0)
				die ('email not sent - "!filesize.."');
			else {
				// Open the uploaded file
				$fp = @fopen($_FILES[$attachment_input_name]['tmp_name'], "r");
				// Read the entire file into a variable
				$read = @fread($fp, filesize($_FILES[$attachment_input_name]['tmp_name']));
				// Base64 encode the file so it can be read by mail programs
				$read = base64_encode($read);
				// Split the long Base64 string to lots of small chunks
				$read = chunk_split($read);
			}

			$filename = $_FILES[$attachment_input_name]['name'];
			$message  = stripslashes($Message);

			// Create the mail body
			$msgbody  = "--$boundary" . EMAIL_CRLF;
			$msgbody .= "Content-type: ".$type."; charset=". $charset . EMAIL_CRLF;
			$msgbody .= "Content-transfer-encoding: 8bit". EMAIL_CRLF;
			$msgbody .= EMAIL_CRLF;
			$msgbody .= "$message" . EMAIL_CRLF;
			$msgbody .= "--$boundary" . EMAIL_CRLF;
			$msgbody .= "Content-type: $MIMEType; name=$filename" . EMAIL_CRLF;
			$msgbody .= "Content-disposition: attachment; filename=$filename" . EMAIL_CRLF;
			$msgbody .= "Content-transfer-encoding: base64" . EMAIL_CRLF;
			$msgbody .= EMAIL_CRLF;
			$msgbody .= "$read" . EMAIL_CRLF;
			$msgbody .= EMAIL_CRLF;
			$msgbody .= "--$boundary--" . EMAIL_CRLF;
		}

		if(!isset($errors))	{
			if(!empty($_FILES[$attachment_input_name]['tmp_name']) && $attachment) {
				// Send the attachment form of the email rather than the normal text form
				$headers  = "MIME-Version: 1.0" . EMAIL_CRLF;
				$headers .= "From: \"".$From_name."\" <".$From_email.">" . EMAIL_CRLF;
				$headers .= "Reply-To: \"".$From_name."\" <".$From_email.">" . EMAIL_CRLF;
				$headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"" . EMAIL_CRLF;
			}
			else {
				// No file to send, so just send a normal text email
				$headers  = "MIME-Version: 1.0" . EMAIL_CRLF;
				$headers .= "From: \"".$From_name."\" <".$From_email.">" . EMAIL_CRLF;
				$headers .= "Reply-To: \"".$From_name."\" <".$From_email.">" . EMAIL_CRLF;
				$headers .= "Content-type: ".$type."; charset=". $charset . EMAIL_CRLF;

				$msgbody  = $Message;
			}

			if(!@mail($email, $Subject, $msgbody, $headers))
			#if(!@mb_send_mail($email, unhtmlspecialchars($Subject), unhtmlspecialchars($msgbody), $headers))
				die ('mail sent faild');
		}
		else {
			foreach($errors as $key=>$value) {
				echo $value . '<br /><br />';
			}
		}
		return true;
	}
	
	function get_modul_id($modul_name) {
		global $_CONF, $CONN, $FUNC;
		$query		= "SELECT cat_id FROM {$_CONF['db']['prefix']}_sitemenu WHERE structure LIKE '%\"{$modul_name}\"%' LIMIT 1";
		$result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$data		= $result->GetRows();
		$mod_id		= $data[0]['cat_id'];
		
		return $mod_id;
	}
	
	function DateUnixFormat(){
		if ($_GET['date'] && preg_match('/\d{4}\.\d{1,2}\.\d{1,2}/', $_GET['date'])){
			$d     = explode('.', $_GET['date']);
			$_date = sprintf('%04d-%02d-%02d', $d[0],$d[1],$d[2]);
		}
		else{
			$_date = date('Y').'-'.date('m').'-'.date('d');
		}
		return $_date;
	}
}

function httpPost($k){
  return isset($_POST[$k]) ? $_POST[$k] : '';
}

function httpGet($k){
  return isset($_GET[$k]) ? $_GET[$k] : '';
}



class global_user_functions {
	static function delete_expired_sessions() {
		global $_CONF, $CONN, $FUNC;
		
		$query  = "DELETE FROM {$_CONF['db']['prefix']}_sessions WHERE expiry<".mktime();
		$result = $CONN->Execute($query) OR $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$data	= $result->_query();
		
		return true;
	}
	
	static function create_file($filename, $content) {
	    if (!$handle = fopen($filename, 'w')) {echo "Can't open file - ($filename)";/*exit;*/}
	    if (fwrite($handle, $content) === FALSE) {echo "Can't write to file - ($filename)";/*exit;*/}
	    fclose($handle);
	    return true;
	}
}


?>