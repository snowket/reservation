<?
if (!defined('ALLOW_ACCESS')) exit;


if($_GET['connect'] == 'on'){
  $wubook = GetConnection();
  $param = array();
  $wu = $wubook->callMethod('is_token_valid', $param);
  if ($wu) {
      $prices_par = array(
          'lcode' => 1511771202,
          'url' => 'http://hms.ge/reservation_dev/hms/wubook_notes.php',
          'test' => '1',
      );
      $prices = $wubook->callMethod('push_activation', $prices_par);
      echo json_encode($prices);
  }
}
$TMPL->ParseIntoVar($_CENTER,"controll");
