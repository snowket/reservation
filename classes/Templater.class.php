<?

class Templater{
	private $rootDir   = "";
	private $GLOBALS   = array();
	private $variables = array();
	private $globals   = array();
	private $templates_extension = '.php'; // .tpl
 
	private static $instance;
 
	private function __construct($path=''){
		$this->setRoot($path);
	}
	
	public static function getInstance(){
		if(!self::$instance){
			self::$instance  = new Templater();
		}
		return self::$instance;
	}

	public function setRoot($path){
		$this->rootDir = $path;
	}

	public function registerGlobal($var_name){
		$this->GLOBALS[] = $var_name;
	}
 
	public function setGlobalVar($var_name,$var_value){
		$this->globals[$var_name] = $var_value;
		unset($var_value);
	}

	public function setGlobalVars($var_array){
		$this->globals = array_merge($this->globals,$var_array);
	}

	public function addVar($template,$var_name,$var_value){
		$this->variables[$template][$var_name] = $var_value;
		unset($var_value);
	}
	
	
	
	public  function addVars($template,$var_array){
		$this->variables[$template] = array_merge($this->variables[$template],$var_array);
		unset($var_array);
	}
   
	public function parseIntoTemplate($templateTo,$tmpl_var,$templateFrom){
		$this->appendToVar($templateTo,$tmpl_var,$this->parseIntoString($templateFrom));
	}
	
	public function parseIntoString($template){
		for($i=0;$i<count($this->GLOBALS);$i++){
			global $$this->GLOBALS[$i];
		}
		if($this->globals)              { extract($this->globals,EXTR_OVERWRITE); }
		if($this->variables[$template]) { extract($this->variables[$template],EXTR_OVERWRITE); }
		// ob_start(array ( &$this , 'parse') ); 
		ob_start();
		require($this->rootDir."/".$template.$this->templates_extension);
		$parsed  = ob_get_clean();
		return $parsed; 
	}  
	
	public function displayParsedTemplate($template){
		for($i=0;$i<count($this->GLOBALS);$i++){
			global $$this->GLOBALS[$i];
		}
		if($this->globals)              { extract($this->globals,EXTR_OVERWRITE); }
		if($this->variables[$template]) { extract($this->variables[$template],EXTR_OVERWRITE); }
		require($this->rootDir."/".$template.$this->templates_extension); 
	}
	
	public function appendToVar($template,$var_name,$string_data){
		$this->variables[$template][$var_name] .= $string_data;
	}
    
	public  function prependToVar($template,$var_name,$string_data){
		$this->variables[$template][$var_name] = $string_data.$this->variables[$template][$var_name];
	}
	
	public function clearTemplate($template){
		if($this->variables[$template])
		unset($this->variables[$template]);
	}
	
	public function cleanGlobals(){
		$this->globals= array();
	}
	
	private function parse(){
		
	}
}

?>