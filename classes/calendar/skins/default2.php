<?
$CALENDAR ="
          <table cellpadding=\"0\" border=\"0\" cellspacing=\"0\" ALIGN=\"center\" style=\"width:100%; background:url(./images/blocks_bg.gif) #939598 repeat-x;\">
          <tr>
           <td>
            <table cellpadding=\"1\" cellspacing=\"1\" border=\"0\" style=\"width:100%;\"  class=\"CAL_main\">
              <tr>";
                for($i=0;$i<6;$i++)
                {
                   $CALENDAR .= "<td align=\"center\" class=\"CAL_day\" style=\"background:transparent;\">".$TEXT['dayweek'][$i]."</td>";
                }
                $CALENDAR .= "<td align=\"center\" class=\"CAL_day\" style=\"background:transparent;color:#A33905\"><b>".$TEXT['dayweek'][$i]."</b></td>";
$CALENDAR .="</tr>";
            foreach ($CAL as $row) 
             {
$CALENDAR .="<tr>";
               foreach ($row as $i=>$v)
$CALENDAR .=   "<td>{$v}</td>";              
$CALENDAR .="</tr>";
             }  
$CALENDAR .="</table>
            </td>
          </tr>
          <tr>
          <td align=\"right\">  
            <table cellpadding=\"4\" cellspacing=\"0\" class=\"CAL_header\">
              <tr>
                <td>
                   <select name=\"calendar_m\" id=\"calendar_m\" style=\"font-size:12px;\">";
                     for($i=1;$i<=12;$i++)
                       {
                         $selected = $i==$this->MONTH?"selected":"";
$CALENDAR .=             "<option value=\"{$i}\" {$selected}>{$TEXT['month'][$i-1]}</option>";                       
                       }
$CALENDAR .=       "</select>                  
             
                </td>            
                <td>
                  <select name=\"calendar_y\" id=\"calendar_y\" style=\"font-size:12px;\">";
                    for($i=$this->MIN_YEAR;$i<=$this->MAX_YEAR;$i++)
                      { 
                        $selected = $i==$this->YEAR?"selected":"";
$CALENDAR .=            "<option value=\"{$i}\" {$selected}>{$i}</option>";
                      }                    
$CALENDAR .=     "</select>
                </td>
                <td align=\"center\" valign=\"middle\"><input onclick=\"javascript:send('{$_LINK}')\" type=\"button\" value=\"&raquo;\" class=\"\" id=\"button\"></td>
              </tr> 
            </table>  
          </td>
          </tr>          
          </table>";
          		
          		
     		
if($this->YEAR==date('Y')&&$this->MONTH==date('n'))
{
   $CALENDAR = preg_replace("/<td>".date('j')."<\/td>/","<td class=\"CAL_today\" style=\"color:#FFF; background:url('./images/square_red.gif') center center no-repeat;\"><b>".date('j')."</b></td>",$CALENDAR);
   $href = $this->_LINK.date('Y').".".date('n').".".date('j');

   $reg_href = str_replace("?","\?",$href);
   $reg_href = str_replace(".","\.",$reg_href);

   $CALENDAR = preg_replace("/<td><a href=\"".$reg_href."\">".date('j')."<\/a>/",  
          "<td class=\"CAL_today\"><a href=\"".$href."\" style=\"color:#FFF;\">".date('j')."</a>",$CALENDAR);       
} 
?>
