<?
// 24.09.2010 17:20 ADDED: DeleteAll($conditions=array(), $limit='')
// 03.11.2010 17:46 ADDED: if(!is_array($conditions)) (DeleteAll function)
// 23.11.2010 11:46 ADDED: $groupBy
// 18.01.2011 16:36 ADDED: class CategoriesModule
class PublishStatus
{
	const Unpublish = 0;
	const Publish = 1;
}

class Module
{
	protected $conn;
	protected $table;
	protected $fields;
	
	protected $idField;
	
	protected function __construct($conn, $table, $idField = 'id')
	{
		$this->conn = $conn;
		$this->table = $table;
		$this->idField = $idField;
	}
	
	protected function FilterData($data)
	{
		if(!is_array($data) || !is_array($this->fields))
		{
			return array();
		}
		
		foreach($data as $field)
		{
			if(!in_array($field, $this->fields))
			{
				unset($data[$field]);
			}
		}
		return $data;
	}
	
	protected function PrepareData($data)
	{
		if(!is_array($data))
		{
			return '';
		}
		$params = array();
		foreach($data as $field => $value)
		{
			$params[] = $field.'='.$value; 
		}
		return implode(',', $params); 
	}
	
	protected function PrepareFields($fields)
	{
		if(!is_array($fields) || count($fields) == 0)
		{
			return '*';
		}
		
		return implode(',', $fields); 
	}
	
	protected function PrepareSimpleConditions($data)
	{
		if(!is_array($data))
		{
			return ' ';
		}
		
		$params = array();
		foreach($data as $condition)
		{
			$params[] = $condition;
		}
		
		if(count($data) > 0)
		{
			return ' WHERE '.implode(' AND ', $params).' ';
		}
		else
		{
			return ' ';
		}
	}
	
	
	public function Add($data, $update = false)
	{
		$data = $this->FilterData($data, $this->fields);
		
		$query = 'INSERT INTO '.$this->table.' SET '.$this->PrepareData($data);
		
		if($update)
		{
			$query .= ' ON duplicate KEY UPDATE '.$this->PrepareData($data);
		}
		
		$this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		
		$id = $this->conn->Insert_ID();
		return $id;
	}
	
	public function Update($id, $data)
	{
		if(!is_numeric($id))
		{
			return null;
		}
		
		$id = (int)$id;
		
		$data = $this->FilterData($data, $this->fields);
		$query = 'UPDATE '.$this->table.' SET '.$this->PrepareData($data).' '.$this->PrepareSimpleConditions(array($this->idField.'='.$id)).' LIMIT 1';
		
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		
		return $result;
	}
	
	public function UpdateAll($conditions=array(), $data)
	{
		$data = $this->FilterData($data, $this->fields);
		
		$query = 'UPDATE '.$this->table.' SET '.$this->PrepareData($data).' '.$this->PrepareSimpleConditions($conditions);
		
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		
		return $result;
	}
	
	public function Delete($id)
	{
		if(!is_numeric($id))
		{
			return null;
		}
		
		$id = (int)$id;
		
		$query = 'DELETE FROM '.$this->table.' '.$this->PrepareSimpleConditions(array($this->idField.'='.$id)).' LIMIT 1';
		
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		
		return $result;
	}
	
	public function DeleteAll($conditions=array(), $limit='')
	{
		if(!is_array($conditions))
		{
			return false;
		}
		
		$query = 'DELETE FROM '.$this->table.' '.$this->PrepareSimpleConditions($conditions);
		
		if(is_numeric($limit))
		{
			$limit = (int)$limit;
			$query .= ' LIMIT '.$limit;
		}
		
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		
		return $result;
	}
	
	public function Select($id, $fields=array())
	{
		if(!is_numeric($id))
		{
			return array();
		}
		
		$id = (int)$id;
		
		$query = 'SELECT '.$this->PrepareFields($fields).' FROM '.$this->table.' '.$this->PrepareSimpleConditions(array($this->idField.'='.$id)).' LIMIT 1';
		
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		return $result->fields;
	}
	
	public function SelectAll($conditions=array(), $limit='', $orderBy='', $groupBy = '')
	{
		if(empty($orderBy))
		{
			$orderBy = ' '.$this->idField.' DESC';
		}
		
		if(!empty($groupBy))
		{
			$groupBy = 'GROUP BY '.$groupBy;
		}
		
		$query = 'SELECT * FROM '.$this->table.' '.$this->PrepareSimpleConditions($conditions).' '.$groupBy.' ORDER BY '.$orderBy; 
		
		if(is_numeric($limit))
		{
			$limit = (int)$limit;
			$query .= ' LIMIT '.$limit;
		}
		
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		return $result;
	}
	
