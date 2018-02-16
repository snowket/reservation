<?
DEFINE("CONN_FAILED", "Connect to ftp server failed");
DEFINE("LOGIN_FAILED","Incorrect login or password");
DEFINE("NLIST_FAILED","Proceeding file list failed");

class FtpManager
{
var $SERVER;
var $LOGIN;
var $PASSWORD;
var $PORT;
var $CONN_ID;
var $ERRORS =array();


function FtpManager($server,$login,$password,$port="21"){
  $this->SERVER   = $server;
  $this->PORT     = $port;
  $this->LOGIN    = $login;
  $this->PASSWORD = $password;
  $this->CONN_ID  = @ftp_connect($server,$port) or $this->RaiseError(CONN_FAILED);
  @ftp_login($this->CONN_ID, $login, $password) or $this->RaiseError(LOGIN_FAILED); 
  //@register_shutdown_function("$this->CloseConnection");
 
}

function SetDir($dir)
  {
  if(!@ftp_chdir($this->CONN_ID, $dir))
   return false;
  return true;
  }

function CurrDir(){
 return @ftp_pwd($this->CONN_ID);
}

function RemoveDir($path){
 $filelist =@ftp_nlist($this->CONN_ID, $path) or $this->RaiseError(NLIST_FAILED);
 if(!empty($filelist))
   {
   foreach($filelist as $value)
      {
      if(!(@ftp_rmdir($this->CONN_ID,$path)||@ftp_delete($this->CONN_ID,$path)))
         $this->RemoveDir($value);
      }
   }
   @ftp_rmdir($this->CONN_ID,$path);
}

function ReadDirectory($path='.'){
  $items['folders']=array();
  $items['files']=array();
  $filelist =@ftp_nlist($this->CONN_ID, $path);
  if(!empty($filelist))  
    {
    foreach($filelist as $value)
       {
       if(ftp_size($this->CONN_ID,$path."/".$value)==-1)
         $items['folders'][]=$value;
       else
          $items['files'][]=$value;   
       }
    }
  return $items; 
  }

// returns size of a file in bytes ,kilobytes or megabytes
// $unit="" || "mb" || "kb"
function getFileSize($filepath,$unit="",$accuracy=2){
 if($unit=="kb") return round(ftp_size($this->CONN_ID,$filepath)/1024,$accuracy);
 elseif($unit=="mb") return round(ftp_size($this->CONN_ID,$filepath)/(1024*1024),$accuracy);
 else return ftp_size($this->CONN_ID,$filepath); 
}

function getFile($filepath,$mode=FTP_BINARY,$resume_pos){
  ftp_get($this->CONN_ID,basename($filepath),$filepath,$mode,$resume_pos); 
}

function RaiseError($ErrCode){
 die("<br>".$ErrCode);
}

function ExecCommand($command){
 ftp_exec($this->CONN_ID,$command);
}

function CloseConnection(){
 ftp_quit ($this->CONN_ID);
}

}
?>