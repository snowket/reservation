<?
require_once(dirname(__FILE__)."/mime.class.php");
class Mailer{
 
 var $Sender         = "";
 var $Headers        = "";
 var $MailBody       = "";
 var $Charset        = 'utf-8';
 var $Encoding       = '8bit';
 var $Format         = 'text';
 var $inlineImg      = array();
 var $Attachs        = array();
 var $ERRORS         = array();
 var $driver         = NULL; 
 var $method         = "mail";
 var $boundary       = "";
 var $mime           = "";
 var $part1          = 0;
 var $part2          = 0;
 var $inline         = true;
 

//***************************************************************//
// Constructor                                                   //
//                                                                //
//***************************************************************// 
 
 function Mailer($method="mail",$params= array()){
    switch($method){
         case "smtp":
              $this->method = "smtp";
              require_once(dirname(__FILE__)."/driver/mail_smtp.class.php");
              $this->driver = new MailerSMTP($params);
            break;
         default:
              require_once(dirname(__FILE__)."/driver/mail_sendmail.class.php"); 
             $this->driver  = new MailerSENDMAIL($params);
           break;
    } 
    $this->mime = new MIME;   
 }
   
 function Open($params){
    $this->Sender  = isset($params['namefrom'])?"{$params['namefrom']}<{$params['mailfrom']}>":$params['mailfrom'];
    $this->mailSubject = htmlspecialchars($params['subject'],ENT_QUOTES);
    $this->MailBody      = $params['body'];
    if(isset($params['charset']))
        $this->Charset = $params['charset'];
    if(isset($params['format']))
        $this->Format = $params['format'];  
    if(isset($params['inlineImg']))
       $this->inline  = $params['inlineImg'];
  }
 
 
 
 function attach($filename){
    if(file_exists($filename))
      {
         $this->Attachs[] = array(
                'file'      =>   $filename,
                'type'      =>   $this->mime->getMimeType($filename)
            );
      }
 }
 

 function addSubject($subject){
   $this->mailSubject = $subject;
 }
 
 function addBody($text){
   $this->MailBody = $text;
 }
 
 function addTo($to){
    $this->driver->to[] = $to;
  }
 
 function addCc($cc){
    $this->driver->cc[] = $cc;
  }
 
 function addBcc($bcc){
    $this->driver->bcc[] = $bcc;
  }
  
 function clearTo(){
   $this->driver->to  = array();
  } 
  
 function clearCc(){
   $this->driver->cc  = array();
  } 
  
 function clearBcc(){
   $this->driver->bcc  = array();
 } 
 
 
 function clearAll() {
	$this->driver->to  = array();
	$this->driver->cc  = array();
	$this->driver->bcc = array();
  }  
 

 function prepareMessage(){
     $this->Headers  = $this->_makeHeaders();
     $this->MailBody = $this->_makeBody();
  } 

 function send(){
    if($this->method =="smtp")
      $this->driver->send($this->Sender,$this->Headers,$this->MailBody);
    else
       $this->driver->send($this->mailSubject,$this->Headers,$this->MailBody);
  }
  
  

  
function close(){
  $this->driver->close();
}

//***************************************************************//
//  for internal use only                                        //
//                                                               //
//***************************************************************// 
 function _makeHeaders(){
   $this->part1   = 0;
   $this->part2   = 0;
   $this->boundary = "_".md5(uniqid(time()));
   $this->inlineImg = array();
   
   $mailHeader = '';
   $mailHeader .="MIME-Version: 1.0\r\n";
   if($this->method =='smtp')
    $mailHeader .= "Subject: {$this->mailSubject}\r\n";
   $mailHeader .= "From: ".$this->Sender."\r\n"; 
   $mailHeader .= "X-Mailer: PHP/".phpversion()."\r\n";
   $mailHeader .= "Content-Type: multipart/".(count($this->Attachs)>0?"mixed":"related").";  boundary=\"{$this->boundary}0\"\r\n"; 
   $mailHeader .= "This is a MIME encoded message.\r\n\r\n";

  
   $mailHeader .= $this->_startPart("multipart/related"); 
   $mailHeader .= $this->_startPart("multipart/alternative");

   $mailHeader .= "--{$this->boundary}2\r\n";
   $mailHeader .=  "Content-Type: text/{$this->Format}; charset={$this->Charset}\r\n";
   $mailHeader .=  "Content-Transfer-Encoding: 7bit\r\n"; 
   return $mailHeader;
 }


