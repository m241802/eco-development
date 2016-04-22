<?php
/**
 * @package Sitebar_News
 * @version 1.0
 */
/*
Plugin Name: Sitebar News
Description: Add News in Sitebar.
Author: Mikhalchenko Sergei
Version: 1.0
НННН
*/
class Sitebar_News extends WP_Widget {
     public function __construct() {
           parent::__construct(
                 'Sitebar_News',
                 'Виджет Новостей',
                 array( 'description' => __( 'Добавляет Новости', 'text_domain' ), )
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
           <div class="sitebar-news">
             <h3><?php echo $instance[ 'title' ]; ?></h3>
              <?php                 
                query_posts('post_type=news'); 
              ?>
              <?php if ( have_posts() ) : while (have_posts()): the_post(); ?>
                   <div class="new">
                     <a href="<?php the_permalink(); ?>">
                       <?php echo the_post_thumbnail('featured-medium'); ?>
                     </a>
                     <div class="info-news">
                       <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>                                             
                     </div>                    
                   </div>  
                <?php endwhile; ?>
              <?php endif; ?>
           </div>
<?php
     }
}
add_action( 'widgets_init', function(){
     register_widget( 'Sitebar_News' );
});





?>
