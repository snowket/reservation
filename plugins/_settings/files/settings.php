<?php
if(!defined('ALLOW_ACCESS')) exit;
$rootpath = "./../";

// ###### Test that the backup directory exists and is writable ###############
#p($_CONF);
$backupEnabled = false;
$backupDir = getcwd() . '/backups/db/';
$backupUrl = $_CONF['path']['url'].'/hms/backups/db/';


$errors = '';

if (!is_dir($backupDir)){
    $errors .= 'Backup Directory ('.$backupDir.') does not exist<br />';
}else{
    if (!is_writable($backupDir)){
        $errors .= 'Backup Directory ('.$backupDir.') is not writable - chmod to 0777<br />';
    }
}
if (!empty($errors)){
    $TMPL->addVar("msg",$errors);
}else{
    $backupEnabled = true;
}

$query = "UPDATE {$_CONF['db']['prefix']}_backups SET
			 	 downloaded=1
			 	 WHERE downloaded=0";
$CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

$TMPL->addVar("html",DisplayTables());
$TMPL->ParseIntoVar($_CENTER,'db_backup');

// ####################### FILE READ/WRITE USING GZIP  ########################
function openFileWrite($filename){
    if(function_exists('gzopen')){
        $filename .= '.gz';
        $handle = gzopen($filename, "w9");
    }
    else{
        $handle = fopen($filename, "w");
    }
    return $handle;
}

function openFileRead($filename){
    if(function_exists('gzopen')){
        $handle = gzopen($filename, "r");
    }
    else{
        $handle = fopen($filename, "r");
    }
    return $handle;
}

function writeFileData($handle, $data){
    if(function_exists('gzwrite')){
        gzwrite($handle, $data);
    }
    else{
        fwrite($handle, $data);
    }
}

function readFileData($handle, $size){
    if(function_exists('gzread')){
        $data = gzread($handle, $size);
    }
    else{
        $data = fread($handle, $size);
    }
    return $data;
}

function eof($handle){
    if(function_exists('gzeof')){
        return gzeof($handle);
    }
    else{
        return feof($handle);
    }
}

function closeFile($handle){
    if(function_exists('gzclose')){
        gzclose($handle);
    }
    else{
        fclose($handle);
    }
}

