<? if (!defined('ALLOW_ACCESS')) exit;

$blocks = GetBlocks();
$roomTypes = GetRoomTypes();
$roomCapacities = GetRoomCapacity();
$rooms = GetRooms();
$pricePlans = GetCommon();

$TMPL->addVar('TMPL_blocks', $blocks);
$TMPL->addVar('TMPL_roomTypes', $roomTypes);
$TMPL->addVar('TMPL_roomCapacities', $roomCapacities);
$TMPL->addVar('TMPL_rooms', $rooms);
$TMPL->addVar('TMPL_pricePlans', $pricePlans);

$TMPL->parseIntoVar($_CENTER, 'all_rooms');
