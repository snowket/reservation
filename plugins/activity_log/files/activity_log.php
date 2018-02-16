<?

$where_clause = "1=1";
(!empty($_GET['start_date']) && isset($_GET['start_date'])) ? $where_clause .= " AND DATE_FORMAT(al.date,'%Y-%m-%d')>='" . $_GET['start_date'] . "'" : $where_clause .= " AND DATE_FORMAT(al.date,'%Y-%m-%d')>='" . date('Y-m-01') . "'";
(!empty($_GET['end_date']) && isset($_GET['end_date'])) ? $where_clause .= " AND DATE_FORMAT(al.date,'%Y-%m-%d')<='" . $_GET['end_date'] . "'" : $where_clause .= " AND DATE_FORMAT(al.date,'%Y-%m-%d')<='" . date('Y-m-d') . "'";

(isset($_GET['user_id']) && (int)$_GET['user_id'] >=0) ? $where_clause .= " AND al.administrator_id=" . $_GET['user_id']  : $where_clause .= '';
if(isset($_GET['group_id'])){
    if((int)$_GET['group_id']==0){
        $where_clause .= " AND al.administrator_id=0";
    }else if((int)$_GET['group_id'] >0){
        $where_clause .= " AND u.group_id=" . $_GET['group_id'];
    }

}
(isset($_GET['keyword']) && $_GET['keyword'] != '') ? $where_clause .= " AND (al.action LIKE '%" . $_GET['keyword'] . "%' OR al.description LIKE '%" . $_GET['keyword'] . "%')" : $where_clause .= '';
$groups=getGroups();
$groups[0]['title']='CRON';
$users=getUsers();
$users[0]['firstname']='CRON';
$users[0]['group_id']=0;


if ($_POST['action'] == 'get_excel') {
    $getActivityLogs=getActivityLogs($where_clause,true);
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Activity Logs');

    $sheet->setCellValue("A1", $TEXT['activity_log']['excel']['id']);
    $sheet->setCellValue("B1", $TEXT['activity_log']['excel']['action']);
    $sheet->setCellValue("C1", $TEXT['activity_log']['excel']['description']);
    $sheet->setCellValue("D1", $TEXT['activity_log']['excel']['user']);
    $sheet->setCellValue("E1", $TEXT['activity_log']['excel']['group']);
    $sheet->setCellValue("F1", $TEXT['activity_log']['excel']['date']);
    $sheet->setCellValue("G1", $TEXT['activity_log']['excel']['ip']);

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
    foreach ($getActivityLogs AS $log) {
        $sheet->setCellValueByColumnAndRow(0, $i + 2, $log['id']);
        $sheet->setCellValueByColumnAndRow(1, $i + 2, $log['action']);
        $sheet->setCellValueByColumnAndRow(2, $i + 2, $log['description']);
        $sheet->setCellValueByColumnAndRow(3, $i + 2, $users[$log['administrator_id']]['firstname']." ".$users[$log['administrator_id']]['lastname']);
        $sheet->setCellValueByColumnAndRow(4, $i + 2, $groups[$users[$log['administrator_id']]['group_id']]['title']);
        $sheet->setCellValueByColumnAndRow(5, $i + 2, $log['date']);
        $sheet->setCellValueByColumnAndRow(6, $i + 2, $log['ip']);
        $cell_name = $columnNames[$columnNumber] . $rowNumber;
        $sheet->getStyle('A' . ($i + 2) . ':G' . ($i + 2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
        $i++;
    }
    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=activity_logs.xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
    exit;
}else{
    $getActivityLogs=getActivityLogs($where_clause);
}


$TMPL->addVar("TMPL_navbar", $navbar);
$TMPL->addVar("TMPL_users", $users);
$TMPL->addVar("TMPL_groups", $groups);
$TMPL->addVar("TMPL_logs", $getActivityLogs);
$TMPL->ParseIntoVar($_CENTER, "activity_log");



