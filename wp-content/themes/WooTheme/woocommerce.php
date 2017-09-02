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
			<?php if ( have_posts() ) :  while ( have_posts() ) : the_post(); ?>
			<div class="col-sm-12">
				<h1><?php the_title();?></h1>
				<p><?php the_content();?></p>
			</div>
			<?php endwhile; ?>
			<?php endif; ?>
		</div>

	</div>

<?php get_footer(); ?>