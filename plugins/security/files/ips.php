<?php
if (!defined('ALLOW_ACCESS')) exit;
$proservice_ip='188.121.218.220';
$server_ip=$_SERVER['SERVER_ADDR'];
if ($POST['action'] == 'add_ip') {
    $ip = $POST['ip'];
    $desc = $POST['desc'];
    if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {
        if ($POST['ip_type'] == 'allow') {
            $denied_ips = array();
            $already_exists = false;
            if(count($allowed_ips)==0){
                $allowed_ips[$proservice_ip]='ProService IP';
                $allowed_ips[$server_ip]='Server IP';
            }
            foreach ($allowed_ips AS $k => $v) {
                if ($ip == $k) {
                    $already_exists = true;
                }
            }
            if (!$already_exists) {
                $allowed_ips[$ip] = $desc;
            }
            updateIps();
            updateHtaccess();
        } elseif ($POST['ip_type'] == 'deny'&&($ip!=$proservice_ip || $ip!=$server_ip)) {
            $allowed_ips = array();
            $already_exists = false;
            foreach ($denied_ips AS $k => $v) {
                if ($ip == $k) {
                    $already_exists = true;
                }
            }
            if (!$already_exists) {
                $denied_ips[$ip] = $desc;
            }
            updateIps();
            updateHtaccess();
        } else {

        }
    } else {

    }
    $FUNC->Redirect($SELF . '&tab='.$tab);
}
if ($GET['action'] == 'delete') {
    $ip = $GET['ip'];
    if ($GET['ip_type'] == 'allow'&&(($ip!=$proservice_ip && $ip!=$server_ip)||(($ip==$proservice_ip || $ip==$server_ip)&&count($allowed_ips)<=2))) {
        $denied_ips = array();
        $tmp = array();
        foreach ($allowed_ips AS $k => $v) {
            if ($ip != $k) {
                $tmp[$k] = $v;
            }
        }
        $allowed_ips = $tmp;
        updateIps();
        updateHtaccess();
    } elseif ($GET['ip_type'] == 'deny') {
        $allowed_ips = array();
        $tmp = array();
        foreach ($denied_ips AS $k => $v) {
            if ($ip != $k) {
                $tmp[$k] = $v;
            }
        }
        $denied_ips = $tmp;
        updateIps();
        updateHtaccess();
    } else {

    }
    $FUNC->Redirect($SELF . '&tab=' . $tab);
}

$TMPL->addVar('TMPL_allow_ips', $allowed_ips);
$TMPL->addVar("TMPL_denay_ips", $denied_ips);
$TMPL->ParseIntoVar($_CENTER, "ips");

function updateIps()
{
    global $allowed_ips, $denied_ips;

    $path_to_file = getcwd() . "/includes/allow_deny_ips.php";
    //$file_contents = file_get_contents($path_to_file);
    $file_contents = "<?php
if(!defined('ALLOW_ACCESS')) exit;
$" . "allowed_ips=array(";

    foreach ($allowed_ips AS $k => $v) {
        $file_contents .= "'" . $k . "'=>'" . $v . "',";
    }
    $file_contents .= ");
$" . "denied_ips=array(";

    foreach ($denied_ips AS $k => $v) {
        $file_contents .= "'" . $k . "'=>'" . $v . "',";
    }
    $file_contents .= ");

?>";
    file_put_contents($path_to_file, $file_contents);
}

function updateHtaccess(){
    global $allowed_ips, $denied_ips;
    $path_to_htaccess=getcwd()."/.htaccess";
    $htacces_content=file_get_contents($path_to_htaccess);
    $splited_htaccess=explode("#SECURITY PLUGIN AREA|Do Not Touch",$htacces_content);
    $new_htaccess_content=$splited_htaccess[0];
    if($splited_htaccess[0]!=''){
        $new_htaccess_content.="\n#SECURITY PLUGIN AREA|Do Not Touch";
    }else{
        $new_htaccess_content.="#SECURITY PLUGIN AREA|Do Not Touch";
    }

    if(count($allowed_ips)==0&&count($denied_ips)==0){
        $new_htaccess_content.='
ServerSignature Off
Options -Indexes

<Files .htaccess>
order allow,deny
deny from all
</Files>

<FilesMatch "\.(php|cgi|pl|php3|php4|php5|php6|phps|phtml|shtml|tpl|tmpl|log)$">
Order allow,deny
Deny from all
</FilesMatch>

<FilesMatch "^(index|auth|logout|index_ajax)\.php$">
Order deny,allow
allow from all
</FilesMatch>

<FilesMatch "^(api)\.php$">
Order allow,deny
allow from all
</FilesMatch>';
    }elseif(count($allowed_ips)==0){
        $deny = implode(' ', array_map(function ($v, $k) { return $k; }, $denied_ips, array_keys($denied_ips)));
        $allow="all";
        $new_htaccess_content.='
ServerSignature Off
Options -Indexes

<Files .htaccess>
order allow,deny
deny from all
</Files>

<FilesMatch "\.(php|cgi|pl|php3|php4|php5|php6|phps|phtml|shtml|tpl|tmpl|log)$">
Order allow,deny
Deny from all
</FilesMatch>

<FilesMatch "^(index|auth|logout|index_ajax)\.php$">
Order allow,deny
allow from '.$allow.'
deny from '.$deny.'
</FilesMatch>

<FilesMatch "^(api)\.php$">
Order allow,deny
allow from all
</FilesMatch>';

    }elseif(count($denied_ips)==0){
        $deny="all";
        $allow = implode(' ', array_map(function ($v, $k) { return $k; }, $allowed_ips, array_keys($allowed_ips)));
        $new_htaccess_content.='
ServerSignature Off
Options -Indexes

<Files .htaccess>
order allow,deny
deny from all
</Files>

<FilesMatch "\.(php|cgi|pl|php3|php4|php5|php6|phps|phtml|shtml|tpl|tmpl|log)$">
Order allow,deny
Deny from all
</FilesMatch>

<FilesMatch "^(index|auth|logout|index_ajax)\.php$">
Order deny,allow
deny from '.$deny.'
allow from '.$allow.'
</FilesMatch>

<FilesMatch "^(api)\.php$">
Order deny,allow
allow from all
</FilesMatch>';

    }else{

    }
    if($splited_htaccess[2]==''){
        $new_htaccess_content.="
#SECURITY PLUGIN AREA|Do Not Touch";
    }else{
        $new_htaccess_content.="
#SECURITY PLUGIN AREA|Do Not Touch\n".$splited_htaccess[2];
    }
    file_put_contents($path_to_htaccess, $new_htaccess_content);
}