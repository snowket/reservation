<?if(!defined('ALLOW_ACCESS')) exit;?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?=HMS_TITLE?> v.<?=HMS_VERSION?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/fonts.css">
	<link rel="stylesheet" type="text/css" href="css/admin.css">
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/modern_tabs.css">
	<link rel="stylesheet" type="text/css" href="js/jquery/ui-1.11.4-hms/jquery-ui.css">


	<script type="text/javascript" src="js/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/jquery/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery/ui-1.11.4-hms/jquery-ui.js"></script>
 	<script type="text/javascript" src="js/functions.js"></script>
	<script type="text/javascript" src="js/httpRequest.js"></script>
	<script src="js/sweetalert/sweetalert-master/dist/sweetalert.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/sweetalert/sweetalert-master/dist/sweetalert.css">
	<script type="text/javascript" language="javascript">


	var pcmsInterface = new pcmsInterface();

	ArrImg = new Image(16,49);
	ArrImg.src ="images/arr_left.gif";

	function DrawSwapButton(){
		if(readCookie('swap')==1) {
			alt    = 'Open';
			im_src = 'images/arr_left.gif';
		}
	   else {
	       alt    = 'Close';
	       im_src = 'images/arr_right.gif';
		}
		document.write("<img src=\""+im_src+"\" id=\"swap_arr\" width=\"16\" height=\"49\" alt=\""+alt+"\" border=\"0\">");
	}

	function SwapWorkspace(){
		if(document.getElementById("td_swap").style.display=="block") {
			display_type="none";
			im_src = ArrImg.src;
			alt    = "Open";
			deleteCookie('swap');
			writeCookie('swap',"1", 100000);
		}
		else {
			display_type="block";
			im_src = "images/arr_right.gif";
			alt    = 'Close';
			deleteCookie('swap');
		}
		document.getElementById("td_swap").style.display=display_type;
		document.getElementById('swap_arr').src = im_src;
		document.getElementById('swap_arr').alt = alt;
	}
	</script>
