<?
error_reporting(E_ALL && ~E_NOTICE);


$_CONF = Array(
	### Database
	'db'				=>	array (
                                    'type'		=>   'mysql',
                                    'host'		=>   'localhost',
                                    'user'		=>   'hmsge_admin',
                                    'name'		=>   'hmsge_admin',
                                    'pass'		=>   'YUUnj6Wy',
                                    'prefix'	=>   'cms'
							),
	
	### Languages for site
	'langs_all'			=>  array ('geo','eng','rus','tur'),
	'langs_publish'		=>  array ('geo','eng','rus','tur'),
	'langs_def'			=>  'geo',
	
	### Languages for pcms
	'langs_pcms_all'	=>  array ('geo','eng'),
	'langs_pcms_def'	=>  'geo',

	### Path settings
	'path'				=>  array (           
							'url'			=> "http://hms.ge/double/",
							'script_upload'	=> "../uploads_script/",
							'user_upload'	=> "./uploads/",
							'error_log'		=> dirname(__FILE__)."/logs/errors.log",
                            'db_backup'		=> "/uploads_script/backups/db/",
							'cgi-bin'		=> "../cgi-bin",
						),


	### Mail settings
	'adminmail'			=>	'sergi@proservice.ge',
	'expire'				=>	1200,
	'remember_me_expire'	=>	7776000,
	
	### CURRENCIES
	'currency'			=>  array ( 'gel', 'usd', 'eur'),
	'tbc'               =>  array(
                            'destination'           =>'demohotels.proservice.ge Payment',
                            'p12_file'              =>'securepay.ufc.ge_5300542_merchant_wp.p12',
                            'cert_pass'             =>'HJDGfiy756jkfFjf',
                            'MerchantHandler'       =>'https://securepay.ufc.ge:18443/ecomm2/MerchantHandler',
                            'ClientHandler'         =>'https://securepay.ufc.ge/ecomm2/ClientHandler'
                        ),
    'tbc_payments_method'=>array(
                            '0'=>'PayNow+PayLater',
                            //'1'=>'PayNow3D + NoPayPos',
                            '1'=>'PayNow Only',
                            '2'=>'PayLater'
    ),
    'country'=>array('id'=>273,'name'=>'Georgia'),
    'system_currency'=>'GEL',//dont change on runtime
    'system_currency_code'=>981,//dont change on runtime
 	'mail_notification'=>array(
        'from_name'=>'hms.ge',
        'from_email'=>'demohotels@hms.ge'
    ),
    ### SMTPMail settings
    'SMTP'    => array (
        'Host'              => 'mail.hms.ge',
        'Port'              => '587',
        'SMTPAuth'          => true,
        'Username'          => 'demohotels@hms.ge',
        'Password'          => 'syjL7UVE',
        'setFromMail'       => 'demohotels@hms.ge',
        'setFromTitle'      => 'Demo Hotels',
        'addReplyToMail'    => 'demohotels@hms.ge',
        'addReplyToTitle'   => 'Demo Hotels')
);

define('C_LANG',$_CONF['langs_def']);
function p($obj) {
	print "<div align='left'><font face='Verdana' color='#536f8c' size='3'><pre>";
	print_r($obj);
	print "</pre></font></div>";
}

function p2($obj) {
	#if ($_SERVER['REMOTE_ADDR'] != '94.137.187.220') return;
	if (!empty($obj)) $n = 4; else $n = 1;
	print "<div align='center'><textarea cols=75 rows=".(count($obj)+$n).">";
	print_r($obj);
	print "</textarea></font></div>";
}

function p3($obj) {
	#if ($_SERVER['REMOTE_ADDR'] != '94.137.187.220') return;
	if (!empty($obj)) $n = 4; else $n = 1;
	print '<div align="center"><textarea style="font-family:Verdana; font-size:12px; width:99%; height:500px;">';
	print_r($obj);
	print "</textarea></font></div>";
}
function dd($obj){
	var_dump($obj);
	exit;
}

