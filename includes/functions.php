<?
if (!defined('ALLOW_ACCESS')) {die('.');}


function BuildBlock($block_id,$moduls=array(),$folder="moduls"){
	#global $tmpl,$_CONF,$SITE_MENU,$TEXT,$CONN,$FUNC,$TEMPLATE,$m,$VALIDATOR,$sess,$sess_array,$ACTIVE;
	global $_CONF,$TEXT,$CONN,$FUNC,$TEMPLATE,$m,$VALIDATOR,$sess,$sess_array,$ACTIVE;
	global $tpl, $DESIGN;

	$GLOBALS['SIDE']=$block_id;
	for($_i=0, $_n=count($moduls); $_i<$_n; $_i++){
		$parts = explode('?',$moduls[$_i]);
		if(file_exists($folder.'/'.$parts[0].'.php')){
			if($parts[1]){parse_str($parts[1]);}
			require($folder.'/'.$parts[0].'.php');
		}
		else if(is_numeric($parts[0])){
			$CONTENT_ID = $parts[0];
			require($folder.'/content.php');
		}
	}
}
function getChanellSettings(){
    global $CONN,$FUNC;
    $query	= "SELECT * FROM cms_channel_settings WHERE publish='1' ORDER BY orderid,id";
    $result	= $CONN->Execute($query)or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $data	= $result->GetRows();

    for ($i=0;$i<count($data);$i++) {
        $out[$data[$i]['input_name']] = $data[$i]['value'];
    }

    return $out;
}


function makeUri($node){
	return (empty($node['redirect']) ? '?m='.$node['cat_id'] : $node['redirect']);
}

function activeMenuId($id){
	global $SITE_MENU;
	for($i=0;$i<count($SITE_MENU);$i++){
		if($id==$SITE_MENU[$i]['cat_id'])
			return $i;
	}
	return false;
}

function parentId($active,$level){
	global $SITE_MENU;
	for($i=0,$n=count($SITE_MENU); $i<$n; $i++) {
		if($SITE_MENU[$i]['cat_left']<=$active['cat_left']&&$SITE_MENU[$i]['cat_right']>=$active['cat_right']&&$SITE_MENU[$i]['cat_level']==$level)
			return $i;
	}
	return false;
}

function nextVisible($current,$currlevel){
	global $SITE_MENU;
	for($i=$current+1,$n=count($SITE_MENU); $i<$n; $i++){
		if($SITE_MENU[$i]['cat_level']<=$currlevel && $SITE_MENU[$i]['publish']==1 && $SITE_MENU[$i]['type']=='main')
			break;
	}
	return $i;
}

function drawImage($img){
	if(empty($img)) return "";
	$img ="<tr><td><img src=\"../uploads_script/sitemenu/{$img}\" width=\"100%\" border=\"0\"></td></tr>
		   <tr><td class=\"horz_border\"><img src=\"./images/dot.gif\" width=\"1\" height=\"1\"></td></tr>";
	return $img;
}

// ################################ SEND EMAIL #################################
function send_mail($to, $from,$sendername,$subject, $message){
   $headers = "MIME-Version: 1.0\r\n";
   $headers .= "Content-type: text/html; charset=utf-8\r\n";
   $headers .="From:{$sendername}<{$from}>\n";
   $headers .= "Reply-To: {$from}\n";
   if (!mail($to, $subject, $message, $headers)) return false;
   else return true;
}


