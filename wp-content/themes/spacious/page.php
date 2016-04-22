<?php 
/**
 * Theme Page Section for our theme.
 *
 * @package ThemeGrill
 * @subpackage Spacious
 * @since Spacious 1.0
 */
?>

<?php get_header(); ?>

	<?php do_action( 'spacious_before_body_content' ); ?>

	<div id="primary">
		<div id="content" class="clearfix">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					do_action( 'spacious_before_comments_template' );
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();					
	      		do_action ( 'spacious_after_comments_template' );
				?>

			<?php endwhile; ?>			
			<div class="single-nav-slaider">
						<?php 
						      for ($i=0; $i<9;) { 
						      	$img_id=get_post_meta(get_the_ID(), 'slide_page_'.$i, true);						      	                         
	                            $img_url = wp_get_attachment_image_src($img_id, 'featured-slide-small'); 
	                            $img_url_full = wp_get_attachment_image_src($img_id, 'full');
	                            if(isset($img_url[0])){ 
	                    ?>
	                            <div class="img-slide-m">
	                              <a rel="example_group" href="<?php echo $img_url_full[0];?>">  
	                            	<img src="<?php echo $img_url[0];?>" alt="">
	                              </a>
	                            </div>
	                    <?php
	                            }
						      	$i++;						     
						  }
						?>
			</div>

		</div><!-- #content -->
	</div><!-- #primary -->
	
	<?php spacious_sidebar_select(); ?>

	<?php do_action( 'spacious_after_body_content' ); ?>

<?php get_footer(); ?>