	public function SelectAllPaginal($pagesTotal, $currentPage, $conditions=array(), $orderBy='', $groupBy = '')
	{
		if(!is_numeric($pagesTotal) || !is_numeric($currentPage))
		{
			return null;
		}
		
		$pagesTotal = (int)$pagesTotal;
		$currentPage = (int)$currentPage;
		
		if(empty($orderBy))
		{
			$orderBy = ' '.$this->idField.' DESC';
		}
		
		if(!empty($groupBy))
		{
			$groupBy = 'GROUP BY '.$groupBy;
		}
		
		$query  = 'SELECT * FROM '.$this->table.' '.$this->PrepareSimpleConditions($conditions).' '.$groupBy.' ORDER BY '.$orderBy; 
		
		$result = $this->conn->PageExecute($query, $pagesTotal, $currentPage) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		return $result;
	}
	
	public function SelectCount($conditions=array())
	{
		$query = 'SELECT COUNT(*) AS num FROM '.$this->table.' '.$this->PrepareSimpleConditions($conditions).'';
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		return $result->fields['num'];
	}
	
	public function GetFields()
	{
		return $this->fields;
	}
	
	/*
	private static $instance;
	
	public static function getInstance($conn, $table, $idField = 'id')
	{
		if(!isset(self::$instance))
		{
			self::$instance = new __construct($conn, $table, $idField);
		}
		return self::$instance;
	}
	*/
}

class PublishModule extends Module
{
	protected $publishField;
	
	protected function __construct($conn, $table, $idField = 'id', $publishField = 'publish')
	{
		$this->publishField = $publishField;
		
		parent::__construct($conn, $table, $idField);
	}
	
	public function Publish($id, $publish = true)
	{
		if(!is_numeric($id))
		{
			return;
		}
		
		if($publish)
		{
			$query = 'UPDATE '.$this->table.' SET '.$this->publishField.'='.PublishStatus::Publish.' '.$this->PrepareSimpleConditions(array($this->idField.'='.$id)).' LIMIT 1';
		}
		else
		{
			$query = 'UPDATE '.$this->table.' SET '.$this->publishField.'='.PublishStatus::Unpublish.' '.$this->PrepareSimpleConditions(array($this->idField.'='.$id)).' LIMIT 1';
		}
		
		$this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
}

class CategoriesModule extends Module
{
	protected $pid;
	public function CategoriesModule($conn, $prefix, $pid)
	{
		$this->pid = $pid;
		$this->prefix = $prefix;
		parent::__construct($conn, $prefix.'_categories', 'cat_id');           
		$this->fields = array('cat_id', 'cat_left', 'cat_right', 'cat_level', 'pid', 'name', 'hidden');
	}
	
	public function SelectParent($catLeft, $catRight, $level)
	{
		if(!is_numeric($catLeft) || !is_numeric($catRight) || !is_numeric($level))
		{
			return array();
		}
		
		$conditions = array('cat_left < '.$catLeft, 'cat_right > '.$catRight, 'cat_level='.$level, 'hidden=0');
		
		if($level > 0)
		{
			$conditions = array_merge($conditions, array('pid='.$this->pid));
		}
		
		$result = $this->SelectAll($conditions, '1');
		
		return $result->fields;
	}
	
	public function SelectChildren($catLeft, $catRight, $level=0)
	{
		if(!is_numeric($catLeft) || !is_numeric($catRight) || !is_numeric($level))
		{
			return null;
		}
		
		if($level > 0)
		{
			$conditions = array_merge($conditions, array('cat_level='.$level));
		}
		
		$conditions = array('cat_left > '.$catLeft, 'cat_right < '.$catRight, 'hidden=0', 'pid='.$this->pid);
		
		return $this->SelectAll($conditions, '', 'cat_left');
	}
	
	public function Add($data, $update = false)
	{
		// NOT IMPLEMENTED!
		return 0;
	}
	
	public function Delete($id)
	{
		// NOT IMPLEMENTED!
		return null;
	}
	
	public function DeleteAll($conditions=array(), $limit='')
	{
		// NOT IMPLEMENTED!
		return null;
	}
	
