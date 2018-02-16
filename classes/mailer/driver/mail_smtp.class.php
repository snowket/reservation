<?
class MailerSMTP{
 
 var $SERVER      = "localhost";
 var $PORT        = 25;
 var $AUTHTYPE    = "LOGIN";  //NONE, PLAIN, DIGEST-MD5, CRAM-MD5
 var $AUTHID      = "";
 var $AUTHPWD     = "";
 var $logfile     = "";
 var $conn        = false;
 var $debug       = true;
 var $mailSubject = "";
 
 var $to    = array();
 var $cc    = array();
 var $bcc   = array();
 
//****************************************************************//
//*** Constructor      *******************************************//
 function MailerSMTP($params){
    $this->SERVER  = $params['server'];
    if($params['port'])     $this->PORT      =  $params['port'];
    if($params['authtype']) $this->AUTHTYPE  =  $params['authtype'];  
    if($params['authid'])   $this->AUTHID    =  $params['authid'];  
    if($params['authpwd'])  $this->AUTHPWD   =  $params['authpwd'];
    if($params['logfile'])
      {     
          $this->logfile   =  $params['logfile'];
          $this->debug     =  @fopen($this->logfile,"w");
      }
 }
  
  
 function send($from,$headers,$body){ 
    $this->conn = @fsockopen ($this->SERVER, $this->PORT, $errno, $errstr, 30);
    if(!$this->_checkAnswer(220)) return false;
    
    fputs($this->conn, "EHLO {$this->SERVER}\r\n");
    $reply = $this->_getReply();
    if($reply['code']!= 250)
      {
        //*** if EHLO is not supported try HELO command ***//
         if($reply['code']==500)
           {
             fputs($this->conn, "HELO {$this->SERVER}\r\n");
             if(!$this->_checkAnswer(250)) return false; 
           }
         else return false;  
      }
      
    if(!$this->Authenticate()) return false;
    fputs($this->conn, "MAIL FROM: ".$from."\r\n"); 
    if(!$this->_checkAnswer(250)) return false; 
    for($i=0;$i<count($this->to);$i++)
	  {
		fputs($this->conn,"RCPT TO: ".$this->to[$i]."\r\n");	
		if(!$this->_checkAnswer(250)) return false; 
	 }				
	//fputs($this->conn, "Subject: {$this->mailSubject}\r\n");
   // fputs($this->conn, "BCC: ".implode(',',$to)."\r\n");
    fputs($this->conn, "DATA\r\n"); 
    if(!$this->_checkAnswer(354)) return false;
    fputs($this->conn, $headers."\r\n");
    fputs($this->conn, $body." \r\n"); 
    fputs($this->conn, ".\r\n"); 
    fputs($this->conn, "RSET\r\n"); 
    if(!$this->_checkAnswer(250)) return false;
 }
  
 function close() {
  if($this->conn) 
    {
   	  fclose($this->conn);
   	  $this->conn = false;
   	}  
   if($this->debug)
     {
       fclose($this->debug);
   	   $this->debug = false;  
     }	
 }
  

