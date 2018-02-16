<?
if(!defined('ALLOW_ACCESS')) exit;
// 16:15 30.09.2009 TranslitUtil class: russian support
// 10.06.2010 11:51 UrlUtil::GetNotEmptyParameter changed: !empty() => !=''
// 05.01.2011 11:57 ['.IpUtil::GetIpAddress().']
class Captcha
{
	public static $randCharacters = '0123456789';
	public static $path  = 'tmp'; // /
	public static $preff = 'tmp_';

	public static $hash;
	public static $tempFile;
	const defaultNum = 3;

	public static $font = 'fonts/arial.ttf';
	public static $size = 34;
	public static $imgWidth  = 80;
	public static $imgHeight = 60;

	public static $letterWidth = 15;
	
	public static $startX = 15;
	public static $startY = 50;

	public static $bgColor = array(255,  255,  255);

	public static $colors = array
		(
			array(255,0,  0  ),
			array(0,  255,0  ),
			array(0,  0,  255),
			array(0,  200,200),
			array(255,255,0  ),
			array(255,0,  255)
		);

	public static $captchaPath = '';

	public static function RandomFile($characterNum = self::defaultNum)
	{
		$randomString  = StringConvertor::RandString(self::$randCharacters, $characterNum);
		self::$tempFile = md5(time()); // self::GenerateHash(time()); // ??
		$fp = fopen(self::$path.'/'.self::$preff.self::$tempFile, 'w');
		fputs($fp, $randomString); // TODO: framework FileUtil class !?
		fclose($fp);
		self::$hash = self::GenerateHash($randomString);
	}

	// use inheritance for overriding this function
	public static function GenerateHash($inputString) 
	{
		return md5($inputString);
	}

	public static function ClearDirectory()
	{
		if($handle = opendir(self::$path))
		{
			while($f = readdir($handle))
			{
				if(is_file(self::$path.'/'.$f) && filemtime(self::$path.'/'.$f) < time() - 1*60)
				{
					unlink(self::$path.'/'.$f);
				}
			}
			closedir($handle);
		}
	}

	public static function GetRandomString($code)
	{
		$code = self::ValidateCode($code);
		$str = '';
		if($fp = fopen(self::$path.'/'.self::$preff.$code, 'r'))
		{
			$str = fgets($fp);
			fclose($fp);
		}
		return $str;
	}

	public static function GenerateCaptcha($str)
	{
		if(empty(self::$captchaPath) && !headers_sent())
		{
			header('Content-type: image/png');
		}

		$im = ImageCreate(self::$imgWidth, self::$imgHeight);
		$bgColor = ImageColorAllocate($im, self::$bgColor[0], self::$bgColor[1], self::$bgColor[2]);
		
		ImageFilledRectangle($im, 0,0, self::$imgWidth, self::$imgHeight, $bgColor);
		for($i = 0, $num = strlen($str); $i < $num; $i++)
		{
			$p = mt_rand(0, count(self::$colors) - 1);
			$color = ImageColorAllocate($im, self::$colors[$p][0], self::$colors[$p][1], self::$colors[$p][2]);
			array_splice(self::$colors, $p, 1);
			ImageTTFText($im, self::$size, rand(-10,10), $i * self::$letterWidth + self::$startX, self::$startY, $color, self::$font, $str[$i]);
		}
		ImageColorTransparent($im, $bgColor);
		
		if(!empty(self::$captchaPath))
		{
			ImagePNG($im, self::$captchaPath);
		}
		else
		{
			ImagePNG($im);
		}
		ImageDestroy($im);
	}

	private static function ValidateCode($code)
	{
		$code = str_replace('../',                  '', $code);
		$code = preg_replace('/[^a-zA-Z0-9_\-.\/]/','', $code);
		$code = preg_replace('/\/+/',              '/', $code);
		return $code;
	}
}

class Date
{
	public $year;
	public $month;
	public $day;
	
	public $hour;
	public $minute;
	public $second;
	
	public function __construct()
	{
		$this->year = date('Y');
		$this->month = date('n');
		$this->day = date('j');

		$this->hour = date('G');
		$this->minute = date('i'); // with leading zeros
		$this->second = date('s'); // with leading zeros
	}
	
