<?php
use stmLms\Classes\Models\Admin\StmStatisticsListTable;
$statistics = new StmStatisticsListTable();
$author_fee = \stmLms\Classes\Models\StmStatistics::get_author_fee();
$v = time();
wp_enqueue_script('stm-lms-statistics', STM_LMS_URL . '/post_type/metaboxes/assets/js/stm-lms-statistics.js', array('vue.js'), $v);
?>
<style>
	.button-panel{
		text-align: right;
		padding: 11px 16px;
		position: relative;
	}
	.button-panel img {
		width: 50px;
		position: absolute;
		right: 15px;
		top: 0px;
	}
	.button-panel .button {
		padding: 5px 25px;
		height: inherit;
		text-transform: uppercase;
	}
	.message{
		display: inline-block;
		padding: 4px 11px;
		font-size: 14px;
	}
</style>

<div class="wrap">
	<br>
	<br>
	<div id="stm_lms_statistics" class="stm-lms-postbox-container">
		<table class="wp-list-table widefat fixed striped orders">
			<tr>
				<td>
					<h1 style="padding: 0">
						<?php echo get_admin_page_title()?>
					</h1>
				</td>
				<td class="button-panel" style="">
					<div class="message" v-if="pay_now_result">{{pay_now_result.message}}</div>
					<img v-if="pay_now_loader" src="<?php echo STM_LMS_URL?>/assets/img/preloader.svg">
					<button v-if="!pay_now_loader" @click="pay_now" class="button button-primary"><?php esc_html_e("Pay now", "masterstudy-lms-learning-management-system")?></button>
				</td>
			</tr>
		</table>
		<div class="metabox-holder">
			<div class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<h2 class="hndle"><span> <?php esc_html_e("Total Amount", "masterstudy-lms-learning-management-system")?></span></h2>
						<div class="inside">
							<div class="main">
								<h2><?php echo STM_LMS_Helpers::display_price($statistics->total_price); ?></h2>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<h2 class="hndle"><span><span> <?php esc_html_e("Website Earning", "masterstudy-lms-learning-management-system")?></span></h2>
						<div class="inside">
							<div class="main">
								<h2><?php echo STM_LMS_Helpers::display_price( $statistics->total_price - ( $statistics->total_price * ($author_fee / 100) ) ); ?></h2>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<h2 class="hndle"><span><span> <?php esc_html_e("Author Fee", "masterstudy-lms-learning-management-system")?></span></h2>
						<div class="inside">
							<div class="main">
								<h2 style="padding-top: 0px"><?php echo STM_LMS_Helpers::display_price($statistics->total_price * ($author_fee / 100 )); ?></h2>
								<span> <?php esc_html_e("Author Fee", "masterstudy-lms-learning-management-system")?> : <?php echo  esc_attr($author_fee)?>% </span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<form action="<?php echo admin_url('admin.php?page=stm_lms_statistics')?>" method="get">
		<input type="hidden" name="page" value="stm_lms_statistics">
		<?php $statistics->display(); ?>
		<a href="<?php echo admin_url("admin.php?page=stm_lms_statistics")?>" class="button"><?php echo esc_html__('Clear filter', "ulisting")?></a>
	</form>
</div>