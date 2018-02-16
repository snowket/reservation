
<script type="text/javascript" src="./js/tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="./js/tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<link rel="stylesheet" href="./js/tablesorter/addons/pager/jquery.tablesorter.pager.css">
<link rel="stylesheet" href="./js/tablesorter/themes/blue/style.css">

<!--link href="./css/select2.min.css" rel="stylesheet"/-->
<script src="./js/select2.min.js"></script>
<!-- -->
<?
$ob_ln = ($_GET['order_name']=='login' && $_GET['order_by']=='ASC')?'DESC':'ASC';
$ob_lnI=($_GET['order_name']=='login')?'<img src="'.$_CONF['path']['url'].'/pcms/images/s_'.$ob_ln.'.gif" border="0">':'';
$ob_ln = 'order_by='.$ob_ln;
$ob_em = ($_GET['order_name']=='email' && $_GET['order_by']=='ASC')?'DESC':'ASC';
$ob_emI=($_GET['order_name']=='email')?'<img src="'.$_CONF['path']['url'].'/pcms/images/s_'.$ob_em.'.gif" border="0">':'';
$ob_em = 'order_by='.$ob_em;
$ob_jn = ($_GET['order_name']=='joined' && $_GET['order_by']=='ASC')?'DESC':'ASC';
$ob_jnI=($_GET['order_name']=='joined')?'<img src="'.$_CONF['path']['url'].'/pcms/images/s_'.$ob_jn.'.gif" border="0">':'';
$ob_jn = 'order_by='.$ob_jn;
$ob_cd = ($_GET['order_name']=='card' && $_GET['order_by']=='ASC')?'DESC':'ASC';
$ob_cdI=($_GET['order_name']=='card')?'<img src="'.$_CONF['path']['url'].'/pcms/images/s_'.$ob_cd.'.gif" border="0">':'';
$ob_cd = 'order_by='.$ob_cd;
if ($_GET['kw'] && $_GET['param']) { $SELF = $SELF.'&kw='.$_GET['kw'].'&param='.$_GET['param']; }
?>


No Reports