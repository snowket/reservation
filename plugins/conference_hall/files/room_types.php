<?php

if($_POST['action']=='add'){
    if(setCH_room_type($_POST['room_type'],$_POST['capacity']))
    {
        header('location:index.php?m=conference_hall&tab=room_types');
    }else{
        header('location:index.php?m=conference_hall&tab=room_types&error=520');
    }

}
if($_POST['action']=='edit'){
    if(updateCH_room_type($_POST['service_id'],$_POST['room_type'],$_POST['capacity']))
    {
        header('location:index.php?m=conference_hall&tab=room_types');
    }else{
        header('location:index.php?m=conference_hall&tab=room_types&error=521');
    }

}

$service_types=getCH_room_type();
$TMPL->addVar("room_types", $service_types);
$TMPL->ParseIntoVar($_CENTER,"room_types");