<?php

/*Options*/
$TEXT['transactions'] = array(
    'all' => 'ყველა',
    'guest' => 'სტუმარი',
    'administrator' => 'ადმინისტრატორი',
    'date' => 'თარიღი',
    'check_out' => 'გამოსვლის დღე',
    'amount' => 'თანხა',
    'debit' => 'შემოსავალი',
    'credit' => 'გასავალი',
    'status' => 'სტატუსი',
    'method' => 'მეთოდი',
    'tax' => 'TAX(დ.ღ.გ)',
    'currency' => 'ლარი',
    'edit' => 'რედაქტირება',
    'delete' => 'წაშლა',
    'booking' => 'ჯავშანი',
    'action' => 'ქმედება',
    'room' => 'ოთახი',
);

$TEXT['transactions']['edit_modal'] = array(
    'title' => 'ტრანზაქციის რედაქტირება',
    'date' => 'თარიღი',
    'amount_in' => 'შემოსავალი',
    'amount_out' => 'გასავალი',
    'payment_method' => 'გადახდის მეთოდი',
    'guest_id' => 'სტუმრის ID',
    'booking_id' => 'ჯავშნის ID',
    'edit' => 'რედაქტირება',
    'cancel' => 'გაუქმება',
    'tax' => 'დ.ღ.გ',
);

$TEXT['transactions']['delete_modal'] = array(
    'title' => 'ტრანზაქციის წაშლა',
    'confirm_message' => 'დარწმუნებული ხართ,რომ გსურთ წაშლა?',
    'delete' => 'წაშლა',
    'cancel' => 'გაუქმება',
);

$TEXT['cashbacks'] = array(
    'partner' => 'პარტნიორი',
    'date' => 'თარიღი',
    'check_out' => 'გამოსვლის დღე',
    'amount' => 'დასაბრუნებელი',
    'amount_paid' => 'დაბრუნებულია',
    'amount_due' => 'ნაშთი',
    'status' => 'სტატუსი',
    'method' => 'მეთოდი',
    'action' => 'ქმედება',
    'tax' => 'TAX(დ.ღ.გ)',
    'currency' => 'ლარი',
    'view_booking' => 'ჯავშნის ნახვა',
);

$TEXT['cashbacks']['modal'] = array(
    'title' => 'Cashback-ის გადახდა',
    'add' => 'დამატება',
    'cancel' => 'გაუქმება',
    'amount' => 'თანხა',
);

$TEXT['guests_financial_state'] = array(
    'guest' => 'სტუმარი',
    'id_number' => 'პირადი ნომერი',
    'type' => 'ტიპი',
    'tax' => 'TAX(დ.ღ.გ)',
    'total_paid' => 'სულ გადახდილი',
    'total_debts' => 'სულ დავალიანება',
    'total_unpaid' => 'დარჩენილი დავალიანება',
    'balance' => 'ბალანსზე არსებული თანხა',
    'action' => 'ქმედება',
    'all' => 'ყველა',
    'add_balance' => 'ბალანსის შევსება',
    'show_all_bookings' => 'ყველა ჯავშნის ნახვა',
);

$TEXT['get_accommodation_invoice'] = 'განთავსების ინვოისის გაგზავნა';
$TEXT['get_service_invoice'] = 'სერვისების ინვოისის გაგზავნა';
$TEXT['get_full_invoice'] = 'ჯამური ინვოისის გაგზავნა';

$TEXT['booking_list'] = array(
    'booking_id' => 'ჯავშნის ID',
    'guest' => 'სტუმარი',
    'affiliate' => 'პარტნიორი',
    'check_in' => 'შესვლის დღე',
    'check_out' => 'გამოსვლის დღე',
    'room_id' => 'ოთახი',
    'status' => 'სტატუსი',
    'food' => 'კვება',
    'debt' => 'დავალიანება',
    'paid' => 'გადახდილია',
    'method' => 'მეთოდი',
    'invoice' => 'ინვოისი',
    'select_all' => 'ყველას მონიშვნა',
    'accommodation_invoice' => 'განთავსების ინვოისი',
    'services_invoice' => 'სერვისების ინვოისი',
    'action' => 'ქმედება',
    'view_booking' => 'ჯავშნის ნახვა',
    'send' => 'გაგზავნა',
    'download' => 'გადმოწერა',
);

