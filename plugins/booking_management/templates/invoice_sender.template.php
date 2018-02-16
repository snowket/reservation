<form class="pdf_send" action="index.php?m=booking_management&tab=booking_list&action=send_invoice" method="post">
    <input type="hidden" name="invoice_id" value="<?=$TMPL_invoice_number?>">
    <input type="hidden" name="invoice_html" class="invoice_html" value="">
    <table width="100%" border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
        <tr>
            <td bgcolor="#ccc" style="color:#000;" colspan="4"><?= $TEXT['invoice']['note']?></td>
        </tr>
        <tr>
            <td bgcolor="#ccc" style="color:#000;" colspan="4">
                <textarea rows="4" name="note" style="width:100%"><?=$TMPL_invoice['note']?></textarea>
            </td>
        </tr>
    </table>
    <center>
        <table frame="box" cellpadding="4" style="border-collapse: collapse; padding:10px;">
            <tr>
                <td>Total Amount Due in </td>
                <td>
                    <select id="currency_selector">
                    <option count="1" rate="1">GEL</option>
                    <?foreach($TMPL_currency_rates as $key=>$currency){?>
                    <option count="<?=$currency['count']?>" rate="<?=$currency['rate']?>" ><?=$currency['name']?></option>
                    <?}?>
                    </select>
                </td>
                <td> is:</td>
                <td>
                    <div id="converted_amount_due"></div>
                </td>
            </tr>
        </table>
        <table border="1" style="padding:4px; width:640px; border-collapse: collapse;" cellpadding="2" cellspacing="0">
            <tr>
                <td>
                    To:<input type="text" name="email1" value="<?=$TMPL_email?>" style="width:100%">
                </td>
                <td>
                    Cc:<input type="text" name="email2" value="" style="width:100%">
                </td>
                <td valign="bottom" style="text-align:center;">
                    <input class="formButton2" type="submit" value="SEND" style="text-align:center; cursor: pointer">
                </td>
                <td>
                    <div id="word_doc" style="cursor: pointer"><img src="images/icos16/word.jpg"></div>
                </td>
                <td width="30" align="center"><img id="printbutton" alt="print this page" onclick="PrintElem('#invoice')" style="cursor:pointer;" src="images/print.gif"></td>
                <td><div style="cursor: pointer" class="lang_changer" lang='geo'>GEO</div></td>
                <td><div style="cursor: pointer" class="lang_changer" lang='eng'>ENG</div></td>
                <td><div style="cursor: pointer" class="lang_changer" lang='rus'>RUS</div></td>
            </tr>
        </table>
    </center>
</form>

<form id="download_word_form" action="<?=$_SERVER['REQUEST_URI']?>&download_word=1" method="post">
    <input type="hidden" name="json" value='<?=json_encode($_POST)?>'>
    <input type="hidden" class="vvtype" name="vvtype" value="">
    <input type="hidden" class="opt" name="opt" value="">
    <input type="hidden" class="rate" name="rate" value="">
    <input type="hidden" class="count" name="count" value="">
</form>

<form id="lang_change" action="<?=$_SERVER['REQUEST_URI']?>" method="post">
    <input type="hidden" name="json" value='<?=json_encode($_POST)?>'>
    <input type="hidden" name="lang" value='geo' id="lang">
</form>

<br>
<br>
<br>
<script src="./js/jquery/jquery-1.11.2.min.js"></script>
<script src="./js/jquery/jquery-migrate-1.2.1.min.js"></script>
<script src="./js/jquery/jquery-ui.js"></script>
<script src="./js/functions.js"></script>
<link rel="stylesheet" href="./css/jquery-ui.css">

<script type="text/javascript">
    $(document).ready(function () {
        var option=$('#currency_selector option:selected').val();

        $('.pp_changed').append(' (GEL)');
        $('.pp_changed_exim').append(' (GEL)');

        var total_amount_due=parseFloat($("#total_amount_due").text());
        $('#converted_amount_due').text(convertGelTo(total_amount_due, 1,1));
        var html=Base64.encode($('#invoice').html());
        $('.invoice_html').val(html);
        $('#currency_selector').change(function () {
            var opt = $("option:selected", this);
            if(option!="GEL"){
                location.reload();
            }
            else{
                option=opt.val();
            }
            var rate=parseFloat(opt.attr('rate'));
            var count=parseFloat(opt.attr('count'));
            $('#converted_amount_due').text(convertGelTo(total_amount_due,rate,count));
            $('.dslr').each(function(){
                var amount=$(this).text();
                $(this).text(convertGelTo(amount,rate,count));
            });
            $('.pp_changed').html('<?=$TEXT['booking_modal']['price']?> ('+opt.val()+')');
            $('.pp_changed_exim').html('<b><?= $TEXT['invoice']['amount_due']?></b> ('+opt.val()+')');
            html=$('#invoice').html();
            $('.invoice_html').val(Base64.encode(html));

            $('#download_word_form').attr('action','<?=$_SERVER['REQUEST_URI']?>&download_word=1&opt='+opt.val()+'&rate='+rate+'&count='+count);
        });

        $('.formButton2').click(function(e){
            e.preventDefault();
         $('.pdf_send').submit();
        });
        function convertGelTo(gel, rate,count){
            return (gel/rate*count).toFixed(2);
        }

        $('#word_doc').click(function () {
         $('#download_word_form').submit();

        });

        $('.lang_changer').click(function () {
            var lang=$(this).attr('lang');
            $('#lang_change #lang').val(lang);
            $('#lang_change').attr('action', function(i, value) {
                  return value + "&lang=" + lang;
              });
            $('#lang_change').submit();
        });
    });
    $(window).load(function(){
        var opt = $("#currency_selector option:selected");
        var rate=parseFloat(opt.attr('rate'));
        var count=parseFloat(opt.attr('count'));
        $('.dslr').each(function(){
            var amount=$(this).text();

            $(this).text(convertGelTo(amount,rate,count));
        });
        function convertGelTo(gel, rate,count){
            return (gel/rate*count).toFixed(2);
        }
    });


    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data)
    {
		var height=$("#gallery").height();
        var mywindow = window.open('', '', 'height='+height+',width=600');
        mywindow.document.write('<html><head><title>my div</title>');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }

</script>