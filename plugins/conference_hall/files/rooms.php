<? if(!defined('ALLOW_ACCESS')) exit;

if($_POST['action']=='add'){
    if(setCH_room($_POST['name'],$_POST['room_types']))
    {
        header('location:index.php?m=conference_hall&tab=rooms');
    }else{
        header('location:index.php?m=conference_hall&tab=rooms&error=520');
    }

}
elseif($_POST['action']=='edit'){
    if(updateCH_room($_POST['room_id'],$_POST['name'],$_POST['room_types']))
    {
        header('location:index.php?m=conference_hall&tab=rooms');
    }else{
        header('location:index.php?m=conference_hall&tab=rooms&error=521');
    }

}


$room_types=getCH_room_type();
#p($room_types);
$room=getCH_room();
$TMPL->addVar("room_types", $room_types);
$TMPL->addVar("room", $room);
$TMPL->ParseIntoVar($_CENTER,"rooms");

