<?php
do_action("stm_lms_payout_settings_save");
$payout_methods = \stmLms\Classes\Models\StmLmsPayout::get_payout_method();
if (isset($_GET['payment_method']) AND isset($payout_methods[$_GET['payment_method']])) {
	$payout_method = $payout_methods[$_GET['payment_method']];
	if($payout_method->enabled == "yes")
		echo $payout_method->render_settings();
} ?>
