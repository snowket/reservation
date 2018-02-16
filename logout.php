<?
	session_start();
	unset($_SESSION['pcms_user_']);
	session_destroy();
	header("Location: index.php");
	exit;
?>