<?
if(!defined('LANG')) define('LANG',"eng");
if(!defined('VALIDATOR_PATH')) define('VALIDATOR_PATH',dirname(__FILE__));

class StringConvertor{


  //****************************************************************//
  // Converts special symbols  to HTML mnemonics                    //
  // param $data - a string or an associative array                 //
  //****************************************************************// 
  public static function ConvertSpecialChars($data){
    if(is_array($data)){
         if(count($data)>0){     
            foreach($data as $k=>$v){
                if(is_array($v)) 
                  $data[$k]=StringConvertor::ConvertSpecialChars($v);
                else{  
                   #if(get_magic_quotes_gpc())  $v=stripslashes($v);
                   $v=StringConvertor::trimWhitespaces($v);
                   $data[$k]=htmlspecialchars($v,ENT_QUOTES);
                }   
            }
         } 
     }
     elseif(is_string($data)) {
         #if(get_magic_quotes_gpc())	$data=stripslashes($data);  
         $data=StringConvertor::trimWhitespaces($data);
         $data=htmlspecialchars($data,ENT_QUOTES);
     }
     return $data;     
  }

  //****************************************************************//
  // Removes dublicate whitespaces from string                      //
  // if $toUnderscroll=true whitespaces are converted to "_" symbol //  
  //****************************************************************//  
  public static function trimWhitespaces($str,$toUnderscroll=false){
     if($toUnderscroll){
       return preg_replace("/\s+/","_",trim($str));
     }  
     return preg_replace("/[\040\t]+/"," ",trim($str));
  }
  
  //***************************************************************//
  // Removes symbols described as regular expression $pattern      //
  // from string $str                                              //  
  //***************************************************************//
  public static function RemoveSymbols($str,$pattern){
    $str=preg_replace($pattern,"",$str);
    $str=$this->trimWhitespaces($str);
    return $str;
  } 
    
  
  public static function RandString($characters,$length){ 
    $n = strlen($characters)-1;
    $RandCode = "";
    srand((double)microtime() * 1000000);
    for($i=0; $i<$length; $i++)
    	$RandCode .= $characters[rand(0, $n)];
    return $RandCode;
  }



  
  public static function  uniqCode(){
    return md5(uniqid(""));
  }
  
  public static function toNatural($number){
    return abs(intval($number));
  }  
  
  
  public static function qstr($data){
     if(is_array($data)){
        foreach($data as $k=>$v){
           $data[$k]=self::qstr($v);
        }
     }
     
     elseif(is_string($data)){
        if(get_magic_quotes_gpc()){
  	       $data= stripslashes($data); 
  	    } 	
  	    $data = mysql_real_escape_string($data);
     }
     
  	 return $data;
  }
  
  
  
}



//***************************************************************//
//                                                               //
//      CLASS PROVIDES DATA VALIDATION                           //
//      EXTENDS  StringConvertor class                           //
//                                                               //  
//***************************************************************// 
class Validator extends  StringConvertor{

  private static $REGEX  =array(
        'DIGIT'         => '/^\d*$/',
        'FLOAT'         => '/^\d+(\.\d+)?$/',
        'HEX'           => '^[\dA-F]*$',
        'WORD'          => '/^[\w\s]*$/',
        'NON_WORD'      => '/^[^\w\s]*$/', 
        'WORD_EXTENDED' => '/^[\w\x7F-\xFF\s]*$/', 
        'LATIN'			=> '/^[a-zA-Z]*$/',
        'LATIN_EXTENDED'=> '/^[_a-zA-Z0-9]*$/',
        'LATIN_TEXT'    => '/^[_a-zA-Z0-9\s\.,]*$/',
        'PHONE'			=> '/^\+995\s\d{3}\s\d{6}$/',
        'MOBILE_PHONE'	=> '/^5\d{2}\d{6}$/',
        'EMAIL'         => '/^\w+([\.\w\-_]+)*\w@\w((\.\w)*\w+)*\.\w{2,4}$/',
        'BIRTHDAY'		=> '/^\d{4}-\d{2}-\d{2}$/',
        'TEXT'			=> '/^[\(\)0-9\w\+\s\-\"\'\x7F-\xFF]*$/',
   );
  
  private $ERRORS =array();

