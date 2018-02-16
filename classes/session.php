<?

/*
session_start();
$s = new session($conn, $table, 'db_sess');
if(isset($_SESSION['db_sess'])){
  $s->ID($_SESSION['db_sess']);
}
$s->start();
$_SESSION['db_sess'] = $s->ID();


$s->save();
//$s->get('name');
*/

class session {
	public static $expire = 1441; // always from self::$expire; из вне меняется session::$expire;

	private $conn;
	private $sessid = false;
	private $table;
	private $name;
	private $data = Array();
	
	public function __construct($conn, $table, $name){
		$this->conn	 = $conn;
		$this->table = $table;
		$this->name	 = $name;
	}
	
	public function start(){
		$expire = $this->_expireTime();
        
		if(!$sessid = $this->sessid) { 
			if		(isset($_COOKIE[$this->name]))	$sessid = $this->_clean($_COOKIE[$this->name]);
			elseif	(isset($_GET[$this->name]))		$sessid = $this->_clean($_GET[$this->name]);
			elseif	(isset($_POST[$this->name]))	$sessid = $this->_clean($_POST[$this->name]);
		} 
		if($sessid) {
			$query = 'SELECT * FROM '.$this->table.' WHERE id="'.$sessid.'" and expiry > "'.time().'"';
			$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
			if($result->fields){
				//### update session
				$query_u  = 'UPDATE '.$this->table.' SET expiry="'.$expire.'" WHERE id="'.$sessid.'"';
				$result_u = $this->conn->Execute($query_u) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
				
				$this->sessid = $result->fields['id'];
				$this->data   = $this->_unpack($result->fields['value']); 
				
				//### update cookie
				@setcookie($this->name,$this->sessid,$expire,'/','');
				return true; 
			}
		}

		$this->_start_genId();
		@setcookie($this->name,$this->sessid,$expire,'/','');
	}
	
	public function ID($id=false){
		if ($id) {$this->sessid = $id; return;}
		return $this->sessid;
	}
	
	public function __toString(){
		print '<pre>';
		print_r($this->data);
		print '<pre>';
		return '';
	}
	
	public function get($param){ return isset($this->data[$param])?$this->data[$param]:false; }
	
	public function getAll(){ return $this->data; }
	
	public function set($param,$value){ $this->data[$param] = $value; }
	
	public function set_array($array = array()){
		 $this->data = array_merge($this->data, $array);
	}
	
	public function undef($param){ unset($this->data[$param]); }
	
	public function save(){
		$expiry = $this->_expireTime();
		$data   = $this->_pack();
		$query = 'INSERT INTO '.$this->table.' (id,expiry,value) VALUES (\''.$this->sessid.'\',\''.$expiry.'\',\''.$data.'\')
				  ON DUPLICATE KEY UPDATE expiry=\''.$expiry.'\', value =\''.$data .'\'';
		$result=$this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		@setcookie($this->name,$this->sessid,$expiry,'/','');
	}
	

	//Private functions
	private function _unpack($string){ return unserialize($string); }

	private function _pack(){ return serialize($this->data); }
	
	private function _clean($sessid){ return preg_replace('/[^a-z\d]/i','',$sessid); }

	private function _expireTime(){ return time()+ self::$expire; }

	private function _genId(){ return md5( md5(time()) . md5(uniqid()) ); }

	private function _start_genId(){ 
		$this->sessid = $this->_genId();
		$this->data   = Array();
	}
	
	public function destroy() {
		if(isset($_COOKIE[$this->name])) @setcookie($this->name, '', time()-86400, '/','');
		
		$query = 'DELETE FROM '.$this->table.' WHERE id="'.$this->sessid.'"';
		$result=$this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		
		$this->data   = array();
		$this->sessid = false;
	}
	
	public function gc(){
		$query = 'DELETE FROM '.$this->table.' WHERE expiry < "'.time().'"';
		$result=$this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
     }
}

?>