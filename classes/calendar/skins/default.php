<?
$CALENDAR ='
          <table border="0" cellpadding="2" cellspacing="0" style="width:10%;" align="center">
          <tr>
           <td>
            <table border="0" cellpadding="2" cellspacing="1" style="width:100%;"  class="CAL_main">
              <tr>';
                for($i=0;$i<6;$i++)
                $CALENDAR .= "<td align=\"center\" class=\"CAL_day\" style=\"background:#fff;\">".$TEXT['dayweek'][$i]."</td>";
                $CALENDAR .= "<td align=\"center\" class=\"CAL_day\" style=\"background:#fff;color:#A33905\"><b>".$TEXT['dayweek'][$i]."</b></td>";
$CALENDAR .="</tr>";
            foreach ($CAL as $row) 
             {
$CALENDAR .="<tr>";
               foreach ($row as $i=>$v)
$CALENDAR .=   "<td>{$v}</td>";              
$CALENDAR .="</tr>";
             }  
$CALENDAR .='</table>
            </td>
          </tr>
          <tr>
          <td align="right" valign="middle">  
            <table border="0" cellpadding="2" cellspacing="0" class="CAL_header">
              <tr>
                <td>
                   <select name="calendar_m" id="calendar_m">';
                     for($i=1;$i<=12;$i++)
                       {
                         $selected = $i==$this->MONTH?"selected":"";
$CALENDAR .=             "<option value=\"{$i}\" {$selected}>{$TEXT['month'][$i-1]}</option>";                       
                       }
$CALENDAR .=       "</select>                  
             
                </td>            
                <td valign=\"middle\">
                  <select name=\"calendar_y\" id=\"calendar_y\">";
                    for($i=$this->MIN_YEAR;$i<=$this->MAX_YEAR;$i++)
                      { 
                        $selected = $i==$this->YEAR?"selected":"";
$CALENDAR .=            "<option value=\"{$i}\" {$selected}>{$i}</option>";
                      }                    
$CALENDAR .=     '</select>
                </td>
                <td align="center" valign="middle">
                  <input onclick="javascript:send(\''.$_LINK.'\')" type="button" value="&raquo;" class="CAL_input">
                </td>
              </tr> 
            </table>  
          </td>
          </tr>          
          </table>';
if($this->YEAR==date('Y')&&$this->MONTH==date('n'))
	$CALENDAR=preg_replace("/#F5F5F5;\">".date('j')."<\/td>/","#fff;\">".date('j')."</td>",$CALENDAR);          
?> 

