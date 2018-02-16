<?
/*Common*/
$TEXT['rooms_floor']='Rooms by the floors';
$TEXT['floor']='Floor';
$TEXT['block']='Building';
$TEXT['floor_number']='Number of floors';
$TEXT['number_of_adult']='Adults';
$TEXT['extra_service']='Additional service';
$TEXT['price']='Price';
$TEXT['cur']='GEL';
$TEXT['price_plan']='Price Plan';
$TEXT['room_type']='Room Tipe';
$TEXT['room_capacity']='Room Capacity';
$TEXT['filter']='Filter';
$TEXT['select_block']='Select Building';
$TEXT['select_type']='Select room tipe';
$TEXT['discount']='Discount';
$TEXT['select_room']='Select room';
$TEXT['select_guest']='New Guest';
$TEXT['all']='All';
$TEXT['select_country']='Country';
$TEXT['select_service']='Select Service';
$TEXT['add_new_service']='New service';


/*Rooms*/
$TEXT['type_id']='Room Tipe';
$TEXT['capacity_id']='Room Capacity';
$TEXT['quantity']='Room Quantity';
$TEXT['extra_services']='Extra Services';
$TEXT['default_services']='Default Services';
$TEXT['block_id']='Building';
$TEXT['intro']='Intro';
$TEXT['image']='Image';
$TEXT['gall']='Gallery';
$TEXT['floor']='Floor';
$TEXT['number']='Room #';

$TEXT['get_accommodation_invoice']='Send Accomondation Invoice';
$TEXT['get_service_invoice']='Send Service Invoice';
$TEXT['get_full_invoice']='Send Total Invoice';

$TEXT['booking_list']= array(
    'booking_id'    => 'Booking ID',
    'guest'         => 'Guest',
    'affiliate'     => 'Partner',
    'check_in'      => 'Check In',
    'check_out'     => 'Check Out',
    'room_id'       => 'Room',
    'status'        => 'Status',
    'food'          => 'Pansion',
    'debt'          => 'Debit',
    'paid'          => 'Paid',
    'method'        => 'Method',
    'invoice'       => 'Invoice',
    'select_all'    => 'Select All',
    'accommodation_invoice'  => 'Accomondation Invoice',
    'services_invoice'       => 'Service Invoice',
    'action'        => 'Action',
    'view_booking'  => 'View Booking details',
    'send'          => 'Send',
    'download'      => 'Download',
);

$TEXT['booking_list']['filter_modal']= array(
    'booking_id'        => 'Booking ID',
    'invoice_id'        => 'Invoice ID',
    'title'             => 'Filter/Search',
    'check_in_date'     => 'Check in',
    'check_out_date'    => 'Check Out',
    'guest_id'          => 'Guest/Organisation ID',
    'guest_type'        =>'Guest Type',
    'guest_name'        => 'Guest Name',
    'payment_method'    => 'Paymant Method',
    'tax'               => 'TAX(VAT)',
    'submit_filter'     =>'Search',
    'status'            =>'Status',
    'all'               =>'All',
    'from'              =>'from',
    'to'                =>'To',
    'today_checkins'    =>'Today Guests',
    'today_checkouts'   =>'Today Leaving',
    'booking_method'    =>'Payment Method',
    'method'            =>array('all'=>'All','online'=>'Online','local'=>'Desk')
);


