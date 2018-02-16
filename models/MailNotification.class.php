<?php


class MailNotification
{
    public $error = false;
    public $errorMessage = NULL;
    private $table = '';
    private $mail;
    private $hotelSettings;

    public function __construct()
    {
        global $_CONF, $CONN, $VALIDATOR, $TEXT, $FUNC, $sess;
        $this->_CONF = $_CONF;
        $this->CONN = $CONN;
        $this->VALIDATOR = $VALIDATOR;
        $this->TEXT = is_array($TEXT['parcel']) ? $TEXT['parcel'] : $TEXT;
        $this->FUNC = $FUNC;
        $this->sess = $sess;
        $this->table = $this->_CONF['db']['prefix'] . '_email_templates';
        $this->hotelSettings=GetHotelSettings();
        $this->init_mail();
    }

    private function init_mail()
    {
        require_once realpath(__DIR__ . '/..').'/classes/PHPMailer/PHPMailerAutoload.php';
        $this->mail = new PHPMailer();

        $this->mail->isSMTP();
        $this->mail->SMTPAuth = $this->_CONF['SMTP']['SMTPAuth'];
        $this->mail->SMTPDebug = 0;
        $this->mail->Debugoutput = 'html';
        $this->mail->Host = $this->_CONF['SMTP']['Host'];
        $this->mail->Port = $this->_CONF['SMTP']['Port'];
        $this->mail->CharSet = 'utf-8';
        $this->mail->debuge = 4;
        $this->mail->Username = $this->_CONF['SMTP']['Username'];
        $this->mail->Password = $this->_CONF['SMTP']['Password'];
        $this->mail->setFrom($this->_CONF['SMTP']['setFromMail'], $this->_CONF['SMTP']['setFromTitle']);
        $this->mail->addReplyTo($this->_CONF['SMTP']['addReplyToMail'], $this->_CONF['SMTP']['addReplyToTitle']);
    }

    public function GetEmailTemplate($emailTypeID, $lang)
    {
        $out = array();
        $query = "SELECT * FROM " . $this->table . " WHERE typeid = " . $emailTypeID . " AND lang='" . $lang . "'";
        $result = $this->CONN->Execute($query) OR $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        $out = $result->fields;
        return $out;
    }

    private function GetHotelSettings()
    {
        $query	= "SELECT * FROM {$this->_CONF['db']['prefix']}_hotel_settings WHERE publish='1' ORDER BY orderid,id";
        $result	= $this->CONN->Execute($query)or $this->FUNC->ServerError(__FILE__,__LINE__,$this->CONN->ErrorMsg());
        $data	= $result->GetRows();

        for ($i=0;$i<count($data);$i++) {
            $out[$data[$i]['input_name']] = $data[$i]['value'];
        }

        return $out;
    }

    public function SendBookingDetailsToGuest($email, $first_name, $last_name,$booked_rooms_info, $booking_data, $lang)
    {
        if($lang=='test'){
            return $this->SendSmtpMail($email, $first_name.''.$last_name,'teqst', 'teqst');
        }
        $emailTemplate = $this->GetEmailTemplate(3, $lang);

        if (!$emailTemplate) {
            $this->error = true;
            $this->errorMessage = 'Invalid $emailTypeID (email template not found in DB)';
            return false;
        }
        $rooms_info="<table border='1' width='100%' style='border-collapse: collapse; border:solid gary 1px'>";
        $rooms_info.="<tr>";
        $rooms_info.="<td align='left'>Booking ID</td><td align='left'>Room Type</td>";
        $rooms_info.="</tr>";
        foreach($booked_rooms_info as $room_info){
            $rooms_info.="<tr>";
            $rooms_info.="<td align='left'>".$room_info['booking_id']."</td><td align='left'>".$room_info['type'][0]['title']."</td>";
            $rooms_info.="</tr>";
        }
        $rooms_info.="</table>";
        $message = "<html><head></head><body>" . $emailTemplate['message'] . "</body></html>";
        // fetch message
        $from_to=array(
            '{firstname}'                   =>$first_name,
            '{lastname}'                    =>$last_name,
            '{email}'                       =>$email,
            '{hotel_checkin_time}'          =>$this->hotelSettings['check_in'],
            '{hotel_checkout_time}'         =>$this->hotelSettings['check_out'],
            '{rooms_count}'                 =>$booking_data['rooms_count'],
            '{rooms_info}'                  =>$rooms_info,
            '{booking_number}'              =>$booking_data['booking_number'],
            '{booking_checkin}'             =>$booking_data['booking_checkin'],
            '{booking_checkout}'            =>$booking_data['booking_checkout'],
            '{total_accommodation_price}'   =>$booking_data['total_accommodation_price'],
            '{total_services_price}'        =>$booking_data['total_services_price'],
            '{booking_vat}'                 =>$booking_data['booking_vat'],
            '{booking_total_price}'         =>$booking_data['booking_total_price'],
            '{night_stay}'                  =>$booking_data['night_stay'],
            '{comment}'                     =>$booking_data['booking_comment'],
            '{services}'                    =>$booking_data['services']
        );
        $message = strtr($message,$from_to);
        return $this->SendSmtpMail($email, $first_name.''.$last_name,$emailTemplate['subject'], $message);
    }