 function _makeBody(){
   if($this->inline){
       
       $this->_convertImages();
    }
   $body = $this->MailBody;
   if(count($this->Attachs)>0)
     $body .= $this->_attachFiles();
   //$body .= "--{$this->boundary}0--\r\n";
    $body .= $this->_endPart();
   return $body;
 }



 function _attachFiles($disposition='attachment'){
   $attachs = "";
   $attachs .= $this->_startPart("multipart/mixed");
   for($i=0; $i<count($this->Attachs); $i++){ 
	    $attachs .= "--{$this->boundary}3\r\n";
		$attachs .= "Content-Type: {$this->Attachs[$i]['type']}; name=\"{$this->Attachs[$i]['file']}\"\r\n";
		$attachs .= "Content-Transfer-Encoding: base64\r\n";
        $attachs .= "Content-Disposition: {$disposition}; filename=\"".basename($this->Attachs[$i]['file'])."\"\r\n\r\n";
        $attachs .=  $this->_encodeFile($this->Attachs[$i]['file'])."\r\n\r\n";
   }
   return $attachs;
 }  


function _convertImages(){

   $server_name = str_replace('www.','',$_SERVER['SERVER_NAME']);
   
   preg_match_all("/<img\s.*src=\"(http:\/\/(www\.)?".$server_name.".*)\".*>/imU",$this->MailBody,$matches);
   
   if($matches[1]){
        $images = array_unique($matches[1]);
        foreach($images as $image){
             $random = md5(uniqid(time()));
             $this->inlineImg[] = array(
                      'name'      =>  basename($image),
                      'file'      =>  $this->_convertImgUri($image),
                      'type'      =>  $this->mime->getMimeType($image),
                      'key'       =>  $random
                    ); 
             $this->MailBody=preg_replace("/src=\"".preg_replace('/\//','\/',$image)."\"/iU","src=\"cid:{$random}\"",$this->MailBody);
        }
    }   


   $this->MailBody .= $this->_endPart();
   for($i=0;$i<count($this->inlineImg);$i++){ 
         $this->MailBody .="--{$this->boundary}1\r\n";
         $this->MailBody .="Content-Type: {$this->inlineImg[$i]['type']}; name=\"{$this->inlineImg[$i]['name']}\"\r\n";
         $this->MailBody .="Content-Disposition: inline;\r\n filename=\"{$this->inlineImg[$i]['name']}\"\r\n";
         $this->MailBody .="Content-Transfer-Encoding: base64\r\n";
         $this->MailBody .="Content-ID: <{$this->inlineImg[$i]['key']}>\r\n\r\n";
         $this->MailBody .= $this->_encodeFile($this->inlineImg[$i]['file'])."\r\n\r\n";
      } 
  $this->MailBody .= $this->_endPart();
}

   
 function _startPart($type){
     $part = "\r\n\r\n--{$this->boundary}".($this->part2++)."\r\n";
     $this->part1++;
	 $part .="Content-Type: {$type}; boundary=\"{$this->boundary}{$this->part1}\"\r\n\r\n";
     
     return $part;
  }

 function _addPart($type,$charset,$name,$disposition,$contents=""){

   $part ="--{$this->boundary}{$this->part1}\r\n";
   foreach($headers as $k=>$v){
      $part .= "{$k}: {$v}\r\n";
     }
   $part .= "\r\n";
   if($contents)
     $part .= $contents."\r\n\r\n";
   return $part;
 }

 function _endPart(){
     return  "\r\n\r\n--{$this->boundary}".($this->part2--)."--\r\n\r\n";
  }



 function _encodeFile ($filename) {
   if(!$fd = fopen($filename, "rb"))
 	  return;
 	$magic_quotes = get_magic_quotes_runtime();
 	set_magic_quotes_runtime(0);
 	$file_buffer = fread($fd, filesize($filename));
 	$file_buffer = chunk_split(base64_encode($file_buffer), 76, "\r\n");
 	fclose($fd);
 	set_magic_quotes_runtime($magic_quotes);
 	return $file_buffer;
 }



 function  _convertImgUri($uri){   
   
    $parts = parse_url($uri); 
    $uri = $_SERVER['DOCUMENT_ROOT'].$parts['path'];
    $uri = str_replace("http://".$_SERVER['SERVER_NAME'],$_SERVER['DOCUMENT_ROOT'],$uri);
    return $uri;   
  }
  
  
  function _isLocal($uri){
     $server_name = str_replace('www.','',$_SERVER['SERVER_NAME']); 
     if(strpos($uri,$server_name)){
       return true;
     }
     return false;
  }
  
 
}

?>