function Send_Email_u($email, $Subject, $Message, $type="text/plain", $charset="utf-8", $From_name="", $From_email="", $attachment_input_name=false, $allowattachments=false,$attachment_type = 'upload') {
	global $_CONF;
	if(isset($_SESSION['site_settings'])){
	   $site_settings = $_SESSION['site_settings'];
	}
	else{
    	$site_settings = settings();
	}



	$From_name	= $From_name  ? $From_name  : $site_settings['fromname'];
	$From_email	= $From_email ? $From_email : $site_settings['frommail'];

	if($site_settings['send_type'] == 'smtp'){
         $mailer = new Mailer('smtp',array(
                         'server'    =>   $_CONF['SMTP']['HOST'],
                         'port'      =>   $_CONF['SMTP']['Port'],
                         'authtype'  =>   'smtp',
                         'authid'    =>  $_CONF['SMTP']['Username'],
                         'authpwd'   =>   $_CONF['SMTP']['Password']
                   ));
    }
    else{
         $mailer = new Mailer('mail');
    }

    $mailer->Open(array(
        'mailfrom'   => $From_email,
        'namefrom'   => $From_name,
        'format'     => 'html',
        'subject'    => $Subject,
        'inlineImg'  => true
    ));


	if(attachment_type=='upload'){
	   if(!empty($_FILES[$attachment_input_name]['tmp_name']) AND $allowattachments){
	       $mailer->attach($_FILES[$attachment_input_name]['tmp_name']);
	   }
    }
    else{
        $mailer->attach($attachment_input_name);
    }

   	$mailer->addBody($Message);
	$mailer->addTo($email);
    $mailer->prepareMessage();
    $mailer->Send();

    return true;
}


