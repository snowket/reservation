<?

require_once(dirname(__FILE__).'/basket_stockCommon.inc.php');

class stock {
	public static function LoadDriver($conn,$prefix,$use_stock=false) {
		$postfix = $use_stock ? 'ON' : 'OFF';

		$driverClass = 'stock_'.$postfix;
		$driverPath  = dirname(__FILE__).'/drivers/basket_stock'.$postfix.'.php';
		 
		require_once($driverPath);    

		eval('$stock  = '.$driverClass.'::singleton($conn,$prefix);');

		return $stock;
	}   
}

?>