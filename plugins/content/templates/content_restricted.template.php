<?if(!defined('ALLOW_ACCESS')) exit;?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td><?require_once(dirname(__FILE__)."/langbar.template.php");?></td> 
 </tr>
 <tr>
   <td>
     <script language="javascript">
      pcmsInterface.drawIframe({
        text: '<?=$TMPL_text?>'
      });
     </script>   
   </td>
 </tr>
</table>


