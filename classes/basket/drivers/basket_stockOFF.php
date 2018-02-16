<?

class stock_OFF extends stockCommon{
	public static function singleton($conn,$prefix) {
		if(!self::$instance) {
			self::$instance = new stock_OFF($conn,$prefix);
		}
		return self::$instance;
	}
		

	public function add($pid,$quantity=1) {
		return true;
	}
	
	public function take($pid,$quantity=1) {
		return $quantity;
	}
}

?>