	public function GetDateString($format = 'Y-m-d')
	{
		return date($format, mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year));
	}
	
	public function SetDateFromString($dateString, $delimiter = '-', $format = 'year-month-day')
	{
		$dateArray   = explode($delimiter, $dateString);
		$formatArray = explode('-', $format);
		$resultArray = array();

		$resultArray[$formatArray[0]] = $dateArray[0];
		$resultArray[$formatArray[1]] = $dateArray[1];
		$resultArray[$formatArray[2]] = $dateArray[2];
		
		$this->year  = $resultArray['year'];
		$this->month = $resultArray['month'];
		$this->day   = $resultArray['day'];
		
		$dateTimeResult = new Date();
		$dateTimeResult->year  = $resultArray['year'];
		$dateTimeResult->month = $resultArray['month'];
		$dateTimeResult->day   = $resultArray['day'];
		
		return $dateTimeResult;
	}
	
	public function CheckDateTime()
	{
		return checkdate($this->month, $this->day, $this->year);
	}
	
	public function GetWeekDay()
	{
		return $this->GetDateString('w');
	}
	
	public function Compare($dateTime)
	{
		$timestamp1 = mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year);
		$timestamp2 = mktime($dateTime->hour, $dateTime->minute, $dateTime->second, $dateTime->month, $dateTime->day, $dateTime->year);
		if($timestamp1 > $timestamp2)
		{
			return 1;
		}
		else if($timestamp1 == $timestamp2)
		{
			return 0;
		}
		else
		{
			return -1;
		}
	}
	
	public function GetTodayDate()
	{
		$dateTime = new Date();
		return $dateTime;
	}
	
	public function GetTimestamp()
	{
		return mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year);
	}
}

class IpUtil
{
	public static $localHostIp = '127.0.0.1';

	public static function CheckIp($ip = '')
	{
		if(empty($ip))
		{
			$ip = self::$localHostIp;
		}
		return ($_SERVER['REMOTE_ADDR'] == $ip);
	}

	public static function GetIpAddress()
	{
		return preg_replace('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/', '\\1.\\2.\\3.\\4',$_SERVER['REMOTE_ADDR']);
	}
}

class Timer
{
	public static $HeadTime;

	public static function Tic()
	{
		list($msec, $sec) = explode(chr(32),microtime());
		self::$HeadTime = $sec + $msec;
	}

	public static function Toc()
	{
		list($msec, $sec) = explode(chr(32),microtime());
		print round(($sec + $msec) - self::$HeadTime, 4);
	}

	/*
	public static function RequestTime()
	{
		print date('H:i:s:u', $_SERVER['REQUEST_TIME']);
	}
	*/
	
	private $HeadTime2;
	private $sum;
	
	public function __construct()
	{
		$this->sum = 0;
	}
	
	public function Tic2()
	{
		list($msec, $sec) = explode(chr(32),microtime());
		$this->HeadTime2 = $sec + $msec;
	}
	
	public function Toc2()
	{
		list($msec, $sec) = explode(chr(32),microtime());
		$this->sum += ($sec + $msec) - $this->HeadTime2;
		print round(($sec + $msec) - $this->HeadTime2, 4);
	}
	
	public function Summary()
	{
		print round($this->sum, 4);
	}
}

class Navigation
{
	public static function Redirect($location)
	{
		header('Location: '.$location);
		exit;
	}
}

/*
class MailUtil
{
	
}
*/

class CookiesUtil
{
	public static function WriteCookie($name, $value = '', $expires = '')
	{
		$expires =  time() + ($expires ? $expires : 60*60*24*365);
		setcookie($name, $value, $expires, '/', '');
	}

	public static function DeleteCookies($name='')
	{
		$expires = time()-3600;
		if($name)
		{
			setcookie($name, '', $expires, '/', '');
		}
		else if(!empty($_COOKIE))
		{
			foreach($_COOKIE as $name => $value)
			{
				setcookie($name, '', $expires, '/', '');
			}
		}
	}

}

class ErrorLog
{
	public static $path = '';
	public static function ServerError($filename, $line, $errorMessage = '', $isExit = true)
	{
		$fullErrorText = '['.date('d.m.Y H:i').']['.IpUtil::GetIpAddress().'] Error in '.$filename.' on line '.$line.' ['.$errorMessage.']'."\r\n";
		$fp = fopen(self::$path, 'a');
		fwrite($fp, $fullErrorText);
		fclose($fp);
		if($isExit)
		{
			exit($errorMessage);
		}
	}
}

ErrorLog::$path = $_CONF['path']['error_log'];

