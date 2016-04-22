<?php

/*
Plugin Name: Custom Press
Plugin URI: http://uniquemethod.com/projects/custom-press
Description: A full featured all-in-one solution for easily creating custom post types and taxonomies.
Version: 1.0
Author: Unique Method
Author URI: http://uniquemethod.com
*/



new ui_for_post_types();



class ui_for_post_types {
	
	
	
	var $post_types;
	var $taxonomies;
	
	
	
	function ui_for_post_types() {
	
		// collect the data, if any
		$db_post_types = get_option('post_types');
		$db_taxonomies = get_option('taxonomies');
		
		// if not an array make it an array
		$this->post_types = (is_string($db_post_types)) ? unserialize($db_post_types) : $db_post_types;
		$this->taxonomies = (is_string($db_taxonomies)) ? unserialize($db_taxonomies) : $db_taxonomies;
		
		// if nothing create empty arrays to work with
		if(!is_array($this->post_types)) $this->post_types = array();
		if(!is_array($this->taxonomies)) $this->taxonomies = array();
		
		if (is_admin()) {
			
			// on activation
			register_activation_hook(__FILE__, array($this, 'activate_plugin'));
			
			// on deactivation
			register_deactivation_hook(__FILE__, array($this, 'deactivate_plugin'));
			
			// add settings link to plugin page
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'plugin_actions'));
			
