<?php 
/**
 * Theme Index Section for our theme.
 *
 * @package ThemeGrill
 * @subpackage Spacious
 * @since Spacious 1.0
 */
?>

<?php get_header(); ?>

	<?php do_action( 'spacious_before_body_content' ); ?>
	  <div class="primary-wrap">
		<div id="primary">

			<div id="content" class="clearfix">
			 <?php  dynamic_sidebar( 'main-sidebar' ); ?>
			</div><!-- #content -->
		</div><!-- #primary -->
	  </div><!-- .primary-wrapp -->
	
	<?php spacious_sidebar_select(); ?>
	
	<?php do_action( 'spacious_after_body_content' ); ?>

<?php get_footer(); ?>



