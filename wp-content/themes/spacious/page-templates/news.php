<?php
/*
Template Name: Новости
*/
get_header(); ?>

  <?php do_action( 'spacious_before_body_content' ); ?>

  <div id="primary">
    <div id="content" class="clearfix">
       <?php 
            $offset_page=($_GET['page_nv']-1)*5;
            if($offset_page<0){
              $offset_page=0;
            }
            query_posts('post_type=news&posts_per_page=15&offset='.$offset_page); 
      ?>
      <?php if ( have_posts() ) : while (have_posts()): the_post(); ?>
           <div class="news">
             <a href="<?php the_permalink(); ?>">
              <?php echo the_post_thumbnail('featured-slide-small'); ?>
             </a>
             <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
             <p><?php do_excerpt(get_the_content(), 50); ?></p>
           </div>  
      <?php endwhile; ?>
    <?php endif; ?>
    <div class="pagination">
     <?php global $wp_query;
          $total = $wp_query->max_num_pages;
          if ( $total > 1 )  {
         if(!$current_page = $_GET['page_nv']){
              $current_page = 1;
         }
         $format = get_home_url() . '/news/?page_nv=%#%';        
        $var=paginate_links(array(
              'base' => '%_%',
              'format' => $format,
              'current' => $current_page,
              'total' => $total,
              'type' => 'array',
              'prev_next' => true,
              'end_size' => 1,
              'mid_size' => 5,
              'prev_text' => __('<'),
              'next_text' => __('>'),
         ));
         $i=0;
         foreach ($var as $key => $value) {
          $i++;
          if($current_page>1&$i==2){        
             print_r('<a class="page-numbers" href="/news">1</a>');            
         }
         elseif ($current_page==2&$i==1) {
           print_r('<a class="page-numbers" href="/news"><</a>');
         }
         else{
         print_r($value);           
          }
         }
       }  
    ?>
    <?php wp_reset_query();                
    ?>
    </div>    

    </div><!-- #content -->
  </div><!-- #primary -->
  
  <?php spacious_sidebar_select(); ?>
  
  <?php do_action( 'spacious_after_body_content' ); ?>

<?php get_footer(); ?>