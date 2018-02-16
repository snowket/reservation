<?
require_once(HMS_ROOT."/models/Task.model.php");
require_once(HMS_ROOT."/models/Guest.model.php");
require_once(HMS_ROOT."/models/User.model.php");
require_once(HMS_ROOT."/models/Booking.model.php");
require_once(HMS_ROOT."/models/ActivityLog.model.php");
require_once(HMS_ROOT."/models/Statistic.model.php");

$taskModel=new Task();
$taskModel->rowsPerPage=10;
$tasks=$taskModel->getTasks('WHERE status=0',true,1);

$guestModel=new Guest();
$last_10_guest=$guestModel->getLast(10);

$bookingModel=new Booking();
$last_online_booking=$bookingModel->getLastOnlineBookings(10);

$activityLog=new ActivityLog();
$last_activity_log=$activityLog->getLastActivityLogs(10);

$userModel=new User();
$users=$userModel->getAllUsers();

$statisticModel=new Statistic();
$usedRoomsCount=$statisticModel->getUsedRoomsCount(date('Y-m-01'),date('Y-m-t'));
$current_year=date('Y');
$last_year=$current_year-1;

$usedRoomsCount_last_year=$statisticModel->getUsedRoomsCount($last_year."-".date('m-01'), $last_year."-".date('m-t'));



$chart_1="<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
    <script type='text/javascript'>
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);


      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', '2015', '2016'],";
        foreach ($usedRoomsCount as $k=>$v) {
            $last_year_day=str_replace($current_year, $last_year, $k);
           $chart_1.="['".date('d',strtotime($k))."',  ".$usedRoomsCount_last_year[$last_year_day]['percent'].", ".$v['percent']."],";
        }
          

        $chart_1.="]);

        var options = {
            chart: {
            
            },
            vAxis: {
                minValue: 0,
                maxValue: 100,
                format:'percent'
            }
        };


        var chart = new google.charts.Bar(document.getElementById('curve_chart'));
        chart.draw(data, options);

      }
    </script>
    <div style='background-color:#FFFFFF; border:solid #3A82CC 1px; width:100%;'>
        <div class='caps_bold dashboard_table_header'>ოთახების დატვირთვიანობა %</div>
        <div id='curve_chart' style='width: 100%; height: 200px; padding:6px'></div>
    </div>";



//---------------TASKS
$tasks_col='<div style="background-color:#3A82CC; border:solid #3A82CC 1px; width:100%;">
<div class="caps_bold dashboard_table_header" >'.$TEXT["dashboard"]["tasks"].'</div>';
$tasks_col.='<table width="100%" class="table-table" cellspacing="0" cellpadding="2">';
$tasks_col.='<tr>';
$tasks_col.='<td class="table-th2">ID</td>';
$tasks_col.='<td class="table-th2" width="140px">Deadline</td>';
$tasks_col.='<td class="table-th2">Task</td>';
$tasks_col.='<td class="table-th2">Status</td>';
$tasks_col.='</tr>';
foreach($tasks as $task){
    if($task['status']==0){
        $h='<td class="table-td2" style="background-color: red; color: #ffffff"><div >შეუსრულებელია</div></td>';
    }else{
        $h='<td class="table-td2" style="background-color: green; color: #000000"><div> შესრულებელია</div></td>';
    }
    $tasks_col.='<tr class="table-tr">';
    $tasks_col.='<td class="table-td2">'.$task['id'].'</td>';
    $tasks_col.='<td class="table-td2" width="140px">'.$task['deadline_at'].'</td>';
    $tasks_col.='<td title="'.$task['task'].'" class="table-td2">'.mb_substr($task['task'],0,52,'utf-8').'</td>';
    $tasks_col.=$h;
    $tasks_col.='</tr>';
}
$tasks_col.='<tr>';
$tasks_col.='<td class="table-th2" colspan="4" align="right"><a href="index.php?m=tasks" class="alink" style="padding-right: 4px">'.$TEXT["dashboard"]["read_more"].'<img style="padding-left: 4px" src="images/arrow.png"></a></td>';
$tasks_col.='</tr>';
$tasks_col.='</table>';
$tasks_col.='</div>';

