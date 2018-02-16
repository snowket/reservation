<?php #p2($items); ?>

<style>
	#addEditForm {}
	#addEditForm table.main {border-spacing:1px;}
	#addEditForm table.main th {padding:5px; text-align:right; vertical-align:middle;}
	#addEditForm table.main td {padding:5px; margin:0; vertical-align:middle; min-width:85px;}
	#addEditForm table.main .currency {text-transform:uppercase; font-style:italic; color:#ccc; font-weight:bold;}
	
	#addEditForm .main {}
	#addEditForm .main .name input {padding:3px; border:0; width:450px;}
	#addEditForm .main .cost input {padding:3px; border:0; width:55px; text-align:center;}
	#addEditForm .main img {width:22px;}
	
	#addEditForm table.main tr.active td {font-weight:bold; /*color:#FF8D39;*/ background:#FDD3B7;}
	
	#errorMessages {margin:10px 0;}
	#errorMessages .row {/*float:left;*/ padding:5px; border-bottom:0px solid #ff0000; background:#FDBABA;}
	#errorMessages .row .title {color:#ff0000; font-weight:bold;}
	#errorMessages .row .msg {/*color:#ff0000;*/}
</style>

<?php
	if ($error) {
		echo '<div id="errorMessages">';
		
		foreach ($errorMessages AS $errorMessage) {
			echo '<div class="row"><b class="title">Error:</b> <span class="msg">'.$errorMessage.'</span></div><div class="clear"></div>';
		}
		
		echo '</div>'; 
	}
?>

<form id="addEditForm" name="addEditForm" action="" method="post" enctype="multipart/form-data" onsubmit="if (!confirm('Are you sure?!')) {return false;}">
	<input type="hidden" name="action" value="update" />
	
	<table class="main">
		<thead>
		<tr>
			<th class="tdrow2">Status</th>
			<th class="tdrow2">Date</th>
			<th class="tdrow2">Set</th>
			<th class="tdrow2">Email sent</th>
			<th class="tdrow2">Notify Manually</th>
		</tr>
		</thead>
		
		<tbody>
		<?php

			#$record['status_delivery_0'] = $record['created'];
			
			$iStatusLast = (key(array_slice(CORE::$MODULES->PARCEL->parcelStatuses, -1, 1, true)));
			
			foreach (CORE::$MODULES->PARCEL->parcelStatuses AS $iStatus => $titleStatus) {
				echo '
					<tr class="'.($record['status_delivery'] == $iStatus ? 'active' : '').'">
						<td class="tdrow2" title="' . $iStatus . '">'.$titleStatus.'</td>
						<td class="tdrow2">'.$record['status_delivery_' . $iStatus].'</td>
						<td class="tdrow2" style="text-align:center;">';
						
						/*if ($record['status_delivery'] > 3 || $record['status_paid']) {
							;
						}
						else */if ($record['status_delivery'] < 3 && $iStatus > 0 && $iStatus < $record['status_delivery']) {
							echo '<input type="submit" name="reset_status['.$iStatus.']" value="reset" title="Re-set as \''.CORE::$MODULES->PARCEL->parcelStatuses[$iStatus].'\'" class="formButton3_" style="cursor:pointer; background:#D0D0D0; color:#fff; border:0; border-radius:3px; padding:2px 5px;" />';
						}
						else if (
							   ($iStatus == $record['status_delivery'] + 1 && $iStatus != $iStatusLast)
							|| ($iStatus == $record['status_delivery'] + 1 && $iStatus == $iStatusLast && $record['status_paid'])
						) {
							echo '<input type="submit" name="set_status['.$iStatus.']" value="set" title="Set as \''.CORE::$MODULES->PARCEL->parcelStatuses[$iStatus].'\'" class="formButton3_" style="cursor:pointer; background:#FF8D39; color:#fff; border:0; border-radius:3px; padding:2px 5px;" />';
						}
						else if ($iStatus == $record['status_delivery'] + 1 && $iStatus == $iStatusLast && !$record['status_paid']) {
								echo '<input type="button" name="" value="not paid" style="background:#ff0000; color:#fff; border:0; border-radius:3px; padding:2px 5px;" />';
						}
						
						echo '</td>
						<td class="tdrow2">'.$record['email_date_sd_' . $iStatus].'</td>
						<td class="tdrow2" style="text-align:center;">';

						#if ($iStatus > 0 && $iStatus <= $record['status_delivery']) {
						if ($iStatus > 0 && $iStatus < 4 && $iStatus == $record['status_delivery']) {
							echo '<input type="submit" name="send_email['.$iStatus.']" value="send manually" class="formButton3_" style="cursor:pointer; background:#FF8D39; color:#fff; border:0; border-radius:3px; padding:2px 5px;" />';
						}
						
						echo '</td>
					</tr>
				';
			}
		?>
		</tbody>
		
		<!--
		<tfoot>
		<tr>
			<th class="tdrow2" colspan="5"><input type="submit" name="" value="update" class="formButton2" /></th>
		</tr>
		</tfoot>
		-->
	</table>
</form>

<script>$('td:empty').html('&nbsp;');</script>

<?php #p($record['status_delivery']); p($record['status_email_delivery']); #p($_POST); #p2(CORE::$MODULES->PARCEL->parcelStatuses); p($record); ?>


