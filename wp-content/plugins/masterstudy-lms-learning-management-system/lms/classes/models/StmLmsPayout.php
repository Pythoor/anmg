<?php
namespace stmLms\Classes\Models;
use \STM_LMS_Helpers;
use stmLms\Classes\Vendor\ArrayHelper;
use stmLms\Classes\Vendor\StmBaseModel;

class StmLmsPayout extends StmBaseModel{

	protected $fillable = [
		'ID',
		'post_author',
		'post_date',
		'post_date_gmt',
		'post_content',
		'post_title',
		'post_excerpt',
		'post_status',
		'comment_status',
		'ping_status',
		'post_password',
		'post_name',
		'to_ping',
		'post_modified',
		'post_modified_gmt',
		'post_content_filtered',
		'post_parent',
		'guid',
		'menu_order',
		'post_type',
		'post_mime_type',
		'comment_count'
	];

	public $ID;
	public $post_author;
	public $post_date;
	public $post_date_gmt;
	public $post_content;
	public $post_title;
	public $post_excerpt;
	public $post_status;
	public $comment_status;
	public $ping_status;
	public $post_password;
	public $post_name;
	public $to_ping;
	public $post_modified;
	public $post_modified_gmt;
	public $post_content_filtered;
	public $post_parent;
	public $guid;
	public $menu_order;
	public $post_type;
	public $post_mime_type;
	public $comment_count;
	public $post;

	public static function get_primary_key()
	{
		return 'ID';
	}

	public static function get_table()
	{
		global $wpdb;
		return $wpdb->prefix . 'posts';
	}

	public static function get_searchable_fields()
	{
		return [
			'ID',
			'post_author',
			'post_date',
			'post_date_gmt',
			'post_content',
			'post_title',
			'post_excerpt',
			'post_status',
			'comment_status',
			'ping_status',
			'post_password',
			'post_name',
			'to_ping',
			'post_modified',
			'post_modified_gmt',
			'post_content_filtered',
			'post_parent',
			'guid',
			'menu_order',
			'post_type',
			'post_mime_type',
			'comment_count',
		];
	}

	public static function init(){
		add_action('init', [self::class, "register_post_type"], 1001);

		if(is_admin()) {
			add_action('admin_menu', [ self::class, 'payment_settings_page' ], 1000);
			add_action('admin_menu', [ self::class, 'payout_page' ], 1000);

			add_action('add_meta_boxes', [self::class, 'edit_panel_init']);
			add_filter('manage_stm-payout_posts_columns', [self::class, 'columns_head']);
			add_action('manage_stm-payout_posts_custom_column',  [self::class, 'columns_content'], 10, 2);
		}
	}

	public static function payout_page()
	{
		add_submenu_page(
			'stm-lms-settings',
			__('Payout', "masterstudy-lms-learning-management-system"),
			__('Payout', "masterstudy-lms-learning-management-system"),
			'manage_options',
			'/edit.php?post_type=stm-payout'
		);
	}

	public static function payment_settings_page()
	{
		add_submenu_page(
			null,
			null,
			false,
			'manage_options',
			'stm-lms-payment-settings',
			[self::class, 'render_payment_settings']
		);
	}

	public static function edit_panel_init() {
		add_meta_box('stm-payout_edit', 'Payout',
			[self::class, 'render_edit'], 'stm-payout', 'advanced', 'high');
	}

	public static function  render_payment_settings() {
		stm_lms_render(STM_LMS_PATH."/lms/views/payout/payment-settings", [], true);
	}

	public static function  render_edit() {
		stm_lms_render(STM_LMS_PATH."/lms/views/payout/meta-box-payout-data", [], true);
	}

	/**
	 * @param $defaults
	 *
	 * @return mixed
	 */
	public static function columns_head($defaults) {
		unset( $defaults['date'] );
		$defaults['status'] = __("Status", "masterstudy-lms-learning-management-system");
		$defaults['author_payout'] = __("Payout from", "masterstudy-lms-learning-management-system");
		$defaults['transaction'] = __("Transaction", "masterstudy-lms-learning-management-system");
		$defaults['amounts'] = __("Amounts", "masterstudy-lms-learning-management-system");
		$defaults['fee_amounts'] = __("Author fee", "masterstudy-lms-learning-management-system");
		$defaults['date'] = __("Date", "masterstudy-lms-learning-management-system");
		return $defaults;
	}

	/**
	 * @param $column_name
	 * @param $post_ID
	 */
	public static function columns_content($column_name, $post_ID) {

		if ($column_name == 'author_payout') {
			$author_payout = get_post_meta($post_ID, "author_payout");
			if(isset($author_payout[0]) AND $user  = get_userdata($author_payout[0])) {
				echo  " (".$user->ID.") ".$user->user_email." ".$user->display_name;
			}
		}

		if ($column_name == 'status') {
			$status = get_post_meta($post_ID, "status");
			if(isset($status[0])) {
				echo $status[0];
			}
		}

		if ($column_name == 'amounts') {
			$amounts = get_post_meta($post_ID, "amounts");
			if(isset($amounts[0])) {
				echo STM_LMS_Helpers::display_price($amounts[0]);
			}
		}

		if ($column_name == 'fee_amounts') {
			$fee_amounts = get_post_meta($post_ID, "fee_amounts");
			if(isset($fee_amounts[0])) {
				echo STM_LMS_Helpers::display_price($fee_amounts[0]);
			}
		}

		if ($column_name == 'transaction') {
			$transaction = get_post_meta($post_ID, "transaction_id", true);
			if( $transaction ) {
				echo $transaction;
			}else{
				echo "-------------";
			}
		}
	}

