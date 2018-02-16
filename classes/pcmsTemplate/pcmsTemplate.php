<?
class pcmsTemplate{

 var $rootDir  = "";
 var $VARIABLES = array();
 var $globalVars = array();
 
 function setRoot($path){
     $this->rootDir = $path;
   }
 
 function importVars($var_name){
     $this->globalVars[] = $var_name;
   }
   
 function addVar($var_name,$var_value){
     $this->VARIABLES[$var_name] = $var_value;
   }
 
  function addVars($var_array){
     array_push($this->VARIABLES,$var_array);
   }
   
   
 function ParseIntoTemplate($tmpl_var,$template){
       $this->VARIABLES[$tmpl_var] = '';
       $this->ParseIntoVar($this->VARIABLES[$tmpl_var],$template);
   }  
   
 function ParseIntoVar(&$var_name,$template){
     for($i=0;$i<count($this->globalVars);$i++){
       global $$this->globalVars[$i];
     }   
     ob_start();
     if($this->VARIABLES){
       extract($this->VARIABLES,EXTR_OVERWRITE);
     }  
     require($this->rootDir."/templates/".$template.".template.php");
     $var_name .=ob_get_contents();
     ob_clean();
   }  
  
  
 	public function parseIntoString($template){
        for($i=0,$n=count($this->globalVars);$i<$n;$i++){
          global $$this->globalVars[$i];
        }

		if($this->VARIABLES){
            extract($this->VARIABLES,EXTR_OVERWRITE);
        }  
		// ob_start(array ( &$this , 'parse') ); 
		ob_start();
		require($this->rootDir."/templates/".$template.".template.php");
		$parsed  = ob_get_clean();
		return $parsed; 
	}   
  
  
 function appendToVar(&$var_name,$str_data){
      $var_name .= $str_data;
   }
    
 function prependToVar(&$var_name,$str_data){
      $var_name = $str_data.$var_name;
   }
   

 function cleanVars(){
     unset($this->VARIABLES);
   }

}
?>