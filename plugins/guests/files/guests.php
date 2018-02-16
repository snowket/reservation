<?

$where_clause = " WHERE 1=1";
$where_clause .= (isset($_GET['filter_guest_id_number']) && $_GET['filter_guest_id_number'] != "") ? " AND id_number LIKE '%" . $_GET['filter_guest_id_number'] . "%'" : "";
$where_clause .= (isset($_GET['start_date']) && $_GET['start_date'] != "") ? " AND DATE(created_at)>='" . $_GET['start_date'] . "'" : "";
$where_clause .= (isset($_GET['end_date']) && $_GET['end_date'] != "") ? " AND DATE(created_at)<='" . $_GET['end_date'] . "'" : " AND DATE(created_at)<='" . date('Y-m-d') . "'";
$where_clause .= (isset($_GET['guest_name']) && $_GET['guest_name'] != "") ? " AND CONCAT(first_name, ' ',last_name) LIKE '%" . $_GET['guest_name'] . "%'" : "";
$where_clause .= (isset($_GET['publish']) && $_GET['publish'] != "") ? " AND publish=" . $_GET['publish'] : "";
$where_clause .= (isset($_GET['guest_type']) && $_GET['guest_type'] != "") ? " AND type='" . $_GET['guest_type'] . "'" : "";
$where_clause .= (isset($_GET['tax']) && $_GET['tax'] != 2) ? " AND type=" . $_GET['tax'] : "";

$SELF_FILTERED = $_SERVER['REQUEST_URI'];
$parts = parse_url($SELF_FILTERED);
$queryParams = array();
parse_str($parts['query'], $queryParams);
unset($queryParams['p']);
$queryString = http_build_query($queryParams);
$SELF_FILTERED = $parts['path'] . '?' . $queryString;