$TEXT['booking_list']['filter_modal'] = array(
    'booking_id' => 'ჯავშნის ID',
    'invoice_id' => 'ინვოისის ID',
    'title' => 'ფილტრი / ძიება',
    'check_in_date' => 'შესვლის თარიღი',
    'check_out_date' => 'გასვლის თარიღი',
    'guest_id' => 'სტუმრის/ორგანიზაციის ID',
    'guest_type' => 'სტუმრის ტიპი',
    'guest_name' => 'სტუმრის სახელი/დასახელება',
    'payment_method' => 'გადახდის მეთოდი',
    'tax' => 'TAX(დ.ღ.გ)',
    'submit_filter' => 'ძიება',
    'status' => 'სტატუსი',
    'all' => 'ყველა',
    'from' => 'დან',
    'to' => 'მდე',
    'today_checkins' => 'დღეს შემოდიან',
    'today_checkouts' => 'დღეს გადიან',
    'booking_method' => 'დაჯავშნის მეთოდი',
    'room' => 'ოთახი',
    'method' => array('all' => 'ყველა', 'online' => 'ონლაინ', 'local' => 'შიდა'),
);

$TEXT['view'] = array(
    'booking_info' => 'ჯავშანი',
    'booking_id' => 'ჯავშნის ID',
    'room' => 'ოთახი',
    'guest' => 'სტუმარი',
    'checkin_checkout' => 'შესვლა/გასვლა',
    'checkin' => 'შესვლა',
    'checkout' => 'გასვლა',
    'tax' => 'დ.ღ.გ',
    'guests' => 'სტუმრები',
    'day' => 'დღე',
    'accommodation' => 'განთავსება',
    'extra_services' => 'დამატებითი სერვისები',
    'action' => 'მოქმედება',
    'total_price' => 'ჯამური ღირებულება',
    'amount_paid' => 'გადახდილია',
    'amount_due' => 'გადასახდელია',
    'payment' => 'გადახდა',
    'transactions' => 'ტრანზაქციები',
    'amount_in' => 'შემოტანა',
    'amount_out' => 'გატანა',
    'balance' => 'ბალანსი',
    'guest' => 'სტუმარი',
    'responsive_guest' => 'პასუხისმგებელი პირი',
);

$TEXT['guests_financial_state']['fill_balance_modal'] = array(
    'title' => 'ბალანსის შევსება',
    'add' => 'დამატება',
    'cancel' => 'გაუქმება',
    'amount' => 'თანხა',
);

$TEXT['first_step'] = array(
    'message' => 'პირველ რიგში უნდა შექმნათ სასტუმროს შენობა(ბლოკი) და ოთახების ტიპი',
);

$TEXT['setts'] = array(
    'th_size' => 'Intro image thumbnail size',
    'img_size' => 'Intro image size',
    'g_th_size' => 'Gallery image thumbnail size',
    'g_size' => 'Gallery image size',
    'resize' => 'Resample and constrain proportions',
    'crop' => 'Resize and crop',
    'use_cats' => 'Enable using categories',
    'add_search' => 'Add "search in created category" moduls to menu builder',
    'levels' => 'Count of search levels for options',
);

$TEXT['tab']['transactions']['title'] = 'ტრანზაქციები';
$TEXT['tab']['guests_financial_state']['title'] = 'სტუმრების ფინანსური მდგომარეობა';
$TEXT['tab']['booking_list']['title'] = 'ჯავშნების სია';
$TEXT['tab']['booking_list']['sub'] = array(
    'booking_list' => 'ინდივიდუალური',
    'booking_dbl_list' => 'ჯგუფური',
);
$TEXT['tab']['cashbacks']['title'] = 'cashbacks';
$TEXT['tab']['reports']['title'] = 'რეპორტები';
$TEXT['tab']['reports']['sub'] = array(
    'daily_annual_income_report' => 'დღიური ბრუნვა',
    'annual_income_report' => 'წლიური ბრუნვა',
    'accrual_based_income' => 'დარიცხული შემოსავალი (ABI)',
    'spending_materials' => 'სახარჯი მასალები',
    'budget' => 'დაგეგმილი ბიუჯეტი',
    'rss' => 'დღიური ფინანსური რეპორტი',
);
$TEXT['tab']['statistics']['title'] = 'სტატისტიკა';
$TEXT['tab']['statistics']['sub'] = array(
    'usage_by_citizenship' => 'სტუმრების სტატისტიკა',
    'rooms_usage' => 'ნომრების % დატვირთვიანობა',
    'room_usage_days' => 'ნომრების დატვირთვიანობა პერიოდზე',
    'room_type_report' => 'ნომრის ტიპების დატვირთვიანობა პერიოდზე',
    'full_report' => 'დღის აუდიტი',
);

