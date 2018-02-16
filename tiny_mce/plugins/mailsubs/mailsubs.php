<?
require_once('../../../includes/header.inc.php');
//print getcwd();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#mailsubs_dlg.title}</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="js/mailsubs.js"></script>
	<base target="_self" />
</head>
<body style="display: none">
	<div>
		<table border="0" cellspacing="0" cellpadding="4" style="backgrownd:#fff;">
		    <tr>
		       <td onclick ="MailsubsDialog.insert('USER')" style="cursor: pointer">{$USER}</td>
		       <td>User name</td>
		    </tr>
		</table>
	</div>
</body>
</html>