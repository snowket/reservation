<?

require(dirname(__FILE__).'/basket_stock.class.php');

class basket {
	private $user;
	private $conn;
	private $tbl_basket;
	private $tbl_prod;
	private $tbl_prod_info;
	private $stock;
	public static $use_stock = false;
	private static $instance;


	private function __construct($conn,$prefix,$user) {
		$this->conn = $conn;
		$this->user = $user;
		$this->tbl_basket = $prefix.'_users_basket';
		$this->tbl_prod = $prefix.'_prod';
		$this->tbl_prod_info = $prefix.'_prod_info';
		$this->stock = stock::LoadDriver($conn,$prefix,self::$use_stock);
	}
	
	public static function singleton($conn,$prefix,$user) {
		if(!self::$instance) {
			self::$instance = new basket($conn,$prefix,$user);
		}
		return self::$instance;   
	}
	
	 public static function getInstance() {
       if(self::$instance) {
             return self::$instance;
         }
       else {
             return CommonFunc::ServerError(__FILE__,__LINE__,'Unable to get basket instance - object does not exists');
        }  
  	}
	
	public function add($pid,$quantity=1) {
		$pid		= StringConvertor::toNatural($pid);
		$quantity	= StringConvertor::toNatural($quantity);
		if ($quantity==0 || ($quantity = $this->stock->take($pid,$quantity))==0) {
			return false;
		}
		$query = "INSERT INTO ".$this->tbl_basket." SET user_id=".$this->user.", pid=".$pid.", quantity=".$quantity.", date='".mktime()."'
					ON DUPLICATE KEY UPDATE quantity=quantity+".$quantity.", date='".mktime()."'";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	public function update($pid,$quantity) {
		for ($i=0,$n=count($pid); $i<$n; $i++) {
			$pid[$i]		= StringConvertor::toNatural($pid[$i]);
			$quantity[$i]	= StringConvertor::toNatural($quantity[$i]);
			
			if($quantity[$i] == 0) {
				$this->_delete($pid[$i]);
			}
			else {
				$this->_update($pid[$i],$quantity[$i]);
			}
		}
	}
	
	public function cleanup() {
		$query = "DELETE FROM ".$this->tbl_basket." WHERE user_id='".$this->user."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	public function contents() {
		$query = "SELECT t0.quantity,t1.id,t1.cat_id,t1.price, t2.title FROM ".$this->tbl_basket." t0 
				  LEFT JOIN ".$this->tbl_prod." t1 ON t0.pid=t1.id
			      LEFT JOIN ".$this->tbl_prod_info." t2 ON t0.pid = t2.rec_id
				  WHERE t0.user_id='".$this->user."' and t2.lang='".LANG."'";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		return $result->GetRows();
	}
	
	public function get_quantity($pid) {
		$query = "SELECT quantity FROM ".$this->tbl_basket." WHERE user_id='".$this->user."' AND pid='".$pid."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		return $result->fields['quantity'];
	}
	
	
	private function _delete($pid) {
		$current_quantity = $this->get_quantity($pid);
		$this->stock->add($pid,$current_quantity);
		$query = "DELETE FROM ".$this->tbl_basket." WHERE user_id='".$this->user."' AND pid='".$pid."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	private function _update($pid,$quantity){
		$current_quantity = $this->get_quantity($pid);
		if(!$current_quantity){
			return;
		}
		$delta_quantity = $quantity-$current_quantity;
		$quantity = $this->stock->take($pid,$delta_quantity);
		
		$query = "UPDATE  ".$this->tbl_basket." SET quantity=quantity+'".$quantity."', date='".mktime()."' WHERE user_id='".$this->user."' AND pid='".$pid."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
}

?>