$TEXT['view']= array(
    'booking_info'      =>'Booking Info',
    'booking_id'        =>'Booking ID',
    'room'              =>'Room',
    'guest'             =>'Guest',
    'checkin_checkout'  =>'Check in/out',
    'checkin'           =>'Check In',
    'checkout'          =>'Check out',
    'tax'               =>'VAT',
    'guests'            =>'Guests',
    'day'               =>'Day',
    'accommodation'     =>'Accomondation',
    'extra_services'    =>'Axtra Services',
    'action'            =>'Action',
    'total_price'       =>'Total Price',
    'amount_paid'       =>'Paid',
    'amount_due'        =>'Not Paid',
    'payment'           =>'Add found',
    'transactions'      =>'Transactions',
    'amount_in'         =>'Amount in',
    'amount_out'        =>'Amount out',
    'balance'           =>'Ballance',
    'guest'             =>'Guest',
    'responsive_guest'  =>'Responsive Guest',
);
/*Options*/
$TEXT['transactions']= array(
    'all'           =>'All',
    'guest'         => 'Guest',
    'administrator' => 'Administrator',
    'date'          => 'Date',
    'check_out'     => 'Check Out',
    'amount'        => 'Amount',
    'debit'         => 'Debit',
    'credit'        => 'Credit',
    'status'        => 'Status',
    'method'        => 'Method',
    'tax'           => 'TAX(VAT)',
    'currency'      => 'Gel',
    'edit'          => 'Edit',
    'delete'        => 'Delete',
    'booking'       => 'Booking',
    'action'        => 'Action'
);

$TEXT['transactions']['edit_modal']= array(
    'title'             =>'Edit Trnasaction',
    'date'              => 'Date',
    'amount_in'         => 'Amoint In',
    'amount_out'        => 'Amount Out',
    'payment_method'    => 'Payment Method',
    'guest_id'          => 'Guest ID',
    'booking_id'        => 'Booking ID',
    'edit'              => 'Edit',
    'cancel'            => 'Cancel',
    'tax'               => 'VAT'
);

$TEXT['transactions']['delete_modal']=array(
    'title'             => 'Delete Transaction',
    'confirm_message'   => 'Are you shure you want to delete?',
    'delete'            => 'Delete',
    'cancel'            => 'Cancel',
);

$TEXT['cashbacks']= array(
    'partner'       => 'Partner',
    'date'          => 'Date',
    'check_out'     => 'Check out',
    'amount'        => 'The Amount to be returned',
    'amount_paid'   => 'Refunded',
    'amount_due'    => 'Ballance',
    'status'        => 'Statusi',
    'method'        => 'Method',
    'action'        => 'Action',
    'tax'           => 'TAX(VAT)',
    'currency'      =>'Currency',
    'view_booking'  =>'View Booking Detais',
);

$TEXT['cashbacks']['modal']=array(
    'title'=>'Pay Cashback',
    'add'=>'Add',
    'cancel'=>'Cancel',
    'amount'=>'Amount',

);

$TEXT['guests_financial_state']= array(
    'guest'              => 'Guest',
    'id_number'          => 'Personal ID',
    'type'               => 'Type',
    'tax'                => 'TAX(VAT)',
    'total_paid'         => 'Total Income',
    'total_debts'        => 'Total Debts',
    'total_unpaid'       => 'The remaining debt',
    'balance'            => 'Balance',
    'action'             => 'Action',
    'all'                => 'All',
    'add_balance'        => 'Add to Balance',
    'show_all_bookings'  => 'View All Bookings'
);

$TEXT['guests_financial_state']['fill_balance_modal']= array(
    'title'         =>'Fill Balance',
    'add'           =>'Add',
    'cancel'        =>'Cancel',
    'amount'        =>'Amount'
);

$TEXT['first_step']=array(
    'message'   => 'PLease, First create Hotel Buildings and room tipes'
);

$TEXT['setts'] = array(
    'th_size'      =>  'Intro image thumbnail size',
    'img_size'     =>  'Intro image size',
    'g_th_size'    =>  'Gallery image thumbnail size',
    'g_size'       =>  'Gallery image size',
    'resize'       =>  'Resample and constrain proportions',
    'crop'         =>  'Resize and crop',
    'use_cats'     =>  'Enable using categories',
    'add_search'   =>  'Add "search in created category" moduls to menu builder',
    'levels'       =>  'Count of search levels for options'
);

