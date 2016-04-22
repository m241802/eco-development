<?php 
/**
 * Theme Single Post Section for our theme.
 *
 * @package ThemeGrill
 * @subpackage Spacious
 * @since Spacious 1.0
 */
?>

<?php get_header(); ?>

	<?php do_action( 'spacious_before_body_content' ); ?>
	<div class="primary-wrapp">	
		<div id="primary">
			<div id="content" class="clearfix">
			   <?php if ( 'typical-projects' == get_post_type() || 'our-works' == get_post_type() ){ ?>
			  
			    <div class="single-main-slaider">
		                <?php 
						      for ($i=0; $i<9;) { 
						      	$img_id=get_post_meta(get_the_ID(), 'slide_img_'.$i, true);
	                            $img_url_big = wp_get_attachment_image_src($img_id, 'featured'); 
	                            $img_url_full = wp_get_attachment_image_src($img_id, 'full');
	                             if(isset($img_url_big[0])){                            
	                    ?>	                            
	                            <div class="img-slide">	
	                              <a rel="example_group" href="<?php echo $img_url_full[0];?>">                              
	                            	<img src="<?php echo $img_url_big[0];?>" alt="">
	                               </a>	                              
	                            </div>	                            
	                    <?php
	                       }
						      	$i++;
						      }
						?>
					</div>
					<div class="single-nav-slaider">
						<?php 
						      for ($i=0; $i<9;) { 
						      	$img_id=get_post_meta(get_the_ID(), 'slide_img_'.$i, true);						      	                         
	                            $img_url = wp_get_attachment_image_src($img_id, 'featured-slide-small'); 
	                            if(isset($img_url[0])){ 
	                    ?>
	                            <div class="img-slide-m">
	                            	<img src="<?php echo $img_url[0];?>" alt="">
	                            </div>
	                    <?php
	                            }
						      	$i++;						     
						  }
						?>
					</div>
				 <?php } ?>				
				 
				<?php while ( have_posts() ) : the_post(); ?>	                
					<?php get_template_part( 'content', 'single' ); ?>
				<?php endwhile; ?>	
				 <?php if(get_post_type(get_the_ID())=='investment-projects'){ ?>
				 	<div class="follow-us"> 
				 	    Следите за новостями в соц. сетях
				 	    <?php $img_soc=wp_get_attachment_image_src($img_id, 'featured-slide-small');?>						   
						       <a href=" http://vk.com/ecolife_crimea" title="EcoLife ВКонтакте" ><div class="vk"></div></a>				    
						      <!--  <a href="<?php echo get_post_meta(182, 'fb_url', true);?>" title="Facebook" ><div class="fb"></div></a>					    
						       <a href="<?php echo get_post_meta(182, 'tw_url', true);?>" title="Twitter" ><div class="tw"></div></a> -->
				 	</div>                     
				<?php } ?>			
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
				<?php while ( have_posts() ) : the_post(); ?>		
					<?php get_template_part( 'navigation' ); ?>
				<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div><!--.primary-wrapp-->
	
<!-- 	<?php spacious_sidebar_select(); ?> -->
	
	

<?php get_footer(); ?> 