class CommonInterface
{
	public static function DrawPageBar($URL, $result, $style='')
	{
		$str =
		'<style>
	    .pg td
		{
			width:15px;
			color: #606060;
			font-size:11px;
			font-family:Arial;
			font-weight:bold;
		}
		.pg td a
		{
			color: #0857A6;
			font-size:11px;
			font-family:Arial;
			text-decoration:none;
		}
		</style>';
		if(!empty($style))
		{
			$str = '<style>'.$style.'</style>';
		}
		
		if ($result->_lastPageNo < 2) return;

		$str .= '<table cellpadding="0" cellspacing="0" border="0" class="pg">
		           <tr>';

		if($result->_currentPage != 1)
		{
			$str .= '<td>
			           <a href="'.$URL.($result->_currentPage-1).'"> &lt;&lt;&nbsp;&nbsp;</a>
			         </td>';
		}
		else
		{
			$str .= '<td> &lt;&lt;&nbsp;&nbsp;</td>';
		}

		if($result->_currentPage < 5)
		{
			$start  = 0;
			$finish = 10;
		}
		else
		{
			$start  = $result->_currentPage - 5;
			$finish = $result->_currentPage + 5;
		}

		if($finish * $result->rowsPerPage > $result->_maxRecordCount)
		{
			$finish = ceil($result->_maxRecordCount/$result->rowsPerPage);
		}

		for($i = $start; $i < $finish; $i++)
		{
			if ($i == $result->_currentPage - 1)
			{
				$str .= '<td>'.($i + 1).'</td>';
			}
			else
			{
				$str .= '<td><a href="'.$URL.($i + 1).'">'.($i + 1).'</a></td>';
			}
		}

		if($result->_currentPage < $result->_lastPageNo)
		{
			$str .= '<td><a href="'.$URL.($result->_currentPage + 1).'"> &gt;&gt; </a></td>';
		}
		else
		{
			$str .= '<td> &gt;&gt; </td>';
		}

		$str .= '  </tr>
		        </table>';
		return($str);
	}
}

class TransferMethods
{
	const Get = 0;
	const Post = 1;
}

class UrlUtil
{
	public static function GetParameter($name, $method = TransferMethods::Get)
	{
		if($method == TransferMethods::Post)
		{
			return isset($_POST[$name]) ? '&'.$name.'='.urlencode($_POST[$name]) : '';
		}
		else
		{
			return isset($_GET[$name]) ? '&'.$name.'='.urlencode($_GET[$name]) : '';
		}
	}

	public static function GetNotEmptyParameter($name, $method = TransferMethods::Get)
	{
		if($method == TransferMethods::Post)
		{
			return isset($_POST[$name]) && $_POST[$name]!='' ? '&'.$name.'='.urlencode($_POST[$name]) : '';
		}
		else
		{
			return isset($_GET[$name]) && $_GET[$name]!=''  ? '&'.$name.'='.urlencode($_GET[$name]) : '';
		}
	}
/*	
 public static function ConvertUrl($url)
 {
  // return $url;
  if(preg_match('~^'.BASE.'/index\.php\?(.*)$~Ui', $url, $match))
  {
   $query = $match[1];
   
   parse_str($query, $output);
   
   
   if(!isset($output['lang']))
   {
    $output['lang'] = LANG;
   }
   
   if(isset($output['m'], $output['lang'], $output['alias'], $output['id']))
   {
    $resultUrl = '/'.$output['lang'].'/'.$output['m'].'/'.$output['alias'].'/id'.$output['id'];
    unset($output['m'], $output['lang'], $output['alias'], $output['id']);
   }
   
   else if(isset($output['m'], $output['lang'], $output['alias']))
   {
    $resultUrl = '/'.$output['lang'].'/'.$output['m'].'/'.$output['alias'];
    unset($output['m'], $output['lang'], $output['alias']);
   }
   
   else if(isset($output['m'], $output['lang'], $output['cat_id'], $output['date']))
   {
    $resultUrl = '/'.$output['lang'].'/'.$output['m'].'/category'.$output['cat_id'].'/'.$output['date'];
    unset($output['m'], $output['lang'], $output['cat_id'], $output['date']);
   }
   else if(isset($output['m'], $output['lang'], $output['date']))
   {
    $resultUrl = '/'.$output['lang'].'/'.$output['m'].'/'.$output['date'];
    unset($output['m'], $output['lang'], $output['date']);
   }
   else if(isset($output['m'], $output['lang'], $output['cat_id']))
   {
    $resultUrl = '/'.$output['lang'].'/'.$output['m'].'/category'.$output['cat_id'];
    unset($output['m'], $output['lang'], $output['cat_id']);
   }
   else if(isset($output['m'], $output['lang']))
   {
    $resultUrl = '/'.$output['lang'].'/'.$output['m'];
    unset($output['m'], $output['lang']);
   }
   else if(isset($output['m']))
   {
    // TODO: LANG ?
    $resultUrl = '/'.$output['m'];
    unset($output['m']);
   }
   
   else
   {
    $resultUrl = '/';
   }

   if(isset($output['p']))
   {
    $resultUrl = $resultUrl.'/page'.$output['p'];
    unset($output['p']);
   }
   
   
   $endOfUrl = http_build_query($output);
   
   if(!empty($endOfUrl))
   {
    $resultUrl = $resultUrl.'&'.$endOfUrl;
   }
   
   return $resultUrl; // BASE.''.
  }
  
  return $url;
 }
 */
}

