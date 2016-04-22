<?php 
/**
 * Theme Footer Section for our theme.
 * 
 * Displays all of the footer section and closing of the #main div.
 *
 * @package ThemeGrill
 * @subpackage Spacious
 * @since Spacious 1.0
 */
?>

    </div><!-- .inner-wrap -->
  </div><!-- #main -->  
  <?php do_action( 'spacious_before_footer' ); ?>
    <footer id="colophon" class="clearfix"> 
      <?php get_sidebar( 'footer' ); ?>
      <div class="partners">
         <h4>
            <?php echo get_the_title(252); ?>   
         </h4>           
          <?php 
                for ($i=0; $i<9;) { 
                  $url_part=get_post_meta(182, 'usefu_link'.$i, true);   
                  $img_id=get_post_meta(182, 'usefu_link_img'.$i, true);                                            
                            $img_url = wp_get_attachment_image_src($img_id, 'featured-slide-small'); 
                            if(isset($img_url[0])){ 
                    ?>
                            <div class="partner">
                              <a href="<?php echo "$url_part";?>">
                                <img src="<?php echo $img_url[0];?>" alt="" class="partners-img">
                              </a>
                            </div>
                    <?php
                            }
                  $i++;                
            }
          ?>
       </div> 
       <div class="contacts">
          <ul>
            <li>Россия,</li>
            <li>Крым,</li>
            <li>г. Симферополь</li>
            <li>+7 (978)-70-73-299 &nbsp; &nbsp; &nbsp; Александр</li>
            <li>Ecodevelopment@mail.ru</li>
          </ul>
         
       </div>
      <div class="footer-socket-wrapper clearfix">
        <div class="inner-wrap">        
          <div class="footer-socket-area">
            <?php do_action( 'spacious_footer_copyright' ); ?>
            <nav class="small-menu" class="clearfix">
              <?php
                if ( has_nav_menu( 'footer' ) ) {                 
                    wp_nav_menu( array( 'theme_location' => 'footer',
                                 'depth'           => -1
                                 ) );
                }
              ?>
              </nav>              
          </div>
        </div>
      </div>      
    </footer>
    <a href="#masthead" id="scroll-up"></a> 
  </div><!-- #page -->
  <?php wp_footer(); ?>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/js/slick-carusel/slick.min.js"></script>   
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ?>/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    <script type="text/javascript">
            $(document).ready(function(){
                $("a[rel=example_group]").fancybox({
                'transitionIn'    : 'none',
                'transitionOut'   : 'none',
                'titlePosition'   : 'over',
                'titleFormat'   : function(title, currentArray, currentIndex, currentOpts) {
                  return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
                }
              });
            $('.autoplay').slick({
                  slidesToShow: 2,
                  /*  slidesToScroll: 1,*/
                  autoplay: true,
                  autoplaySpeed: 3000,                  
                  prevArrow: '',
                  nextArrow: '',
                });
            $('.sitebar-news').slick({
                  slidesToShow: 3,
                 /*  slidesToScroll: 1,*/
                  autoplay: true,
                  autoplaySpeed: 6000,
                  vertical: true,
                  nextArrow: '<button type="button" class="slick-next-arrow"><div class="next-arrow"></div></button>',
                  prevArrow: '<button type="button" class="slick-prev-arrow"><div class="prev-arrow"></div></button>',                 
                });
            $('.single-main-slaider').slick({
            slidesToShow: 1,                  
                  arrows: false,                 
                  asNavFor: '.single-nav-slaider'
           });
           $('.single-nav-slaider').slick({
            slidesToShow: 3,
                /*  slidesToScroll: 1,*/
                  asNavFor: '.single-main-slaider',
                  dots: false,
                  centerMode: true,
                  focusOnSelect: true,                                   
                  centerPadding: '160px',
                  prevArrow: '<button type="button" class="slick-prev">Назад</button>',
                  nextArrow: '<button type="button" class="slick-next">Вперёд</button>',
           });
        });
      
    </script>
</body>
</html>