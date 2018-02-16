<?
// 13:34 29.01.2009 
// CHANGE: return mod_id & plugin_id
// ADD: private function AddDataToArray($data, $currentLang='')
// CHANGE: $dataString -> $dataStringArray
// CHANGE: for($langs) -> foreach
// CHANGE: $path, $langs -> to __constructor(), private
// TODO: AddContentId
// 14.01.2011 15:47 __construct($conn, $prefix,
if(!defined('ALLOW_ACCESS')) exit;
class VariableCacheBuilder
{
	private $path = 'varCache';
	private $langs = array('eng', 'geo', 'rus');
	private $dataStringArray = array();
	private $conn;
	private $pluginTable;
	private $sitemenuTable;
	public $pluginPrefix = 'PID_';
	public $modulePrefix = 'MOD_';

	public function __construct($conn, $prefix, $path = '', $langs = '')
	{
		$this->conn = $conn;
		$this->pluginTable = $prefix.'_pcms_plugins';
		$this->sitemenuTable = $prefix.'_sitemenu';
		
		if(!empty($path) && is_string($path))
		{
			$this->$path = $path;
		}
		
		if(!empty($langs) && is_array($langs))
		{
			$this->$langs = $langs;
		}
	}

	public function WriteConstant($name, $value, $currentLang='')
	{
		$this->AddDataToArray('define(\''.trim(addslashes($name)).'\', \''.trim(addslashes($value)).'\', true);'."\n", $currentLang);
	}

	public function WriteCode($code, $currentLang='')
	{
		$this->AddDataToArray($code."\n", $currentLang);
	}

	public function WriteComment($text, $currentLang='')
	{
		$this->AddDataToArray('/*'.$text.'*/'."\n", $currentLang);
	}

	public function AddPluginId($pluginName, $currentLang='')
	{
		$query  = 'SELECT id FROM '.$this->pluginTable.' WHERE plugin=\''.$pluginName.'\'';
		$result = $this->conn->Execute($query) or $this->WriteComment('error writing "'.$pluginName.'" plugin id '.$this->conn->ErrorMsg());
		
		if(isset($result->fields['id']) && !empty($result->fields['id']))
		{
			$this->WriteConstant($this->pluginPrefix.strtoupper($pluginName), $result->fields['id'], $currentLang);
			return $result->fields['id'];
		}
		else
		{
			$this->WriteComment('error writing "'.$pluginName.'" plugin id ', $currentLang);
			return 0;
		}
	}

	public function AddModuleId($moduleName, $currentLang='')
	{
		$query  = 'SELECT cat_id FROM '.$this->sitemenuTable.' WHERE structure LIKE \'%"'.$moduleName.'"%\' LIMIT 1';
		$result = $this->conn->Execute($query) or $this->WriteComment('error writing "'.$moduleName.'" module id '.$this->conn->ErrorMsg());
		
		if(isset($result->fields['cat_id']) && !empty($result->fields['cat_id']))
		{
			$this->WriteConstant($this->modulePrefix.strtoupper($moduleName), $result->fields['cat_id'], $currentLang);
			return $result->fields['cat_id'];
		}
		else
		{
			$this->WriteComment('error writing "'.$moduleName.'" module id ', $currentLang);
			return 0;
		}
	}

	public function WriteCache()
	{
		// for($i = 0, $n = count($this->langs); $i < $n; $i++)
		foreach($this->langs as $lang)
		{
			file_put_contents($this->path.'/'.$lang.'/var.php', '<?'."\n".$this->dataStringArray[$lang].'?>'); // $this->langs[$i]
		}
	}
	
	public function ClearCacheDirectory()
	{
		$this->ClearDir($this->path);
	}

	private function ClearDir($folder)
	{
		if($handle = opendir($folder))
		{
			while ($f = readdir($handle))
			{
				$err = false;
				if(is_dir($folder.'/'.$f) && ($f!='.') && ($f!='..'))
				{
					ClearDir($folder.'/'.$f);
				}
				
				if(is_file($folder.'/'.$f))
				{
					if(!unlink($folder.'/'.$f))
					{
						return false;
					}
				}
			}
			closedir($handle);
		}
		else
		{
			return false;
		}
		return true;
	} 

	private function AddDataToArray($data, $currentLang='')
	{
		if(!empty($currentLang) && in_array($currentLang, $this->langs))
		{
			if(isset($this->dataStringArray[$currentLang]))
			{
				$this->dataStringArray[$currentLang] .= $data;
			}
			else
			{
				$this->dataStringArray[$currentLang] = $data;
			}
		}
		else
		{
			foreach($this->langs as $lang)
			{
				if(isset($this->dataStringArray[$lang]))
				{
					$this->dataStringArray[$lang] .= $data;
				}
				else
				{
					$this->dataStringArray[$lang] = $data;
				}
			}
		}
	}
}
?>