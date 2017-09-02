<?php get_header(); ?>

<?php
	global $wp_query;
	$breadcrumb_args = array(
		'home' => 'Главная',
		'delimiter' => ' / ',
	);
?>

	<div class="container">

		<div class="row woocommerce_breadcrumb">
				<div class="col-sm-12">
					<?php woocommerce_breadcrumb( $breadcrumb_args ); ?>
				</div>
		</div>

		<div class="row">

			<?php

			if ( is_singular( 'product' ) ) {

				while ( have_posts() ) : the_post();

					$overridden_template = locate_template('wooTemplates/content-single-product.php');
					load_template( $overridden_template );

				endwhile;

			} else { ?>

				<!-- Start show products -->

				<?php if ( have_posts() ) : ?>

					<?php do_action( 'woocommerce_before_shop_loop' ); ?>

					<?php woocommerce_product_loop_start(); ?>

					<?php woocommerce_product_subcategories(); ?>

					<?php
                        while ( have_posts() ) : the_post();

                        load_template( locate_template('wooTemplates/content-product.php'), false );

					    endwhile; // end of the loop.
                    ?>

					<?php woocommerce_product_loop_end(); ?>

					<?php do_action( 'woocommerce_after_shop_loop' ); ?>

				<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

					<?php do_action( 'woocommerce_no_products_found' ); ?>

				<?php endif;?>

				<!-- End show products -->

			<?php } ?>

		</div>

	</div>

<?php get_footer(); ?>