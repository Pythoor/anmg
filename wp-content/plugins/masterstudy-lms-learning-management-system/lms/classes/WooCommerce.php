<?php
class STM_LMS_Data_Store_CPT extends WC_Product_Data_Store_CPT {

	public function read( &$product ) {

		add_filter( 'woocommerce_is_purchasable', function () { return true; }, 10, 1);

		$product->set_defaults();

		$post_object = get_post( $product->get_id() );

		if ( ! $product->get_id() || ! ( $post_object = get_post( $product->get_id() ) ) || ! ( ('product' === $post_object->post_type) || ('stm-courses' === $post_object->post_type) ) ) {
			throw new Exception( __( 'Invalid product.', 'woocommerce' ) );
		}

		$product->set_props( array(
			'name'              => $post_object->post_title,
			'slug'              => $post_object->post_name,
			'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
			'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
			'status'            => $post_object->post_status,
			'description'       => $post_object->post_content,
			'short_description' => $post_object->post_excerpt,
			'parent_id'         => $post_object->post_parent,
			'menu_order'        => $post_object->menu_order,
			'reviews_allowed'   => 'open' === $post_object->comment_status,
		) );

		$this->read_attributes( $product );
		$this->read_downloads( $product );
		$this->read_visibility( $product );
		$this->read_product_data( $product );
		$this->read_extra_data( $product );
		$product->set_object_read( true );

	}

	public function get_product_type( $product_id ) {
		$post_type = get_post_type( $product_id );
		if ( 'product_variation' === $post_type ) {
			return 'variation';
		} elseif ( ( $post_type === 'product' ) || ('stm-courses' === $post_type) ) {
			$terms = get_the_terms( $product_id, 'product_type' );
			return ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
		} else {
			return false;
		}
	}
}

add_filter( 'woocommerce_data_stores', 'stm_lms_woocommerce_data_stores' );

function stm_lms_woocommerce_data_stores ( $stores ) {
	$stores['product'] = 'STM_LMS_Data_Store_CPT';
	return $stores;
}

if(class_exists("WooCommerce")) {
	update_post_meta($post_id, '_price', $perPayListingPrice);
	$checkoutUrl =  wc_get_checkout_url() . '?add-to-cart=' . $post_id;
}