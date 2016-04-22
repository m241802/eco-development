<?php
/**
 * @package main_slider
 * @version 1.0
 */
/*
Plugin Name: Main Sslider
Description: Add Slider.
Author: Mikhalchenko Sergei
Version: 1.0

*/

register_sidebar( array(
      'name' =>'Сайдбар в Шапке',
      'id' => 'header-sidebar',
      'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
      'after_widget' => '</li>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
) );
register_sidebar( array(
      'name' =>'Сайдбар в теле сайта',
      'id' => 'main-sidebar',
      'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
      'after_widget' => '</li>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
) );
register_sidebar( array(
      'name' =>'Сайдбар для Новостей',
      'id' => 'news-sidebar',
      'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
      'after_widget' => '</li>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
) );


class Main_slider extends WP_Widget {
     public function __construct() {
           parent::__construct(
                 'Main_slider',
                 'Виджет Слайдера',
                 array( 'description' => __( 'Добавляет Горизонтальный Слайдер', 'text_domain' ), )
           );
     }
     public function update( $new_instance, $old_instance ) {
           $instance = array();
           $instance['title'] = strip_tags( $new_instance['title'] );
           return $instance;
     }
     public function form( $instance ) {
?>
           <p>
                 <label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок</label>
                 <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" 
                  name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" 
                  value="<?php echo $instance['title']; ?>" />
           </p>
<?php
     }
     public function widget( $args, $instance ) {
?>
           <div class="autoplay">
            <?php               
              query_posts('post_type=typical-projects'); 
              ?>
        <?php if ( have_posts() ) : while (have_posts()): the_post(); ?>
             <div class="project">
               <a href="<?php the_permalink(); ?>"> 
               <?php echo the_post_thumbnail('featured'); ?> 
               </a>     
               <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
               <div class="info_project">         
               <!--   <div  class="price-project">       -->        
                   <!-- <span>Цена:</span> -->
            <!--        <?php $post_id=get_the_ID(); ?>
                   <?php echo get_post_meta($post_id, 'cost_of_the_project', true);?> -->
              <!--      <span>руб.</span> -->
            <!--      </div> -->
                 <div class="area-project">
                   <!-- <span>Общая площадь:</span> -->                  
                   <?php echo get_post_meta($post_id, 'total_area', true);?>
                   <span>м.кв.</span>
                 </div>
                 <!-- <li>
                   <span>Жилая площадь:</span>
                   <?php echo get_post_meta($post->ID, 'built-up-area', true);?>
                   <span>м.кв.</span>
                 </li>  -->
               </div>     
             </div>
          <?php endwhile; ?>
        <?php endif; ?>
        </div>
<?php
     }
}
add_action( 'widgets_init', function(){
     register_widget( 'Main_slider' );
});





?>