    public function SendBookingCancellationInfoToGuest($email, $first_name, $last_name, $booking_data, $lang)
    {
        $emailTemplate = $this->GetEmailTemplate(4, $lang);

        if (!$emailTemplate) {
            $this->error = true;
            $this->errorMessage = 'Invalid $emailTypeID (email template not found in DB)';
            return false;
        }

        $message = "<html><head></head><body>" . $emailTemplate['message'] . "</body></html>";
        // fetch message
        $from_to=array(
            '{firstname}'               =>$first_name,
            '{lastname}'                =>$last_name,
            '{email}'                   =>$email,
            '{hotel_checkin_time}'      =>$this->hotelSettings['check_in'],
            '{hotel_checkout_time}'     =>$this->hotelSettings['check_out'],
            '{rooms_count}'             =>$booking_data['rooms_count'],
            '{booking_number}'          =>$booking_data['booking_number'],
            '{booking_checkin}'         =>$booking_data['booking_checkin'],
            '{booking_checkout}'        =>$booking_data['booking_checkout'],
            '{total_accommodation_price}'=>$booking_data['total_accommodation_price'],
            '{total_services_price}'    =>$booking_data['total_services_price'],
            '{booking_vat}'             =>$booking_data['booking_vat'],
            '{booking_total_price}'     =>$booking_data['booking_total_price'],
            '{night_stay}'              =>$booking_data['night_stay'],

        );
        $message = strtr($message,$from_to);
        return $this->SendSmtpMail($email, $first_name.''.$last_name,$emailTemplate['subject'], $message);
    }

    public function SendCancelReminderToGuest($email, $first_name, $last_name, $notification_data, $lang)
    {
        $emailTemplate = $this->GetEmailTemplate(5, $lang);

        if (!$emailTemplate) {
            $this->error = true;
            $this->errorMessage = 'Invalid $emailTypeID (email template not found in DB)';
            return false;
        }

        $message = "<html><head></head><body>" . $emailTemplate['message'] . "</body></html>";
        // fetch message
        $from_to=array(
            '{firstname}'               =>$first_name,
            '{lastname}'                =>$last_name,
            '{email}'                   =>$email,
            '{hotel_checkin_time}'      =>$this->hotelSettings['check_in'],
            '{hotel_checkout_time}'     =>$this->hotelSettings['check_out'],
            '{cancellation_date}'       =>$notification_data['cancellation_date'],
            '{rooms_count}'             =>$notification_data['rooms_count'],
            '{booking_number}'          =>$notification_data['booking_number'],
            '{booking_checkin}'         =>$notification_data['check_in'],
            '{booking_checkout}'        =>$notification_data['check_out'],
            '{night_stay}'              =>$notification_data['night_stay'],

        );
        $message = strtr($message,$from_to);
        return $this->SendSmtpMail($email, $first_name.''.$last_name,$emailTemplate['subject'], $message);
    }

    public function NotifyGuestOnRegistration($email, $first_name, $last_name, $password, $lang)
    {
        $emailTemplate = $this->GetEmailTemplate(1, $lang);

        if (!$emailTemplate) {
            $this->error = true;
            $this->errorMessage = 'Invalid $emailTypeID (email template not found in DB)';
            return false;
        }

        $message = "<html><head></head><body>" . $emailTemplate['message'] . "</body></html>";
        // fetch message
        $message = str_replace(
            array('{firstname}', '{lastname}', '{email}', '{password}'),
            array($first_name, $last_name, $email, $password),
            $message
        );
        return $this->SendSmtpMail($email, $first_name.''.$last_name,$emailTemplate['subject'], $message);
    }

