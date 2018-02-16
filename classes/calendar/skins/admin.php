<?
$CALENDAR ="
          <table cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%;\" class=\"text1\">
          <tr>
          <td>  
            <table width=\"100%\" border=\"0\" cellpading=\"0\" cellspacing=\"0\" class=\"CAL_header\">
              <tr>
                <td width=\"70\">
                   <select name=\"calendar_m\" id=\"calendar_m\" class=\"formField1\">";
                     for($i=1;$i<=12;$i++)
                       {
                         $selected = $i==$this->MONTH?"selected":"";
$CALENDAR .=             "<option value=\"{$i}\" {$selected}>{$TEXT['month'][$i-1]}</option>";                       
                       }
$CALENDAR .=       "</select>                  
             
                </td>            
                <td width=\"50\">
                  <select name=\"calendar_y\" id=\"calendar_y\"  class=\"formField1\">";
                    for($i=$this->MIN_YEAR;$i<=$this->MAX_YEAR;$i++)
                      { 
                        $selected = $i==$this->YEAR?"selected":"";
$CALENDAR .=            "<option value=\"{$i}\" {$selected}>{$i}</option>";
                      }                    
$CALENDAR .=     "</select>
                </td>
                <td align=\"center\" valign=\"bottom\">
                  <a href=\"javascript:send()\" class=\"CAL_button\"><img src=\"./images/icos16/refresh.gif\" width=\"16\" height=\"16\" border=\"0\"></a>
                </td>
              </tr> 
            </table>  
          </td>
          </tr>
          <tr>
           <td>
            <table cellpadding=\"1\" cellspacing=\"1\" border=\"0\" style=\"width:100%;\"  class=\"CAL_main\">
              <tr>";
                for($i=0;$i<6;$i++)
                $CALENDAR .= "<td align=\"center\" class=\"CAL_day\" style=\"background:#fff;\">".$TEXT['dayweek'][$i]."</td>";
                $CALENDAR .= "<td align=\"center\" class=\"CAL_day\" style=\"background:#fff;color:#900\"><b>".$TEXT['dayweek'][$i]."</b></td>";
$CALENDAR .="</tr>";
            foreach ($CAL as $row) 
             {
$CALENDAR .="<tr>";
               foreach ($row as $i=>$v)
$CALENDAR .=   "<td style=\"background:#F5F5F5;\">{$v}</td>";              
$CALENDAR .="</tr>";
             }  
$CALENDAR .="</table>
            </td>
          </tr>
          </table>";
if($this->YEAR==date('Y')&&$this->MONTH==date('n'))
  $CALENDAR=preg_replace("/#F5F5F5;\">".date('j')."<\/td>/","#fff;\">".date('j')."</td>",$CALENDAR);          
?> 

