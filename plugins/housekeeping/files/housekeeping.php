<?

$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$where_clause = "";

(isset($_GET['floor']) && $_GET['floor'] != '') ? $where_clause .= " AND R.floor =" . (int)$_GET['floor'] : $where_clause .= '';
(isset($_GET['block']) && $_GET['block'] != '') ? $where_clause .= " AND M.block_id =" . (int)$_GET['block'] : $where_clause .= '';
(isset($_GET['room_title']) && $_GET['room_title'] != '') ? $where_clause .= " AND R.name LIKE '%" . $_GET['room_title'] . "%'" : $where_clause .= '';
(isset($_GET['status']) && $_GET['status'] != '') ? $where_clause .= " AND R.housekeeping_status ='" . $_GET['status'] . "'" : $where_clause .= '';


(isset($_GET['guest_name']) && $_GET['guest_name'] != '') ? $where_clause .= " AND CONCAT(G.first_name, ' ', G.last_name ) LIKE '%" . $_GET['guest_name'] . "%'" : $where_clause .= '';
(isset($_GET['guest_type']) && $_GET['guest_type'] != '') ? $where_clause .= " AND G.type='" . $_GET['guest_type'] . "'" : $where_clause .= "";


if ($POST['action'] == 'multi_change_status') {
    $status = $POST['status'];
    $selected_rooms = $POST['selected_rooms'];
    $ids = array(0);
    foreach ($selected_rooms AS $k => $v) {
        $ids[] = $v;
    }
    $query = "UPDATE {$_CONF['db']['prefix']}_rooms SET
                                 housekeeping_status='" . $status . "',
                                 hs_updated_at='" . date("Y-m-d H:i:s") . "'
                                 WHERE id IN (" . implode(',', $ids) . ")";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $FUNC->Redirect($SELF);
}

$joinedRooms = getJoinedRooms($where_clause);
$roomTypes = GetRoomTypes();
$roomStates = getRoomsStateByDate(date('Y-m-d'));

if ($_POST['action'] == 'get_excel') {
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Transactions');

    $sheet->setCellValue("A1", $TEXT['transactions']['guest']);
    $sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $sheet->setCellValue("B1", $TEXT['transactions']['date']);
    $sheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('B1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('B')->setAutoSize(true);

    $sheet->setCellValue("C1", $TEXT['transactions']['method']);
    $sheet->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('C1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('C1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('C')->setAutoSize(true);

    $sheet->setCellValue("D1", $TEXT['transactions']['tax']);
    $sheet->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('D1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('D1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $sheet->setCellValue("E1", $TEXT['transactions']['debit']);
    $sheet->getStyle('E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('E1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('E1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $sheet->setCellValue("F1", $TEXT['transactions']['credit']);
    $sheet->getStyle('F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('F1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('F1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('F')->setAutoSize(true);

    $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getColumnDimension('A:F')->setAutoSize(true);

    for ($i = 0; $i < count($joinedTransactions); $i++) {
        $firstname = $joinedTransactions[$i]['first_name'];
        $lastname = $joinedTransactions[$i]['last_name'];
        $guest = ($lastname == "") ? $firstname : $firstname . " " . $lastname;
        $date = date('Y-m-d', strtotime($joinedTransactions[$i]['end_date']));
        $payment_method = $paymentMethods[$joinedTransactions[$i]['payment_method_id']]['title'];
        $tax = ($joinedTransactions[$i]['guest_tax'] == 0) ? 'TAX FREE' : 'TAX INCLUDED';
        $amount = $joinedTransactions[$i]['amount'];
        $debit = ($amount >= 0) ? $amount : 0;
        $credit = ($amount < 0) ? $amount : 0;

        $sheet->setCellValueByColumnAndRow(0, $i + 2, $guest);
        $sheet->setCellValueByColumnAndRow(1, $i + 2, $date);
        $sheet->setCellValueByColumnAndRow(2, $i + 2, $payment_method);
        $sheet->setCellValueByColumnAndRow(3, $i + 2, $tax);
        $sheet->setCellValueByColumnAndRow(4, $i + 2, $debit);
        $sheet->setCellValueByColumnAndRow(5, $i + 2, $credit);
    }
    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=transactions.xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');

}

$blocks = GetBlocks();
$max_floors = 0;
foreach ($blocks as $block) {
    if ($block['floors'] > $max_floors) {
        $max_floors = $block['floors'];
    }
}

if (isset($_GET['availability']) && $_GET['availability'] != '') {
    $tmpJoinedRooms=array();
    foreach ($joinedRooms as $room) {
        if (in_array($_GET['availability'], $roomStates[$room['id']])) {
            $tmpJoinedRooms[] = $room;
        }
        if ($_GET['availability'] == 'free' && count($roomStates[$room['id']]) == 0) {
            $tmpJoinedRooms[] = $room;
        }
    }
    $joinedRooms = $tmpJoinedRooms;
}

if (isset($_GET['room_type']) && $_GET['room_type'] != '') {
    $tmpJoinedRooms=array();
    foreach ($joinedRooms as $room) {
        if ((int)$_GET['room_type'] == (int)$room['type_id']) {
            $tmpJoinedRooms[] = $room;
        }
    }
    $joinedRooms = $tmpJoinedRooms;
}


$TMPL->addVar("TMPL_spending_materials", GetServices(10));
$TMPL->addVar("TMPL_mb_items", GetServices(4));
$TMPL->addVar("TMPL_blocks", $blocks);
$TMPL->addVar("TMPL_max_floors", $max_floors);
$TMPL->addVar("TMPL_rooms_states", $roomStates);
$TMPL->addVar("TMPL_room_types", $roomTypes);
$TMPL->addVar("TMPL_rooms", $joinedRooms);
$TMPL->ParseIntoVar($_CENTER, "housekeeping");