	public static function register_post_type() {
		$labels = array(
			'name' => esc_html__('Payout', "masterstudy-lms-learning-management-system"),
			'singular_name' => esc_html__('Payout', "masterstudy-lms-learning-management-system"),
			'add_new' => esc_html__('Add New', "masterstudy-lms-learning-management-system"),
			'add_new_item' => esc_html__('Add New Payout', "masterstudy-lms-learning-management-system"),
			'edit_item' => esc_html__('Edit Payout', "masterstudy-lms-learning-management-system"),
			'new_item' => esc_html__('New Payout', "masterstudy-lms-learning-management-system"),
			'view_item' => esc_html__('View Payout', "masterstudy-lms-learning-management-system"),
			'search_items' => esc_html__('Search Payout', "masterstudy-lms-learning-management-system"),
			'not_found' =>  esc_html__('No Payout found', "masterstudy-lms-learning-management-system"),
			'not_found_in_trash' => esc_html__('No Payout found in Trash', "masterstudy-lms-learning-management-system"),
			'parent_item_colon' => ''
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => 'admin.php?page=stm-lms-settings',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'stm-payout' ),
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title' ),
            'exclude_from_search' => true,
		);
		register_post_type( 'stm-payout', $args );
	}

	/**
	 * Generation payout for course author
	 */
	public static function generation_payout() {
		global $wpdb;
		$wpdb->prefix;
		$author_fee = \stmLms\Classes\Models\StmStatistics::get_author_fee();

		$order_items = StmOrderItems::query()
	        ->select(" order_items.*, (order_items.`price` * order_items.`quantity`) as total_price, course.`post_author` as author, _order.ID ")
			->asTable("order_items")
			->join(" left join `".$wpdb->prefix."posts` as _order on (order_items.order_id = _order.ID) ")
			->join(" left join `".$wpdb->prefix."posts` as course on (order_items.`object_id` = course.ID) ")
			->join(" left join `".$wpdb->prefix."postmeta` as meta on (meta.`post_id` = _order.ID) ")
			->where_raw(" order_items.`payout_id` IS NULL ")
			->where_in("_order.`post_type`", ["shop_order", "stm-orders"])
			->where_raw(" ( DATE(_order.post_date) <= DATE('".date("Y-m-d")."') ) ")
			->where("course.`post_type`", "stm-courses")
			->where_raw("
				(  
	                 ( meta.`meta_key` = 'status' AND meta.`meta_value` = 'completed')  OR  
			         ( _order.`post_status` = 'wc-completed' )  	
	            )
			")
			->group_by(" order_items.id ")
	        ->find();
		$items = [];
		foreach ($order_items as $order_item) {
			if(!isset($items[$order_item->author]['amounts']))
				$items[$order_item->author]['amounts'] = 0;
			$items[$order_item->author]['amounts'] += ($order_item->price * $order_item->quantity);
			$items[$order_item->author]['order_items'][] = $order_item;
		}

		foreach ($items as $author => $item){
			if($user = new StmUser($author)){
				$user_data =" (".$user->ID.") ".$user->user_firstname." ".$user->user_lastname." ".$user->user_email;
				$post_data = array(
					"post_type"     => "stm-payout",
					'post_title'    => wp_strip_all_tags( "Payout ".$user_data),
					'post_status'   => "publish",
				);
				$post_id = wp_insert_post($post_data);
				if($post_id){
					add_post_meta($post_id, "status", "create");
					add_post_meta($post_id, "author_payout", $user->ID);
					add_post_meta($post_id, "amounts", $item['amounts']);
					add_post_meta($post_id, "transaction_id", 0);
					add_post_meta($post_id, "fee_amounts", round( $item['amounts'] * ($author_fee / 100 ), 2, PHP_ROUND_HALF_UP ));
					foreach ($item['order_items'] as $order_item) {
						$order_item->payout_id = $post_id;
						$order_item->save();
					}
				}

			}
		}
		return \stmLms\Classes\Models\StmLmsPayout::payout_author_fee();
	}

	/**
	 * @return mixed|void
	 */
	public static function get_payout_method(){
		return apply_filters("stm_lms_payout_methods", []);
	}

	/**
	 * Ajax settings payment method
	 */
	public static function settings_payment_method() {

		$payout_method = new StmLmsPayout();

		$result = array(
			'errors' => [],
			'message' => null,
			'status'  => 'error'
		);

		if($_POST['StmLmsPaymentMethod']['type'] == 'install') {
			if($payout_method->install_payment_method($_POST['StmLmsPaymentMethod']['payment_method'])) {
				$result['status'] = 'success';
				$result['message'] = esc_html__('Installing completed successfully.', "masterstudy-lms-learning-management-system");
			}
			return $result;
		}
		if($_POST['StmLmsPaymentMethod']['type'] == 'uninstall') {
			if($payout_method->uninstall_payment_method($_POST['StmLmsPaymentMethod']['payment_method'])) {
				$result['status'] = 'success';
				$result['message'] = esc_html__('Uninstalling completed successfully.', "masterstudy-lms-learning-management-system");
			}
			return $result;
		}

		return $result;
	}

	/**
	 * @param $payment_method
	 *
	 * @return bool
	 */
	public function install_payment_method($payment_method) {
		$payment_methods = $this->get_payout_method();
		if(isset($payment_methods[$payment_method]) AND $payment = $payment_methods[$payment_method] ) {
			$payment->install();
			$payment->update_option("enabled", "yes");
			return true;
		}
		return false;
	}

	/**
	 * @param $payment_method
	 *
	 * @return bool
	 */
	public function uninstall_payment_method($payment_method) {
		$payment_methods = $this->get_payout_method();
		if(isset($payment_methods[$payment_method]) AND $payment = $payment_methods[$payment_method] ) {
			$payment->uninstall();
			$payment->update_option("enabled", "no");
			return true;
		}
		return false;
	}

	/**
	 * @return mixed|void
	 */
	public static function payout_author_fee() {
		global $wpdb;
		$data = [];
		$payouts = StmLmsPayout::query()
	       ->select("payout.ID, author_payout.`meta_value` as author_payout, fee_amounts.`meta_value` as fee_amounts")
		   ->asTable("payout")
		   ->join(' left join `'.$wpdb->prefix.'postmeta` as meta on meta.`post_id` = payout.Id AND meta.`meta_key` = "transaction_id" ')
		   ->join(' left join `'.$wpdb->prefix.'postmeta` as fee_amounts on fee_amounts.`post_id` = payout.Id AND fee_amounts.`meta_key` = "fee_amounts" ')
		   ->join(' left join `'.$wpdb->prefix.'postmeta` as author_payout on author_payout.`post_id` = payout.Id AND author_payout.`meta_key` = "author_payout" ')
		   ->where("payout.post_type", "stm-payout")
		   ->where("payout.post_status", "publish")
		   ->where("meta.`meta_value`", 0)
		   ->find();

		foreach ($payouts as $payout){
			$data[] = [
				"id" => $payout->ID,
				"author_payout" => $payout->author_payout,
				"fee_amounts" => $payout->fee_amounts,
			];
		}
		return apply_filters("stm_lms_payout_author_fee", $data);
	}

	public function payout_items_set_success(){
		StmOrderItems::query()->where("payout_id",$this->ID)->update(["transaction" => 1]);
	}

	/**
	 * @return array
	 */
	public static function payment_set_default(){
		$result = array(
			'success' => false,
			'message' => ":(",
		);
		if(isset($_POST['payment_method'])){
			$payout_methods = self::get_payout_method();
			if(isset($payout_methods[$_POST['payment_method']]) ){
				$payout_method = $payout_methods[$_POST['payment_method']];
				if($payout_method->enabled == "yes") {
					update_option("stm_lms_payout_default", $payout_method->id);
				}
				$result["success"] = true;
				$result["message"] = "Success";
			}
		}
		return $result;
	}

	/**
	 * @return mixed|void
	 */
	public static function pay_now(){
		return self::generation_payout();
	}

	/**
	 * @param $payout_id
	 *
	 * @return mixed|void
	 */
	public static function pay_now_by_payout_id($payout_id){
		$payout = StmLmsPayout::find_one($payout_id);

		$status = get_post_meta($payout->ID, "status", true);

        if($status == "PENDING"){
        	return [
        		"success" => false,
        		"message" => "Status payout in ".$status,
	        ];
        }

		$data[] = [
			"id" => $payout->ID,
			"author_payout" => get_post_meta($payout->ID, "author_payout", true),
			"fee_amounts" => get_post_meta($payout->ID, "fee_amounts", true),
		];
		return apply_filters("stm_lms_payout_author_fee", $data);
	}

	/**
	 * @param $payout_id
	 *
	 * @return mixed|void
	 */
	public static function payed($payout_id){
		$result = array(
			'success' => false,
			'message' => "",
		);
		if( !($payout = StmLmsPayout::find_one($payout_id)) ) {
			$result["message"] = "Payout not found :(";
			return $result;
		}
		update_post_meta($payout->ID, "status", "SUCCESS");
		update_post_meta($payout->ID, "transaction_id", "0000000");
		update_post_meta($payout->ID, "paid", 1);
		$payout->payout_items_set_success();
		$result['success'] = true;
		$result['message'] = "Success";
		return $result;
	}
}