<?php

if($_POST['action']=='add'){
    if(setCH_service_type($_POST['service_type'],$_POST['type_of']))
    {
        header('location:index.php?m=conference_hall&tab=service_types');
    }else{
        header('location:index.php?m=conference_hall&tab=service_types&error=520');
    }

}elseif($_POST['action']=='edit'){
    if(updateCH_service_type($_POST['service_id'],$_POST['service_type'],$_POST['type_of']))
    {
        header('location:index.php?m=conference_hall&tab=service_types');
    }else{
        header('location:index.php?m=conference_hall&tab=service_types&error=520');
    }
}


$service_types=getCH_service_type();
$TMPL->addVar("service_types", $service_types);
$TMPL->ParseIntoVar($_CENTER,"service_types");