// ####################### END FILE READ FUNCTIONS ############################
function BackupTable($tablename, $fp){
    global $CONN,$FUNC,$TMPL,$SELF,$TEXT,$LOADED_PLUGIN,$SETTINGS,$_CONF,$_CENTER,$ROOT;

    if(empty($tablename) || empty($fp)){
        $msg = "Failed to export table '$tablename'<br/>";
        return '';
    }
    // Get the SQL to create the table
    $query		= "SHOW CREATE TABLE `$tablename`";
    $result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $createTable= $result->fields;

    // Drop if it exists
    $tableDump = "DROP TABLE IF EXISTS `$tablename`;\n" . $createTable['Create Table'] . ";\n\n";

    writeFileData($fp, $tableDump);

    // get data
    $query		= "SELECT * FROM `$tablename`";
    if($getRows = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg())){
        $fieldCount = $getRows->fields;
        $rowCount	= 0;

        while($row = $getRows->FetchNextObject()){
            #p($row);
            $tableDump = "INSERT INTO `$tablename` VALUES(";

            #$fieldcounter = -1;
            $firstfield = true;

            // get each field's data
            foreach($row AS $k=>$v) {
                if(!$firstfield)	$tableDump .= ', ';
                else				$firstfield = 0;

                if(!isset($v))	$tableDump .= 'NULL';
                elseif($v != '')	$tableDump .= '\''.addslashes($v).'\'';
                else	$tableDump .= '\'\'';
            }
            /*
            while (++$fieldcounter < $fieldCount)
            {
              if(!$firstfield)
              {
                $tableDump .= ', ';
              }
              else
              {
                $firstfield = 0;
              }

              if(!isset($row["$fieldcounter"]))	$tableDump .= 'NULL';
              elseif($row["$fieldcounter"] != '')	$tableDump .= '\'' . addslashes($row["$fieldcounter"]) . '\'';
              else	$tableDump .= '\'\'';
            }
              */
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

function BackupSingleTable($tablename){
    global $backupDir;

    $msg = '';
    if(!empty($tablename)){
        $path = $backupDir . $tablename . '_' . time() . '.sql';
        if($fp = openFileWrite($path)){
            $msg = BackupTable($tablename, $fp);
            closeFile($fp);
            $msg .= '<br/>Database backup saved to ' . $path . '<br/>';
        }
        else{
            $msg .= '<br/>ERROR: writing to file "' . $path . '" failed!<br/>';
        }
    }
    return $msg;
}

function DbFullBackup(){
    global $DB, $backupDir, $CONN,$_CONF, $FUNC;
    $tablenames=$CONN->MetaTables();
    $file_name=$CONN->databaseName . '_' . date('Y-m-d_H-i-s',time()) . '.sql';
    $path = $backupDir . $file_name;
    $msg = '';
    if(strlen($CONN->databaseName) && ($fp = openFileWrite($path))){
        for($i=0; $i<count($tablenames); $i++){
            $msg = $msg . BackupTable($tablenames[$i], $fp);
        }
        closeFile($fp);
        $query = "INSERT INTO {$_CONF['db']['prefix']}_backups SET
								  type='db',
								  downloaded=1,
                                  file_name='".$file_name.".gz'";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $msg .= '<br/>Database backup saved to ' . $path . '<br/>';
    }
    else{
        $msg .= '<br/><strong>Backup writing to "' . $path . '" failed!</strong><br/>';
    }

    return $msg;
}

function BatchBackupTable($tablenames){
    global $DB, $backupDir, $CONN;

    $path = $backupDir . $CONN->databaseName . '_' . time() . '.sql';
    $msg = '';
    if(strlen($CONN->databaseName) && ($fp = openFileWrite($path))){
        for($i=0; $i<count($tablenames); $i++){
            $msg = $msg . BackupTable($tablenames[$i], $fp);
        }
        closeFile($fp);
        $msg .= '<br/>Database backup saved to ' . $path . '<br/>';
    }
    else{
        $msg .= '<br/><strong>Backup writing to "' . $path . '" failed!</strong><br/>';
    }

    return $msg;
}

function ParseQueries($sql, $delimiter){
    $matches = array();
    $output = array();

    $queries = explode($delimiter, $sql);
    $sql = "";

    $query_count = count($queries);
    for ($i = 0; $i < $query_count; $i++){
        if (($i != ($query_count - 1)) || (strlen($queries[$i] > 0))){
            $total_quotes = preg_match_all("/'/", $queries[$i], $matches);
            $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $queries[$i], $matches);
            $unescaped_quotes = $total_quotes - $escaped_quotes;

            if (($unescaped_quotes % 2) == 0){
                $output[] = $queries[$i];
                $queries[$i] = "";
            }
            else{
                $temp = $queries[$i] . $delimiter;
                $queries[$i] = "";

                $complete_stmt = false;

                for ($j = $i + 1; (!$complete_stmt && ($j < $query_count)); $j++){
                    $total_quotes = preg_match_all("/'/", $queries[$j], $matches);
                    $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $queries[$j], $matches);
                    $unescaped_quotes = $total_quotes - $escaped_quotes;

                    if (($unescaped_quotes % 2) == 1){
                        $output[] = $temp . $queries[$j];

                        $queries[$j] = "";
                        $temp = "";

                        $complete_stmt = true;
                        $i = $j;
                    }
                    else{
                        $temp .= $queries[$j] . $delimiter;
                        $queries[$j] = "";
                    }
                }
            }
        }
    } //for

    return $output;
}

function RestoreBackup($filename){
    global $CONN, $backupDir, $SELF, $FUNC, $TMPL;

    $out = '';
    // Read the file into memory and then execute it
    if($fp = openFileRead($backupDir . $filename)){
        $query = '';
        while (!eof($fp)){
            $query .= readFileData($fp, 10000);
        }
        closeFile($fp);

        // Split into discrete statements
        $queries = ParseQueries($query, ';');

        if($cnt=count($queries)){
            $inserts = 0;
            for($i = 0; $i < $cnt; $i++){
                $sql = trim($queries[$i]);
                if(!empty($sql)){
                    if(substr($sql,0,6)=='INSERT')
                        ++$inserts;
                    $CONN->Execute($sql) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
                }
            }
            $out .= "<center><strong>Processed $cnt statements in total.<br />$inserts rows added in total.</strong></center>";
        }
        $TMPL->addVar("msg",$out);
        PrintRedirect($SELF,3);
    }
    else{
        $out .= "<strong>Failed to open backup file '$filename'!</strong>";
        $TMPL->addVar("msg",$out);
        PrintRedirect($SELF,3);
    }
}

