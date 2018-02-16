<?
if (!defined('ALLOW_ACCESS')) exit;


//-------------WHERE CLAUSE
$where_clause = "";

$where_clause.=(isset($GET['creator_id'])&&(int)$GET['creator_id']!=0)?" AND creator_id=".(int)$GET['creator_id']:"";
$where_clause.=(isset($GET['executor_id'])&&(int)$GET['executor_id']!=0)?" AND executor_id=".(int)$GET['executor_id']:"";
$where_clause.=(isset($GET['status'])&& $GET['status']!='')?" AND status=".(int)$GET['status']:"";
$where_clause.=(isset($GET['in_task'])&&!empty($GET['in_task']))?" AND task LIKE '%".$GET['in_task']."%'":"";

$where_clause.=(isset($GET['start_deadline'])&&!empty($GET['start_deadline']))? " AND DATE(deadline_at)>='{$GET['start_deadline']}'" :"";
$where_clause.=(isset($GET['end_deadline'])&&!empty($GET['end_deadline']))? " AND DATE(deadline_at)<='{$GET['end_deadline']}'" :"";

$where_clause.=(isset($GET['start_executed'])&&!empty($GET['start_executed']))? " AND DATE(executed_at)>='{$GET['start_executed']}'" :"";
$where_clause.=(isset($GET['end_executed'])&&!empty($GET['end_executed']))? " AND DATE(executed_at)<='{$GET['end_executed']}'" :"";

$where_clause =($where_clause!='')?" WHERE 1=1".$where_clause:'';


//-------------WHERE CLAUSE



require_once(HMS_ROOT."/models/Task.model.php");
require_once(HMS_ROOT."/models/Guest.model.php");
require_once(HMS_ROOT."/models/User.model.php");

$taskModel=new Task();
$userModel=new User();
$users=$userModel->getAllUsers();

if($POST['action'] == 'add_new_task'){
    $task=$POST['task'];
    $deadline_date=$POST['deadline_date'];
    $deadline_time=$POST['deadline_time'];
    $taskModel->createHotelTask($deadline_date." ".$deadline_time, $task);
    header("Location:" . $SELF);
}elseif($POST['action'] == 'edit_task'){
    $booking_id=$GET['booking_id'];
    $task_id=$POST['task_id'];
    $task=$POST['task'];
    $deadline=$POST['deadline_date']." ".$POST['deadline_time'];
    $taskModel->changeTaskDeadline($task_id,$deadline);
    $taskModel->changeTask($task_id,$task);
    header("Location:" . $SELF);
}elseif($POST['action'] == 'change_task_status'){
    $booking_id=$GET['booking_id'];
    $task_id=$POST['task_id'];
    $taskModel->changeTaskStatus($task_id);
    header("Location:" . $SELF);
}elseif($POST['action'] == 'delete_task'){
    $booking_id=$GET['booking_id'];
    $task_id=$POST['task_id'];
    $taskModel->deleteBookingTask($task_id);
    header("Location:" . $SELF);
}elseif($POST['action'] == 'get_excel') {
    $tasks=$taskModel->getTasks($where_clause,false);
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Tasks List');

    $sheet->setCellValue("A1", $TEXT['tasks']['id']);
    $sheet->setCellValue("B1", $TEXT['tasks']['creator']);
    $sheet->setCellValue("C1", $TEXT['tasks']['deadline']);
    $sheet->setCellValue("D1", $TEXT['tasks']['executor']);
    $sheet->setCellValue("E1", $TEXT['tasks']['executed_at']);
    $sheet->setCellValue("F1", $TEXT['tasks']['task']);
    $sheet->setCellValue("G1", $TEXT['tasks']['status']);

    $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1:G1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A1:G1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));

    foreach (range('A', 'G') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    $i=0;
    foreach ($tasks AS $task) {
        $creator="(".$users[$task['creator_id']]['login'].") ".$users[$task['creator_id']]['firstname'];
        $creator.=($users[$task['creator_id']]['lastname']!='')?' '.$users[$task['creator_id']]['lastname']:'';

        if((int)$task['executor_id']>0){
            $executor="(".$users[$task['executor_id']]['login'].") ".$users[$task['executor_id']]['firstname'];
            $executor.=($users[$task['executor_id']]['lastname']!='')?' '.$users[$task['executor_id']]['lastname']:'';
            $executed_at=$task['executed_at'];
        }else{
            $executor=$TEXT['tasks']['undefined'];
            $executed_at=$TEXT['tasks']['undefined'];
        }

        $sheet->setCellValueByColumnAndRow(0, $i + 2, $task['id']);
        $sheet->setCellValueByColumnAndRow(1, $i + 2, $creator);
        $sheet->setCellValueByColumnAndRow(2, $i + 2, $task['deadline_at']);
        $sheet->setCellValueByColumnAndRow(3, $i + 2, $executor);
        $sheet->setCellValueByColumnAndRow(4, $i + 2, $executed_at);
        $sheet->setCellValueByColumnAndRow(5, $i + 2, $task['task']);
        $sheet->setCellValueByColumnAndRow(6, $i + 2, ($task['status']==1)?$TEXT['tasks']['executed']:$TEXT['tasks']['unexecuted']);
        $cell_name = $columnNames[$columnNumber] . $rowNumber;
        $sheet->getStyle('A' . ($i + 2) . ':G' . ($i + 2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
        $i++;
    }
    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=tasks_list.xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
    exit;
}

$page=((int)$GET['p']==0)?1:(int)$GET['p'];
$tasks=$taskModel->getTasks($where_clause,true,$page);

$TMPL->addVar("TMPL_tasks",$tasks );
$TMPL->addVar("TMPL_users",$users );
$TMPL->addVar("TMPL_rowsPerPage",$taskModel->rowsPerPage );
$TMPL->addVar("TMPL_navbar",$taskModel->pageBar );
$TMPL->ParseIntoVar($_CENTER,"tasks");