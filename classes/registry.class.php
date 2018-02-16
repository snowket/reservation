<?
class Registry
{
	private static $instance;

	private $objects = array();

	private function __construct(){}

	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new Registry();
		}
		return self::$instance;
	}

	public function add($key, &$object)
	{
		$instance = self::$instance;
		$key = strtolower($key);
		if(array_key_exists($key, $instance->objects) === false)
		{
			$instance->objects[$key] = &$object;
		}
	}

	function &get($key)
	{
		$instance = self::$instance;
		$key = strtolower($key);
		if(array_key_exists($key, $instance->objects))
		{
			return $this->objects[$key];
		}
		return NULL;
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
}
?>