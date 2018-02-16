<?
if(!defined('CALENDAR_DIR')) define('CALENDAR_DIR',dirname(__FILE__));

class CALENDAR{
	var $DATE_VAR;
	var $YEAR;
	var $MONTH;
	var $MIN_YEAR;
	var $MAX_YEAR;
	var $MAX_MONTH;
	var $TIMESTAMP;
	var $ERRORS;
	var $ACTIVE_DATES =array();
  
	function CALENDAR($min_year="1970",$max_year="",$date_var="date") {
		$this->MIN_YEAR  =($min_year>1970&&$min_year<=date('Y'))?$min_year:1970;
		$this->MAX_YEAR  =($max_year>=$this->MIN_YEAR&&$max_year<=date('Y'))?$max_year:date('Y');
		$this->DATE_VAR =$date_var;
		if(isset($_GET[$date_var])&&!empty($_GET[$date_var]))
			$this->_setDate($_GET[$date_var]);
		else{
			$this->YEAR  = $this->MAX_YEAR;
			$this->MONTH = $this->MAX_MONTH =($this->YEAR==date('Y'))?date('n'):"12";
		}
 	}
   
 function _setDate($_date)
   { 
    $d=explode(".",$_date);
    if($d[0]>=$this->MIN_YEAR&&$d[0]<=$this->MAX_YEAR)
       $this->YEAR =$d[0];
    else 
       $this->YEAR = $this->MAX_YEAR;  
            
    if($this->YEAR==date('Y'))
        $this->MONTH =($d[1]<=date('n')&&$d[1]>0)?$d[1]:date('n'); 
    else
        $this->MONTH =($d[1]<=12&&$d[1]>0)?$d[1]:12;  
    $this->MAX_MONTH =($this->YEAR==date('Y'))?date('n'):"12";   
   } 


	function getActiveDays($conn_id,$table,$field='date',$add_query=""){
		$query = "select DATE_FORMAT({$field},'%e') as date from {$table} where YEAR({$field})='{$this->YEAR}' and MONTH({$field})='{$this->MONTH}' ".$add_query;
		$result = $conn_id->Execute($query);
		if(!$result)
		{
		$this->ERRORS = $conn_id->ErrorMsg();
		return false;
		}      
		while($o = $result->FetchNextObject()) 
		$this->ACTIVE_DATES[] = $o->DATE;
	}
   
  function getActiveDays2($conn_id,$table,$field='date',$add_query=""){
  	    $date1 = mktime(0,0,0,$this->MONTH,1,$this->YEAR);
  	    $date2 = mktime(0,0,0,$this->MONTH+1,1,$this->YEAR);
		$query = "SELECT {$field} AS date FROM {$table} WHERE {$field}>={$date1} AND {$field}<={$date2} ".$add_query;
		$result = $conn_id->Execute($query);
		if(!$result) {
			$this->ERRORS = $conn_id->ErrorMsg();
			return false;
		}
		while($row = $result->FetchRow())
			$this->ACTIVE_DATES[] = Date('j',$row['date']);
	}    
    
   
 function printCalendar($_link,$lang="eng",$skin="default",$make_links="true"){    
    $_LINK =$_link.$this->DATE_VAR."=";   
    $firstday=date("w",mktime(0,0,0,$this->MONTH,1,$this->YEAR));    // day of week 
    if($firstday==0) $firstday=7;
    $lastday=date("d",mktime(0,0,0,$this->MONTH+1,0,$this->YEAR));   // last day of the month
    $CAL= array();
    $d= 02-$firstday;
    $CAL_table ="";
    for($i=0;$i<=ceil(($lastday-8+$firstday)/7);$i++)
	   {
	   $row=array();
	   for($j=0;$j<7;$j++,$d++)
	     {
	       if(checkdate($this->MONTH, $d, $this->YEAR))
	          {
	            if(in_array($d,$this->ACTIVE_DATES)) 
	              $row[]="<a href=\"{$_LINK}{$this->YEAR}.{$this->MONTH}.{$d}\">{$d}</a>";
	            else
	              $row[]=$d;  
	          }
	        else $row[] = "&nbsp;";
	     }
	   $CAL[$i]=$row;   
       }  
 
    if(file_exists(CALENDAR_DIR."/lang/calendar_{$lang}.php"))
       include(CALENDAR_DIR."/lang/calendar_{$lang}.php");
    else
       include(CALENDAR_DIR."/lang/calendar_eng.php");  
    include(CALENDAR_DIR."/skins/{$skin}.php");
    return $CALENDAR;   
   }     
}
?>