	protected function PrepareSimpleConditions($data)
	{
		if(!is_array($data))
		{
			return ' ';
		}
		
		$data = array_merge($data, array('pid='.$this->pid));
		
		return parent::PrepareSimpleConditions($data);
	}
}


class Settings extends PublishModule
{
	public function Settings($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_settings');
		
		$this->fields = array(
			'id', 'orderid', 'title', 'input_name', 'value', 'publish'
		);
	}
}

class PluginsSettings extends Module
{
	public function PluginsSettings($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_pcms_plugins');
		
		$this->fields = array(
			'id', 'settings'
		);
	}
	
	public function Add($data, $update = false){}
	public function Update($id, $data){}
	public function UpdateAll($conditions=array(), $data){}
	public function Delete($id){}
	public function DeleteAll($conditions=array(), $limit=''){}
	
	public function GetAllSettings()
	{
		$result = $this->SelectAll(array());
		$settings = array();
		while($row = $result->fetchRow())
		{
			if(isset($row['settings']) && !empty($row['settings']))
			{
				$settings[$row['id']] = @unserialize($row['settings']);
			}
		}
		
		return $settings;
	}
}
  
class NewsPositions
{
	const News    = 'news';
	const Main 	  = 'main';
	const Society = 'society';
	const Journey = 'journey';
	const Sport   = 'sport';
	const Auto 	  = 'auto';
	const Diary   = 'diary';
	const Culture = 'culture';
	const Moda 	  = 'moda';
	const ShowBusiness 	  = 'ShowBusiness';
}

class AfishaPositions
{

	const Moda 	  = 'moda';
	const ShowBusiness 	  = 'ShowBusiness';
}

class News extends PublishModule
{
	private $languages;
	
	public function News($conn, $prefix, $languages)
	{
		parent::__construct($conn, $prefix.'_news', 'rec_id');
		
		$this->languages = $languages;
		
		$this->fields = array(
			
			'id',
			'rec_id',
			'cat_id',   // multi ? comma\serialized?
			// 'mod_id', ?
			'lang',     // not single table...
			// 'in_other_lang',
			'position', // ?, multi ? comma\serialized?
			'creator',
			
			// data
			'title',
			'intro',
			'text',
			'author',
			
			'date',
			'display_date',
			'timestamp',
			
			'image',
			'display_image',
			
			// large_image ?
			'image_description',
			
			'display_print',
			'display_main_news',
			
			'related', // multi: serialized - href/title
			
			'commentaries_count',
			
			'visited',
			'voted_num',
			'voted_sum',
			
			'publish',
			'gallery'
		);
	}
	
	public function Add($data, $update, $currentLang)
	{
		if(!isset($currentLang))
		{
			return 0;
		}
		
		$query = 'SELECT MAX(rec_id) AS rec_id FROM '.$this->table.' LIMIT 1';
		
		$result = $this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg().' ['.$query.']');
		
		$newRecId = ((int)$result->fields['rec_id']) + 1;
		
		$data['rec_id'] = $newRecId;
		$data['lang'] = StringConvertor::qstr($currentLang);
		
		parent::Add($data, $update);
		
		unset($data['title']);
		unset($data['intro']);
		unset($data['text']);
		unset($data['author']);
		
		foreach($this->languages as $lang)
		{
			if($lang == $currentLang)
			{
				continue;
			}
			
			$data['lang'] = StringConvertor::qstr($lang);
			
			parent::Add($data, $update);
		}
		
		return $newRecId;
	}
	
	public function Update($id, $data, $currentLang)
	{
		if(!is_numeric($id) || !isset($currentLang))
		{
			return null;
		}
		
		$id = (int)$id;
		
		$this->UpdateAll(array('rec_id='.$id, 'lang='.StringConvertor::qstr($currentLang)), $data);
		
		unset($data['title']);
		unset($data['intro']);
		unset($data['text']);
		unset($data['author']);
		
		foreach($this->languages as $lang)
		{
			if($lang == $currentLang)
			{
				continue;
			}
			
			$this->UpdateAll(array('rec_id='.$id, 'lang='.StringConvertor::qstr($lang)), $data);
		}
		
		return null;
	}
	
	public function Select($id, $fields=array(), $currentLang)
	{
		if(!is_numeric($id) || !isset($currentLang))
		{
			return array();
		}
		
		$id = (int)$id;
		
		$result = $this->SelectAll(array('rec_id='.$id, 'lang='.StringConvertor::qstr($currentLang)), '1');
		
		return $result->fields;
	}
	
