<?php if (class_exists('STM_LMS_Pro_Addons')):?>
	<?php
	$v = time();
	wp_enqueue_script('stm-payout', STM_LMS_URL . '/post_type/metaboxes/assets/js/stm-payout.js', array('vue.js'), $v);
	$payout_methods = [];
	foreach ( \stmLms\Classes\Models\StmLmsPayout::get_payout_method() as $key => $payment_method ) {
		$payout_methods[] = [
			"settings_url" => get_admin_url(null, "admin.php?page=stm-lms-payment-settings&payment_method=".$key),
			"image" => $payment_method->icon,
			"payment_method" => $key,
			"title" => $payment_method->get_method_title(),
			"description" => $payment_method->get_method_description(),
			"loader" =>false,
			"is_default" => (get_option("stm_lms_payout_default") == $payment_method->id) ? true : false,
			"is_active" => ($payment_method->enabled == "yes") ? 1 : 0
		];
	}
	$data = [
		"payout_methods" => $payout_methods
	];
	wp_add_inline_script( 'stm-payout', "var stm_payout_data = JSON.parse('".stm_lms_convert_content(json_encode($data))."') ", "before" );
	?>
	<stm-payout inline-template>
		<div>
			<label><span> <?php esc_html_e("Cron At 00:00 on day-of-month 1.", "masterstudy-lms-learning-management-system")?></label>
			<code> 0 0 1 * * wget -O /dev/null <?php echo get_site_url(null, "wp-cron.php?doing_wp_cron")?> </code>
			<br>
			<br>

			<label><span> <?php esc_html_e("Add in wp-config.php", "masterstudy-lms-learning-management-system")?></label>
			<code> define('DISABLE_WP_CRON', true); </code>
			<hr>

			<label><span> <?php esc_html_e("Payment method for payout", "masterstudy-lms-learning-management-system")?></label>
			<div class="stm-lms-addons">
				<div v-for="payout_method in payout_methods" class="stm-lms-addon" v-bind:class="{active:payout_method.is_active}">
					<div class="stm-lms-addon__image">
						<a v-if="payout_method.is_active" v-bind:href="payout_method.settings_url" target="_blank">
							<i class="lnr lnr-cog"></i>
						</a>
						<img v-bind:src="payout_method.image">
					</div>
					<div class="stm-lms-addon__install">
						<a v-if="payout_method.loader"><?php esc_html_e("Loading", "masterstudy-lms-learning-management-system")?></a>
						<a v-if="!payout_method.loader" href="javascript:void(0)" class="stm-lms-addon-enable">
							<span v-if="payout_method.is_active" @click="uninstall(payout_method)"><?php esc_html_e("Disable Payment method", "masterstudy-lms-learning-management-system")?></span>
							<span v-if="!payout_method.is_active" @click="install(payout_method)"><?php esc_html_e("Enable Payment method", "masterstudy-lms-learning-management-system")?></span>
						</a>
						<a v-if="!payout_method.loader && payout_method.is_active && payout_method.is_default"><?php esc_html_e("Default", "masterstudy-lms-learning-management-system")?></a>
						<a v-if="!payout_method.loader && payout_method.is_active && !payout_method.is_default" @click="set_default(payout_method)" href="javascript:void(0)"><?php esc_html_e("Make default", "masterstudy-lms-learning-management-system")?></a>
					</div>
				</div>
			</div>
		</div>
	</stm-payout>
<?php endif;?>
