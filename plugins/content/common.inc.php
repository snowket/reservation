<?
function createContent($title,$searchable=0){
   global $CONN,$_CONF,$FUNC;
   
   $query = "INSERT INTO {$_CONF['db']['prefix']}_content_title SET title='{$title}', creator='{$_SESSION['pcms_user_id']}'";
   $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
   $rec_id = $CONN->Insert_ID();
   $search = $searchable==1?1:0;
   for($i=0;$i<count($_CONF['langs_all']);$i++){
       $query = "insert into {$_CONF['db']['prefix']}_content set 
                rec_id ='{$rec_id}',lang ='{$_CONF['langs_all'][$i]}',search='{$search}'";
       $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());                      
   }   
     
   return $rec_id;
}

//---

function getContent($id,$lang=''){
   global $CONN,$_CONF,$FUNC;
   
   $id   = StringConvertor::toNatural($id);
   $lang = $FUNC->validLang($lang,'langs');
   
   $query = 'SELECT t.*,c.lang,c.content FROM '.$_CONF['db']['prefix'].'_content_title t
             LEFT JOIN '.$_CONF['db']['prefix'].'_content c ON t.id=c.rec_id
             WHERE 
               t.id   = "'.$id.'" AND 
               c.lang = "'.$lang.'"';
   $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
    
   return $result->fields;            
}

//---

function saveContent($id,$lang,$text){
   global $CONN,$_CONF,$FUNC;
   
   $id   = StringConvertor::toNatural($id);
   $lang = $FUNC->validLang($lang,'langs');
   $text = StringConvertor::qstr($text);
   
   $query = 'UPDATE '.$_CONF['db']['prefix'].'_content 
             SET 
                content= "'.$text.'" 
             WHERE 
                rec_id = "'.$id.'" AND 
                lang   = "'.$lang.'"'; 
   $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
   
   return 1;
}


?>