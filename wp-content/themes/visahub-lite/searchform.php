<?php
/**
 * Theme Search Form
 */
?>

<form method="get" class="contact-form form-style-one" action="<?php print esc_url( home_url( '/' ) ); ?>">
	<div class="input-group right-border">
		<label for="<?php esc_attr_e( 'searchbar-result-widget', 'visahub-lite' ); ?>"><span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'visahub-lite' ); ?></span></label>
		<input class="form-control form-control-lg" id="searchbar-result-widget" type="text" value="<?php print esc_attr( trim(get_search_query()) ); ?>"  name="s" placeholder="<?php esc_attr_e( 'Search', 'visahub-lite' ); ?>">
		<span class="input-group-btn">
			<button class="btn btn-default btn-search-v2" type="submit"><?php esc_html_e( 'GO!', 'visahub-lite' ); ?></button>
		</span>
	</div>
</form>