//---------------GUESTS
$guests_col.='<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;  margin-top: 10px;">
<div class="caps_bold dashboard_table_header">'.$TEXT["dashboard"]["guests"].'</div>';
$guests_col.='<table width="100%" class="table-table" cellspacing="0" cellpadding="2">';
$guests_col.='<tr>';
$guests_col.='<td class="table-th2">ID</td>';
$guests_col.='<td class="table-th2" width="140px">Guest</td>';
$guests_col.='<td class="table-th2">Email</td>';
$guests_col.='<td class="table-th2">Created at</td>';
$guests_col.='</tr>';
foreach($last_10_guest as $guest){
    $guests_col.='<tr class="table-tr">';
    $guests_col.='<td class="table-td2">'.$guest['id'].'</td>';
    $guests_col.='<td class="table-td2" width="140px">'.$guest['first_name']." ".$guest['last_name'].'</td>';
    $guests_col.='<td class="table-td2">'.$guest['email'].'</td>';
    $guests_col.='<td class="table-td2">'.$guest['created_at'].'</td>';
    $guests_col.='</tr>';
}
$guests_col.='<tr>';
$guests_col.='<td class="table-th2" colspan="4" align="right"><a href="index.php?m=guests" class="alink" style="padding-right: 4px">'.$TEXT["dashboard"]["read_more"].'<img style="padding-left: 4px" src="images/arrow.png"></a></td>';
$guests_col.='</tr>';
$guests_col.='</table>';
$guests_col.='</div>';

//---------------BOOKING
$bookings_col.='<div style="background:#FFF; border:solid #3A82CC 1px; width:100%; margin-top: 10px;">
<div class="caps_bold dashboard_table_header">'.$TEXT["dashboard"]["online_bookings"].'</div>';
$bookings_col.='<table width="100%" class="table-table" cellspacing="0" cellpadding="2">';
$bookings_col.='<tr>';
$bookings_col.='<td class="table-th2" width="40px">ID</td>';
$bookings_col.='<td class="table-th2" width="40px">check_in</td>';
$bookings_col.='<td class="table-th2" width="40px">check out</td>';
$bookings_col.='<td class="table-th2">Guest</td>';
$bookings_col.='<td class="table-th2" align="center">Action</td>';
$bookings_col.='</tr>';
foreach($last_online_booking as $booking){
    $bookings_col.='<tr class="table-tr">';
    $bookings_col.='<td class="table-td2" width="40px">'.$booking['id'].'</td>';
    $bookings_col.='<td class="table-td2">'.$booking['check_in'].'</td>';
    $bookings_col.='<td class="table-td2">'.$booking['check_out'].'</td>';
    $bookings_col.='<td class="table-td2">'.$booking['first_name'].' '.$booking['last_name'].'</td>';
    $bookings_col.='<td class="table-td2" align="center"><a href="index.php?m=booking_management&tab=booking_list&action=view&booking_id='.$booking['id'].'"><img src="./images/icos16/booking_bell.png"></a></td>';
    $bookings_col.='</tr>';
}
$bookings_col.='<tr>';
$bookings_col.='<td class="table-th2" colspan="6" align="right"><a href="index.php?m=booking_management&tab=booking_list" class="alink" style="padding-right: 4px">'.$TEXT["dashboard"]["read_more"].'<img style="padding-left: 4px" src="images/arrow.png"></a></td>';
$bookings_col.='</tr>';
$bookings_col.='</table>';
$bookings_col.='</div>';

//---------------LOGS
$logs_col.='<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
<div class="caps_bold dashboard_table_header">'.$TEXT["dashboard"]["activity_logs"].'</div>';
$logs_col.='<table width="100%" class="table-table" cellspacing="0" cellpadding="2">';
$logs_col.='<tr>';
$logs_col.='<td class="table-th2" width="40px">ID</td>';
$logs_col.='<td class="table-th2">Date</td>';
$logs_col.='<td class="table-th2">Action</td>';
$logs_col.='<td class="table-th2">Administrator</td>';

$logs_col.='</tr>';
foreach($last_activity_log as $log){
    $logs_col.='<tr class="table-tr">';
    $logs_col.='<td class="table-td2">'.$log['id'].'</td>';
    $logs_col.='<td class="table-td2">'.$log['date'].'</td>';
    $logs_col.='<td class="table-td2">'.$log['action'].'</td>';
    $logs_col.='<td class="table-td2">'.$users[$log['administrator_id']]['login'].'</td>';
    $logs_col.='</tr>';
}
$logs_col.='<tr>';
$logs_col.='<td class="table-th2" colspan="4" align="right"><a href="index.php?m=activity_log" class="alink" style="padding-right: 4px">'.$TEXT["dashboard"]["read_more"].'<img style="padding-left: 4px" src="images/arrow.png"></a></td>';
$logs_col.='</tr>';
$logs_col.='</table>';
$logs_col.='</div>';


$ficha="
<table width='100%' cellpadding='5px'>
    <tr>
        <td colspan='2'>".$chart_1."</td>
    </tr>
    <tr>
        <td width='50%'>".$tasks_col."</td>
        <td width='50%'>".$logs_col."</td>
    </tr>
    <tr>
        <td width='50%'>".$bookings_col."</td>
        <td width='50%'>".$guests_col."</td>
    </tr>
</table>";



$_CENTER	= $ficha;