 function Authenticate() {
  
  if($this->AUTHTYPE =='NONE') return true;
 
 //*** start authentification    **********************// 
  fputs($this->conn,"AUTH {$this->AUTHTYPE}\r\n");

 //***  DIGEST-MD5 / CRAM-MD5 authorization  **********//
  if($this->AUTHTYPE=='DIGEST-MD5'||$this->AUTHTYPE=='CRAM-MD5')
    {
       $reply = $this->_getReply();
       //**** if server does not support this method **//
       if($reply['code']!="334")
         {
            $this->_fixError($reply['msg']);
            return false; 
         }
       else{
            //***  CRAM-MD5 authorization  **********//
             if($this->AUTHTYPE=='CRAM-MD5')
               {
                  fputs($this->conn, $this->_cramMd5Login($reply['msg']));
                  if(!$this->_checkAnswer(235)) return false;
               }
             //***  DIGEST-MD5 authorization  *******//
            else{
                 fputs($this->conn, $this->_digestMd5Login($reply['msg']));
                 if(!$this->_checkAnswer(334)) return false;
                 fputs($this->conn,'\r\n');
                 if(!$this->_checkAnswer(235)) return false;
              }   
        }  
    }
    
 //***  PLAIN / LOGIN  authorization  ******************//  
  else
    {
       if(!$this->_checkAnswer(334))  return false;
       //***  PLAIN  ******************************//
       if($this->AUTHTYPE=='PLAIN')
         { 
            fputs($this->conn, base64_encode($this->AUTHID."\0".$this->AUTHID."\0".$this->AUTHPWD)."\r\n");
            if(!$this->_checkAnswer(235)) return false;

         }
       //***  LOGIN  ******************************//  
       else{
          //*** sending username   ************//
          fputs($this->conn, base64_encode($this->AUTHID)."\r\n");
          if(!$this->_checkAnswer(334)) return false;
          
          //*** sending password   ************//  
          fputs($this->conn, base64_encode($this->AUTHPWD)."\r\n");
          if(!$this->_checkAnswer(235)) return false;
       }   
   }
  return true;
}
	
	
//******************************************************************// 
//*** PRIVATE METHODS  *********************************************// 		
	
//******************************************************************// 
//*** Performs check if last command was successfull ***************// 	
function _checkAnswer($goodCode){
  $reply = $this->_getReply();
  if($reply['code'] != $goodCode) 
	{ 
	   $this->_fixError($reply['msg']);	
	   return false;
	}
  return true;	
}
		
//******************************************************************// 
//*** Returns SMTP Server answer  **********************************// 
function _getReply() {
  $data = "";
  
  while($str = fgets($this->conn)) 
	 {
		$data .= $str;
		if(substr($str,3,1) == " ")
		   break;
     }  
     
   if($this->debug) fwrite($this->debug,$data);
   print nl2br($data);
   $reply = array(
           'code'   => substr($str,0,3),
           'msg'    => substr($str,4)
    );  
  return $reply;
}

//******************************************************************// 
//*** Errors handler   *********************************************//
function _fixError($error) {
  $this->ERRORS[] = $error;  
}

//******************************************************************//
//*** Creates authentication  responce for CRAM-MD5 method  ********//
function _cramMd5Login($challenge){
    $challenge=base64_decode($challenge);
    $hash=bin2hex($this->_hmac_md5($challenge,$this->AUTHPWD));
    $response=base64_encode($this->AUTHID. " " . $hash) . "\r\n";
    return $response;
 }
 
//******************************************************************//
//*** Creates authentication  responce for DIGEST-MD5 method  ******// 
 function _digestMd5Login($challenge) {
    $result = $this->_digestmd5_parse_challenge($challenge);

    // verify server supports qop=auth
    // $qop = explode(",",$result['qop']);
    //if (!in_array("auth",$qop)) {
    // rfc2831: client MUST fail if no qop methods supported
    // return false;
    //}
    $cnonce = base64_encode(bin2hex($this->_hmac_md5(microtime())));
    $ncount = "00000001";

    /* This can be auth (authentication only), auth-int (integrity protection), or
       auth-conf (confidentiality protection).  Right now only auth is supported.
       DO NOT CHANGE THIS VALUE */
    $qop_value = "auth";

    $digest_uri_value = 'smtp/'.$this->SERVER;

    // build the $response_value
    //FIXME This will probably break badly if a server sends more than one realm
    $string_a1  = utf8_encode($this->AUTHID).":";
    $string_a1 .= utf8_encode($result['realm']).":";
    $string_a1 .= utf8_encode($this->AUTHPWD);
    $string_a1  = $this->_hmac_md5($string_a1);
    $A1 = $string_a1 . ":" . $result['nonce'] . ":" . $cnonce;
    $A1 = bin2hex($this->_hmac_md5($A1));
    $A2 = "AUTHENTICATE:$digest_uri_value";
    // If qop is auth-int or auth-conf, A2 gets a little extra
    if ($qop_value != 'auth') {
        $A2 .= ':00000000000000000000000000000000';
    }
    $A2 = bin2hex($this->_hmac_md5($A2));

    $string_response = $result['nonce'] . ':' . $ncount . ':' . $cnonce . ':' . $qop_value;
    $response_value = bin2hex($this->_hmac_md5($A1.":".$string_response.":".$A2));

    $response = 'charset=utf-8,username="' . $this->AUTHID . '",realm="' . $result["realm"] . '",';
    $response .= 'nonce="' . $result['nonce'] . '",nc=' . $ncount . ',cnonce="' . $cnonce . '",';
    $response .= "digest-uri=\"$digest_uri_value\",response=$response_value";
    $response .= ',qop=' . $qop_value;
    $response = base64_encode($reply);
    return $response . "\r\n";

}
 
function _digestmd5_parse_challenge($challenge) {
  $challenge=base64_decode($challenge);
  while(isset($challenge)) 
    {
      if ($challenge{0} == ',') 
       { // First char is a comma, must not be 1st time through loop
            $challenge=substr($challenge,1);
       }
     $key=explode('=',$challenge,2);
     $challenge=$key[1];
     $key=$key[0];
     if($challenge{0} == '"') 
       {
            // We're in a quoted value
            // Drop the first quote, since we don't care about it
            $challenge=substr($challenge,1);
            // Now explode() to the next quote, which is the end of our value
            $val=explode('"',$challenge,2);
            $challenge=$val[1]; // The rest of the challenge, work on it in next iteration of loop
            $value=explode(',',$val[0]);
            // Now, for those quoted values that are only 1 piece..
            if(sizeof($value) == 1) 
              $value=$value[0];  // Convert to non-array
            
        } 
      else {
            // We're in a "simple" value - explode to next comma
            $val=explode(',',$challenge,2);
            if(isset($val[1])) 
               $challenge=$val[1];
            else 
              unset($challenge);
            $value=$val[0];
         }
        $parsed["$key"]=$value;
    } // End of while loop
    return $parsed;
} 
 
 
//******************************************************************// 
//*** Creates a HMAC digest that can be used for auth purposes  ****//
function _hmac_md5($data, $key='') {
  if(extension_loaded('mhash')) 
    {
       if($key== '') 
          $mhash=mhash(MHASH_MD5,$data);
       else 
          $mhash=mhash(MHASH_MD5,$data,$key);
      return $mhash;
    }
   if(!$key) 
      return pack('H*',md5($data));
    
    $key = str_pad($key,64,chr(0x00));
    if(strlen($key) > 64) 
       $key = pack("H*",md5($key));
    
    $k_ipad =  $key ^ str_repeat(chr(0x36), 64) ;
    $k_opad =  $key ^ str_repeat(chr(0x5c), 64) ;
    /*** get it recursive. ***/
    $hmac=hmac_md5($k_opad . pack("H*",md5($k_ipad . $data)));
    return $hmac;
} 
 

 
//******************************************************************//  
}  
?>