/*MONTHS*/
$TEXT['months'] = array(
    '01' => 'იანვარი',
    '02' => 'თებერვალი',
    '03' => 'მარტი',
    '04' => 'აპრილი',
    '05' => 'მაისი',
    '06' => 'ივნისი',
    '07' => 'ივლისი',
    '08' => 'აგვისტო',
    '09' => 'სექტემბერი',
    '10' => 'ოქტომბერი',
    '11' => 'ნოემბერი',
    '12' => 'დეკემბერი',
);
$TEXT['months_budget'] = array(
    '1' => 'იანვარი',
    '2' => 'თებერვალი',
    '3' => 'მარტი',
    '4' => 'აპრილი',
    '5' => 'მაისი',
    '6' => 'ივნისი',
    '7' => 'ივლისი',
    '8' => 'აგვისტო',
    '9' => 'სექტემბერი',
    '10' => 'ოქტომბერი',
    '11' => 'ნოემბერი',
    '12' => 'დეკემბერი',
);

$TEXT['week_days'] = array(
    '0' => 'კვ',
    '1' => 'ორშ',
    '2' => 'სამ',
    '3' => 'ოთხ',
    '4' => 'ხუთ',
    '5' => 'პარ',
    '6' => 'შაბ',
);
/*ERRORS*/
$TEXT['sp_materials']['filter'] = array(
    'title' => 'ფილტრი / ძიება',
    'date' => 'თარიღი დან / მდე',
    'submit_filter' => 'ძიება',
    'from' => 'დან',
    'to' => 'მდე',
);

$TEXT['sp_materials']['board'] = array(
    'title' => 'სახარჯი მასალები',
    'year' => 'წელი',
    'month' => 'თვე',
    'day' => 'დღე',
    'day_of_week' => 'კვ/დღე',
);

$TEXT['transactions']['filter_modal'] = array(
    'title' => 'ფილტრი / ძიება',
    'date' => 'თარიღი დან / მდე',
    'amount' => 'თანხა',
    'guest_id' => 'სტუმრის/ორგანიზაციის ID',
    'guest_type' => 'სტუმრის ტიპი',
    'guest_name' => 'სტუმრის სახელი/დასახელება',
    'payment_method' => 'გადახდის მეთოდი',
    'tax' => 'TAX(დ.ღ.გ)',
    'submit_filter' => 'ძიება',
    'destination' => 'დანიშნულება',
    'tr_type' => 'ტრანზაქციის ტიპი',
    'from' => 'დან',
    'to' => 'მდე',
);

$TEXT['destination'] = array(
    'all' => 'ყველა',
    'accommodation' => 'განთავსება',
    'extra-service' => 'დამატებითი სერვისები',
    'restaurant' => 'რესტორანი',
    'auto-park' => 'ავტო პარკი',
);

$TEXT['annual_income_report'] = array(
    'month' => 'თვე',
    'accommodation_in' => 'შემოსავალი განთავსება',
    'services_in' => 'შემოსავალი სერვისები',
    'out' => 'გასავალი',
    'balance' => 'ბალანსი',
    'chart_title' => 'წლიური ბრუნვის ანგარიში',
    'chart_subtitle' => 'წარმოდგენილია შემდეგი წლების ანგარიში',
);