if (isset($_POST['action'])) {
    // ### ADDING USERS
    if ($_POST['action'] == "add") {
        //p($_POST);p($_FILES);exit;
        $POST = $VALIDATOR->ConvertSpecialChars($_POST);
        /*$VALIDATOR->validateLength($POST['id_number'],'ID Number',1,25);
        $VALIDATOR->validateLength($POST['first_name'],$TEXT['users']['first_name'],2,255);
        $VALIDATOR->validateLength($POST['last_name'],$TEXT['users']['last_name'],2,255);
        $VALIDATOR->validateString($POST['birth_day'],'BIRTHDAY','Birth Day',10,10);
        $VALIDATOR->validateLength($POST['telephone'],'phone',1, 32);
        $VALIDATOR->validateString($POST['email'],'EMAIL',$TEXT['users']['email'],5,255);
        $VALIDATOR->validateLength($POST['address'],'address',5, 255);
        //$VALIDATOR->compareValues($POST['passw'],$POST['passw2'],$TEXT['users']['pass']);*/
        $errors = $VALIDATOR->passErrors();


        $POST['password'] = $VALIDATOR->RandString("1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm!@#$%^&*+/-_|", 6);
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests WHERE (email='{$POST['email']}' AND email<>'') OR (id_number='{$POST['id_number']}' AND id_number<>'')";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        if ($result->RecordCount() > 0){
            $errors []= $TEXT['users']['user_exists'];
        }

        if (!empty($errors)) {
            $TMPL->addVar("TMPL_errors", $errors);
            $TMPL->addVar("TMPL_data", $POST);
        } else {
            $id_scan = (isset($_FILES['id_scan']) && $_FILES['id_scan']['name'] != '') ? saveImage('id_scan') : "";
            $extra_doc = (isset($_FILES['extra_doc']) && $_FILES['extra_doc']['name'] != '') ? saveImage('extra_doc') : "";
            $passhash = $VALIDATOR->RandString("!@#$%^&*+/-_|", 5);
            $password = $FUNC->CompiledPass($POST['password'], $passhash);
            $comment = $POST['comment'];
            $query = "INSERT INTO {$_CONF['db']['prefix']}_guests set
                      id_number = '{$POST['id_number']}',
                      first_name = '{$POST['first_name']}',
                      last_name='{$POST['last_name']}',
                      company_co='{$POST['company_co']}',
                      birth_day='{$POST['birth_day']}',
                      type='{$POST['guest_type']}',
                      tax=" . (int)$POST['tax'] . ",
                      ind_discount=" . (int)$POST['guest_ind_discount'] . ",
                      telephone='{$POST['telephone']}', 
                      email='{$POST['email']}',
                      country={$POST['country']}, 
                      address='{$POST['address']}',
                      id_scan='{$id_scan}',
                      extra_doc='{$extra_doc}',
                      comment='{$comment}',
                      password='{$password}', 
                      passhash ='{$passhash}', 
                      group_id=5, 
                      created_at=NOW(),
                      publish=1";
           # dd($query);
            $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

            $TMPL->addVar('TMPL_password', $POST['password']);
            $TMPL->addVar('TMPL_email', $POST['email']);
         
            $FUNC->Redirect($_SERVER['REQUEST_URI']);
        }
    }
    elseif ($_POST['action'] == "edit") {
        $POST = $VALIDATOR->ConvertSpecialChars($_POST);
        $errors = $VALIDATOR->passErrors();


        $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests
                      WHERE id<>{$POST['guest_id']} AND (
                      (id_number='{$POST['id_number']}' AND id_number<>'') OR  (email='{$POST['email']}' AND email<>'')
                      )";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        if ($result->RecordCount() > 0) {
            $errors[]= $TEXT['users']['user_exists'];
        }

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests
                      WHERE id={$POST['guest_id']}";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        if ($result->RecordCount() != 1) {
            $errors[]= $TEXT['users']['user_not_exists'];
        }

        if (!empty($errors)) {
            $TMPL->addVar("TMPL_errors", $errors);
            $TMPL->addVar("TMPL_data", $POST);
        } else {
            if (!empty($_FILES['id_scan']['name'])) {
                if (!empty($POST['id_scan_old'])) {
                    @unlink($imgDIR . "/" . $POST['id_scan_old']);
                    @unlink($imgDIR . "/thumb_" . $POST['id_scan_old']);
                }
                $id_scan = saveImage('id_scan');
            } else {
                $id_scan = $POST['id_scan_old'];
            }

            if (!empty($_FILES['extra_doc']['name'])) {
                if (!empty($POST['extra_doc_old'])) {
                    @unlink($imgDIR . "/" . $POST['extra_doc_old']);
                    @unlink($imgDIR . "/thumb_" . $POST['extra_doc_old']);
                }
                $extra_doc = saveImage('extra_doc');
            } else {
                $extra_doc = $POST['extra_doc_old'];
            }
            $comment = $POST['comment'];

            $query = "UPDATE {$_CONF['db']['prefix']}_guests SET
                      id_number = '{$POST['id_number']}',
					  first_name = '{$POST['first_name']}',
					  last_name='{$POST['last_name']}',
					  birth_day='{$POST['birth_day']}',
					  type='{$POST['guest_type']}',
                      tax=" . (int)$POST['tax'] . ",
                      ind_discount=" . (int)$POST['guest_ind_discount'] . ",
					  telephone='{$POST['telephone']}',
					  email='{$POST['email']}',
					  country={$POST['country']},
					  address='{$POST['address']}',
					  id_scan='{$id_scan}',
					  extra_doc='{$extra_doc}',
                      comment='{$comment}',
					  group_id=5,
					  updated_at=NOW()
					  WHERE id={$POST['guest_id']}";

            $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $FUNC->Redirect($_SERVER['REQUEST_URI']);
        }
    }
    elseif ($_POST['action'] == 'get_excel') {

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests" . $where_clause . " ORDER BY id DESC";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $guests = $result->GetRows();
        $countries = getMappedCountries();
        require_once('classes/PHPExcel/PHPExcel.php');
        require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('Guests List');

        $sheet->setCellValue("A1", $TEXT['excel']['id']);
        $sheet->setCellValue("B1", $TEXT['excel']['full_name']);
        $sheet->setCellValue("C1", $TEXT['excel']['id_number']);
        $sheet->setCellValue("D1", $TEXT['excel']['type']);
        $sheet->setCellValue("E1", $TEXT['excel']['telephone']);
        $sheet->setCellValue("F1", $TEXT['excel']['email']);
        $sheet->setCellValue("G1", $TEXT['excel']['country']);
        $sheet->setCellValue("H1", $TEXT['excel']['address']);
        $sheet->setCellValue("I1", $TEXT['excel']['balance']);

        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:I1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
        $sheet->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A1:I1')->getFill()->getStartColor()->setRGB('3a82cc');
        $sheet->getStyle('A1:I1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));

        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $i = 0;
        foreach ($guests AS $guest) {
            $full_name = $guest['first_name'];
            $full_name .= ($guest['last_name'] != '') ? ' ' . $guest['last_name'] : '';
            $sheet->setCellValueByColumnAndRow(0, $i + 2, $guest['id']);
            $sheet->setCellValueByColumnAndRow(1, $i + 2, $full_name);
            //$sheet->setCellValueByColumnAndRow(2, $i + 2, $guest['id_number']);
            $sheet->setCellValueExplicit('C' . ($i + 2), $guest['id_number'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueByColumnAndRow(3, $i + 2, $TEXT['excel'][$guest['type']]);
            $sheet->setCellValueByColumnAndRow(4, $i + 2, $guest['telephone']);
            $sheet->setCellValueByColumnAndRow(5, $i + 2, $guest['email']);
            $sheet->setCellValueByColumnAndRow(6, $i + 2, $countries[$guest['country']]);
            $sheet->setCellValueByColumnAndRow(7, $i + 2, $guest['address']);
            $sheet->setCellValueByColumnAndRow(8, $i + 2, $guest['balance']);
            $cell_name = $columnNames[$columnNumber] . $rowNumber;
            $sheet->getStyle('A' . ($i + 2) . ':I' . ($i + 2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
            $i++;
        }
        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=guest_list.xls");

        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('php://output');
        exit;
    }
}


if ($_GET['action'] == "change_status") {
    $uid = StringConvertor::toNatural($_GET['uid']);
    $query = "update {$_CONF['db']['prefix']}_guests set
		        publish=if(publish=0,1,0) where id='{$uid}'";
    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    #$FUNC->Redirect($SELF);
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
} //*** Deleting single user ****************************//
elseif ($_GET['action'] == "del_user") {
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
    exit;
    $uid = StringConvertor::toNatural($_GET['uid']);
    if ($uid > 1) {
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests WHERE id='{$uid}'";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());


        if (!$data = $result->fields)
            $FUNC->Redirect($SELF);
        if ($data['group_id'] != 5)
            $FUNC->Redirect($SELF);

        if (!empty($data['id_scan'])) {
            @unlink($imgDIR . "/" . $data['id_scan']);
            @unlink($imgDIR . "/thumb_" . $data['id_scan']);
        }
        if (!empty($data['extra_doc'])) {
            @unlink($imgDIR . "/" . $data['extra_doc']);
            @unlink($imgDIR . "/thumb_" . $data['extra_doc']);
        }

        $query = "DELETE FROM {$_CONF['db']['prefix']}_guests WHERE id={$uid}";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
} else {

    show_users($where_clause);
}

// ### DEFAULT
// ### LIST of USERS
function show_users($where_clause)
{
    global $_CONF, $CONN, $FUNC, $LOADED_PLUGIN, $TMPL, $SETTINGS, $_CENTER, $SELF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_guests" . $where_clause." ORDER BY id DESC";
    //$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result = $CONN->PageExecute($query, 50, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $TMPL->addVar("TMPL_countries", getAllCountries());
    $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar_($SELF . "&p=", $result));
    $TMPL->addVar("TMPL_plugin", $LOADED_PLUGIN['plugin']);
    $TMPL->addVar("TMPL_settings", $SETTINGS);
    $TMPL->addVar("TMPL_users", $result->GetRows());
    $TMPL->ParseIntoVar($_CENTER, "guests");
}

function saveImage($image)
{
    global $imgDIR;
    $SET['img_width'] = 480;
    $SET['img_height'] = 640;
    $SET['th_width'] = 340;
    $SET['th_height'] = 260;
    $SET['th_method'] = 'r';

    if ($_FILES[$image]['name'] && $_FILES[$image]['size']) {
        $IMG = new ImageGD($imgDIR);
        if ($img = $IMG->uploadImage($image)) {
            $IMG->resizeImage($img, $SET['img_width'], $SET['img_height'], 100, false, $img);
            //*** Making thumbnail  ************************//
            if ($SET['th_method'] == 'c') {
                $IMG->cropImage($img, $SET['th_width'], $SET['th_height'], 100, 'thumb_' . $img);
            } else {
                $IMG->resizeImage($img, $SET['th_width'], $SET['th_height'], 100, false, 'thumb_' . $img);
            }
            //*** Making thumbnail  ************************//
        }
        // p($IMG->passErrors());
        if ($errors = $IMG->passErrors()) {
            @unlink($imgDIR . '/' . $img);
            @unlink($imgDIR . '/thumb_' . $img);
            @unlink($imgDIR . '/thumb2_' . $img);
        }
    }
    return $img;
}

function getAllCountries()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_country WHERE publish =1 ORDER BY sort_id ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $result->GetRows();
}

function getMappedCountries()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_country WHERE publish =1 ORDER BY sort_id ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $countries = $result->GetRows();
    foreach ($countries as $country) {
        $tmp[$country['id']] = $country[LANG];
    }
    return $tmp;
}

?>