class BrowserOS
{
	const Win = 'Win';
	const Mac = 'Mac';
	const Linux = 'Linux';
	const Unix = 'Unix';
	const OS2 = 'OS/2';
	const Other = 'Other';
}

class BrowserAgent
{
	const OPERA = 'OPERA';
	const IE = 'IE';
	const OMNIWEB = 'OMNIWEB';
	const KONQUEROR = 'KONQUEROR';
	const SAFARI = 'SAFARI';
	const MOZILLA = 'MOZILLA';
	const OTHER = 'OTHER';
}

class Browser
{
	public static function GetUserAgentInfo()
	{
		$HTTP_USER_AGENT = '';
		if(!empty($_SERVER['HTTP_USER_AGENT']))
		{
			$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		}

		$out = array();
		// 1. Platform
		if(strstr($HTTP_USER_AGENT, 'Win'))
		{
			$out['OS'] = BrowserOS::Win;
		}
		else if(strstr($HTTP_USER_AGENT, 'Mac'))
		{
			$out['OS'] = BrowserOS::Mac;
		}
		else if(strstr($HTTP_USER_AGENT, 'Linux'))
		{
			$out['OS'] = BrowserOS::Linux;
		}
		else if(strstr($HTTP_USER_AGENT, 'Unix'))
		{
			$out['OS'] = BrowserOS::Unix;
		}
		else if(strstr($HTTP_USER_AGENT, 'OS/2'))
		{
			$out['OS'] = BrowserOS::OS2;
		}
		else
		{
			$out['OS'] = BrowserOS::Other;
		}

		// 2. browser and version
		// (must check everything else before Mozilla)
		if(preg_match('@Opera(/| )([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version))
		{
			$out['BROWSER_VER'] = $log_version[2];
			$out['BROWSER_AGENT'] = BrowserAgent::OPERA;
		}
		else if(preg_match('@MSIE ([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version))
		{
			$out['BROWSER_VER'] = $log_version[1];
			$out['BROWSER_AGENT'] = BrowserAgent::IE;
		}
		else if(preg_match('@OmniWeb/([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version))
		{
			$out['BROWSER_VER'] = $log_version[1];
			$out['BROWSER_AGENT'] = BrowserAgent::OMNIWEB;
			//} else if (ereg('Konqueror/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			// Konqueror 2.2.2 says Konqueror/2.2.2
			// Konqueror 3.0.3 says Konqueror/3
		}
		else if(preg_match('@(Konqueror/)(.*)(;)@', $HTTP_USER_AGENT, $log_version))
		{
			$out['BROWSER_VER'] = $log_version[2];
			$out['BROWSER_AGENT'] = BrowserAgent::KONQUEROR;
		}
		else if(preg_match('@Mozilla/([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version) && preg_match('@Safari/([0-9]*)@', $HTTP_USER_AGENT, $log_version2))
		{
			$out['BROWSER_VER'] = $log_version[1].'.'.$log_version2[1];
			$out['BROWSER_AGENT'] = BrowserAgent::SAFARI;
		}
		else if(preg_match('@Mozilla/([0-9].[0-9]{1,2})@', $HTTP_USER_AGENT, $log_version))
		{
			$out['BROWSER_VER'] = $log_version[1];
			$out['BROWSER_AGENT'] = BrowserAgent::MOZILLA;
		}
		else
		{
			$out['BROWSER_VER'] = 0;
			$out['BROWSER_AGENT'] = BrowserAgent::OTHER;
		}
		return $out;
	}
}

class CommonUtil
{
	public static $allLangs = array('eng');
	public static $defaultLang = 'eng';

	public static function UnpackData($packedData, $lang = '')
	{
		$unpackedData = unserialize($packedData);
		return $lang ? $unpackedData[$lang] : $unpackedData;
	}

	public static function SetLang($sessName = 'lang') // $langset, 
	{
		if(isset($_GET[$sessName])) // 'lang'
		{
			$lang = $_GET[$sessName];
		}
		else if(isset($_SESSION[$sessName.'1']))
		{
			$lang = $_SESSION[$sessName.'1'];
		}
		else if(isset($_COOKIE[$sessName]))
		{
			$lang = $_COOKIE[$sessName];
		}
		else
		{
			return $_SESSION[$sessName.'1'] = self::$defaultLang; // $_CONF[$langset.'_def'];
		}

		$_SESSION[$sessName.'1'] = $lang = self::validLang($lang); // , $langset
		CookiesUtil::writeCookie($sessName,$lang);
		return $lang;
	}

	public static function ValidLang($lang) //, $langset
	{
		return in_array($lang, self::$allLangs) ? $lang : self::$defaultLang; //$_CONF[$langset.'_all'] $_CONF[$langset.'_def']
	}
}

CommonUtil::$allLangs = $_CONF['langs_all'];
CommonUtil::$defaultLang = $_CONF['langs_def'];

class PasswordUtil
{
	public static function CompiledPass($password, $passcode)
	{
		return md5(md5($password).md5($passcode));
	}
}
/*
class Settings
{
	private $conn;
	private $tableSettings;

	public function __construct(&$conn, $tableSettings)
	{
		$this->conn = &$conn;
		$this->tableSettings = $tableSettings;
	}

	public function SelectSettings()
	{
		// 12:30 16.02.2010 sort_num
		$query  = 'SELECT * FROM '.$this->tableSettings.' WHERE publish=1 ORDER BY orderid, id DESC'; 
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		$data = array();
		while($row = $result->fetchRow())
		{
			$data[$row['input_name']] = $row;
		}
		return $data;
	}

	public function AddSetting($name, $value)
	{
		$query = 'INSERT INTO '.$this->tableSettings.' SET value='.StringConvertor::qstr($value).', input_name='.StringConvertor::qstr($name).
				 ' ON DUPLICATE KEY UPDATE value='.StringConvertor::qstr($value).', input_name='.StringConvertor::qstr($name);
		$this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		return $this->conn->Insert_ID();
	}

	public function UpdateSetting($name, $value)
	{
		$query = 'UPDATE '.$this->tableSettings.' SET value='.StringConvertor::qstr($value).' WHERE input_name='.StringConvertor::qstr($name);
		$this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}

	public function DeleteSetting($name)
	{
		$query = 'DELETE FROM '.$this->tableSettings.' WHERE input_name='.StringConvertor::qstr($name);
		$this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}

	public function SelectSetting($name)
	{
		$query  = 'SELECT * FROM '.$this->tableSettings.' WHERE input_name='.StringConvertor::qstr($name).' LIMIT 1';
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		return $result->fields;
	}
}
*/

class TranslitUtil
{
	// private static $geoUTF   = array('ა', 'ბ',  'გ', 'დ', 'ე',  'ვ', 'ზ', 'თ', 'ი',  'კ', 'ლ', 'მ', 'ნ', 'ო', 'პ',  'ჟ', 'რ', 'ს', 'ტ', 'უ',  'ფ', 'ქ', 'ღ', 'ყ', 'შ', 'ჩ',  'ც', 'ძ',  'წ', 'ჭ', 'ხ', 'ჯ',  'ჰ');
	private static $geoUTF   = array('ა', 'ბ',  'გ', 'დ', 'ე',  'ვ', 'ზ', 'თ', 'ი',  'კ', 'ლ', 'მ', 'ნ', 'ო', 'პ',  'ჟ', 'რ', 'ს', 'ტ', 'უ',  'ფ', 'ქ', 'ღ', 'ყ', 'შ', 'ჩ',  'ც', 'ძ',  'წ', 'ჭ', 'ხ', 'ჯ',  'ჰ',  'ჱ','ჲ','ჳ','ჴ','ჵ');
	private static $geoLatin = array('a', 'b', 'g', 'd', 'e', 'v', 'z', 'T', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'J', 'r', 's', 't', 'u', 'f', 'q', 'R', 'y', 'S', 'C', 'c', 'Z', 'w', 'W', 'x', 'j', 'h','', '','', '', '');
	private static $geoDecoded = array('&#4304;','&#4305;','&#4306;','&#4307;','&#4308;','&#4309;','&#4310;','&#4311;','&#4312;','&#4313;','&#4314;','&#4315;','&#4316;','&#4317;','&#4318;','&#4319;','&#4320;','&#4321;','&#4322;','&#4323;','&#4324;','&#4325;','&#4326;','&#4327;','&#4328;','&#4329;','&#4330;','&#4331;','&#4332;','&#4333;','&#4334;','&#4335;','&#4336;','&#4337;','&#4338;','&#4339;','&#4340;','&#4341;');
	
	# {
		// 41 - 04 = 37 
		private static $capitalGeoDecoded = array(
		'&#4256;', //ა  // 93 - 56 = 37
		'&#4257;', //ბ
		'&#4258;', //გ
		'&#4259;', //დ
		'&#4260;', //ე
		'&#4261;', //ვ
		'&#4262;', //ზ
		'&#4263;', //თ
		'&#4264;', //ი
		'&#4265;', //კ
		'&#4266;', //ლ
		'&#4267;', //მ
		'&#4268;', //ნ
		'&#4269;', //ო
		'&#4270;', //პ
		'&#4271;', //ჟ
		'&#4272;', //რ
		'&#4273;', //ს
		'&#4274;', //ტ
		'&#4275;', //უ
		'&#4276;', //ფ
		'&#4277;', //ქ
		'&#4278;', //ღ
		'&#4279;', //ყ
		'&#4280;', //შ
		'&#4281;', //ჩ
		'&#4282;', //ც
		'&#4283;', //ძ
		'&#4284;', //წ
		'&#4285;', //ჭ
		'&#4286;', //ხ
		'&#4287;', //ჯ
		'&#4288;', //ჰ
		'&#4289;', //ჱ
		'&#4290;', //ჲ
		'&#4291;', //ჳ
		'&#4292;', //ჴ
		'&#4293;'  //ჵ
		);
	# }

	private static $rusUTF   = array
	(
	'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ь','ы','ъ','э','ю','я',
	'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ь','Ы','Ъ','Э','Ю','Я'
	);
	private static $rusLatin = array
	(
	'a','b','v','g','d','e','e','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sh','\'','y','\'','e','yu','ya',
	'A','B','V','G','D','E','E','Zh','Z','I','\'','K','L','M','N','O','P','R','S','T','U','F','H','C','Ch','Sh','Sh','\'','Y','\'','E','Yu','Ya'
	);

	// RENAMED: ToUpperGeo
	public static function GeoToUpperGeo($text)
	{
		$text = str_replace(self::$geoUTF, self::$capitalGeoDecoded, $text);
		return $text;
	}

	public static function GeoToLatin($text)
	{
		$text = str_replace(self::$geoUTF, self::$geoLatin, $text);
		return $text;
	}
	
	public static function GeoToDecodedGeo($text)
	{
		$text = str_replace(self::$geoUTF, self::$geoDecoded, $text);
		return $text;
	}
	
	public static function RusToLatin($text)
	{
		$text = str_replace(self::$rusUTF, self::$rusLatin, $text);
		return $text;
	}
}

class YoutubeLniks
{
	public static function GetVideo($url)
	{
		$id = self::ParseYoutubeUrl($url);
		return 'http://www.youtube.com/v/'. $id .'?fs=1&amp;rel=0';
	}
	
	public static function GetScreen($url)
	{
		$id = self::ParseYoutubeUrl($url);
		return 'http://img.youtube.com/vi/'. $id .'/0.jpg'; // 1, 2
	}
	
	private static function ParseYoutubeUrl($url)
	{
		if(preg_match('|[\?&]v=([^&#]*)|', $url, $match))
		{
			return $match[1];
		}
		else if(preg_match('|youtube.com/embed/(.*)|', $url, $match))
		{
			return $match[1];
		}
		else
		{
			return $url;
		}
	}
}
?>