function DeleteBackup($filename){
    global $DB, $backupDir, $FUNC, $SELF, $TMPL,$_CONF,$CONN;
    $query = "DELETE FROM {$_CONF['db']['prefix']}_backups
              WHERE file_name='{$filename}'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $fname = $backupDir . $filename;
    if(is_file($fname)){
        if(!@unlink($fname)){
            $out = "<strong>Failed to remove backup file '$fname'!</strong>";
        }
    }

    $TMPL->addVar("msg",$out);
    PrintRedirect($SELF,3);
}

// ####################### Display Action Results ######################
function PrintResults($title, $message){
    global $TMPL;
    $out = '<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td class="tdrow2" align="center">'.$message.'</td></tr></table>';
    $TMPL->addVar("msg",$out);
}

// ####################### Perform OP on Table ######################
function TableOperation($tablename, $OP){
    global $CONN, $FUNC;

    if(!empty($tablename) && ereg("^CHECK$|^OPTIMIZE$|^REPAIR$",$OP)){
        $query	= "$OP TABLE `$tablename`";
        $result	= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
        $result = $result->fields;
        return "Operation on table '" . $tablename . "' Result: <strong>" . $result['Msg_text'] . "</strong><br/>";
    }
    else{
        return "<strong>Invalid table operation!</strong>";
    }
}


function BatchTableOperation($tablenames, $OP){
    global $DB;

    $msg = '';
    if(!empty($tablenames) && !empty($OP) && ereg("^CHECK$|^OPTIMIZE$|^REPAIR$",$OP)){
        for($i = 0; $i < count($tablenames); $i++){
            $msg = $msg . TableOperation($tablenames[$i], $OP);
        }
    }
    else{
        $msg = '<strong>No tables specified or invalid operation!</strong>';
    }

    return $msg;
}

