<?php
	$post_ID = get_the_ID();
	$paid = get_post_meta($post_ID, "paid", true);

	$v = time();
	wp_enqueue_script('stm-payout-form', STM_LMS_URL . '/post_type/metaboxes/assets/js/stm-payout-form.js', array('vue.js'), $v);
?>
<style>
#post-body-content,
#normal-sortables{
	display: none;
}
.btn-panel{
	text-align: center;
}

.btn-panel .preloader{

}
.btn-panel .preloader img{
	width: 100px;
}

.stm-lms-mesage{
	padding: 15px 0px;
	font-size: 18px;
	border: 1px solid #f1f1f1;
}
</style>

<div id="stm-payout-form" class="stm_metaboxes_grid ">
		<div class="stm_metaboxes_grid__inner">
			<div class="stm-order">
				<br>
				<h4><?php _e("Payout data", "masterstudy-lms-learning-management-system");?></h4>
				<?php if(!$paid):?>
					<div class="btn-panel">
						<div v-if="loader" class="preloader">
							<img class="" src="<?php echo STM_LMS_URL?>/assets/img/preloader.svg">
						</div>
						<p class="stm-lms-mesage" v-if="mesage" >{{mesage}}</p>
						<div v-if="!loader">
							<a @click="pay_now(<?php echo $post_ID;?>)" href="javascript:void(0)" class="button button-primary"><?php _e("Process Payment Now", "masterstudy-lms-learning-management-system");?></a>
							<a @click="payed(<?php echo $post_ID;?>)" href="javascript:void(0)" class="button button-primary"><?php _e("Change Status to PAYED", "masterstudy-lms-learning-management-system");?></a>
						</div>
					</div>
				<?php endif;?>
				<table>
					<tbody>
					<tr>
						<th>
							<?php echo get_the_title()?>
						</th>
						<th></th>
					</tr>

					<tr>
						<th>
							<?php _e("Status", "masterstudy-lms-learning-management-system");?>
						</th>
						<td>
							<?php echo  get_post_meta($post_ID, "status", true); ?>
						</td>
					</tr>

					<tr>
						<th>
							<?php _e("Payout from", "masterstudy-lms-learning-management-system");?>
						</th>
						<td>
							<?php
							$author_payout = get_post_meta($post_ID, "author_payout");
							if(isset($author_payout[0]) AND $user  = get_userdata($author_payout[0])) {
								echo  " (".$user->ID.") ".$user->user_email." ".$user->display_name;
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php _e("Transaction", "masterstudy-lms-learning-management-system");?>
						</th>
						<td>
							<?php
							$transaction = get_post_meta($post_ID, "transaction_id", true);
							if($transaction) {
								echo $transaction;
							} else {
								echo "-------------";
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php  _e("Amounts", "masterstudy-lms-learning-management-system");?>
						</th>
						<td>
							<?php
							$amounts = get_post_meta($post_ID, "amounts");
							if(isset($amounts[0])) {
								echo STM_LMS_Helpers::display_price($amounts[0]);
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php  _e("Author fee", "masterstudy-lms-learning-management-system");?>
						</th>
						<td>
							<?php
							$fee_amounts = get_post_meta($post_ID, "fee_amounts");
							if(isset($fee_amounts[0])) {
								echo STM_LMS_Helpers::display_price($fee_amounts[0]);
							}
							?>
						</td>
					</tr>
					</tbody>
				</table>


				<?php
				$order_items = \stmLms\Classes\Models\StmOrderItems::query()->where("payout_id", $post_ID)->find();
				?>

				<table>
					<tbody>

					<thead>
					<tr>
						<th>#</th>
						<th><?php _e("ID", "masterstudy-lms-learning-management-system")?></th>
						<th><?php _e("Course", "masterstudy-lms-learning-management-system")?></th>
						<th><?php _e("Order ID", "masterstudy-lms-learning-management-system")?></th>
						<th><?php _e("Quantity", "masterstudy-lms-learning-management-system")?></th>
						<th><?php _e("Price", "masterstudy-lms-learning-management-system")?></th>
						<th><?php _e("Total Price", "masterstudy-lms-learning-management-system")?></th>
					</tr>
					</thead>


					<?php $i = 1; foreach ($order_items as $order_item):?>

						<tr>
							<th>
								<?php echo esc_attr($i)?>
							</th>
							<td>
								<?php echo esc_attr($order_item->id)?>
							</td>
							<td>
								<?php echo esc_html($order_item->get_items_posts()->post_title)?>
							</td>
							<td>
								<?php echo esc_attr($order_item->order_id)?>
							</td>
							<td>
								<?php echo esc_attr($order_item->quantity)?>
							</td>
							<td>
								<?php echo STM_LMS_Helpers::display_price($order_item->price);?>
							</td>
							<th>
								<?php echo STM_LMS_Helpers::display_price($order_item->price * $order_item->quantity);?>
							</th>
						</tr>

						<?php $i++; endforeach;?>

					<tr>
						<th></th>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<th> <?php _e("Total Amounts", "masterstudy-lms-learning-management-system")?> </th>
						<th>
							<?php
								$amounts = get_post_meta($post_ID, "amounts");
								if(isset($amounts[0])) {
									echo STM_LMS_Helpers::display_price($amounts[0]);
								}
							?>
						</th>
					</tr>

					</tbody>
				</table>
			</div>
		</div>
</div>