			// add plugin menu options
			add_action('admin_menu',  array($this, 'plugin_menu'));
			
		}
		
		// register custom post types and taxonomies
		add_action('init', array($this, 'initalize_plugin'), 0);
		
	}
	
	
	
	// on activation
	function activate_plugin() {
		flush_rewrite_rules(); // add rules for custom post types
	}
	
	
	
	// on deactivation
	function deactivate_plugin() {
		flush_rewrite_rules(); // remove rules for custom post types
	}
	
	
	
	// add settings link to plugin page
	function plugin_actions($links) {
		// before deativate link
		//$links[] = '<a href="themes.php?page=post-types">' . __('Settings') . '</a>';
		//return $links;
		
		// after deactive link
		array_unshift($links, '<a href="themes.php?page=post-types">' . __('Settings') . '</a>');
		return $links;
	}
	
	
	
	// add plugin menu options
	function plugin_menu() {
		add_theme_page('Post Types', 'Post Types', 'manage_options', 'post-types', array($this, 'manage_post_types'));
		add_theme_page('Taxonomies', 'Taxonomies', 'manage_options', 'taxonomies', array($this, 'manage_taxonomies'));
	}
	
	
	
	// register post types and taxonomies
	function initalize_plugin() {
		$this->handle_action_call();
		
		// register custom post types
		foreach ( $this->post_types as $post_type ) {
		
			 // menu_position must be typecasted to int or be null
			if ( (int) $post_type['args']['menu_position'] > 0 )
				$post_type['args']['menu_position'] = (int) $post_type['args']['menu_position'];
			else
				$post_type['args']['menu_position'] = null;
			
			// make sure that taxonomies is an array
			$post_type['args']['taxonomies'] = (is_array($post_type['args']['taxonomies'])) ? $post_type['args']['taxonomies'] : array();
			
			register_post_type($post_type['name'], $post_type['args']);
		}
		
		// register custom taxonomies
		foreach ( $this->taxonomies as $taxonomy ) {
			register_taxonomy($taxonomy['name'], $taxonomy['object_type'], $taxonomy['args']);
		}
		
		if ( isset($_POST['action']) || isset($_GET['action']) ) flush_rewrite_rules();
		
	}
	
	
	
	// handles add, edit, and delete actions
	function handle_action_call() {
	
		if ( isset($_POST['action']) ) {
		
			switch ( $_POST['action'] ) {
				case 'add_post_type':
					$post_type = $this->register_post_type_array($_POST);
					if ( is_array($post_type) ) {
						$this->post_types[] = $post_type;
						update_option('post_types', serialize($this->post_types));
					}
					wp_redirect($_POST['_wp_http_referer']);
					break;
					
				case 'edit_post_type':
					$post_type = $this->register_post_type_array($_POST);
					if(is_array($post_type)) {
						$this->post_types[$_POST['post_type_index']] = $post_type;
						update_option('post_types', serialize($this->post_types));
					}
					//wp_redirect($_POST['_wp_http_referer']);
					wp_redirect('?page='. $_GET['page']);
					break;
					
				case 'add_taxonomy':
					$taxonomy = $this->register_taxonomy_array($_POST);
					if(is_array($taxonomy)) {
						$this->taxonomies[] = $taxonomy;
						update_option('taxonomies', serialize($this->taxonomies));
					}
					wp_redirect($_POST['_wp_http_referer']);
					break;
					
				case 'edit_taxonomy':
					$taxonomy = $this->register_taxonomy_array($_POST);
					if(is_array($taxonomy)) {
						$this->taxonomies[$_POST['taxonomy_index']] = $taxonomy;
						update_option('taxonomies', serialize($this->taxonomies));
					}
					//wp_redirect($_POST['_wp_http_referer']);
					wp_redirect('?page='. $_GET['page']);
					break;
			}
			
		}
		
		if ( isset($_GET['action']) ) {
		
			switch ( $_GET['action'] ) {
				case 'delete_post_type':
					unset($this->post_types[$_GET['i']]);
					update_option('post_types', serialize($this->post_types));
					wp_redirect('?page='. $_GET['page']);
					break;
					
				case 'delete_taxonomy':
					unset($this->taxonomies[$_GET['i']]);
					update_option('taxonomies', serialize($this->taxonomies));
					wp_redirect('?page='. $_GET['page']);
					break;
			}
			
		}
		
	}
	
	
	
	function manage_post_types() {
	
		// default values
		
		$post_type_index			= '';
		
		$btn_text					= 'Add New Post Type';
		$form_action				= 'add_post_type';
		
		$item_name 					= '';
		$item_label 				= '';
		$item_singular_label 		= '';
		$item_description			= '';
		
		$item_publicly_queryable	= ' checked="checked"';
		$item_exclude_from_search	= ' checked="checked"';
		$item_show_ui				= ' checked="checked"';
		
		$item_menu_position			= 20;
		$menu_position_items		= array('2 - Below Dashboard', '5 - Below Posts', '10 - Below Media', '15 - Below Links', '20 - Below Pages', '25 - Below Comments', '60 - Below First Separator', '65 - Below Plugins', '70 - Below Users', '75 - Below Tools', '80 - Below Settings', '100 - Below Second Separator');
		
		$item_menu_icon				= '';
		$item_capability_type		= 'post';
		$item_hierarchical			= '';
		
		$item_support				= array('title' => 'Title', 'editor' => 'Editor', 'author' => 'Author', 'thumbnail' => 'Thumbnail', 'excerpt' => 'Excerpt', 'trackbacks' => 'Trackbacks', 'custom-fields' => 'Custom Fields', 'comments' => 'Comments', 'revisions' => 'Revisions', 'page-attributes' => 'Page Attributes');
		$support_selection			= array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes');
		
		$item_taxonomy	= array('category' => 'Category', 'post_tag' => 'Post Tags');
		$taxonomy_selection			= array();
		
		$item_rewrite				= ' checked="checked"';
		$item_slug					= '';
		$item_with_front			= '';
		
		$item_query_var				= ' checked="checked"';
		$item_can_export			= ' checked="checked"';
		$item_show_in_nav_menus		= ' checked="checked"';
		
		
		// if editing a post type
		
		if(isset($_GET['action'])) {
			switch($_GET['action']) {
				case 'edit_post_type':
					$post_type_index		= $_GET['i'];
					$item 					= $this->post_types[$post_type_index];
					
					$btn_text				= 'Update Post Type';
					$form_action			= 'edit_post_type';

					$item_name				= $item['name'];
					$item_label				= $item['args']['labels']['name'];
					$item_singular_label	= $item['args']['labels']['singular_name'];
					$item_description		= $item['args']['description'];
					
					if ( !$item['args']['publicly_queryable'] ) $item_publicly_queryable = '';
					if ( !$item['args']['exclude_from_search'] ) $item_exclude_from_search = '';
					if ( !$item['args']['show_ui'] ) $item_show_ui = '';
					
					$item_menu_position = $item['args']['menu_position'];
					$item_menu_icon = $item['args']['menu_icon'];
					$item_capability_type = $item['args']['capability_type'];
					
					if ( $item['args']['hierarchical'] ) $item_hierarchical = ' checked="checked"';
					
					$support_selection = $item['args']['supports'];
					if ( !is_array($support_selection) ) $support_selection = array();
					
					$taxonomy_selection = $item['args']['taxonomies'];
					if ( !is_array($taxonomy_selection) ) $taxonomy_selection = array();
					
					if ( !$item['args']['rewrite'] ) $item_rewrite = '';
					$item_slug = $item['args']['rewrite']['slug'];
					if ( $item['args']['rewrite']['with_front'] ) $item_with_front = ' checked="checked"';
					
					if ( !$item['args']['query_var'] ) $item_query_var = '';
					if ( !$item['args']['can_export'] ) $item_can_export = '';
					if ( !$item['args']['show_in_nav_menus'] ) $item_show_in_nav_menus = '';
					
					break;
			}
		}
		
		
		$menu_position_list = implode(', ', $menu_position_items);
		
		$support_options = '';
		foreach($item_support as $support_item_key => $support_item_value) {
			$support_options .= (in_array($support_item_key, $support_selection)) ? '<option value="'. $support_item_key .'" selected="selected">'. $support_item_value .'</option>' : '<option value="'. $support_item_key .'">'. $support_item_value .'</option>' ;
		}
		
		$taxonomy_options = '';
		foreach ($item_taxonomy as $taxonomy_item_key => $taxonomy_item_value) {
			$taxonomy_options .= (in_array($taxonomy_item_key, $taxonomy_selection)) ? '<option value="'. $taxonomy_item_key .'" selected="selected">'. $taxonomy_item_value .'</option>' : '<option value="'. $taxonomy_item_key .'">'. $taxonomy_item_value .'</option>' ;
		}
		
		
		?>
		
		<div class="wrap">
		
			<?php screen_icon(); ?>
			<h2><?php _e('Post Types'); if ( !empty($_REQUEST['s']) ) printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html( stripslashes($_REQUEST['s']) ) ); ?></h2>
			
			<?php //<div id="message" class="updated"><p>Post type updated.</p></div> ?>
			
			<div id="ajax-response"></div>
			
			<form method="get" action="" class="search-form">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<p class="search-box">
					<label for="tag-search-input" class="screen-reader-text">Search Categories:</label>
					<input type="text" id="tag-search-input" name="s" value="<?php echo $_GET['s']; ?>" />
					<input type="submit" class="button" value="Search Post Types" />
				</p>
			</form>
			
			<br class="clear" />
			
			<div id="col-container">
			
				<div id="col-right">
					<div class="col-wrap">
					
						<div class="tablenav">
							<div class="alignleft actions"></div>
							<br class="clear" />
						</div>
						
						<table cellspacing="0" class="widefat tag fixed">
						
							<thead>
								<tr>
									<th scope="col" class="manage-column column-cb check-column" id="cb" scope="col"></th>
									<th scope="col" class="manage-column column-name" id="name" scope="col">Name</th>
									<th scope="col" class="manage-column column-description" id="description" scope="col">Description</th>
									<th scope="col" class="manage-column column-slug" id="slug" scope="col">Slug</th>
									<th scope="col" class="manage-column column-posts num" id="posts" scope="col">Posts</th>
								</tr>
							</thead>
							
							<tfoot>
								<tr>
									<th scope="col" class="manage-column column-cb check-column" id="cb" scope="col"></th>
									<th scope="col" class="manage-column column-name" id="name" scope="col">Name</th>
									<th scope="col" class="manage-column column-description" id="description" scope="col">Description</th>
									<th scope="col" class="manage-column column-slug" id="slug" scope="col">Slug</th>
									<th scope="col" class="manage-column column-posts num" id="posts" scope="col">Posts</th>
								</tr>
							</tfoot>
							
							<tbody id="the-list" class="list:tag">
								<?php
								if ( count($this->post_types) > 0 ) {
									foreach ( $this->post_types as $index => $post_type ) {
									
										if ( isset($_GET['s']) && strlen($_GET['s']) > 0 ) {
											$search_string = strtolower($_GET['s']);
											
											// the # is a "hack" to prevent 0 in response
											if ( !strpos(strtolower('#'. $post_type['args']['labels']['name']), $search_string) && !strpos(strtolower('#'. $post_type['args']['description']), $search_string) ) continue;
										}
										
										$num_posts = (array) wp_count_posts($post_type['args']['labels']['name'], 'readable');
										$total_posts = array_sum((array) $num_posts);
										
										$row_class = ( $row_class == '' ? ' class="alternate"' : '' );
										?>
										<tr<?php echo $row_class; ?>>
											<th class="check-column" scope="row"></th>
											<td class="name column-name">
												<strong><a class="row-title" href="?page=<?php echo $_GET['page']; ?>&amp;action=edit_post_type&amp;i=<?php echo $index; ?>"><?php echo $post_type['args']['labels']['name']; ?></a></strong><br />
												<div class="row-actions">
													<span class="embed"><a href="">Embed&nbsp;Code</a> | </span>
													<span class="edit"><a href="?page=<?php echo $_GET['page']; ?>&amp;action=edit_post_type&amp;i=<?php echo $index; ?>">Edit</a> | </span>
													<span class="trash"><a class="submitdelete" href="?page=<?php echo $_GET['page']; ?>&amp;action=delete_post_type&amp;i=<?php echo $index; ?>">Delete</a></span>
												</div></td>
											<td class="description column-description">
												<?php echo $post_type['args']['description']; ?></td>
											<td class="slug column-slug">
												<?php echo ( $post_type['args']['rewrite']['slug'] ) ? $post_type['args']['rewrite']['slug'] : $post_type['name']; ?></td>
											<td class="posts column-posts num">
												<a href="edit.php?post_type=<?php echo $post_type['name']; ?>"><?php echo wp_count_posts($post_type['name'])->publish; ?></a></td>
										</tr>
										<tr class="custom-code"><td colspan="5">
												<textarea style="width:100%;height:150px;" onclick="this.select();"><?php echo $this->build_post_type_code($post_type); ?></textarea>
												<p>Copy the code above into the <code>functions.php</code> file to use with a theme or use it directly with a plugin.</p>
											</td></tr>
										<?php
									}
								} else {
									?><tr class="no-items"><td class="colspanchange" colspan="5">No post types found.</td></tr><?php
								}
								?>
							</tbody>
							
						</table>
						
						<br class="clear" />
						
						<div class="form-wrap">
							<p><strong>Note:</strong><br />
							Deleting a post type does not delete the content in that post type. You can recreate post types and the content will still exist.</p>
						</div>
						
					</div><!-- .col-wrap -->
				</div><!-- #col-right -->
				
				<div id="col-left">
					<div class="col-wrap">
						<div class="form-wrap">
						
							<form class="validate" action="" method="post" id="addtag">
								
								<input type="hidden" name="action" value="<?php echo $form_action; ?>" />
								<input type="hidden" name="post_type_index" value="<?php echo $post_type_index; ?>" />
								<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $_SERVER['QUERY_STRING']; ?>" />
								<input type="hidden" name="public" value="1" />
								
								<h3><?php echo $btn_text; ?></h3>
								
								<div class="form-field">
									<label for="post-name">Name</label>
									<input type="text" aria-required="true" maxlength="20" size="40" id="post-name" name="name" value="<?php echo $item_name; ?>">
									<p>The name of the post type. You should use a short 'namespace', all lowercase letters, underscores for spaces, and less than 20 characters. (e.g. <code>um_project</code>)</p>
								</div>
								
								<div class="form-field">
									<label for="label">Label</label>
									<input type="text" aria-required="true" size="40" id="label" name="label" value="<?php echo $item_label; ?>">
									<p>The label for the post type, usually plural. (e.g. <code>Projects</code>)</p>
								</div>
								
								<div class="form-field">
									<label for="singular_label">Singular Label</label>
									<input type="text" aria-required="true" size="40" id="singular_label" name="singular_label" value="<?php echo $item_singular_label; ?>">
									<p>The label for one object of this post type. (e.g. <code>Project</code>)</p>
								</div>
								
								<div class="form-field">
									<label for="post-description">Description</label>
									<textarea rows="5" name="description" id="post-description"><?php echo $item_description; ?></textarea>
									<p>A short descriptive summary of what the post type is. (e.g. <code>Client work and personal projects.</code>)</p>
								</div>
								
								<p class="submit">
									<input type="submit" class="button-primary" value="<?php echo $btn_text; ?>" />
									<input type="submit" class="advanced" value="<?php _e('Advanced Settings') ?>" />
									<?php if(isset($_GET['action'])) { ?><input type="submit" class="cancel-update" style="float:right;" value="Cancel Update" /><?php } ?></p>
								
								<script>
								jQuery(function(){
									jQuery('#advanced-settings').hide();
									jQuery('.advanced').click(function(){
										jQuery('#advanced-settings').slideToggle('fast');
										return false;
									});
									jQuery('.custom-code').hide();
									jQuery('.embed').click(function(){
										jQuery(this).closest('tr').next('tr').toggle();
										return false;
									});
									jQuery('.cancel-update').click(function(){
										window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?page=post-types';
										return false;
									});
								});
								</script>
								
								<div id="advanced-settings">
								
									<label for="publicly_queryable"><input type="checkbox" value="1" id="publicly_queryable" name="publicly_queryable"<?php echo $item_publicly_queryable; ?> /> Publicly Queryable</label>
									<div class="form-field">
										<p>Whether post type queries can be performed from the front end.</p>
									</div>
									
									<label for="exclude_from_search"><input type="checkbox" value="1" id="exclude_from_search" name="exclude_from_search"<?php echo $item_exclude_from_search; ?> /> Exclude From Search</label>
									<div class="form-field">
										<p>Whether to exclude posts with this post type from search results.</p>
									</div>
									
									<label for="show_ui"><input type="checkbox" value="1" id="show_ui" name="show_ui"<?php echo $item_show_ui; ?> /> Show UI</label>
									<div class="form-field">
										<p>Whether to generate a default UI for managing this post type.</p>
									</div>
									
									<div class="form-field">
										<label for="menu_position">Menu Position</label>
										<input type="text" aria-required="true" size="40" id="menu_position" name="menu_position" value="<?php echo $item_menu_position; ?>">
										<p>The position in the admin menu the post type should appear.
											<code><?php echo $menu_position_list; ?></code></p>
									</div>
									
									<div class="form-field">
										<label for="menu_icon">Menu Icon</label>
										<input type="text" aria-required="true" size="40" id="menu_icon" name="menu_icon" value="<?php echo $item_menu_icon; ?>" />
										<p>The url to the icon to be used for this menu.<br />
											<code>(Defaults to the posts icon)</code></p>
									</div>
									
									<div class="form-field">
										<label for="capability_type">Capability Type</label>
										<input type="text" aria-required="true" size="40" id="capability_type" name="capability_type" value="<?php echo $item_capability_type; ?>" />
										<p>The post type to use for checking read, edit, and delete capabilities.</p>
									</div>
									
									<label for="hierarchical"><input type="checkbox" value="1" id="hierarchical" name="hierarchical"<?php echo $item_hierarchical; ?> /> Hierarchical</label>
									<div class="form-field">
										<p>Whether a parent can be specified.</code></p>
									</div>
									
									<div class="form-field">
										<label for="supports[]">Supports</label>
										<select class="postform" style="height: 155px; width: 250px;" id="supports[]" name="supports[]" multiple="multiple" size="5">
											<?php echo $support_options; ?>
										</select>
										<p>The standard items you want to add to the post type.</p>
									</div>
									
									<div class="form-field">
										<label for="taxonomies[]">Built-in Taxonomies</label>
										<select class="postform" style="height: 40px; width: 250px;" id="taxonomies[]" name="taxonomies[]" multiple="multiple" size="2">
											<?php echo $taxonomy_options; ?>
										</select>
										<p>The standard taxonomies you want to add to the post type.</p>
									</div>
									
									<label for="rewrite"><input type="checkbox" value="1" id="rewrite" name="rewrite"<?php echo $item_rewrite; ?> /> Rewrite</label>
									<div class="form-field">
										<p>Rewrite permalinks with this format.</p>
									</div>
									
									<div class="form-field">
										<label for="post-slug">Slug</label>
										<input type="text" aria-required="true" size="40" id="post-slug" name="slug" value="<?php echo $item_slug; ?>" />
										<p>The URL-friendly version of the name. Rewrite must be true to enter a slug.<br />
											<code>(Defaults to post type's name)</code></p>
									</div>
									
									<label for="with_front"><input type="checkbox" value="1" id="with_front" name="with_front"<?php echo $item_with_front; ?> /> Rewrite Front</label>
									<div class="form-field">
										<p>Allowing permalinks to be prepended with front base. A slug must be entered to set as true<br />
											(e.g. if true <code>/projects/web-design</code> or if false<code>/web-design</code>)</p>
									</div>
									
									<label for="query_var"><input type="checkbox" value="1" id="query_var" name="query_var"<?php echo $item_query_var; ?> /> Query Var</label>
									<div class="form-field">
										<p>Use the post type name for the query var, false to prevent queries.</p>
									</div>
									
									<label for="can_export"><input type="checkbox" value="1" id="can_export" name="can_export"<?php echo $item_can_export; ?> /> Can Export</label>
									<div class="form-field">
										<p>Can this post type be exported.</p>
									</div>
									
									<label for="show_in_nav_menus"><input type="checkbox" value="1" id="show_in_nav_menus" name="show_in_nav_menus"<?php echo $item_show_in_nav_menus; ?> /> Show in Nav Menus</label>
									<div class="form-field">
										<p>Whether post type is available for selection in navigation menus.</p>
									</div>
									
									<p class="submit"><input type="submit" class="button-primary" value="<?php echo $btn_text; ?>" /></p>
									
								</div>
								
							</form>
							
						</div><!-- .form-wrap -->
					</div><!-- .col-wrap -->
				</div><!-- #col-left -->
				
			</div><!-- #col-container -->
			
		</div>
		
		<?php
		
	}
	
	
	
	function build_post_type_code($post_values) {
	
		if ( $this->get_boolean($post_values['args']['rewrite']) ) {
			$rewrite = 'true';
			if ( strlen($post_values['args']['rewrite']['slug']) > 0 ) {
				$rewrite = 'array(\'slug\' => '.$post_values['args']['rewrite']['slug'].', \'with_front\' => '.$this->echo_boolean($post_values['args']['rewrite']['with_front']).', )';
			}
		}
		else {
			$rewrite = 'false';
		}
		
		$menu_icon = ( $post_values['args']['menu_icon'] ) ? '\''.$post_values['args']['menu_icon'].'\'' : 'null';
		
		if ( is_array($post_values['args']['supports']) ) {
			foreach ( $post_values['args']['supports'] as $supports ) {
				$supports_array .= '\''.$supports.'\', ';
			}
		}
		
		if ( is_array($post_values['args']['taxonomies']) ) {
			foreach ( $post_values['args']['taxonomies'] as $taxonomies ) {
				$taxonomies_array .= '\''.$taxonomies.'\', ';
			}
		}
		
$code .= '$labels = array(
	\'name\' => _x( \''.$post_values['args']['labels']['name'].'\', \'post type general name\' ),
	\'singular_name\' => _x( \''.$post_values['args']['labels']['singular_name'].'\', \'post type singular name\' ),
	\'add_new\' => __( \'Add New '.$post_values['args']['labels']['singular_name'].'\' ),
	\'add_new_item\' => __( \'Add New '.$post_values['args']['labels']['singular_name'].'\' ),
	\'edit_item\' => __( \'Edit '.$post_values['args']['labels']['singular_name'].'\' ),
	\'new_item\' => __( \'New '.$post_values['args']['labels']['singular_name'].'\' ),
	\'view_item\' => __( \'View '.$post_values['args']['labels']['singular_name'].'\' ),
	\'search_items\' => __( \'Search '.$post_values['args']['labels']['name'].'\' ),
	\'not_found\' => __( \'No '.strtolower($post_values['args']['labels']['name']).' found.\' ),
	\'not_found_in_trash\' => __( \'No '.strtolower($post_values['args']['labels']['name']).' found in Trash.\' ),
	\'menu_name\' => __( \''.$post_values['args']['labels']['name'].'\' ),
);
$args = array(
	\'labels\' => $labels,
	\'description\' => \''.$post_values['args']['description'].'\',
	\'public\' => true,
	\'publicly_queryable\' => '.$this->echo_boolean($post_values['args']['publicly_queryable']).',
	\'exclude_from_search\' => '.$this->echo_boolean($post_values['args']['exclude_from_search']).',
	\'show_ui\' => '.$this->echo_boolean($post_values['args']['show_ui']).',
	\'menu_position\' => '.$post_values['args']['menu_position'].',
	\'menu_icon\' => '.$menu_icon.',
	\'capability_type\' => '.$post_values['args']['capability_type'].',
	\'hierarchical\' => '.$this->echo_boolean($post_values['args']['hierarchical']).',
	\'supports\' => array('.$supports_array.'),
	\'taxonomies\' => array('.$taxonomies_array.'),
	\'rewrite\' => '.$rewrite.',
	\'query_var\' => '.$this->echo_boolean($post_values['args']['query_var']).',
	\'can_export\' => '.$this->echo_boolean($post_values['args']['can_export']).',
	\'show_in_nav_menus\' => '.$this->echo_boolean($post_values['args']['show_in_nav_menus']).',
);
register_post_type(\''.$post_values['name'].'\', $args);';
		
		return $code;
		
	}
	
	
	
	function register_post_type_array($post_values) {
	
		$labels = array(
			'name'						=> _x($post_values['label'], 'post type general name'),
			'singular_name'				=> _x($post_values['singular_label'], 'post type singular name'),
			'add_new'					=> _x('Add New', $post_values['singular_label']),
			'add_new_item'				=> __('Add New '.$post_values['singular_label']),
			'edit_item'					=> __('Edit '.$post_values['singular_label']),
			'new_item'					=> __('New '.$post_values['singular_label']),
			'view_item'					=> __('View '.$post_values['singular_label']),
			'search_items'				=> __('Search '.$post_values['label']),
			'not_found'					=> __('No '.strtolower($post_values['label'].' found.')),
			'not_found_in_trash'		=> __('No '.strtolower($post_values['label']).' found in Trash.'), 
			//'parent_item_colon'		=> null,
			'menu_name'					=> __($post_values['label']),
		);
		
		$menu_icon = ( $post_values['menu_icon'] ) ? $post_values['menu_icon'] : null;
		
		if ( $this->get_boolean($post_values['rewrite']) ) {
			$rewrite = true;
			if ( strlen($post_values['slug']) > 0 ) {
				$rewrite = array('slug' => $post_values['slug'], 'with_front' => $this->get_boolean($post_values['with_front']));
			}
		}
		else {
			$rewrite = false;
		}
		
		$taxonomies_array = ( is_array($post_values['taxonomies']) ) ? $post_values['taxonomies'] : array() ;
		
		$args = array(
			'labels'					=> $labels,
			'description'				=> $post_values['description'],
			'public'					=> true,
			'publicly_queryable'		=> $this->get_boolean($post_values['publicly_queryable']),
			'exclude_from_search'		=> $this->get_boolean($post_values['exclude_from_search']),
			'show_ui'					=> $this->get_boolean($post_values['show_ui']),
			//'show_in_menu'			=> null,
			'menu_position'				=> $post_values['menu_position'],
			'menu_icon'					=> $menu_icon,
			'capability_type'			=> $post_values['capability_type'],
			//'capabilities'			=> null,
			//'map_meta_cap'			=> false,
			'hierarchical'				=> $this->get_boolean($post_values['hierarchical']),
			'supports'					=> $post_values['supports'],
			//'register_meta_box_cb'	=> null,
			'taxonomies'				=> $taxonomies_array,
			//'permalink_epmask			=> null,
			//'has_archive				=> false,
			'rewrite'					=> $rewrite,
			'query_var'					=> $this->get_boolean($post_values['query_var']),
			'can_export'				=> $this->get_boolean($post_values['can_export']),
			'show_in_nav_menus'			=> $this->get_boolean($post_values['show_in_nav_menus']),
			//'_builtin'				=> false,
			//'_edit_link'				=> null,
		);
		
		return array('name' => $post_values['name'], 'args' => $args);
		
	}
	
	
	
	function manage_taxonomies() {
	
		$taxonomy_index 			= '';
		
		$btn_text 					= 'Add New Taxonomy';
		$form_action 				= 'add_taxonomy';
		
		$item_name					= '';
		$item_label					= '';
		$item_singlular_label 		= '';
		$select_items				= array();
		
		$item_show_in_nav_menus		= ' checked="checked"';
		$item_show_ui				= ' checked="checked"';
		$item_show_tagcloud			= ' checked="checked"';
		$item_hierarchical			= ' checked="checked"';
		
		$item_rewrite				= ' checked="checked"';
		$item_slug					= '';
		$item_with_front			= '';
		
		$item_query_var				= ' checked="checked"';
		
		
		// if editing a taxonomy
		
		if(isset($_GET['action'])) {
			switch($_GET['action']) {
				case 'edit_taxonomy':
					$taxonomy_index				= $_GET['i'];
					$item						= $this->taxonomies[$taxonomy_index];
					
					$btn_text					= 'Update Taxonomy';
					$form_action				= 'edit_taxonomy';
					
					$item_name					= $item['name'];
					$item_label					= $item['args']['labels']['name'];
					$item_singular_label		= $item['args']['labels']['singular_name'];
					
					$select_items = $item['object_type'];
					if ( !is_array($select_items) ) $select_items = array();
					
					if ( !$item['args']['show_in_nav_menus'] ) $item_show_in_nav_menus = '';
					if ( !$item['args']['show_ui'] ) $item_show_ui = '';
					if ( !$item['args']['show_tagcloud'] ) $item_show_tagcloud = '';
					if ( !$item['args']['hierarchical'] ) $item_hierarchical = '';
					
					if ( !$item['args']['rewrite'] ) $item_rewrite = '';
					$item_slug = $item['args']['rewrite']['slug'];
					if ( $item['args']['rewrite']['with_front'] ) $item_with_front = ' checked="checked"';
					
					if ( !$item['args']['query_var'] ) $item_query_var = '';
					
					break;
			}
		}
		
		
		$options = '';
		
		if ( count($this->post_types) > 0 ) {
			
			foreach ( $this->post_types as $index => $post_type ) {
				$options .= ( in_array($post_type['name'], $select_items) ) ? '<option value="'. $post_type['name'] .'" selected="selected">'. $post_type['args']['labels']['name'] .'</option>' : '<option value="'. $post_type['name'] .'">'. $post_type['args']['labels']['name'] .'</option>' ;
			}
			
		}
		
		$builtin_post_types = array('post' => 'Posts', 'page' => 'Pages', 'mediapage' => 'Media', 'attachment' => 'Attachments', 'revision' => 'Revisions', 'nav_menu_item' => 'Nav Menu Items');
		
		foreach ($builtin_post_types as $index => $post_type) {
			$options .= ( in_array($index, $select_items) ) ? '<option value="'. $index .'" selected="selected">'. $post_type .'</option>' : '<option value="'. $index .'">'. $post_type .'</option>' ;
		}
		
		
		?>
		
		<div class="wrap">
		
			<?php screen_icon(); ?>
			<h2><?php _e('Taxonomies');
				if ( !empty($_REQUEST['s']) ) printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html( stripslashes($_REQUEST['s']) ) ); ?></h2>
			
			<?php //<div id="message" class="updated"><p>Taxonomy updated.</p></div> ?>
			
			<div id="ajax-response"></div>
			
			<form method="get" action="" class="search-form">
				
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				
				<p class="search-box">
					<label for="tag-search-input" class="screen-reader-text">Search Taxonomies:</label>
					<input type="text" id="tag-search-input" name="s" value="<?php echo $_GET['s']; ?>" />
					<input type="submit" class="button" value="Search Taxonomies" />
				</p>
				
			</form>
			
			<br class="clear" />
			
			<div id="col-container">
			
				<div id="col-right">
					<div class="col-wrap">
						
						<div class="tablenav">
							<div class="alignleft actions"></div>
							<br class="clear" />
						</div>
						
						<table class="wp-list-table widefat fixed tags" cellspacing="0">
							<thead>
								<tr>
									<th scope="col" class="manage-column column-cb check-column" id="cb" scope="col"></th>
									<th scope="col" class="manage-column column-name" id="name" scope="col">Name</th>
									<th scope="col" class="manage-column column-description" id="description" scope="col">Post&nbsp;Types</th>
									<th scope="col" class="manage-column column-slug" id="slug" scope="col">Slug</th>
									<th scope="col" class="manage-column column-posts num" id="posts" scope="col">Posts</th>
								</tr>
							</thead>
							
							<tfoot>
								<tr>
									<th scope="col" class="manage-column column-cb check-column" id="cb" scope="col"></th>
									<th scope="col" class="manage-column column-name" id="name" scope="col">Name</th>
									<th scope="col" class="manage-column column-description" id="description" scope="col">Post&nbsp;Types</th>
									<th scope="col" class="manage-column column-slug" id="slug" scope="col">Slug</th>
									<th scope="col" class="manage-column column-posts num" id="posts" scope="col">Posts</th>
								</tr>
							</tfoot>
							
							<tbody id="the-list" class="list:tag">
								<?php
								if ( count($this->taxonomies) > 0 ) {
									foreach ( $this->taxonomies as $index => $taxonomy ) {
									
										if ( isset($_GET['s']) && strlen($_GET['s'] ) > 0) {
											$search_string = strtolower($_GET['s']);
											
											// the # is a "hack" to prevent 0 in response
											if ( !strpos(strtolower('#'. $taxonomy['args']['labels']['name']), $search_string) && !strpos(strtolower('#'. $taxonomy['object_type']), $search_string) ) continue;
										}
										
										$row_class = ( $row_class == '' ? ' class="alternate"' : '' );
										?>
										<tr<?php echo $row_class; ?>>
											<th class="check-column" scope="row"></th>
											<td class="name column-name"><strong><a class="row-title" href="?page=<?php echo $_GET['page']; ?>&amp;action=edit_taxonomy&amp;i=<?php echo $index; ?>"><?php echo $taxonomy['args']['labels']['name']; ?></a></strong><br />
												<div class="row-actions">
													<span class="embed"><a href="">Embed&nbsp;Code</a> | </span>
													<span class="edit"><a href="?page=<?php echo $_GET['page']; ?>&amp;action=edit_taxonomy&amp;i=<?php echo $index; ?>">Edit</a> | </span>
													<span class="trash"><a class="submitdelete" href="?page=<?php echo $_GET['page']; ?>&amp;action=delete_taxonomy&amp;i=<?php echo $index; ?>">Delete</a></span>
												</div></td>
											<td class="description column-description">
												<?php echo ( is_array($taxonomy['object_type']) ) ? implode(', ', $taxonomy['object_type']) : $taxonomy['object_type']; ?></td>
											<td class="slug column-slug">
												<?php echo ( $taxonomy['args']['rewrite']['slug'] ) ? $taxonomy['args']['rewrite']['slug'] : $taxonomy['name']; ?></td>
											<td class="posts column-posts num">
												<?php echo wp_count_terms($taxonomy['name']); ?></td>
										</tr>
										<tr class="custom-code"><td colspan="5">
												<textarea style="width:100%;height:150px;" onclick="this.select();"><?php echo $this->build_taxonomy_code($taxonomy); ?></textarea>
												<p>Copy the code above into the <code>functions.php</code> file to use with a theme or use it directly with a plugin.</p>
											</td></tr>
										<?php
									}
								} else {
									?><tr class="no-items"><td class="colspanchange" colspan="4">No taxonomies found.</td></tr><?php
								}
								?>
							</tbody>
						</table>
						
						<br class="clear" />
						
					</div><!-- .col-wrap -->
				</div><!-- #col-right -->
				
				<div id="col-left">
					<div class="col-wrap">
						<div class="form-wrap">
							
							<form class="validate" action="" method="post" id="addtag">
								
								<input name="action" value="<?php echo $form_action; ?>" type="hidden" />
								<input name="taxonomy_index" value="<?php echo $taxonomy_index; ?>" type="hidden" />
								<input name="_wp_http_referer" value="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $_SERVER['QUERY_STRING']; ?>" type="hidden" />
								
								<h3><?php echo $btn_text; ?></h3>
								
								<div class="form-field">
									<label for="taxonomy-name">Name</label>
									<input type="text" aria-required="true" size="40" id="taxonomy-name" name="name" value="<?php echo $item_name; ?>" />
									<p>The name of the taxonomy. You should use a short 'namespace', all lowercase letters, and underscores for spaces. (e.g. <code>um_project_category</code>)</p>
								</div>
								
								<div class="form-field">
									<label for="label">Label</label>
									<input type="text" aria-required="true" size="40" id="label" name="label" value="<?php echo $item_label; ?>" />
									<p>The label for the taxonomy, usually plural. (e.g. <code>Project Categories</code>)</p>
								</div>
								
								<div class="form-field">
									<label for="singular_label">Singular Label</label>
									<input type="text" aria-required="true" size="40" id="singular_label" name="singular_label" value="<?php echo $item_singular_label; ?>" />
									<p>The name for one object of this taxonomy. (e.g. <code>Project Category</code>)</p>
								</div>
								
								<div class="form-field">
									<label for="post_type_name[]">Post Types</label>
									<select id="post_type_name[]" class="postform" style="height: 120px; width: 250px;" name="post_type_name[]" multiple="multiple" size="5">
										<?php echo $options; ?>
									</select>
									<p>Select the custom post types this taxonomy is used for.</p>
								</div>
								
								<p class="submit">
									<input type="submit" class="button-primary" value="<?php echo $btn_text; ?>" />
									<input type="submit" class="advanced" value="<?php _e('Advanced Settings') ?>" />
									<?php if(isset($_GET['action'])) { ?><input type="submit" class="cancel-update" style="float:right;" value="Cancel Update" /><?php } ?></p>
								
								<script>
								jQuery(function(){
									jQuery('#advanced-settings').hide();
									jQuery('.advanced').click(function(){
										jQuery('#advanced-settings').slideToggle('fast');
										return false;
									});
									jQuery('.custom-code').hide();
									jQuery('.embed').click(function(){
										jQuery(this).closest('tr').next('tr').toggle();
										return false;
									});
									jQuery('.cancel-update').click(function(){
										window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?page=taxonomies';
										return false;
									});
								});
								</script>
								
								<div id="advanced-settings">
								
									<label for="show_in_nav_menus"><input type="checkbox" value="1" id="show_in_nav_menus" name="show_in_nav_menus"<?php echo $item_show_in_nav_menus; ?> /> Show In Nav Menus</label>
									<div class="form-field">
										<p>Whether this taxonomy should be available for selection in navigation menus.</p>
									</div>
									
									<label for="show_ui"><input type="checkbox" value="1" id="show_ui" name="show_ui"<?php echo $item_show_ui; ?> /> Show UI</label>
									<div class="form-field">
										<p>Whether to generate a default UI for managing this taxonomy.</p>
									</div>
									
									<label for="show_tagcloud"><input type="checkbox" value="1" id="show_tagcloud" name="show_tagcloud"<?php echo $item_show_tagcloud; ?> /> Show Tag Cloud</label>
									<div class="form-field">
										<p>Whether to allow the Tag Cloud widget to use this taxonomy.</p>
									</div>
									
									<label for="hierarchical"><input type="checkbox" value="1" id="hierarchical" name="hierarchical"<?php echo $item_hierarchical; ?> /> Hierarchical</label>
									<div class="form-field">
										<p>Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags.</p>
									</div>
									
									<label for="rewrite"><input type="checkbox" value="1" id="rewrite" name="rewrite"<?php echo $item_rewrite; ?> /> Rewrite</label>
									<div class="form-field">
										<p>Set to false to prevent rewrite, or array to customize query var.<br />
											<code>(Defaults to taxonomy name as query var)</code></p>
									</div>
									
									<div class="form-field">
										<label for="post-slug">Slug</label>
										<input type="text" aria-required="true" size="40" id="post-slug" name="slug" value="<?php echo $item_slug; ?>" />
										<p>The URL-friendly version of the name. Rewrite must be true to enter a slug.<br />
											<code>(Defaults to taxonomy name)</code></p>
									</div>
									
									<label for="with_front"><input type="checkbox" value="1" id="with_front" name="with_front"<?php echo $item_with_front; ?> /> Rewrite Front</label>
									<div class="form-field">
										<p>Allowing permalinks to be prepended with front base. A slug must be entered to set as true<br />
											(e.g. if true <code>/projects/web-design</code> or if false<code>/web-design</code>)</p>
									</div>
									
									<label for="query_var"><input type="checkbox" value="1" id="query_var" name="query_var"<?php echo $item_query_var; ?> /> Query Var</label>
									<div class="form-field">
										<p>False to prevent queries, or string to customize query var.<br />
											<code>(Default to taxonomy name as query var)</code></p>
									</div>
									
									<p class="submit"><input type="submit" class="button-primary" value="<?php echo $btn_text; ?>" /></p>
									
								</div>
								
							</form>
							
						</div><!-- .form-wrap -->
					</div><!-- .col-wrap -->
				</div><!-- #col-left -->
				
			</div><!-- #col-container -->
		
		</div>
		
		<?php
		
	}
	
	
	
	function build_taxonomy_code($post_values) {
	
		if ( $this->get_boolean($post_values['args']['rewrite']) ) {
			$rewrite = 'true';
			if ( strlen($post_values['args']['rewrite']['slug']) > 0 ) {
				$rewrite = 'array(\'slug\' => '.$post_values['args']['rewrite']['slug'].', \'with_front\' => '.$this->echo_boolean($post_values['args']['rewrite']['with_front']).', )';
			}
		}
		else {
			$rewrite = 'false';
		}
		
		if ( is_array($post_values['object_type']) ) {
			foreach ($post_values['object_type'] as $object_type) {
				$object_type_array .= '\''.$object_type.'\', ';
			}
		}
		
$code .= '$labels = array(
	\'name\' => _x( \''.$post_values['args']['labels']['name'].'\', \'taxonomy general name\' ),
	\'singular_name\' => _x( \''.$post_values['args']['labels']['singular_name'].'\', \'taxonomy singular name\' ),
	\'search_items\' => __( \'Search '.$post_values['args']['labels']['name'].'\' ),
	\'popular_items\' => __( \'Popular '.$post_values['args']['labels']['name'].'\' ),
	\'all_items\' => __( \'All '.$post_values['args']['labels']['name'].'\' ),
	\'edit_items\' => __( \'Edit '.$post_values['args']['labels']['name'].'\' ),
	\'update_item\' => __( \'Update '.$post_values['args']['labels']['name'].'\' ),
	\'add_new_item\' => __( \'Add New '.$post_values['args']['labels']['singular_name'].'\' ),
	\'new_item_name\' => __( \'New '.$post_values['args']['labels']['singular_name'].'\' ),
	\'separate_items_with_commas\' => __( \'Separate '.strtolower($post_values['args']['labels']['name']).' with commas\' ),
	\'add_or_remove_items\' => __( \'Add or remove '.strtolower($post_values['args']['labels']['name']).'\' ),
	\'choose_from_most_used\' => __( \'Choose from the most used '.strtolower($post_values['args']['labels']['name']).'\' ),
	\'menu_name\' => __( \''.$post_values['args']['labels']['name'].'\' ),
);
$args = array(
	\'labels\' => $labels,
	\'public\' => true,
	\'show_in_nav_menus\' => '.$this->echo_boolean($post_values['args']['show_in_nav_menus']).',
	\'show_ui\' => '.$this->echo_boolean($post_values['args']['show_ui']).',
	\'show_tagcloud\' => '.$this->echo_boolean($post_values['args']['show_tagcloud']).',
	\'hierarchical\' => '.$this->echo_boolean($post_values['args']['hierarchical']).',
	\'rewrite\' => '.$rewrite.',
	\'query_var\' => '.$this->echo_boolean($post_values['args']['query_var']).',
);
register_taxonomy(\''.$post_values['name'].'\', array('.$object_type_array.'), $args);';
		
		return $code;
		
	}
	
	
	
	function register_taxonomy_array($post_values) {
	
		$labels = array(
			'name'							=> _x( $post_values['label'], 'taxonomy general name' ),
			'singular_name'					=> _x( $post_values['singular_label'], 'taxonomy singular name' ),
			'search_items'					=> __( 'Search '.$post_values['label'] ),
			'popular_items'					=> __( 'Popular '.$post_values['label'] ),
			'all_items'						=> __( 'All '.$post_values['label'] ),
			//'parent_item'					=> null,
			//'parent_item_colon'			=> null,
			'edit_item'						=> __( 'Edit '.$post_values['label'] ), 
			'update_item'					=> __( 'Update '.$post_values['label'] ),
			'add_new_item'					=> __( 'Add New '.$post_values['singular_label'] ),
			'new_item_name'					=> __( 'New '.$post_values['singular_label'] ),
			'separate_items_with_commas'	=> __( 'Separate '.strtolower($post_values['label']).' with commas' ),
			'add_or_remove_items'			=> __( 'Add or remove '.strtolower($post_values['label']) ),
			'choose_from_most_used'			=> __( 'Choose from the most used '.strtolower($post_values['label']) ),
			'menu_name'						=> __( $post_values['label'] ),
		);
		
		if ( $this->get_boolean($post_values['rewrite']) ) {
			$rewrite = true;
			if ( strlen($post_values['slug']) > 0 ) {
				$rewrite = array('slug' => $post_values['slug'], 'with_front' => $this->get_boolean($post_values['with_front']));
			}
		}
		else {
			$rewrite = false;
		}
		 
		$args = array(
			//'label'						=> $post_values['label'],
			'labels'						=> $labels,
			'public'						=> true,
			'show_in_nav_menus'				=> $this->get_boolean($post_values['show_in_nav_menus']),
			'show_ui'						=> $this->get_boolean($post_values['show_ui']),
			'show_tagcloud'					=> $this->get_boolean($post_values['show_tagcloud']),
			'hierarchical'					=> $this->get_boolean($post_values['hierarchical']),
			//'update_count_callback'		=> null,
			'rewrite'						=> $rewrite,
			'query_var'						=> $this->get_boolean($post_values['query_var']),
			//'capabilities'				=> null,
			//'_builtin'					=> false,
		);
		
		return array('name' => $post_values['name'], 'object_type' => $post_values['post_type_name'], 'args' => $args);
		
	}
	
	
	
	function echo_boolean($bool) {
		if ( $bool > 0 ) return 'true';
			else return 'false';
	}
	
	
	
	function get_boolean($bool) {
		if ( $bool > 0 ) return true;
			else return false;
	}
	
}

?>