    public function SendPassRecoveryLinkToGuest($email, $first_name, $last_name, $pass_recovery_link, $lang)
    {
        $emailTemplate = $this->GetEmailTemplate(2, $lang);
        if (!$emailTemplate) {
            $this->error = true;
            $this->errorMessage = 'Invalid $emailTypeID (email template not found in DB)';
            return false;
        }
        $message = "<html><head></head><body>" . $emailTemplate['message'] . "</body></html>";
        // fetch message
        $message = str_replace(
            array('{firstname}', '{lastname}', '{pass_recovery_link}'),
            array($first_name, $last_name, $pass_recovery_link),
            $message
        );
        return $this->SendSmtpMail($email, $first_name.''.$last_name,$emailTemplate['subject'], $message);
    }

    public function SendBookingDetailsToAdministrator($email, $guest, $booked_rooms_info, $booking_data, $lang)
    {
        $emailTemplate = $this->GetEmailTemplate(6, $lang);

        if (!$emailTemplate) {
            $this->error = true;
            $this->errorMessage = 'Invalid $emailTypeID (email template not found in DB)';
            return false;
        }
        $rooms_info="<table border='1' width='100%' style='border-collapse: collapse; border:solid gary 1px'>";
        $rooms_info.="<tr>";
        $rooms_info.="<td align='left'>Booking ID</td><td align='left'>Block</td><td align='left'>Room Type</td><td align='left'>Room</td><td align='left'>Floor</td><td align='left'>Status</td>";
        $rooms_info.="</tr>";
        foreach($booked_rooms_info as $room_info){
            $rooms_info.="<tr>";
            $rooms_info.="<td align='left'>".$room_info['booking_id']."</td>";
            $rooms_info.="<td align='left'>".$room_info['block'][0]['title']."</td>";
            $rooms_info.="<td align='left'>".$room_info['type'][0]['title']."</td>";
            $rooms_info.="<td align='left'>".$room_info['room']."</td>";
            $rooms_info.="<td align='left'>".$room_info['floor']."</td>";
            $rooms_info.="<td align='left'>".$room_info['housekeeping_status']."</td>";
            $rooms_info.="</tr>";
        }
        $rooms_info.="</table>";

        $message = "<html><head></head><body>" . $emailTemplate['message'] . "</body></html>";
        // fetch message
        $from_to=array(
            '{firstname}'                   =>$guest['first_name'],
            '{lastname}'                    =>$guest['last_name'],
            '{email}'                       =>$email,
            '{hotel_checkin_time}'          =>$this->hotelSettings['check_in'],
            '{hotel_checkout_time}'         =>$this->hotelSettings['check_out'],
            '{rooms_count}'                 =>$booking_data['rooms_count'],
            '{booking_number}'              =>$booking_data['booking_number'],
            '{booking_checkin}'             =>$booking_data['booking_checkin'],
            '{booking_checkout}'            =>$booking_data['booking_checkout'],
            '{total_accommodation_price}'   =>$booking_data['total_accommodation_price'],
            '{total_services_price}'        =>$booking_data['total_services_price'],
            '{booking_vat}'                 =>$booking_data['booking_vat'],
            '{booking_total_price}'         =>$booking_data['booking_total_price'],
            '{night_stay}'                  =>$booking_data['night_stay'],
            '{comment}'                     =>$booking_data['booking_comment'],
            '{services}'                    =>$booking_data['services'],
            '{room}'						=>$rooms_info
        );
        $message = strtr($message,$from_to);
        return $this->SendSmtpMail($email, $guest['first_name'].''.$guest['last_name'],$emailTemplate['subject'], $message);
    }

    private function SendMail($emailData){
        //TO:DO swich smtp or pop

    }

    private function SendStandardMail($emailData){
        $r = $this->FUNC->Send_Email_u(
            $emailData['email'],
            $emailData['subject'],
            $emailData['message'],
            $emailData['type'],
            $emailData['charset'],
            $emailData['from_name'],
            $emailData['from_email']
        );
        if (!$r) {
            $this->error = true;
            $this->errorMessage = 'Mail sending failed';
        }
        return $this->error;

    }
    private function SendSmtpMail($address, $Name, $Subject, $Message)
    {
        $this->mail->clearAddresses();
        $this->mail->clearAttachments();
        $this->mail->addAddress($address, $Name);
        $this->mail->Subject = $Subject;
        $this->mail->msgHTML($Message);
        //$mail->Body=$Message;

        if (!$this->mail->send()) {
            $this->errorMessage = $this->mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    }
}