	public function Delete($id)
	{
		if(!is_numeric($id))
		{
			return null;
		}
		
		$id = (int)$id;
		
		return $this->DeleteAll(array('rec_id='.$id));
	}
	
	public function DeleteImage($id)
	{
		if(!is_numeric($id))
		{
			return null;
		}
		
		$id = (int)$id;
		
		return $this->UpdateAll(array('rec_id='.$id), array('image' => '\'\'', 'image_description' => '\'\''));
	}
	
	public function Publish($id, $publish = true)
	{
		if(!is_numeric($id))
		{
			return;
		}
		
		if($publish)
		{
			$query = 'UPDATE '.$this->table.' SET '.$this->publishField.'='.PublishStatus::Publish.' '.$this->PrepareSimpleConditions(array($this->idField.'='.$id));
		}
		else
		{
			$query = 'UPDATE '.$this->table.' SET '.$this->publishField.'='.PublishStatus::Unpublish.' '.$this->PrepareSimpleConditions(array($this->idField.'='.$id));
		}
		
		$this->conn->Execute($query) or ErrorLog::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
}

class NewsCategories extends CategoriesModule
{
	public function NewsCategories($conn, $prefix)
	{
		$this->prefix = $prefix;
		parent::__construct($conn, $prefix, PID_NEWS);           
		$this->fields = array('cat_id', 'cat_left', 'cat_right', 'cat_level', 'pid', 'name', 'hidden');
	}
}

class Commentaries extends PublishModule
{
	public function Commentaries($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_commentaries');
		
		$this->fields = array(
			
			'id',
			'news_id',
			'creator_id',
			'ip',
			'title',
			'text',
			'lang',
			'date',
			'timestamp',
			'publish',
		);
	}
}

class Rate extends Module
{
	public function Rate($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_rate');
		
		$this->fields = array(
			
			'id',
			'ip',
			'news_id',
			'timestamp',
		);
	}
}

class Gallery extends PublishModule
{
	public function Gallery($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_gallery');
		$this->fields = array(
		'id',
		'cat_id',
		'title',
		// 'image_description',
		'image',
		'creator',
		'date',
		'timestamp',
		'publish',
		);
	}
}

class AlbumGalleryFolders extends PublishModule
{
	public function AlbumGalleryFolders($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_gallery_album_folders');
		$this->fields = array(
		'id',
		'mid',
		'title',
		'sort_id',	
		'creator',
		'publish',
		);
	}
}

class AlbumGalleryImages extends PublishModule
{
	public function AlbumGalleryImages($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_gallery_album_images');
		$this->fields = array(
		'id',
		'mid',
		'title',
		'sort_id',	
		'creator',
		'publish',
		);
	}
}


class GalleryCategories extends CategoriesModule
{
	public function GalleryCategories($conn, $prefix)
	{
		$this->prefix = $prefix;
		parent::__construct($conn, $prefix, PID_GALLERY);           
		$this->fields = array('cat_id', 'cat_left', 'cat_right', 'cat_level', 'pid', 'name', 'hidden');
	}
}

class AlbumGalleryCategories extends CategoriesModule
{
	public function AlbumGalleryCategories($conn, $prefix)
	{
		$this->prefix = $prefix;
		parent::__construct($conn, $prefix, PID_ALBUM_GALLERY);           
		$this->fields = array('cat_id', 'cat_left', 'cat_right', 'cat_level', 'pid', 'name', 'hidden');
	}
}

class Video extends PublishModule
{
	public function Video($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_video');
		$this->fields = array(
		'id',
		'cat_id',
		'title',
		// 'video_description',
		'youtube_link',
		'video',
		'image',
		'creator',
		'date',
		'timestamp',
		'publish',
		);
	}
}

class VideoCategories extends CategoriesModule
{
	public function VideoCategories($conn, $prefix)
	{
		$this->prefix = $prefix;
		parent::__construct($conn, $prefix, PID_VIDEO);           
		$this->fields = array('cat_id', 'cat_left', 'cat_right', 'cat_level', 'pid', 'name', 'hidden');
	}
}


class SelectBlockMenu extends Module
{
	public function SelectBlockMenu($conn, $prefix)
	{
		parent::__construct($conn, $prefix.'_sitemenu');
		$this->fields = array('cat_id', 'cat_left', 'cat_right', 'cat_level', 'name', 'title', 'modul', 'redirect', 'type', 'blocked', 'structure','img','hidden');
	}
}



?>