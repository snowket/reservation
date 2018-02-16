<?

abstract class stockCommon {
	protected $conn;
	protected $tbl_prod;
	protected static $instance;
	
	protected function __construct($conn,$prefix) {
		$this->conn = $conn;
		$this->tbl_prod = $prefix.'_prod';
	}
	
	public static function getInstance() {
		if(self::$instance) {
			return self::$instance;
		}
		else {
			return CommonFunc::ServerError(__FILE__,__LINE__,'Unable to get stock instance - object does not exists');
		}
	}
		
	public function get($pid) {
		$query	= "SELECT * FROM ".$this->tbl_prod." WHERE id='{$pid}'";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		$row	= $result->fields;
		return $row['quantity'];
	}
	
	abstract public function add($pid,$quantity=1);
	abstract public function take($pid,$quantity=1);
}

?>