<?php
/**
 * The template part for displaying navigation.
 *
 * @package spacious
 */
?>

<?php
if( is_archive() || is_home() || is_search() ) {
	/**
	 * Checking WP-PageNaviplugin exist
	 */
	if ( function_exists('wp_pagenavi' ) ) : 
		wp_pagenavi();

	else: 
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) : 
		?>
		<ul class="default-wp-page clearfix">
			<li class="previous"><?php next_posts_link( __( '&laquo; Previous', 'spacious' ) ); ?></li>
			<li class="next"><?php previous_posts_link( __( 'Next &raquo;', 'spacious' ) ); ?></li>
		</ul>
		<?php
		endif;
	endif;
}

if ( is_single() ) {
	if( is_attachment() ) {
	?>
		<ul class="default-wp-page clearfix">
			<li class="previous"><?php previous_image_link( false, __( '&larr; Previous', 'spacious' ) ); ?></li>
			<li class="next"><?php next_image_link( false, __( 'Next &rarr;', 'spacious' ) ); ?></li>
		</ul>
	<?php
	}
	else {
	?>
		<ul class="default-wp-page clearfix">
			<li class="previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( ' ', 'Previous post link', 'spacious' ) . '</span> < Предыдущая статья' ); ?></li>
			<li class="next"><?php next_post_link( '%link', 'Следующая статья > <span class="meta-nav">' . _x( ' ', 'Next post link', 'spacious' ) . '</span>' ); ?></li>
		</ul>
	<?php
	}	
}

?>