$TEXT['rooms_usage_report'] = array(
    'title' => 'ოთახების დატვირთვიანობა %',
    'date' => 'თარიღი',
    'used_rooms_count' => 'დატვირთული ოთახების რაოდენობა ',
    'total_rooms_count' => 'სულ ოთახების რაოდენობა',
    'percent' => 'პროცენტული მაჩვენებელი',
);
$TEXT['daily_annual_income_report'] = array(
    'month' => 'თვე',
    'day' => 'დღე',
    'accommodation_in' => 'შემოსავალი განთავსება',
    'services_in' => 'შემოსავალი სერვისები',
    'out' => 'გასავალი',
    'balance' => 'ბალანსი',
    'chart_title' => 'დღიური ბრუნვის ანგარიში',
    'chart_subtitle' => 'წარმოდგენილია შემდეგი თვეების ანგარიში',
);

$TEXT['accrual_based_income'] = array(
    'month' => 'თვე',
    'acc_income_tax_included' => 'განთავსება (tax included)',
    'acc_income_tax_free' => 'განთავსება (tax free)',
    'services_income' => 'შემოსავალი(სერვისები)',
    'income' => 'შემოსავალი(ჯამი)',
    'tax' => 'მათ შორის დ.ღ.გ',
    'last_12_month_income' => 'ბოლო 12 თვის შემოსავალი',
    'chart_title' => 'დარიცხული შემოსავლის ანგარიში',
    'chart_subtitle' => 'წარმოდგენილია შემდეგი წლების დარიცხული შემოსავლის ანგარიშები',
    'summary' => 'ჯამი',
);

$TEXT['usage_by_citizenship'] = array(
    'country' => 'ქვეყანა',
    'guests_count' => 'კაცი',
    'guests_count_night' => 'კაც/დღე',
    'local' => 'ადგილობრივი',
    'forign' => 'უცხოელი',
    'local_forign_title' => 'სტუმრების მიერ გათეული ღამეების ჯაამური რაოდენობა',
    'all_country_title' => 'შემოსული სტუმრის რაოდენობა ქვეყნების მიხედვით',
);
$TEXT['usage_by_citizenship']['excel'] = array(
    'description' => 'გთხოვთ მოგვაწოდოთ ინდივიდუალური მონაცემები შემდეგი კითხვარის მიხედვით. მოცემული ინფორმაცია იქნება გამოყენებული რეგიონში ჩამოსულ ტურისტთა სტატისტიკური ანალიზის წარმოების მიზნით და იქნება დაცული საქართველოს ზოგად ადმინისტრაციული კოდექსით.',
    'object' => 'ობიექტის დასახელება',
    'address' => 'მისამართი',
    'tel' => 'ტელეფონის ნომერი',
    'contact_person' => 'საკონტაქტო პირი',
    'cell_phone' => 'მობილური ტელეფონის ნომერი',
    'mail' => 'ელ.ფოსტის მისამართი',
    'period_range' => 'დაკვირვების პერიოდი (თვე)',
    'local' => 'ადგილობრივი',
    'forign' => 'უცხოელი',
    'guest' => 'კაცი',
    'guest.night' => 'კაც/დღე',
    'title_2' => 'შემოსული სტუმრის რაოდენობა ქვეყნების მიხედვით',
    'country' => 'ქვეყანა',
    'guest_count' => 'ტურისტთა რ-ბა',
);

$TEXT['tr_type'] = array(
    'all' => 'ყველა',
    'debit' => 'შემოსავალი',
    'credit' => 'გასავალი',
);

$TEXT['guest_type'] = array(
    'all' => 'ყველა',
    'non-corporate' => 'ფიზიკური',
    'company' => 'კომპანია',
    'tour-company' => 'ტურ ოპერატორი',
);

$TEXT['guests']['filter_modal'] = array(
    'title' => 'სტუმრების ძებნა/გაფილტვრა',
    'date' => 'რეგ.თარიღი დან / მდე',
    'publish' => 'სტატუსი',
    'guest_id' => 'სტუმრის/ორგანიზაციის ID',
    'guest_type' => 'სტუმრის ტიპი',
    'guest_name' => 'სტუმრის სახელი/დასახელება',
    'payment_method' => 'გადახდის მეთოდი',
    'tax' => 'TAX(დ.ღ.გ)',
    'submit_filter' => 'ძიება',
    'destination' => 'დანიშნულება',
);

