<?php

add_action( 'visahub_lite_page_banner', 'visahub_lite_page_banner_markup' );

function visahub_lite_page_banner_markup(){

	if( ! ( is_front_page() && is_home() ) ) :

	?><header class="page-header mb-5"><div class="container"><div class="row"><div class="col-12"><?php

		if( is_page() ){

			the_title( '<h1 class="page-title">', '</h1>' );
		}

		if( is_archive() ){

			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="archive-description text-white">', '</div>' );
		}

		if( is_404() ){

			?><h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'visahub-lite' ); ?></h1><?php
		}


		if( is_search() ){

			?>
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'visahub-lite' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			<?php
		}


		if( is_single() ){

			?>
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					the_title();
					?>
				</h1>
			<?php
		}

	?></div></div></div></header><?php

	endif;
}


?>