$TEXT['tab']['transactions']['title']='Transactions';
$TEXT['tab']['guests_financial_state']['title']='Guests Financial Status';
$TEXT['tab']['booking_list']['title']='Booking List';
$TEXT['tab']['cashbacks']['title']='cashbacks';
$TEXT['tab']['reports']['title']='Reports';
$TEXT['tab']['reports']['sub']=array(
    'daily_annual_income_report'=>'Daily Income',
    'annual_income_report'=>'Anual Income',
    'accrual_based_income'=>'Accrued Based Income (ABI)',
    'spending_materials'=>'Spending Materials',
    'budget'=>'Budget',
    'rss'=>'Daily Fainancial Report',
);
$TEXT['tab']['statistics']['title']='Statistic';
$TEXT['tab']['statistics']['sub']=array(
    'usage_by_citizenship'=>'Guest Statistic',
    'rooms_usage'=>'Rooms booked %',
    'room_usage_days'=>'room loadin period',
    'full_report'=>'day audit',
);


/*MONTHS*/
$TEXT['months']= array(
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'Jule',
    '08' => 'August',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December',
);
$TEXT['months_budget']= array(
    '1' => 'January',
    '2' => 'February',
    '3' => 'March',
    '4' => 'April',
    '5' => 'May',
    '6' => 'June',
    '7' => 'Jule',
    '8' => 'August',
    '9' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December',
);

$TEXT['week_days']= array(
    '0' => 'Su',
    '1' => 'Mo',
    '2' => 'Tu',
    '3' => 'We',
    '4' => 'Th',
    '5' => 'Fr',
    '6' => 'St',
);
/*ERRORS*/
$TEXT['sp_materials']['filter']= array(
    'title'         => 'Filter / Search',
    'date'          => 'Date From / To',
    'submit_filter' =>'Search',
    'from'          =>'From',
    'to'            =>'Tu',
);

$TEXT['sp_materials']['board']= array(
    'title'          => 'Spending Materials',
    'year'           => 'Year',
    'month'          => 'Month',
    'day'            => 'Day',
    'day_of_week'    => 'D/W'
);


$TEXT['transactions']['filter_modal']= array(
    'title'             => 'Filter / Saerch',
    'date'              => 'Date From / To',
    'amount'            => 'Amount',
    'guest_id'          => 'Guest/Organisation ID',
    'guest_type'        => 'Guest Type',
    'guest_name'        => 'Guest Name',
    'payment_method'    => 'Payment Method',
    'tax'               => 'TAX(VAT)',
    'submit_filter'     => 'Search',
    'destination'       => 'Description',
    'tr_type'           => 'Transaction Type',
    'from'              => 'From',
    'to'                => 'To',
);

$TEXT['destination']= array(
    'all'           => 'All',
    'accommodation' => 'Accommodation',
    'extra-service' => 'Extra Service',
    'restaurant'    => 'Restaurant',
    'auto-park'     => 'Auto Park',
);

$TEXT['annual_income_report']= array(
    'month'             => 'Month',
    'accommodation_in'  => 'Income of Accomondation',
    'services_in'       => 'Income of Service',
    'out'               => 'Amount Out',
    'balance'           => 'Ballance',
    'chart_title'       => 'Annual Income Report',
    'chart_subtitle'    => 'Report of Years'
);

$TEXT['daily_annual_income_report']= array(
    'month'             => 'Month',
    'day'               => 'Day',
    'accommodation_in'  => 'Income of Accomondation',
    'services_in'       => 'Income of Service',
    'out'               => 'Amount Out',
    'balance'           => 'Balance',
    'chart_title'       => 'Dayly Income Report',
    'chart_subtitle'    => 'Report of Month'
);

$TEXT['accrual_based_income']= array(
    'month'                       => 'Month',
    'acc_income_tax_included'     => 'Accomondation (tax included)',
    'acc_income_tax_free'         => 'Accomondation (tax free)',
    'services_income'             => 'Income(Services)',
    'income'                      => 'Income(Total)',
    'tax'                         => 'VAT',
    'last_12_month_income'        => 'Last 12 Month Income',
    'chart_title'                 => 'Calculation of Accrued Based Income',
    'chart_subtitle'              => 'Accrued Based Income of Years',
    'summary'                     => 'Total'
);