$TEXT['cashbacks']['filter_modal'] = array(
    'title' => 'ფილტრი / ძიება',
    'date' => 'თარიღი დან / მდე',
    'amount' => 'თანხა დან / მდე',
    'guest_id' => 'პარტნიორის ID',
    'guest_name' => 'პარტნიორის სახელი/დასახელება',
    'payment_method' => 'გადახდის მეთოდი',
    'tax' => 'TAX(დ.ღ.გ)',
    'submit_filter' => 'ძიება',
    'status' => 'სტატუსი',
    'type' => array(
        0 => "ყველა",
        1 => "გადახდილი",
        2 => "გადასახდელი",
    ),
);

$TEXT['date'] = 'თარიღი';
$TEXT['b_year'] = 'წლის';
$TEXT['b_years'] = 'წლების';
$TEXT['b_s_years'] = 'აირჩიეთ წლები';
$TEXT['b_s_day'] = 'აირჩიეთ თარიღი';
$TEXT['add_budget'] = 'ბიუჯეტის დამატება';
$TEXT['check_budget'] = 'ბიუჯეტის შედარება შემოსავალთან';
$TEXT['check_budgets'] = 'ბიუჯეტის შედარება შემოსავალთან';
$TEXT['mdt_charge'] = 'თვის ჭრილში';
$TEXT['edit'] = 'რედაქტირება';
$TEXT['user'] = 'მომხმარებელი';
$TEXT['acounting'] = 'აღწერა';
$TEXT['start_day'] = 'დაკვირვების დღე ';
$TEXT['start_month'] = 'დაკვირვების თვე ';
$TEXT['start_year'] = 'დაკვირვების წელი  ';
$TEXT['last_day'] = 'წინა წლის დღე';
$TEXT['last_month'] = 'წინა წლის თვე';
$TEXT['last_year'] = 'წინა წელი';
$TEXT['income'] = 'ჯავშნების შემოსავალი (დარიცხული)';
$TEXT['income_service'] = 'სერვისების შემოსავალი (დარიცხული)';
$TEXT['income_sum'] = 'ჯამური შემოსავალი (დარიცხული)';
$TEXT['income_inc'] = 'ჯავშნების შემოსავალი (საკასო)';
$TEXT['income_inc_service'] = 'სერვისების შემოსავალი (საკასო)';
$TEXT['income_inc_sum'] = 'ჯამური შემოსავალი (საკასო)';
$TEXT['wat'] = 'დღგ (საკასო)';
$TEXT['all_rooms'] = 'სულ ოთახები';
$TEXT['used_rooms'] = 'დაკავებული ოთახები';
$TEXT['used_rooms_per'] = 'დაკავებული ოთახების პროცენტულობა';
$TEXT['check_in'] = 'დღეს შემოდის ';
$TEXT['check_out'] = 'დღეს გადის ';

$TEXT['rss'] = array(
    'daily_rev' => 'არჩეული დღის შემოსავალი',
    'sel_day' => 'არჩეული დღე',
    'budget' => 'ბიუჯეტი',
    'diff' => 'სხვაობა',
    'l_year' => 'გასული წლის შესაბამისი დღე',
    'mtd' => 'შემოსავალი თვის 1 რიცხვიდან',
    'mtd_day' => 'არჩეული თვის რიცხვი',
    'mtd_l_year' => 'გასული წლის შესაბამისი დღე',
    'income' => 'დღის შემოსავალი',
    'a_rooms' => 'ხელმისაწვდომი ოთახები',
    'o_rooms' => 'მიუწვდომელი ოთახები',
    'r_rooms' => 'გაყიდული ოთახები',
    'oc_rooms' => 'დაკავებულობის მაჩვენებელი',
    'adr' => 'გაყიდული ოთახების საშუალო მაჩვენებელი',
    'rev_par' => 'ყველა ოთახის საშუალო შემოსავალი',
    'status' => array(
        '1' => 'ბიუჯეტი',
        '2' => 'შემოსავალი',
        '3' => 'სხვაობა',
    ),
);
