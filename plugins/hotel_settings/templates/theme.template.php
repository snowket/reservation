<?if(!defined('ALLOW_ACCESS')) exit;?>

<link rel="stylesheet" media="screen" type="text/css" href="js/jquery/colorpicker/css/colorpicker.css" />
<link rel="stylesheet" media="screen" type="text/css" href="../css/custom.css" />
<script type="text/javascript" src="js/jquery/colorpicker/js/colorpicker.js"></script>
<form method="post">
    <input type="hidden" name="action" value="update">

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b>Configure</b>
    </div>
    <table width="100%">
        <tr>
            <td>
                <table cellpadding="2">
                    <tr>
                        <td align="right">Body Bg Color</td>
                        <td bgcolor="<?=$theme['body_back_color_selector']?>" class="custom-td"><input id="body_back_color_selector" type="text" name="body_back_color_selector" value="<?=$theme['body_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Header Bg Color</td>
                        <td bgcolor="<?=$theme['header_back_color_selector']?>" class="custom-td"><input id="header_back_color_selector" type="text" name="header_back_color_selector" value="<?=$theme['header_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Header Font Color</td>
                        <td bgcolor="<?=$theme['header_font_color_selector']?>" class="custom-td"><input id="header_font_color_selector" type="text" name="header_font_color_selector" value="<?=$theme['header_font_color_selector']?>"></td>
                    </tr>


                    <tr>
                        <td align="right">Footer BG Color</td>
                        <td bgcolor="<?=$theme['footer_back_color_selector']?>" class="custom-td"><input id="footer_back_color_selector" type="text" name="footer_back_color_selector" value="<?=$theme['footer_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Footer Font Color</td>
                        <td bgcolor="<?=$theme['footer_font_color_selector']?>" class="custom-td"><input id="footer_font_color_selector" type="text" name="footer_font_color_selector" value="<?=$theme['footer_font_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Carousel Gradient Color</td>
                        <td bgcolor="<?=$theme['carousel_gradient_color_selector']?>" class="custom-td"><input id="carousel_gradient_color_selector" type="text" name="carousel_gradient_color_selector" value="<?=$theme['carousel_gradient_color_selector']?>"></td>
                    </tr>
                </table>
            </td>
            <td>
                <table cellpadding="2" border="0">
                    <tr>
                        <td align="right">Arrow BG color</td>
                        <td bgcolor="<?=$theme['arrow_back_color_selector']?>" class="custom-td"><input id="arrow_back_color_selector" type="text" name="arrow_back_color_selector" value="<?=$theme['arrow_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Arrow Shadow BG color</td>
                        <td bgcolor="<?=$theme['arrow_shadow_back_color_selector']?>" class="custom-td"><input id="arrow_shadow_back_color_selector" type="text" name="arrow_shadow_back_color_selector" value="<?=$theme['arrow_shadow_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Button BG color</td>
                        <td bgcolor="<?=$theme['button_back_color_selector']?>" class="custom-td"><input id="button_back_color_selector" type="text" name="button_back_color_selector" value="<?=$theme['button_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Button Font color</td>
                        <td bgcolor="<?=$theme['button_font_color_selector']?>" class="custom-td"><input id="button_font_color_selector" type="text" name="button_font_color_selector" value="<?=$theme['button_font_color_selector']?>"></td>
                    </tr>

                    <tr>
                        <td align="right">Carousel Font Color</td>
                        <td bgcolor="<?=$theme['carousel_font_color_selector']?>" class="custom-td"><input id="carousel_font_color_selector" type="text" name="carousel_font_color_selector" value="<?=$theme['carousel_font_color_selector']?>"></td>
                    </tr>
                </table>
            </td>
            <td>
                <table cellpadding="2">
                    <tr>
                        <td align="right">Menu Bg Color</td>
                        <td bgcolor="<?=$theme['menu_back_color_selector']?>" class="custom-td"><input id="menu_back_color_selector" type="text" name="menu_back_color_selector" value="<?=$theme['menu_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Active Menu Bg Color</td>
                        <td bgcolor="<?=$theme['active_menu_back_color_selector']?>" class="custom-td"><input id="active_menu_back_color_selector" type="text" name="active_menu_back_color_selector" value="<?=$theme['active_menu_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Active Menu font Color</td>
                        <td bgcolor="<?=$theme['active_menu_font_color_selector']?>" class="custom-td"><input id="active_menu_font_color_selector" type="text" name="active_menu_font_color_selector" value="<?=$theme['active_menu_font_color_selector']?>"></td>
                    </tr>

                    <tr>
                        <td align="right">Passive Menu Bg Color</td>
                        <td bgcolor="<?=$theme['passive_menu_back_color_selector']?>" class="custom-td"><input id="passive_menu_back_color_selector" type="text" name="passive_menu_back_color_selector" value="<?=$theme['passive_menu_back_color_selector']?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Passive Menu font Color</td>
                        <td bgcolor="<?=$theme['passive_menu_font_color_selector']?>" class="custom-td"><input id="passive_menu_font_color_selector" type="text" name="passive_menu_font_color_selector" value="<?=$theme['passive_menu_font_color_selector']?>"></td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td align="right"><input type="submit" style="cursor: pointer" value="შენახვა" class="formButton2"></td>
        </tr>
    </table>