$TEXT['usage_by_citizenship']= array(
    'country'                     => 'Country',
    'guests_count'                => 'Guest',
    'guests_count_night'          => 'Guest/Dey',
    'local'                       => 'Local',
    'forign'                      => 'Foreign',
    'local_forign_title'          => 'Total Nighnts Spend by Guests',
    'all_country_title'           => 'Total Quantity of Guests by Countries'
);

$TEXT['tr_type']= array(
    'all'           =>'All',
    'debit'         =>'Paid In',
    'credit'        =>'Paid out',
);

$TEXT['guest_type']= array(
    'all'           =>'All',
    'non-corporate' => 'Individual',
    'company'       =>'Company',
    'tour-company'  =>'Tour Operator'
);

$TEXT['guests']['filter_modal']= array(
    'title'             => 'Find Guests/Filter',
    'date'              => 'Date of Registration From / To',
    'publish'           => 'Status',
    'guest_id'          => 'Guest/Organization ID',
    'guest_type'        => 'Guets Type',
    'guest_name'        => 'Guest Name',
    'payment_method'    => 'Payment Method',
    'tax'               => 'TAX(VAT)',
    'submit_filter'     =>'Search',
    'destination'       =>'Description'
);


$TEXT['cashbacks']['filter_modal']= array(
    'title'             => 'Filter / Search',
    'date'              => 'Date From / To',
    'amount'            => 'Amount From / To',
    'guest_id'          => 'Partner ID',
    'guest_name'        => 'Partner Name',
    'payment_method'    => 'Payment Method',
    'tax'               => 'TAX(VAT)',
    'submit_filter'     =>'Search',
    'status'            =>'Status',
    'type'              =>array(
        0=>"All",
        1=>"Have to pay",
        2=>"Paid",
    )
);
$TEXT['check_budget'] ='Budget Compare To Income';
$TEXT['check_budgets'] ='Budget Compare To Income';
$TEXT['mdt_charge'] ='MTD';
$TEXT['b_s_day']='Select Date';
$TEXT['b_year'] ='Year';
$TEXT['b_years'] ='Years';
$TEXT['b_s_years']='Select Yaers';
$TEXT['add_budget'] ='Add Budget';
$TEXT['edit'] ='Edit';
$TEXT['date'] ='date';
$TEXT['user'] ='user';
$TEXT['acounting'] ='accounting';
$TEXT['start_day'] ='start day';
$TEXT['start_month'] ='start month ';
$TEXT['start_year'] ='Observ year';
$TEXT['last_day'] ='last year day';
$TEXT['last_month'] ='last year month';
$TEXT['last_year'] ='last year';
$TEXT['income'] ='reservations income (accrualed)';
$TEXT['income_service'] ='services income (accrualed)';
$TEXT['income_sum'] ='sums income (accrualed)';
$TEXT['income_inc'] ='reservations income (cash)';
$TEXT['income_inc_service'] ='services income (cash)';
$TEXT['income_inc_sum'] ='sums income (cash)';
$TEXT['wat'] ='vat (cash)';
$TEXT['all_rooms'] ='all rooms';
$TEXT['used_rooms'] ='used rooms';
$TEXT['used_rooms_per'] ='used rooms percentage';
$TEXT['check_in'] ='check in ';
$TEXT['check_out'] ='check out ';

$TEXT['rss']=array(
  'daily_rev'=>'Daily REVENUE',
  'sel_day'=>'Selected Day',
  'budget'=>'Budget',
  'diff'=>'Difference',
  'l_year'=>'Last Year',
  'mtd'=>'MTD REVENUE',
  'mtd_day'=>'Selected MTD',
  'mtd_l_year'=>'Selected MTD Last Year',
  'income'=>'Income',
  'a_rooms'=>'Available Rooms',
  'o_rooms'=>'OOO Rooms',
  'r_rooms'=>'Sold Room',
  'oc_rooms'=>'Occupancy Rate',
  'adr'=>'ADR',
  'rev_par'=>'RevPAR',
  'status'=>array(
    '1'=>'Budget',
    '2'=>'Income',
    '3'=>'Difference',
  ),
);
