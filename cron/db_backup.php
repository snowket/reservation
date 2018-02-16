<?php
error_reporting(E_ALL && ~E_NOTICE);
set_time_limit(0);
ignore_user_abort(true);

//mail('sergi@proservice.ge','HMS - new_day.php','cronjob started at: '.date("Y-m-d H:i:s"));
$parts = explode('/hms/', __DIR__);
$hmsDir = $parts[0] . "/hms";
$reservationDir = $parts[0];


//*******************************************************//
//*** Authorization  ************************************//
header('Content-Type: text/html; charset=utf-8');
//*******************************************************//
//*** Including base classes ****************************//
require_once($hmsDir . "/config.php");

require_once($hmsDir . "/common.php");
$FUNC = new CommonFunc();

//*******************************************************//
//*** Setting site language  ****************************//
DEFINE('LANG', $FUNC->SetLang("langs_pcms"));
require_once($hmsDir . "/lang/" . LANG . ".php");
require_once($hmsDir . "/classes/adodb/adodb.inc.php");
require_once($hmsDir . "/classes/datavalidator/validator.class.php");


//*******************************************************//
//*** Establishing connection with database *************//
$CONN = &ADONewCONNection($_CONF['db']['type']);
$CONN->connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass'], $_CONF['db']['name']);
$CONN->debug = 0;
$CONN->setFetchMode(ADODB_FETCH_ASSOC);

$query = "SET NAMES utf8 COLLATE utf8_general_ci";
$CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());


$query = "SELECT * FROM {$_CONF['db']['prefix']}_backups ORDER BY created_at DESC";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__,$CONN->ErrorMsg());
$backups = $result->getRows();

foreach ($backups as $backup) {
    $today = date('Y-m-d');
    $backup_date = date("Y-m-d", strtotime($backup['created_at']));
    $minus_one_week = date("Y-m-d", time() - 60 * 60 * 24 * 6);
    if ($backup_date <= $minus_one_week) {
        $query = "DELETE FROM {$_CONF['db']['prefix']}_backups
                  WHERE id=" . $backup['id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $file_path =$reservationDir. $_CONF['path']['db_backup'] . '' . $backup['file_name'];

        clearstatcache();
        if (is_file($file_path)) {
            if (@unlink($file_path)) {
                echo "<b>DELETED: " . $backup['file_name'] . "</b></br>";
            }else{
                echo "CAN'T DELETED: " . $backup['file_name'] . "</br>";
            }
        }else{
            echo "It is Not a File: " . $file_path . "</br>";
        }
    }
}


$msg=DbFullBackup();
function DbFullBackup()
{
    global $CONN, $_CONF, $FUNC;
    $tablenames = $CONN->MetaTables();
    $file_name = $CONN->databaseName . '_' . date('Y-m-d_H-i-s', time()) . '.sql';
    $path = dirname((dirname(__DIR__))).$_CONF['path']['db_backup'] . '' . $file_name;
    $msg = '';
    if (strlen($CONN->databaseName) && ($fp = openFileWrite($path))) {
        for ($i = 0; $i < count($tablenames); $i++) {
            $msg = $msg . BackupTable($tablenames[$i], $fp);
        }
        closeFile($fp);
        $query = "INSERT INTO {$_CONF['db']['prefix']}_backups SET
								  type='db',
                                  file_name='" . $file_name . ".gz'";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $msg .= '<br/>Database backup saved to ' . $path . '<br/>';
    } else {
        $msg .= '<br/><strong>Backup writing to "' . $path . '" failed!</strong><br/>';
    }

    return $msg;
}

function BackupTable($tablename, $fp)
{
    global $CONN, $FUNC;

    if (empty($tablename) || empty($fp)) {
        $msg = "Failed to export table '$tablename'<br/>";
        return '';
    }
    // Get the SQL to create the table
    $query = "SHOW CREATE TABLE `$tablename`";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $createTable = $result->fields;

    // Drop if it exists
    $tableDump = "DROP TABLE IF EXISTS " . $tablename . ";\n" . $createTable['Create Table'] . ";\n\n";

    writeFileData($fp, $tableDump);

    // get data
    $query = "SELECT * FROM " . $tablename;
    if ($getRows = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg())) {
        $fieldCount = $getRows->fields;
        $rowCount = 0;

        while ($row = $getRows->FetchNextObject()) {
            #p($row);
            $tableDump = "INSERT INTO " . $tablename . " VALUES(";

            #$fieldcounter = -1;
            $firstfield = true;

            // get each field's data
            foreach ($row AS $k => $v) {
                if (!$firstfield) $tableDump .= ', ';
                else                $firstfield = 0;

                if (!isset($v)) $tableDump .= 'NULL';
                elseif ($v != '') $tableDump .= '\'' . addslashes($v) . '\'';
                else    $tableDump .= '\'\'';
            }
            $tableDump .= ");\n";

            writeFileData($fp, $tableDump);
            $rowCount++;
        } //while
        #$DB->free_result($getRows);
    }
    writeFileData($fp, "\n\n\n");

    $msg = "Exported $rowCount rows from table '$tablename'<br/>";
    return $msg;
}

function openFileWrite($filename)
{
    if (function_exists('gzopen')) {
        $filename .= '.gz';
        $handle = gzopen($filename, "w9");
    } else {
        $handle = fopen($filename, "w");
    }
    return $handle;
}

function writeFileData($handle, $data)
{
    if (function_exists('gzwrite')) {
        gzwrite($handle, $data);
    } else {
        fwrite($handle, $data);
    }
}

function closeFile($handle)
{
    if (function_exists('gzclose')) {
        gzclose($handle);
    } else {
        fclose($handle);
    }
}
