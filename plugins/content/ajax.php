<?
if(!defined('ALLOW_ACCESS')) exit;
require_once(dirname(__FILE__).'/common.inc.php');

$postAct = httpPost('action');
$getAct  = httpGet('action');


if($postAct=='add'){
  $POST = StringConvertor::ConvertSpecialChars($_POST);
  
  $validator = new Validator();
  $validator->validateLength($POST['title'],$TEXT['global']['title'],3,80);
  $errors = $validator->PassErrors();
  
  if($errors){
     print '{result:"0",errors:"'.$errors.'"}';
  }
  else{     
     $rec_id = createContent($POST['title'],httpPost('search'));  
     print '{result:"1",data:{rec_id:"'.$rec_id.'",title:"'.$POST['title'].'"}}'; 
  }

  exit;
}


/*******/

if($postAct=='save'){
   $result = saveContent(httpPost('id'),httpPost('lang'),httpPost('text'));
   print '{result:"1"}';
   exit;
}

/*******/

if($getAct=='get'){
   $data = getContent(httpGet('id'), httpGet('lang'));
   if(!$data){
      die('{}');
   }
   
   $content = preg_replace('/\r?\n/' ,'\n', addslashes($data['content']));
   
   print '{id:"'.$data['id'].'",lang:"'.$data['lang'].'",title:"'.$data['title'].'",text:"'.$content.'",creator:"'.$data['creator'].'"}';
   exit;   
}


?>