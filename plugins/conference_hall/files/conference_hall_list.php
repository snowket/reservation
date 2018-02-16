<?php
if ($_GET['action'] == "send_invoice") {

    $invoice_id = (int)$_POST['invoice_id'];
    $note = $_POST['note'];

    $to = $_POST['email1'];


    $html = base64_decode($_POST['invoice_html']);
    if ($invoice['note'] != '') {
        $html = str_replace("<!--note ", '<', $html);
        $html = str_replace("note-->", '>', $html);
        $html = str_replace("[note]", nl2br($invoice['note']), $html);
    }
    $pdf_path = $ROOT . '/last_invoice/invoice_' . $invoice_id . '.pdf';
    $feedback_attachment = create_pdf($pdf_path, $html);
    ##
   # dd($to);
    $mail_smtp->setFrom($_CONF['SMTP']['Username'],$settings['ltd']['value']);

    $mail_smtp->addAddress($to,$to);     // Add a recipient
    $mail_smtp->addReplyTo($_CONF['SMTP']['Username'], 'Information');
    $mail_smtp->addCC($_POST['email2']);

    $mail_smtp->addAttachment($feedback_attachment);            // Add attachments
    $mail_smtp->isHTML(true);                                  // Set email format to HTML

    $mail_smtp->Subject = 'Booking Invoice';
    $mail_smtp->Body    = 'The invoice is sent by the administration of '.$settings['ltd']['value']." <br><br> Address:".
        $settings['address']['value']." <br> TEL:".$settings['tel']['value'];

    if(!$mail_smtp->send()) {
        die("Unable to Send Email");
    } else {
        sleep(5);
        $mail_smtp->ClearAllRecipients();
        $mail_smtp->AddAddress($settings['e_mail']['value']); //some another email
        $mail_smtp->Subject = 'Booking Invoice';
        $mail_smtp->Body    = 'The invoice is sent by the administration of '.$settings['ltd']['value']." <br><br> Address:".
            $settings['address']['value']." <br> TEL:".$settings['tel']['value'];
        $mail_smtp->send();
        echo "The mail has been sent successfully";
        echo '<script>setInterval(function() {window.close();},2000);</script>';
        exit;
    }
}
if($_GET['filter']=='filter'){
    $where_query=array();
    if($_GET['booking_id']){
        $where_query['booking_id']=$_GET['booking_id'];
    }
    if($_GET['guest_name']){
        $where_query['guest_name']=$_GET['guest_name'];
    }
    if($_GET['guest_id']){
        $where_query['guest_id']=$_GET['guest_id'];
    }
    if($_GET['in_start_date']){
        $where_query['in_start_date']=$_GET['in_start_date'];
    }
    if($_GET['in_end_date']){
        $where_query['in_end_date']=$_GET['in_end_date'];
    }

    $bookings=getBookingbyQuery($where_query);
    $rooms=getCH_room();
    $TMPL_plugin='conference_hall';
    $TMPL->addVar("TMPL_booking", $bookings);
    $TMPL->addVar("rooms", $rooms);
    $TMPL->addVar("TMPL_plugin", $TMPL_plugin);
    $TMPL->ParseIntoVar($_CENTER,"conference_hall_list");
}
elseif(isset($_GET['booking_id']) && !isset($_GET['filter']) && $_GET['action'] !='get_invoice'){
    if(isset($_GET['action']) && $_GET['action']=='view'){
        $id=$_GET['booking_id'];
        $booking=getBooking($id);
    }
    $services=getCH_service();
    $booking['info']['services']=unserialize($booking['info']['services']);
    foreach ($booking['info']['services'] as $key => $value) {
      $booking['service_info'][$key]['count']=$value;
      $booking['service_info'][$key]['info']=getCH_service(null,$key);
    }
    #dd($booking);
    $TMPL->addVar("TMPL_booking", $booking);
    $TMPL->addVar("TMPL_services", $services);
    $TMPL->ParseIntoVar($_CENTER,"conference_hall_view");
}
elseif($_GET['action']=='delete'){
    $booking_id=$_GET['id'];
    $room_id=$_GET['room_id'];
    $affected_rows=deleteRoomFromBooking($booking_id,$room_id);
    header('location:index.php?m=conference_hall&tab=conference_hall_list&action=view&booking_id='.$booking_id);
}
elseif ($_GET['action'] == "get_invoice") {
 if (isset($_GET['booking_id'])) {
    if (isset($_GET['lang']) && $_GET['lang'] != '') {
        require_once($ROOT . "/lang/" . $_GET['lang'] . ".php");
    }
    if (isset($_POST['lang']) && $_POST['lang'] != '') {
        require_once($ROOT . "/lang/" . $_POST['lang'] . ".php");
        $stringJSON = get_magic_quotes_gpc() ? stripslashes($_POST['json']) : $_POST['json'];
        $_POST = json_decode($stringJSON, true);
    }
            $lang=(isset($_GET['lang'])?$_GET['lang']:LANG);
            $booking_id = (int)$_GET['booking_id'];

            $query = "SELECT * FROM {$_CONF['db']['prefix']}_ch_booking WHERE id={$booking_id}";

            $booking = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $booking_finantials=getBookingInfo($booking['id']);
            $booking_finantials['services']=unserialize($booking_finantials['services']);
            foreach ($booking_finantials['services'] as $key => $value) {
              $booking_finantials['service_info'][$key]['count']=$value;
              $booking_finantials['service_info'][$key]['info']=getCH_service(null,$key);
            }
            ##dd($booking_finantials);


            if (!empty($booking)) {


                $TMPL->addVar("TMPL_booking", $booking);
                $TMPL->addVar("founds", $booking_finantials);
                $TMPL->addVar("TMPL_settings", $settings);

                    $guest = getGuestByID($booking['guest_id']);
                    $template = "full_invoice";
                    $invoices[$booking['id']]['a'] = "on";
                    $invoices[$booking['id']]['s'] = "on";

                #var_dump($guest);
               $invoice_number = $booking['id'];
               # $invoice = getInvoiceByID($invoice_number);

                $TMPL->addVar("TMPL_invoice_number", $invoice_number);
                $TMPL->addVar("TMPL_invoice", $invoice);
                $TMPL->addVar("TMPL_guest", $guest);
                $html = $TMPL->ParseIntoString($template);
                #addBlobToInvoice($invoice_number, $html);

                if ($_GET['download_word'] == 1) {

                    //p($invoices);exit;
                    $bookings[] = $booking;
                    require_once $ROOT . '/files/PhpWord.inc.php';
                    require_once $ROOT . '/files/GetWordDoc.php';
                    header_remove();
                    header("Content-Disposition: attachment; filename=" . $invoice_number . ".docx");
                    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                    readfile($ROOT . '/last_invoice/' . $invoice_number . '.docx'); // or echo file_get_contents($temp_file);
                    unlink($ROOT . '/last_invoice/' . $invoice_number . '.docx');
                    exit;
                }

                p($html);
                $TMPL->addVar("TMPL_email", $guest['email']);
                $html = $TMPL->ParseIntoString("invoice_sender");
                p($html);
                exit;
            } else {
                die("Booking with this id not exists!");
            }

        } else {
            die("Booking ID not set");
        }
  }else
  {
    $bookings=getBookings();
    $rooms=getCH_room();
    $TMPL_plugin='conference_hall';
    $TMPL->addVar("TMPL_booking", $bookings);
    $TMPL->addVar("rooms", $rooms);
    $TMPL->addVar("TMPL_plugin", $TMPL_plugin);
    $TMPL->ParseIntoVar($_CENTER,"conference_hall_list");
}
function deleteRoomFromBooking($id,$room_id){
    global $CONN;
    $query_count="SELECT * FROM cms_ch_booking_info WHERE booking_id=".$id;
    $query_count=$CONN->Execute($query_count);
    $query_count=$query_count->getRows();
    if(count($query_count)>1){

    $query="DELETE FROM cms_ch_booking_info WHERE booking_id=".$id." AND room_id=".$room_id;
    $CONN->Execute($query,$affected_rows);
    return true;
    }else{
       $query="DELETE FROM cms_ch_booking WHERE id=".$id;
        $CONN->Execute($query,$affected_rows);
        return true;
    }
}