</div>
</form>

<div style="position:relative; width:100%; height: 1000px; background-color: #FFFFFF;  border:solid #3A82CC 1px; width:100%; margin-top: 30px">


    <div id="body_back_color" class="body_back_color_selector" style="position:absolute; width:100%; height: 810px;"></div>
    <div style="position:absolute; width:100%; height: 810px;" class="header-bg">

    </div>
    <div id="arrow_shadow_back_color" class="l-arrow-back arrow_shadow_back_color_selector" ></div>
    <div id="arrow_shadow_back_color" class="r-arrow-back arrow_shadow_back_color_selector" ></div>

    <div style="position:relative; width: 800px; height: 920px; background-color: #FFFFFF; margin: 0 auto; box-shadow: 0 0 5px;">
        <div id="header_back_color"  class="header_back_color_selector" style="position:relative; height: 96px; width: 100%;">
            <div style="width: 220px; height: 96px"><img src="../uploads/logo.png" width="220" height="96"></div>
            <div id="menu_back_color"  class="menu_back_color_selector" style="width:100%; height: 36px;">
                <div id="active_menu_back_color" class="menu-item active_menu_back_color_selector" style="float:left; padding: 8px 8px 0px 8px; width:100px; height: 28px;"><b>ACTIVE ITEM</b></div>
                <div id="passive_menu_back_color" class="menu-item passive_menu_back_color_selector" style="float:left; padding: 8px 8px 0px 8px; width:100px; height: 28px;"><b>PASSIVE ITEM</b></div>
            </div>
            <div style=" width:100%; height: 270px; overflow: hidden">
                <img src="<?=$banner_image?>">

            </div>
            <div id="carousel_gradient_color" class="carousel_gradient_color_selector" style="position:absolute; top:264px; width:100%; height: 138px;">
                <div id="carousel_font_color" class="carousel_font_color_selector" style="padding-top:90px; padding-left: 20px; font-size: 26px;">Most Amasing Place In The World</div>
            </div>
            <div class="arrow_back_color_selector" style="position:absolute; width:50px; height:50px; top:240px; text-align: center; left:-25px;">
                <div style="margin-top: 15px;"><img src="../images/l_arrow.png"></div>
            </div>
            <div class="arrow_back_color_selector" style="position:absolute; width:50px; height:50px; top:240px; text-align: center; left:775px;">
                <div style="margin-top: 15px;"><img src="../images/r_arrow.png"></div>
            </div>
        </div>
        <div style="position:absolute; width: 100%; top:402px;">
            <div id="button_back_color" class="button_back_color_selector" style="border-radius: 2px; width:150px; height: 32px; text-align: center; font-size: 16px; margin: 10px">
                <div id="button_font_color" class="button_font_color_selector caps" style="font-size: 16px; line-height: 36px">დაჯავშნა</div>
            </div>
        </div>
    </div>
    <div id="footer_back_color"  class="footer_back_color_selector" style="width: 100%; height:80px; position: absolute; bottom: 0px; text-align: center">
        <h2 id="footer_font_color" class="footer_font_color_selector" style="margin-top: 30px">© Hotel 2016. All rights Reserved.</h2>

    </div>
</div>