function Send_Email_u_($email, $Subject, $Message, $type="text/plain", $charset="utf-8", $From_name, $From_email, $attachment_input_name=false, $allowattachments=false) {
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


// ###	get site settings
function settings() {
	global $CONN, $_CONF,$FUNC;

	$query	= "SELECT * FROM {$_CONF['db']['prefix']}_settings WHERE publish='1' ORDER BY orderid,id";
	$result	= $CONN->Execute($query)or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data	= $result->GetRows();

	for ($i=0;$i<count($data);$i++) {
		$out[$data[$i]['input_name']] = $data[$i]['value'];
	}

	return $out;
}

function getHotelSettings() {
    global $CONN, $_CONF, $FUNC;

    $query	= "SELECT * FROM {$_CONF['db']['prefix']}_hotel_settings WHERE publish='1' ORDER BY orderid,id";
    $result	= $CONN->Execute($query)or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $data	= $result->GetRows();

    for ($i=0;$i<count($data);$i++) {
        $out[$data[$i]['input_name']] = $data[$i]['value'];
    }

    return $out;
}

function dbtree_info($cat_id) {
	global $_CONF, $CONN, $FUNC;
	$query	= "SELECT * FROM {$_CONF['db']['prefix']}_sitemenu WHERE cat_id={$cat_id}";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$menu	= $result->GetRows();
	$menu	= $menu[0];
	$menu['name']	= $FUNC->unpackData($menu['name'],LANG);
	$menu['title']	= $FUNC->unpackData($menu['title'],LANG);

	return $menu;
}

function get_modul_id($modul_name) {
	global $_CONF, $CONN, $FUNC;
	$query		= "SELECT cat_id FROM {$_CONF['db']['prefix']}_sitemenu WHERE structure LIKE '%\"{$modul_name}\"%' LIMIT 1";
	$result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data		= $result->GetRows();
	$mod_id		= $data[0]['cat_id'];

	return $mod_id;
}

function get_plugin_id($modul_name) {
	global $_CONF, $CONN, $FUNC;
	$query		= "SELECT id FROM {$_CONF['db']['prefix']}_pcms_plugins WHERE plugin = '".$modul_name."' LIMIT 1";
	$result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data		= $result->GetRows();
	$pid		= $data[0]['id'];

	return $pid;
}

function get_modul_sitemenu_block($modul_name) {
	global $_CONF, $CONN, $FUNC;
	$query		= "SELECT * FROM {$_CONF['db']['prefix']}_sitemenu_blocks WHERE name='{$modul_name}' LIMIT 1";
	$result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data		= $result->GetRows();
	$mod_id		= $data[0];

	return $mod_id;
}

function dbtree_parent_info($cat_id) {
	global $_CONF, $CONN, $FUNC;

	$dbtree = new dbtree($_CONF['db']['prefix']."_sitemenu", "cat", $CONN);
	$menu_parent = $dbtree->GetParentInfo($cat_id);

	if ($menu_parent['cat_level']==0) {
		$query	= "SELECT * FROM {$_CONF['db']['prefix']}_sitemenu WHERE cat_id={$cat_id}";
		$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$menu_parent =$result->GetRows();
		$menu_parent =$menu_parent[0];
	}
	$menu_parent['name'] = $FUNC->unpackData($menu_parent['name'],LANG);
	$menu_parent['title'] = $FUNC->unpackData($menu_parent['title'],LANG);

	return $menu_parent;
}

function dbtree_parent_root_info($cat_id) {
	global $ACTIVE, $SITE_MENU;
	if (!$parentId = parentId($ACTIVE,1))
		return $ACTIVE;
	else
		return $SITE_MENU[$parentId];
}

function get_ip_address() {
     return preg_replace("/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", "\\1.\\2.\\3.\\4",$_SERVER['REMOTE_ADDR']);
}

/*
function sess_set_array($data) {
	global $sess;

	foreach ($data as $k => $v) {
		$sess->set($k,$v);
	}
	return true;
}*/

function genCaptcha(){
	$random = StringConvertor::RandString("123456789ABCDEFHKMP",4);
	$tempfile=md5(time());
	$fp=@fopen("./tmp/tmp_".$tempfile,"w");
	@fputs($fp,$random);
	@fclose($fp);
	return array(md5($random),$tempfile);
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

function user_pspcardInfo() {
	global $_CONF, $CONN, $VALIDATOR, $TEXT, $FUNC, $sess, $sess_array;

	if ($sess_array['user']['card']=='yes' && $sess_array['user']['card_id']) {
		$data = $FUNC->db_query_getRows('_card','*','card_id="'.$sess_array['user']['card_id'].'"',__FILE__,__LINE__);
		return $data[0];
	}
	else
		return false;
}


//
function CreateCache($lang, $text, $size, $tx, $bg, $path='', $bg_img='', $x_pos=0, $y_pos=15){
	if(!file_exists('menuCache/'.$lang.'/'.$path.'/'.$text.'.gif')){
		$font = 'fonts/arial.ttf';

		if($lang == 'geo'){
			$font = 'fonts/sanet.ttf';
			$text = GeoToLatin($text);
			$size = $size-4;
		}

		if(!empty($bg_img)){
			$im  = imagecreatefromgif('images/'.$bg_img);
		}
		else{
			$length = imagettfbbox($size,0,$font,$text);
			$im     = imagecreate($length[2]+7, 20);
		}

		$bg  = ColorHexDec($bg);
		$tx  = ColorHexDec($tx);

		$bgcolor    = imagecolorallocate($im, $bg['R'], $bg['G'], $bg['B']);
		$fontcolor  = imagecolorallocate($im, $tx['R'], $tx['G'], $tx['B']);

		imagefill($im,0,0,$bgcolor);

		imagettftext($im, $size, 0, $x_pos,$y_pos, $fontcolor, $font, $text);
		imagegif($im, 'menuCache/'.$lang.'/'.$path.'/'.$text.'.gif');
		imagedestroy($im);
	}
	return '<img src="menuCache/'.$lang.'/'.$path.'/'.$text.'.gif" alt="'.$text.'">';
}
function random_file($num = 3){
	$random		= StringConvertor::RandString('0123456789',$num);
	$temp_file	= md5(time()+mt_rand(100,1000));
	$fp			= fopen('tmp/tmp_'.$temp_file, 'w');
	fputs($fp, $random);
	fclose($fp);
	return array(md5($random), $temp_file);
}
function GeoToLatin($text){
	$geo   = array('&#4304;', '&#4305;', '&#4306;', '&#4307;', '&#4308;',  '&#4309;', '&#4310;', '&#4311;', '&#4312;',  '&#4313;', '&#4314;', '&#4315;',  '&#4316;', '&#4317;', '&#4318;',  '&#4319;', '&#4320;', '&#4321;',  '&#4322;', '&#4323;', '&#4324;', '&#4325;', '&#4326;', '&#4327;',  '&#4328;', '&#4329;',  '&#4330;', '&#4331;', '&#4332;',  '&#4333;', '&#4334;', '&#4335;',  '&#4336;');
	$latin = array('a','b', 'g', 'd', 'e', 'v', 'z', 'T', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'J', 'r', 's', 't', 'u', 'f', 'q', 'R', 'y', 'S', 'C', 'c', 'Z', 'w', 'W', 'x', 'j', 'h');
	return str_replace($geo,$latin,$text);
}
function ColorHexDec($hex){
	$dec['R'] = hexdec(substr($hex,0,2));
	$dec['G'] = hexdec(substr($hex,2,2));
	$dec['B'] = hexdec(substr($hex,4,2));
	return $dec;
}


// paradox
// 4.6 - floor(4.6) != 0.6
// 100% checked - f.ck php typization
function RoundRateTo05($v) {
	$out = 0;
	$main = floor($v);
	$tmp = $v - $main;
	$tmp = round($tmp,1);

	/*var_dump($tmp);
	var_dump((string)$tmp == '0.6');
	var_dump($tmp == 0.6);*/
	switch ($tmp) {
		case 0.1: $out = 0;   break;
		case 0.2: $out = 0;   break;
		case 0.3: $out = 0.5; break;
		case 0.4: $out = 0.5; break;
		case 0.5: $out = 0.5; break;
		case 0.6: $out = 0.5; break;
		case 0.7: $out = 1;   break;
		case 0.8: $out = 1;   break;
		case 0.9: $out = 1;   break;
	}

	return ($main + $out);
}

function RemoveSpecialChars($data){
  if(is_array($data))
    {
     if(count($data)>0)
       {
       foreach($data as $k=>$v)
          {
          if(get_magic_quotes_gpc())
            $v=stripslashes($v);
          $v=trim(chop($v));
          $data[$k]=htmlspecialchars($v,ENT_QUOTES);
          }
       }
    }
    elseif(is_string($data))
       {
       if(get_magic_quotes_gpc())
          $data=stripslashes($data);
       $data=trim(chop($data));
       $data=htmlspecialchars($data,ENT_QUOTES);
       }
 return $data;
}

function check_structure($StSt) {
	$StConf = array('left','center','right','bottom','top','block1','block2','block3','block4','block5');

	$struct = unserialize($StSt);

	foreach ($StConf AS $k => $v) {
		if (!empty($struct[$v])) return true;
	}

	return false;
}


function check_structure_categories($StSt) {
	$StConf = array('left','center','right','bottom','top','block1','block2','block3','block4','block5');

	$struct = unserialize($StSt);
	foreach ($StConf AS $k => $v) {
		foreach ($struct[$v] as $key => $value) {
			if ($value=='products_categories') return true;
		}

	}

	return false;
}


function input($type,$classname,$name,$id='',$value='', $style='' ){
  $input_id = $id ? $id.'_i' : '';
  ?>
    <div class="<?=$classname?>" id="<?=$id?>">
       <div class="l"></div>
       <div class="c"><input type="<?=$type?>" name="<?=$name?>" id="<?=$input_id?>" value="<?=$value?>" style="<?=$style?>"></div>
       <div class="r"></div>
    </div>
  <?
}

function button($classname,$text,$id,$btn_float='none'){
  ?>
    <div class="<?=$classname?>" id="<?=$id?>" style="float:<?=$btn_float?>;">
       <div class="l"></div>
       <div class="c"><?=$text?></div>
       <div class="r"></div>
    </div>
  <?
}


function message($title, $message){
   global $tpl, $DESIGN;
   $tpl->addVar('main', 'TPL_section_title', $title);
   $tpl->addVar('message', 'TPL_message', $message);
   $tpl->parseIntoTemplate($DESIGN, $GLOBALS['SIDE'], 'message');
   $tpl->clearTemplate($message);
}

function xssafe($data,$encoding='UTF-8')
{
   return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding);
}
