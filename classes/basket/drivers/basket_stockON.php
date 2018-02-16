<?

class stock_ON extends stockCommon{
	public static function singleton($conn,$prefix) {
		if(!self::$instance) {
			self::$instance = new stock_ON($conn,$prefix);
		}
		return self::$instance;
	}

	public function add($pid,$quantity=1) {
		$query	= "UPDATE ".$this->tbl_prod." SET quantity=quantity+{$quantity} WHERE id='{$pid}'";
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		return true;
	}
	
	public function take($pid,$quantity=1) {
		$quantity = min($quantity,$this->get($pid));
		$query	= "UPDATE ".$this->tbl_prod." SET quantity=quantity-{$quantity} WHERE id='{$pid}'";//p($query);
		$result = $this->conn->Execute($query) or CommonFunc::ServerError(__FILE__,__LINE__,$this->conn->ErrorMsg());
		return $quantity;
	}
}

?>