</head>
<body>
<table border="0" style="width:100%; height:100%;" cellspacing="0" cellpadding="0">
	<tr><td colspan="2" style="height:1px;">
		<table border="0" style="width:100%; height:100%; background:url('images/header1_bg.jpg') repeat-x bottom;" cellspacing="0" cellpadding="0">
	 	<tr>
			<td valign="middle" align="center" style="width:60px; background:#FF6C00; height:50px;"><a href="index.php"><img src="images/ico_question.gif" width="26" height="26" border="0"><br><img src="images/button_home.gif" width="60" height="10" border="0"></a></td>
			<td valign="middle" align="center" style="width:60px; background:#07A108;"><a href="mailto:contact@proservice.ge"><img src="images/ico_phone.gif" width="26" height="26" border="0"><br><img src="images/button_contact.gif" width="60" height="10" border="0"></a></td>
			<td valign="middle" align="center" style="width:60px; background:#3A82CC;">
                <a href="logout.php">
                <img src="images/ico_voscliz.gif" width="26" height="26" border="0"></br><img src="images/button_logout.gif" width="60" height="10" border="0">
                </a>
			</td>
			<td>
			<?
			$style=($currency_rates['created_at']==date('Y-m-d'))?'currency_normal':'currency_blinking';
			?>
			<a href="http://currency.boom.ge/" target="_blank" title="Currency Rates [<?=$currency_rates['created_at']?>]"  style="float:left">
                <div class="<?=$style?>">
                <?
                $rates=unserialize($currency_rates['currency_rates']);
                echo '<b>GEL = 1<br>';
                echo 'USD = '.$rates['USD']['rate'].'<br>';
                echo 'EUR = '.$rates['EUR']['rate'].'</b>';
                ?>
                </div>
			</a>
			<?if(count($backups)!=0){?>
			    <a href="index.php?m=security&tab=db_backup" title="Download Backup" style="float:left;">
                    <div class="currency_blinking" style=" padding: 18px 10px 14px 10px; color:black;">Download Backup</div>
                 </a>
			<?}?>
			<?if($_SESSION['pcms_user_group']<=2){?>
			    <?
			    $cl=(CURRENT_DATE==date('Y-m-d'))?'currency_normal':'currency_blinking';
			    $tt=(CURRENT_DATE==date('Y-m-d'))?'Change Date':'Date is different';
			    ?>
                <div class="<?=$cl?>" style=" padding: 14px 8px 12px 50px; background-image: url('./images/delorean.png'); background-repeat: no-repeat; background-size:43px 30px; background-position: left center; " title="<?=$tt?>">
                    <form  method="post" id="current_date_form">
                    <input type="hidden" name="action" value="change_current_date">
                    <input name="current_date" id="current_date" type="text" value="<?=CURRENT_DATE?>" class="calendar-icon" autocomplete="off">
                    </form>
                    <script type="text/javascript">

$(document).ready(function () {

    console.log("document ready");
	$( document ).tooltip();
<?if($_SESSION['pcms_user_group']<=2){?>
    $( "#current_date" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            //minDate: 'today',
            numberOfMonths: 2,
            dateFormat:'yy-mm-dd',
            onSelect: function( selectedDate ) {
                $('#current_date_form').submit();
            }
        });
<?}?>

});
</script>
                </div>
            <?}?>
						<div style="float:right;color:white;line-height:40px;">
 							Welcome Back  <b><?=$user['firstname']." ".$user['lastname']?></b>
						</div>
			</td>
			<td align="right" style="padding-right:7px;">
		 	    <a style="color:#ffffff; font-size:14px; font-family:Verdana;" target="_blank" href="http://hms.ge/help/">
		 	       <b>Help</b>
		 		</a>
				<div style="padding-top:10px;">
					<?
					$gets=$_GET;
					unset($gets['lng']);
					foreach ($gets as $key => $value) {
						$getsArr[]=$key.'='.$value;
					}
					$lang_uri=implode('&',$getsArr);
					foreach ($_CONF['langs_pcms_all'] as $v) {?>
						<a style="color:#fff; text-decoration:<?=LANG==$v?'underline':'none';?>"
							href="index.php?<?=$lang_uri?>&lng=<?=$v?>"><?=$v?></a>
					<?}?>
				</div>
		 	</td>
	 	</tr>
		</table>
	</td></tr>


	<tr><td valign="top" width="180">
		<table border="0" style="width:100%; height:100%;" cellspacing="0" cellpadding="0">
		<tr><td style="height:1px;" align="center"><img src="./images/blank.gif" width="1" height="10"></td></tr>
		<tr><td style="height:1px;" align="center"><a href="http://www.proservice.ge" target="_blank"><img src="images/logo_proservice.gif" border="0"></a></td></tr>
		<tr>
			<td style="height:20px; padding-top:10px;">
				<? for($i=0, $n=count($PLUGINS); $i<$n; $i++) {
					if($PLUGINS[$i]['section']==0){?>
					<!--a href="<?=$_SERVER['PHP_SELF']?>?m=<?=$PLUGINS[$i]['plugin']?>" class="menuMain <?=($_GET['m']==$PLUGINS[$i]['plugin']?'menuMain_active':'')?>">
						<?=$PLUGINS[$i]['title']?>
					</a-->
				<? }} ?>
				<div style="border:solid #FF6C00 1px; margin-top:10px;">
				<div style="background: #FF6C00; color:#FFF; height:30px; line-height:28px">
                    <b>Hotel Management System</b>
                </div>
				<? for($i=0, $n=count($PLUGINS); $i<$n; $i++) {
					if($PLUGINS[$i]['section']==1){ ?>
					<a href="<?=$_SERVER['PHP_SELF']?>?m=<?=$PLUGINS[$i]['plugin']?>" style="line-height: 1.6;" class="caps menuMain <?=($_GET['m']==$PLUGINS[$i]['plugin']?'menuMain_active':'')?>">
						<?=$PLUGINS[$i]['title_'.LANG]?>
					</a>
				<? }} ?>
				</div>
			</td>
		</tr>
		<tr>
            <td valign="top" style="font-size:13px;"><?=$_LEFT?></td>
        </tr>
	  </table>
	</td>

	<td valign="top" style="border-left: 1px Dashed Silver;">
		<table border="0" style="width:100%; height:100%;" cellspacing="0" cellpadding="0">
	    <tr>
			<td height="100%" valign="top">
				<table border="0" style="width:100%; height:100%;" cellspacing="0" cellpadding="0"><tr>
					<td valign="top" style="padding:10px 5px 10px 10px; width:100%"><?=$_CENTER?><br><br></td>
					<!---td bgcolor="#ffffff" valign="top">
						<div style="display:block; width:180px; padding:5px;" id="td_swap">
						<img src="images/1x1.gif" width="170" height="1" border="0"><br>
						<?=$_RIGHT?>
						</div>
					</td-->
				</tr></table>
			</td>
		</tr>
		<tr><td align="right" height="1" valign="middle"><img src="images/1x1.gif" width="600" height="1" border="0"></td></tr>
		</table>
	</td></tr>

	<tr><td colspan="2" valign="top" style="height:5px; background:url('images/bg5_2.gif') repeat-x;"><img src="images/spacer.gif" width="1" height="5" border="0"></td></tr>
	<tr><td colspan="2" style="background:#387FC8 url(images/bg3.gif) repeat-y; height:25px;" valign="middle">
		<table border="0" style="width:100%;" cellspacing="0" cellpadding="2"><tr>
		<td align="left"  class="copy">Copyright &copy; 2004-<?=date("Y")?> <a href="http://www.proservice.ge" target="_blank"><b>Proservice</b></a>. All Rights Reserved.</td>
		<td align="right" class="psCMS"><a><?=HMS_TITLE?> v.<?=HMS_VERSION?></a></td>
		</tr></table>
	</td></tr>
</table>

</body>
</html>