// ######################### Instructions ##########################
function PrintInstructions(){
    return '<table border="1" width="100%" cellpadding="5" cellspacing="0">
			<tr>
				<td class="tdrow2">
					<strong>Check</strong> - Checks the table for errors<br />
					<strong>Optimize</strong> - Removes any wasted space (as reported in the \'Overhead\' column)<br />
					<strong>Repair</strong> - Attempts to recover errors in the table
				</td>
			</tr>
			</table>';
}

// ####################### List Backup Files #########################
function DisplayBackups(){
    global $DB, $backupDir, $backupUrl, $backupEnabled, $SELF, $TEXT;
    clearstatcache();

    $out = '<table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td class="tdrow1" colspan="6">'.$TEXT['db_backup']['backup_directory'].': "'.$backupDir.'"</td>
        </tr>
        <tr>
          <td class="tdrow1" width="50%">'.$TEXT['db_backup']['list_table']['file_name'].'</td>
          <td class="tdrow1">'.$TEXT['db_backup']['list_table']['size'].'</td>
          <td class="tdrow1">'.$TEXT['db_backup']['list_table']['last_modified'].'</td>
          <td class="tdrow1" colspan="3">&nbsp;</td>';
    if($dir = @opendir($backupDir)){
        while (false !== ($file = readdir($dir))){
            if((substr($file,0,1)!='.') && (strpos(strtolower($file),'.sql') > 0)){
                if($stats = @stat($backupDir . $file)){
                    $filestats[$stats['mtime']] = array(
                        'file'  => $file,
                        'size'  => $stats['size'],
                        'mtime' => date('Y-m-d H:i:s',$stats['mtime']),
                        'mktime' => $stats['mtime'],
                        'error' => false);
                }
                else{
                    $filestats[] = array('file' => $file, 'error' => true);
                }
            }
        }
        ksort($filestats);
        if(!empty($filestats)){
            @sort($filestats,SORT_STRING);
            for($i = 0;$i < count($filestats); $i++){
                if(empty($filestats[$i]['error'])){
                    $out .= '<tr>
                              <td class="tdrow3">' . $filestats[$i]['file'] . '</td>
                              <td class="tdrow3">' . DisplayReadableFilesize($filestats[$i]['size']) . '</td>
                              <td class="tdrow3">' . $filestats[$i]['mtime'] . '</td>
                              <!--td class="tdrow3">
                              <a href="'.$SELF.'&dbaction=restorebackup&filename=' . $filestats[$i]['file'].
                                        '" onclick="return confirm(\'Are you sure you wish to restore this backup file?\nAll data entered after backup date will be deleted.\');">Restore</a>
                              </td-->
                              <td class="tdrow3"><a href="' . $backupUrl . $filestats[$i]['file']. '">'.$TEXT['db_backup']['list_table']['download'].'</a></td>
                              <td class="tdrow3"><a href="'.$SELF.'&dbaction=deletebackup&filename=' . $filestats[$i]['file'].
                                        '" onclick="return confirm(\'Are you sure you wish to delete this backup file?\');">'.$TEXT['db_backup']['list_table']['delete'].'</a></td>
                            </tr>';
                }
                else{
                    $out .= '<tr>
                              <td class="tdrow3">'.$filestats[$i]['file'].'</td>
                              <td class="tdrow3" colspan="5">No info available (permissions wrong?).</td>
                            </tr>';
                }
            } //for
        }
    }

    $out .= '</table>';

    return $out;
}

// ####################### List Database Tables ######################
function DisplayTables(){
    global $backupEnabled;
    global $CONN,$FUNC,$TMPL,$SELF,$TEXT,$LOADED_PLUGIN,$SETTINGS,$_CONF,$_CENTER,$ROOT;

    //$out  = PrintInstructions();
    $out  ='';

    $out .= '<form method="post" action="" name="tables" id="pcms_tables">
			<input type="hidden" name="dbaction" value=""/>';

    $out .='<table border="0"  cellpadding="5" cellspacing="0" width="100%">';
    /*$gettables	= array();
    $query		= "SHOW TABLES FROM `". $_CONF['db']['name'] ."` LIKE '". $_CONF['db']['prefix'] ."%' ";p($query);
    $result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $gettables	= $result->GetArray();
    p($gettables);*/


    /*
    $gettables = $CONN->MetaTables();
    if(count($gettables)>0){
        for ($i=0,$n=count($gettables); $i<$n; $i++){
            $query		= "SHOW TABLE STATUS LIKE '" . $gettables[$i] . "'";
            $result		= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
            $tableinfo	= $result->fields;
            $out .= '<tr>
					<td class="tdrow3"><input type="checkbox" name="tablenames[]" value="' . $tableinfo['Name'] . '" checkme="group" style="cursor:pointer;" /></td>
					<td class="tdrow3">' . $tableinfo['Name'] . '</td>
					      <td class="tdrow3">' . $tableinfo['Rows'] . '</td>
					      <td class="tdrow3">' . DisplayReadableFilesize($tableinfo['Data_length']) . '</td>
					      <td class="tdrow3">' . DisplayReadableFilesize($tableinfo['Index_length']) . '</td>
					      <td class="tdrow3">' . (!empty($tableinfo['Data_free'])?'<b>':'') . DisplayReadableFilesize($tableinfo['Data_free']) .
                (!empty($tableinfo['Data_free'])?'</b>':'') .  '</td>
					      <td class="tdrow3"><a href="'.$SELF.'&dbaction=checktable&tablename=' . $tableinfo['Name'] . '">Check</a></td>
					      <td class="tdrow3"><a href="'.$SELF.'&dbaction=optimizetable&tablename=' . $tableinfo['Name'] . '">Optimize</a></td>
					      <td class="tdrow3"><a href="'.$SELF.'&dbaction=repairtable&tablename=' . $tableinfo['Name'] . '">Repair</a></td>
					      <td class="tdrow3">' . ($backupEnabled?'<a href="'.$SELF.'&dbaction=backuptable&tablename=' . $tableinfo['Name'] . '">Backup</a>':'Backup') . '</td>
				</tr>';
        }
    }
*/

    $out .= '<tr>
        <!--td class="tdrow1" colspan="6">&nbsp;</td>
                <td class="tdrow1"><input type="submit" value="Check" onclick="document.forms[\'tables\'].dbaction.value = \'checkall\';"/></td>
                <td class="tdrow1"><input type="submit" value="Optimize" onclick="document.forms[\'tables\'].dbaction.value = \'optimizeall\';"/></td>
                <td class="tdrow1"><input type="submit" value="Repair" onclick="document.forms[\'tables\'].dbaction.value = \'repairall\';"/></td-->
                <td class="tdrow1" align="right"><input type="submit" value="'.$TEXT['db_backup']['create_backup'].'" onclick="document.forms[\'tables\'].dbaction.value = \'backupall\';" ' .
        ($backupEnabled?'':'disabled') . '/></td>
      </tr>
    </table>
    </form>';

    $out .= DisplayBackups();
    return $out;
}

// get the value of $action
// Note: $tablename and $tablenames are 2 different settings!
$action = !empty($_POST['dbaction']) ? $_POST['dbaction'] : (!empty($_GET['dbaction']) ? $_GET['dbaction'] : '');
$tablename = !empty($_POST['tablename']) ? $_POST['tablename'] : (!empty($_GET['tablename']) ? $_GET['tablename'] : '');
$filename = !empty($_POST['filename']) ? $_POST['filename'] : (!empty($_GET['filename']) ? $_GET['filename'] : '');
$tablenames = (!empty($_POST['tablenames']) && is_array($_POST['tablenames']))?$_POST['tablenames']:'';

if(!empty($action)){
    switch ($action){
        case 'checktable':
            PrintResults('Check Table Results', TableOperation($tablename, 'CHECK'));
            break;
        case 'checkall':
            PrintResults('Check Table Results', BatchTableOperation($tablenames, 'CHECK'));
            break;
        case 'optimizetable':
            PrintResults('Optimize Table Results', TableOperation($tablename, 'OPTIMIZE'));
            break;
        case 'optimizeall':
            PrintResults('Optimize Table Results',BatchTableOperation($tablenames, 'OPTIMIZE'));
            break;
        case 'repairtable':
            PrintResults('Repair Table Results',TableOperation($tablename, 'REPAIR'));
            break;
        case 'repairall':
            PrintResults('Repair Table Results',BatchTableOperation($tablenames, 'REPAIR'));
            break;
        case 'backuptable':
            PrintResults('Backup Table Results',BackupSingleTable($tablename));
            break;
        case 'backupall':
            PrintResults('Backup Table Results', DbFullBackup());
            $FUNC->Redirect('index.php?m=security&tab=db_backup');
            break;
        case 'restorebackup':
            RestoreBackup($filename);
            break;
        case 'deletebackup':
            DeleteBackup($filename);
            $FUNC->Redirect('index.php?m=security&tab=db_backup');
            break;
    }
}


function DisplayReadableFilesize($filesize){
    global $language;

    $kb = 1024;         // Kilobyte
    $mb = 1048576;      // Megabyte

    if($filesize < $kb)			$size = $filesize . ' B';
    else if($filesize < $mb)	$size = round($filesize/$kb,2) . ' KB';
    else						$size = round($filesize/$mb,2) . ' MB';

    return (isset($size) AND $size != ' B') ? $size : '';
}

function DisplayDate($gmepoch, $dateformat = ''){
    global $sdlanguage, $mainsettings;

    if(empty($dateformat))	$dateformat = $mainsettings['dateformat'];
    $timezoneoffset = $mainsettings['timezoneoffset'];
    $dst = empty($mainsettings['daylightsavings'])?0:3600;

    // return a date if a date exists
    if(!empty($gmepoch))	return strtr(@gmdate($dateformat, $gmepoch + 3600 * $timezoneoffset + $dst), $sdlanguage);
    else					return '';
}


function PrintRedirect($gotopage, $timeout = 0, $message = "Settings Updated!"){
    global $admindir, $TMPL;

    $gotopage = str_replace('&amp;', '&', $gotopage);

    $out = '
  <table width="" border="0" cellpadding="5" cellspacing="0" align="center">
  <tr>
    <td class="tdrow1" background="'.$admindir.'images/gradient.gif" colspan="2">Redirecting...</td>
  </tr>
  <tr>
    <td class="tdrow2" width="70%">
    <a href="'.$gotopage.'" onclick="javascript:clearTimeout(timerID);">' . $message . ' Click here if you are not redirected.</a>
    </td>
  </tr>
  </table>
  <script language="javascript" type="text/javascript">
  <!--
  ';
    if(empty($timeout)){
        $out .= 'window.location="'.$gotopage.'"';
    }
    else{
        $out .= '
    timeout = '.($timeout*10).';
    function Refresh()
    {
      timerID = setTimeout("Refresh();", 100);

      if (timeout > 0)
      {
        timeout -= 1;
      }
      else
      {
        clearTimeout(timerID);
        window.location="'.$gotopage.'";
      }
    }
    Refresh();';
    }

    $out .= '
  //-->
  </script>';
    $TMPL->addVar("redirect",$out);
    #exit();
}

?>