<script>
    $(document).ready(function(){
        $('#body_back_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#body_back_color').css('backgroundColor', '#' + hex);
                $('#body_back_color_selector').val('#' + hex);
            }
        });
        $('#header_back_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#header_back_color').css('backgroundColor', '#' + hex);
                $('#header_back_color_selector').val('#' + hex);
            }
        });
        $('#header_font_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#header_font_color').css('backgroundColor', '#' + hex);
                $('#header_font_color_selector').val('#' + hex);
            }
        });
        $('#menu_back_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#menu_back_color').css('backgroundColor', '#' + hex);
                $('#menu_back_color_selector').val('#' + hex);
            }
        });
        $('#active_menu_back_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#active_menu_back_color').css('backgroundColor', '#' + hex);
                $('#active_menu_back_color_selector').val('#' + hex);
            }
        });
        $('#active_menu_font_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#active_menu_back_color').css('color', '#' + hex);
                $('#active_menu_font_color_selector').val('#' + hex);
            }
        });
        $('#passive_menu_back_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#passive_menu_back_color').css('backgroundColor', '#' + hex);
                $('#passive_menu_back_color_selector').val('#' + hex);
            }
        });
        $('#passive_menu_font_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#passive_menu_back_color').css('color', '#' + hex);
                $('#passive_menu_font_color_selector').val('#' + hex);
            }
        });
        $('#carousel_font_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $('#carousel_font_color').css('color', '#' + hex);
                $('#carousel_font_color_selector').val('#' + hex);
            }
        });
        $('#carousel_gradient_color_selector').ColorPicker({
            color: '#0000ff',
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {

                rgb=rgb.r+","+rgb.g+","+rgb.b;
                var gradient="#"+hex;
                var gradient2="-webkit-linear-gradient(top,rgba("+rgb+",0),rgba("+rgb+",1))";
                var gradient3="-o-linear-gradient(top,rgba("+rgb+",0),rgba("+rgb+",1))";
                var gradient4="-moz-linear-gradient(top,rgba("+rgb+",0),rgba("+rgb+",1))";
                var gradient5="linear-gradient(to top, rgba("+rgb+",0), rgba("+rgb+",1))}";
                console.log(rgb, gradient);

                $('#carousel_gradient_color').css("background",gradient);
                $('#carousel_gradient_color').css("background",gradient2);
                $('#carousel_gradient_color').css("background",gradient3);
                $('#carousel_gradient_color').css("background",gradient4);
                $('#carousel_gradient_color').css("background",gradient5);
                $('#carousel_gradient_color_selector').val('#' + hex);
            }
        });
        $('#footer_back_color_selector').ColorPicker({
            color: "#0000ff",
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $("#footer_back_color").css('background-color', '#' + hex);
                $("#footer_back_color_selector").val('#' + hex);
            }
        });

        $('#arrow_shadow_back_color_selector').ColorPicker({
            color: "#0000ff",
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $("#arrow_shadow_back_color").css('background-color', '#' + hex);
                $("#arrow_shadow_back_color_selector").val('#' + hex);
            }
        });
        $('#arrow_back_color_selector').ColorPicker({
            color: "#0000ff",
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $("#arrow_back_color").css('background-color', '#' + hex);
                $("#arrow_back_color_selector").val('#' + hex);
            }
        });

        $('#footer_font_color_selector').ColorPicker({
            color: "#0000ff",
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $("#footer_font_color").css('color', '#' + hex);
                $("#footer_font_color_selector").val('#' + hex);
            }
        });

        $('#button_back_color_selector').ColorPicker({
            color: "#0000ff",
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $("#button_back_color").css('background-color', '#' + hex);
                $("#button_back_color_selector").val('#' + hex);
            }
        });

        $('#button_font_color_selector').ColorPicker({
            color: "#0000ff",
            onShow: function (colpkr) {
                $(colpkr).fadeIn(100);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(100);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $("#button_font_color").css('color', '#' + hex);
                $("#button_font_color_selector").val('#' + hex);
            }
        });
    })
</script>
<style>
    .l-arrow-back {
        position: absolute;
        left: 109px;
        top: 220px;
        height: 0;
        width: 0;
        border-left: 25px solid transparent;
    }
    .r-arrow-back {
        left: 934px;
        position: absolute;
        top: 220px;
        height: 0;
        width: 0;
        border-right: 25px solid transparent;
    }
    .custom-td{
        padding-left: 24px;
        border:solid gray 1px;
    }
</style>
