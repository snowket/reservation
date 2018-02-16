<?
class MailerSENDMAIL{
 
 var $to    = array();
 var $cc    = array();
 var $bcc   = array();
  
 function MailerSENDMAIL(){
 
 }
  
 function send($subject,$headers,$body){
    $to = implode(',',$this->to);
    @mail($to, $subject, $body, $headers);
 }  
 
 function close(){} 
  
}  
?>