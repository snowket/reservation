<?if(!defined('ALLOW_ACCESS')) exit;?>
<!DOCTYPE html>
<html>
<!--
	Copyright (c) 2010 ProService LLC
-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<meta http-equiv="X-UA-Compatible" content="IE=8;chrome=1" />
	<!--[if IE]><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script><![endif]-->
	
	<title><?=$_CONF['pcms_title']?></title>
	<meta name="keywords" CONTENT="ProService, pro-service, cms, content management system" />
	<meta name="description" CONTENT="ProService Content Management System, PCMS" />

	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="PUBLIC">
	
	<link href="css/admin.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.getElementById('login').focus();">
<form action="" method="post">
	<input type="hidden" name="action" value="submit">
	<table border="0" width="435" style="border:1px Solid #C0C0C0; background:#fff; margin-top:20px" align="center" cellspacing="1">
		<tr>
			<td width="156"><img src="./images/auth.jpg"></td>
			<td valign="top">
				<div align="center"><a href="http://www.proservice.ge"><img src="images/logo_proservice.gif" width="160" height="52" border="0" vspace="5"></a></div>
				<div align="center" style="font-family:Impact, Verdana; font-size:18px; color:red;">(995 32) &nbsp; 243 00 44</div>
				
				<table border="0" width="100%" cellspacing="1">
					
					<tr>
						<td align="center">
							<table border="0" width="90%" cellspacing="1" class="text_black">
								<tr>
									<td align="right" colspan="2" class="err"><?=$TMPL['errors']?></td>
								</tr>
								<tr>
									<td><?=$TEXT['global']['login']?>:</td>
									<td align="right"><input type="text" id="login" name="login" size="20" class="formField2" style="width:155px;" /></td>
								</tr>
								<tr>
									<td><?=$TEXT['global']['password']?>:</td>
									<td align="right"><input type="password" name="password" size="20" class="formField2" style="width:155px;" /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align="right"><input type="submit" value="<?=$TEXT['global']['enter']?>"  style="border:1px Solid #C0C0C0; height:22px; padding-top:0px;"/></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td class="text_gray" style="padding:10px;"><?=$TEXT['global']['copyright']?></td></tr>
				</table>
			</td>
		</tr>
	</table>

</form>
</body>