<? if (!defined('ALLOW_ACCESS')) exit;



if ($_POST['action'] == "update") {
    $value = json_encode($_POST);
    $query = "UPDATE {$_CONF['db']['prefix']}_hotel_settings SET value = '" . $value . "' WHERE input_name = 'theme'";
    $res = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    generateCustomCss($_POST);
    $FUNC->Redirect($SELF);
}

$query = "SELECT * FROM {$_CONF['db']['prefix']}_banner_carusel";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$banner_images=$result->GetRows();
$banner_image=$banner_images[0]['img'];

$query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_settings WHERE input_name='theme'";
$theme = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$theme_array= json_decode($theme['value'],true);

$TMPL->addVar("theme",$theme_array);
$TMPL->addVar("banner_image",$banner_image);

$TMPL->ParseIntoVar($_CENTER, 'theme');

function generateCustomCss($data){
    $css_content="
.body_back_color_selector{
    background-color:{$data['body_back_color_selector']};
}
.header_back_color_selector{
    background-color:{$data['header_back_color_selector']};
}
.menu_back_color_selector{
    background-color:{$data['menu_back_color_selector']};
}
.active_menu_back_color_selector{
    background-color:{$data['active_menu_back_color_selector']};
    color:{$data['active_menu_font_color_selector']};
}
.jquerycssmenu ul li:hover{
 background:{$data['active_menu_back_color_selector']};
    color:{$data['active_menu_font_color_selector']};
}
.task_items a{
color:{$data['header_font_color_selector']} !important;
}
.passive_menu_back_color_selector{
    background-color:{$data['passive_menu_back_color_selector']};
    color:{$data['passive_menu_font_color_selector']};
}
.carousel_gradient_color_selector{
    background:{$data['carousel_gradient_color_selector']};
    background:-webkit-linear-gradient(bottom,rgba(".hex2rgb($data['carousel_gradient_color_selector']).",0),rgba(".hex2rgb($data['carousel_gradient_color_selector']).",1));
    background:-o-linear-gradient(bottom,rgba(".hex2rgb($data['carousel_gradient_color_selector']).",0),rgba(".hex2rgb($data['carousel_gradient_color_selector']).",1));
    background:-moz-linear-gradient(bottom,rgba(".hex2rgb($data['carousel_gradient_color_selector']).",0),rgba(".hex2rgb($data['carousel_gradient_color_selector']).",1));
    background:linear-gradient(to bottom,rgba(".hex2rgb($data['carousel_gradient_color_selector']).",0),rgba(".hex2rgb($data['carousel_gradient_color_selector']).",1));

}
.carousel_font_color_selector{
    color:{$data['carousel_font_color_selector']};
}
.footer_back_color_selector{
    background-color:{$data['footer_back_color_selector']};
}
.footer_font_color_selector{
    color:{$data['footer_font_color_selector']};
}

.arrow_shadow_back_color_selector{
        border-bottom: 20px solid {$data['arrow_shadow_back_color_selector']};
}
.arrow_back_color_selector{
        background-color: {$data['arrow_back_color_selector']} !important;
}
.button_back_color_selector{
        background-color: {$data['button_back_color_selector']};
}
.button_font_color_selector{
        color: {$data['button_font_color_selector']};
}

";
    $path_to_file = RESERVATION_ROOT . "/css/custom.css";

    file_put_contents($path_to_file, $css_content);

}

function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    $rgb = array($r, $g, $b);
    return implode(",", $rgb); // returns the rgb values separated by commas
    //return $rgb; // returns an array with the rgb values
}