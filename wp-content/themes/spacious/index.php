<?php 
/*
Template Name: Главная
*/
?>

<?php get_header(); ?>

	<?php do_action( 'spacious_before_body_content' ); ?>
	  <div class="primary-wrap">
		<div id="primary">

			<div id="content" class="clearfix">
		     <!-- <div class="title_img">
				  <?php echo get_the_post_thumbnail(); ?>
			 </div>  -->			
             <p class="margin-b15 line-height"> <?php  echo get_post(182)->post_content; ?>  </p>
             
			 <?php  dynamic_sidebar( 'main-sidebar' ); ?>
			 <div class="poleznie-ssilki">
						<?php print_r(get_post_meta(get_the_ID(), 'usefu_link_img'.$i, true));
						      for ($i=0; $i<9;) { 
						      	$img_id=get_post_meta(get_the_ID(), 'usefu_link_img'.$i, true);						      	                         
	                            $img_url = wp_get_attachment_image_src($img_id, 'featured-blog-medium'); 
	                            if(isset($img_url[0])){ 
	                    ?>
	                            <div class="img-slide-m">
	                            	<a href="<?php get_post_meta(get_the_ID(), 'usefu_link'.$i, true); ?>"><img src="<?php echo $img_url[0];?>" alt="">
	                            </div>
	                    <?php
	                            }
						      	$i++;						     
						  }
						?>
			</div>
			</div><!-- #content -->
		</div><!-- #primary -->
	  </div><!-- .primary-wrapp -->
	
	<?php spacious_sidebar_select(); ?>
	
	<?php do_action( 'spacious_after_body_content' ); ?>

<?php get_footer(); ?>



