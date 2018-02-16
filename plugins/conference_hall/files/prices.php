<?

if(isset($_GET['room_id']) && $_GET['room_id']!=''){
    $weekdays=getCH_weekdays();
    $prices=getCh_prices($_GET['room_id']);
    $dayprices=array();
    $days=array();
    foreach($prices as $key=> $price){
        $dayprices[$price['day']][$price['from_time']]=$price;
        $days[$price['from_time']]=$price['from_time'];
        unset($prices[$key]);
    }
    $TMPL->addVar("weekDays", $weekdays);
    $TMPL->addVar("prices", $dayprices);
    $TMPL->addVar("price_items", $days);
}
if(isset($_POST['action']) && $_POST['action']=='add_price'){

    if(setCh_prices($_POST))
    {
        header('location:index.php?m=conference_hall&tab=prices&room_id='.$_GET['room_id']);
    }else{
        header('location:index.php?m=conference_hall&tab=prices&room_id='.$_GET['room_id'].'&error=520');
    }
}elseif($_POST['action']=='delete'){

           if(deleteCH_price($_POST['room_id'],$_POST['start_date']))
           {
               header('location:index.php?m=conference_hall&tab=prices&room_id='.$_GET['room_id']);
           }else{
               header('location:index.php?m=conference_hall&tab=prices&room_id='.$_GET['room_id'].'&error=520');
           }

}
$rooms=getCH_room();

$TMPL->addVar("rooms", $rooms);
$TMPL->ParseIntoVar($_CENTER,"prices");