<?
if(!defined('LANG')) define('LANG',"eng");

class basket_gifts {
	private $user;
	private $conn;
	private $tbl_basket;
	private $tbl_gift_info;
	private static $instance;
	
	private function __construct(&$conn,$prefix,$user) {
		$this->conn = &$conn;
		$this->user = $user;
		$this->tbl_basket	= $prefix.'_users_gifts';
		$this->tbl_gift_info= $prefix.'_card_gifts';
	}
	
	public static function &singleton(&$conn,$prefix,$user) {
		if(!self::$instance) {
			self::$instance = &new basket_gifts(&$conn,$prefix,$user);
		}
		return self::$instance;   
	}
	
	public static function &getInstance() {
		if(self::$instance) {
			return self::$instance;
		}
		else {
			return CommonFunc::ServerError(__FILE__,__LINE__,'Unable to get basket(gift) instance - object does not exists');
		}
	}
	
	public function add($gid,$quantity=1) {
		$gid		= StringConvertor::toNatural($gid);
		$quantity	= StringConvertor::toNatural($quantity);
		if ($quantity==0) {
			return false;
		}
		$query = "INSERT INTO ".$this->tbl_basket." SET user_id=".$this->user.", gid=".$gid.", quantity=".$quantity."
					ON DUPLICATE KEY UPDATE quantity=quantity+".$quantity;
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	public function update($gid,$quantity) {
		for ($i=0;$i<count($gid);$i++) {
			$gid[$i]		= StringConvertor::toNatural($gid[$i]);
			$quantity[$i]	= StringConvertor::toNatural($quantity[$i]);
	
			if($quantity[$i] == 0) {
				$this->_delete($gid[$i]);
			}
			else {
				$this->_update($gid[$i],$quantity[$i]);
			}
		}
	}
	
	public function cleanup() {
		$query = "DELETE FROM ".$this->tbl_basket." WHERE user_id='".$this->user."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	public function contents() {
		$query = "SELECT t0.quantity,t1.rec_id as id,t1.mid,t1.cid,t1.smiles,t1.title,t1.intro,t1.img
					FROM ".$this->tbl_basket." t0 
					LEFT JOIN ".$this->tbl_gift_info." t1 ON t0.gid=t1.rec_id
					WHERE t0.user_id='".$this->user."' and t1.lang='".LANG."'";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		return $result->GetRows();
	}
	
	private function _delete($gid) {
		$query = "DELETE FROM ".$this->tbl_basket." WHERE user_id='".$this->user."' AND gid='".$gid."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	private function _update($gid,$quantity){
		$query = "UPDATE  ".$this->tbl_basket." SET quantity='".$quantity."' WHERE user_id='".$this->user."' AND gid='".$gid."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}

}

?>