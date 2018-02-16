<?
$POST = $VALIDATOR->ConvertSpecialChars($_POST);
if ($_GET['action']=='add'){

}else{
    $TMPL->addVar('TMPL_guests',getAllGuests());

    $TMPL->addVar("TMPL_payment_methods", getAllPaymentMethods());

    //$TMPL->addVar('TMPL_transactions',getAllBookingTransactions($whereClause));

    // $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
    $TMPL->ParseIntoVar($_CENTER,"reports");
}

