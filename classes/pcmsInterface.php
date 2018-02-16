<?

class pcmsInterface
{
    static function drawOptions()
    {
        $args = func_get_args();
        $numargs = func_num_args();
        $out = "";
        switch ($args[$numargs - 1]) {
            case(_ASSOC_):
                if (count($args[0]) > 0) {
                    foreach ($args[0] as $k => $v) {
                        $select = self::_selected($k, $args[1]);
                        $out .= "<option value=\"{$k}\" {$select}>{$v}</option>";
                    }
                }
                break;
            case(_RANGE_):
                for ($i = $args[0]; $i <= $args[1]; $i++) {
                    $select = self::_selected($i, $args[2]);
                    $out .= "<option value=\"{$i}\" {$select}>{$i}</option>";
                }
                break;
            case(_LINEAR_ASSOC_):
                for ($i = 0; $i < count($args[0]); $i++) {
                    $select = self::_selected($i, $args[1]);
                    $out .= "<option value=\"{$i}\" {$select}>{$args[0][$i]}</option>";
                }
                break;
            case(_LINEAR_):
                for ($i = 0; $i < count($args[0]); $i++) {
                    $select = self::_selected($args[0][$i], $args[1]);
                    $out .= "<option value=\"{$args[0][$i]}\" {$select}>{$args[0][$i]}</option>";
                }
                break;
            default:
                break;
        }
        return $out;
    }


    static function drawTabs($uri, $tabs = array(), $active = '')
    {
        $out = "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\" style=\"margin-bottom:20px\">
           <tr>";
        foreach ($tabs as $k => $v) {
            $style = $k == $active ? 'tab_active' : 'tab_inactive';
            $out .= "<td nowrap class=\"{$style}\">
                  <a href=\"{$uri}{$k}\" class=\"text1\">
                     {$v}
                   </a>  
             </td>";
        }
        $out .= "<td width=\"100%\" class=\"tab_devider\">&nbsp;</td>
          </tr>
         </table>";
        return $out;
    }

    static function drawModernTabs($uri, $tabs = array(), $active = '')
    {
        $out = '<div style="display: block; padding-bottom: 10px" class="preview-menu">
                <div id="cssmenu">
                    <ul>';
        foreach ($tabs as $k => $v) {

            $out2 = '';
            if (count($v) > 1) {

                $style = '';
                $out2 = '<ul >';
                foreach ($v['sub'] as $k2 => $v2) {
                    $out2 .= '<li class="' . $style . '" ><a href = "' . $uri . '' . $k2 . '" ><span >' . $v2 . '</span ></a ></li>';
                    if ($k2 == $active) {
                        $style = ' active';
                    }
                }
                $out2 .= '</ul>';
                $out .= '<li class="has-sub' . $style . '" ><a href = "#" ><span >' . $v['title'] . '</span ></a >';
                $out .= $out2;
                $out .= '</li >';

            } else {
                $style = $k == $active ? 'active' : '';
                $out .= '<li class="' . $style . '" ><a href = "' . $uri . '' . $k . '" ><span >' . $v['title'] . '</span ></a >';
            }
        }
        $out .= '</ul>
                </div>
           </div>';


        /*
        $out .="<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\" style=\"margin-bottom:20px\">
           <tr>";
        foreach($tabs as $k=>$v){
            $style = $k==$active?'tab_active':'tab_inactive';
            $out .="<td nowrap class=\"{$style}\">
                  <a href=\"{$uri}{$k}\" class=\"text1\">
                     {$v}
                   </a>
             </td>";
        }
        $out .="<td width=\"100%\" class=\"tab_devider\">&nbsp;</td>
          </tr>
         </table>";*/
        return $out;
    }

    //**********************************************************//
    //*** Private Methods  *************************************//
    static function _selected($param, $dataset = "")
    {
        return in_array((string)$param, (array)$dataset) ? "selected" : "";
    }

}

?>