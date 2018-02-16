<?
if(!defined('LANG')) define('LANG',"eng");

class basket_services {
	private $user;
	private $conn;
	private $tbl_basket;
	private $tbl_item;
	private $tbl_item_info;
    private static $instance;
    
	private function __construct(&$conn,$prefix,$user) {
		$this->conn = &$conn;
		$this->user = $user;
		$this->tbl_basket		= $prefix.'_users_basket_services';
		$this->tbl_item			= $prefix.'_services';
		$this->tbl_item_info	= $prefix.'_services_info';
	}
	
	public static function &singleton(&$conn,$prefix,$user) {
		if(!self::$instance) {
			self::$instance = &new basket_services(&$conn,$prefix,$user);
		}
		return self::$instance;   
	}
	
	public static function &getInstance() {
		if(self::$instance) {
			return self::$instance;
		}
		else {
			return CommonFunc::ServerError(__FILE__,__LINE__,'Unable to get basket instance - object does not exists');
		}  
	}
	
	public function add($sid, $text="") {
		$sid	= StringConvertor::toNatural($sid);
		$text	= $text; #StringConvertor::toNatural($quantity);
		if ($sid==0) {
			return false;
		}
		$query = "INSERT INTO ".$this->tbl_basket." SET user_id=".$this->user.", sid=".$sid.", text=".$this->conn->qstr($text,get_magic_quotes_gpc()).", date=NOW()";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	public function update($sid) {
		for ($i=0, $n=count($sid); $i<$n; $i++) {
			$sid[$i]		= StringConvertor::toNatural($sid[$i]);
			
			if($quantity[$i] == 0) {
				$this->_delete($sid[$i]);
			}
			/*else {
				$this->_update($sid[$i],$text[$i]);
			}*/
		}
	}
	
	public function updateStatus($sid,$status) {
		for ($i=0, $n=count($sid); $i<$n; $i++) {
			$sid[$i]		= StringConvertor::toNatural($sid[$i]);
			
			if($quantity[$i] == 0) {
				$this->_updateStatus($sid[$i],$status[$i]);
			}
		}
	}
	
	public function cleanup() {
		$query = "DELETE FROM ".$this->tbl_basket." WHERE user_id='".$this->user."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	public function contents() {
		$query = "SELECT t0.id,t0.price,t0.text,t0.status,t0.date, t1.id as sid,t1.cat_id,t1.introimg, t2.title
					FROM ".$this->tbl_basket." t0
					LEFT JOIN ".$this->tbl_item." t1 ON t0.sid=t1.id
					LEFT JOIN ".$this->tbl_item_info." t2 ON t0.sid = t2.rec_id
					WHERE t0.user_id='".$this->user."' and t2.lang='".LANG."'";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		return $result->GetRows();
	}
	
	
	
	
	private function _delete($id) {
		$query = "DELETE FROM ".$this->tbl_basket." WHERE user_id='".$this->user."' AND id='".$id."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	private function _update($id,$text){
		$query = "UPDATE  ".$this->tbl_basket." SET text=".$this->conn->qstr($text,get_magic_quotes_gpc())." WHERE user_id='".$this->user."' AND id='".$id."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
	private function _updateStatus($id,$status){
		$query = "UPDATE  ".$this->tbl_basket." SET status=".$status." WHERE user_id='".$this->user."' AND id='".$id."' ";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
	}
	
}

?>