  function validateString($str,$regex,$str_title='',$_min=0,$_max=""){
    if(array_key_exists($regex,self::$REGEX)){
       $regex=self::$REGEX[$regex];
    }   
    if(!$this->validateLength($str,$str_title,$_min,$_max)){ 
      return false;
    }  
    if($_min==0 && strlen($str)==0){
      return true;
    }  
    if(!@preg_match($regex,$str)){
       $this->_fixError($str_title,'INVALID_SYMBOLS');
       return false;
    }
    return true;    
  }
  
  function compareValues($str1,$str2,$title=''){
   if($str1!=$str2)
     {
      $this->_fixError($title,'DO_NOT_MATCH');
      return false;
      }
   return true;  
  }
  
  function validateNumeric($num,$str_title='',$_min=0,$_max=""){
   if(!is_numeric($num))
     {
       $this->_fixError($str_title,'INVALID_SYMBOLS');
       return false; 
     }
   if($num<$_min)  
     {
       $this->_fixError($str_title,'SMALL_VALUE');
       return false; 
     }
   if($_max&&$num>$_max)  
     {
       $this->_fixError($str_title,'BIG_VALUE');
       return false; 
     }  
  }
  
  function validateLength($str,$str_title='',$_min=0,$_max=""){ 
    if(strlen($str)<$_min)
      {
      if(strlen($str)==0)
        $this->_fixError($str_title,'NULL_LENGTH');
      else  
        $this->_fixError($str_title,'LACK_LENGTH');
      return false;
      } 
    if($_max&&strlen($str)>$_max)
      {
      $this->_fixError($str_title,'EXCEED_LENGTH');
      return false;
      }  
    return true;
  }
   
  
  //***************************************************************//
  // $dateToCheck   - array(day,month,year)                        //
  // $yearMin, $yearMax - int                                      //
  //***************************************************************//
   
  function validateDate($dateToCheck,$str_title='',$yearMin="",$yearMax=""){
    for($i=0;$i<3;$i++)
      /*$dateToCheck[$i] = $this->ToNatural($dateToCheck[$i]);*/
    	print_r($dateToCheck);
    if(!checkdate($dateToCheck[1],$dateToCheck[0],$dateToCheck[2])){
       $this->_fixError($str_title,'INVALID_VALUE'); 
       return false;
    } 
    if($yearMin&&($dateToCheck[2]<$yearMin)){
       $this->_fixError($str_title,'INVALID_VALUE'); 
       return false;
    }
    if($yearMax&&($dateToCheck[2]>$yearMax)){ 
        $this->_fixError($str_title,'INVALID_VALUE'); 
        return false;
    } 
    return true;   
  }
  
  
  function validateDate2($date, $format = 'Y-m-d H:i:s')
  {
      $d = DateTime::createFromFormat($format, $date);
      if($d && $d->format($format) == $date){
      	return $d && $d->format($format) == $date;
      }else{
        $this->_fixError('BIRTHDAY','INVALID_VALUE'); 
      }
  }
  
  function findInSet($value,$set,$str_title=''){
     if(!in_array($value,$set)){
        $this->_fixError($str_title,'INVALID_VALUE'); 
        return false;
     }
     return true;
  } 
   
  
  //***************************************************************//
  // Returns error messages or FALSE                               //
  //***************************************************************// 
  function passErrors(){
       if(file_exists(VALIDATOR_PATH."/lang/validator_".LANG.".php")){
           include_once(VALIDATOR_PATH."/lang/validator_".LANG.".php");
       }    
       else{
          include_once(VALIDATOR_PATH."/lang/validator_eng.php");
       }   
       if(count($this->ERRORS)>0){
          
           for($i=0;$i<count($this->ERRORS);$i++)
              $err[]= $this->ERRORS[$i][0].$VALIDATOR_ERR[$this->ERRORS[$i][1]]."<br>";
           return implode("",$err);   
       }    
       return false;    
   }
  
   function getLastError(){
    require(VALIDATOR_PATH."/lang/validator_".LANG.".php");
    $cn = count($this->ERRORS)-1; 
    return $this->ERRORS[$cn][0].$VALIDATOR_ERR[$this->ERRORS[$cn][1]];
  } 
  
  //***************************************************************//
  //         FOR INTERNAL USE ONLY                                 //
  //                                                               //
  //***************************************************************// 
  function _fixError($title,$err_id){
      $this->ERRORS[]= array($title,$err_id);
  } 
 
}


?>