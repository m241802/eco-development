<?php
/*
Plugin Name: SiteHeart
Plugin URI: http://siteheart.com/
Description: SiteHeart - Free online chat for website. 
Version: 1.0.0
Author:  Pavel Kutsenko, Dmitry Goncharov, Inna Goncharova
Author URI: http://siteheart.com/
*/
define('SH_DEV',false);
define('SH_CONTENT_URL', get_option('siteurl') . '/wp-content');
define('SH_PLUGIN_URL', SH_CONTENT_URL . '/plugins/siteheart');
define('SH_XML_PATH',$_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads');
define('SH_VERSION', '1.0.0');

define('SH_URL', 'http://siteheart.com');

register_deactivation_hook(__FILE__,'sh_delete');
register_activation_hook(__FILE__,'sh_active');          
add_action('init', 'sh_request_handler');

add_action('admin_menu', 'sh_add_pages', 10);
add_action('admin_notices', 'sh_messages');
add_action ('wp_head', 'sh_add_widget');


function sh_request_handler() {
    
    if(function_exists('load_plugin_textdomain')) {
        load_plugin_textdomain('siteheart', 'wp-content/plugins/siteheart/locales');
    }
    
}


/**
 * Action by activating the plugin
 */
function sh_active(){
    
    update_option( 'sh_widget_id',  '' );
    update_option( 'sh_template',  '' );
    update_option( 'sh_side',  '' );
    update_option( 'sh_position',  '' );
    update_option( 'sh_title',  '' );
    update_option( 'sh_title_offline',  '' );
    update_option( 'sh_inviteTimeout',  '' );
    update_option( 'sh_inviteCancelTimeout',  '' );
    update_option( 'sh_inviteText',  '' );
    update_option( 'sh_inviteImage',  '' );
    update_option( 'sh_devisions',  '' );
    update_option( 'sh_track',  '' );
    update_option( 'sh_hide',  '' );
    update_option( 'sh_hide_offline',  '' );
    update_option( 'sh_offline_pay',  '' );
    update_option( 'sh_text_layout',  '' );
	update_option( 'sh_secret_key',  '' );
    
}
/**
 * Action when uninstall plugin
 */
function sh_delete(){  
    
    delete_option( 'sh_widget_id' );
    delete_option( 'sh_template' );
    delete_option( 'sh_side' );
    delete_option( 'sh_position' );
    delete_option( 'sh_title' );
    delete_option( 'sh_title_offline' );
    delete_option( 'sh_inviteTimeout' );
    delete_option( 'sh_inviteCancelTimeout' );
    delete_option( 'sh_inviteText' );
    delete_option( 'sh_inviteImage' );
    delete_option( 'sh_devisions' );
    delete_option( 'sh_track' );
    delete_option( 'sh_hide' );
    delete_option( 'sh_hide_offline' );
    delete_option( 'sh_offline_pay' );
    delete_option( 'sh_text_layout' );
	delete_option( 'sh_secret_key' );

}

function sh_show_script(){
    
}

function sh_messages() {
    $page = (isset($_GET['page']) ? $_GET['page'] : null);
    if ( !get_option('sh_widget_id') && $page != 'siteheart' && $page != 'siteheart_admin') {
       echo _e('<div class="updated"><p><b>You must <a href="admin.php?page=siteheart">configure the plugin</a> to enable Siteheart.</b></p></div>', 'siteheart');
    }
}

function sh_options_page() {
    
    if( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	
	update_option( 'sh_widget_id',  $_POST['widget_id'] );
        update_option( 'sh_template',  $_POST['template'] );
	update_option( 'sh_side',  $_POST['side'] );
	update_option( 'sh_position',  $_POST['position'] );
	update_option( 'sh_title',  $_POST['title'] );
	update_option( 'sh_title_offline',  $_POST['title_offline'] );
	update_option( 'sh_inviteTimeout',  $_POST['inviteTimeout'] );
	update_option( 'sh_inviteCancelTimeout',  $_POST['inviteCancelTimeout'] );
	update_option( 'sh_inviteText',  $_POST['inviteText'] );
	update_option( 'sh_inviteImage',  $_POST['inviteImage'] );
	update_option( 'sh_devisions',  $_POST['devisions'] );
	update_option( 'sh_track',  $_POST['track'] );
	update_option( 'sh_hide',  $_POST['hide'] );
	update_option( 'sh_hide_offline',  $_POST['hide_offline'] );
	update_option( 'sh_offline_pay',  $_POST['offline_pay'] );
	update_option( 'sh_text_layout',  $_POST['text_layout'] );
	update_option( 'sh_secret_key',  $_POST['secret_key'] );
	
	include_once(dirname(__FILE__) . '/success.php');
	
    }
    
    include_once(dirname(__FILE__) . '/set.php');
    
}

function sh_add_pages() {
    
    $local_setings = substr(get_locale(), 0, 2) == 'ru' ? 'Настройки' : 'Settings';
    
    $local_admin = substr(get_locale(), 0, 2) == 'ru' ? 'Перейти в панель администратора' : 'Go to administrator panel';
    
    add_menu_page( 'SiteHeart', 'SiteHeart', 'None', 'sh_options_page', '', '/wp-content/plugins/siteheart/img/siteheart_logo.png');
    
    add_submenu_page(
        'sh_options_page',
        'SiteHeart',
	$local_setings,
        'moderate_comments',
        'siteheart',
        'sh_options_page'
    );

    add_submenu_page(
        'sh_options_page',
        'SiteHeart',
	$local_admin,
        'moderate_comments',
        'siteheart_admin',
        'sh_go'
    );
}

function sh_add_widget(){
    
    include_once dirname(__FILE__).'/widget.php';

}

function sh_go(){
    
    echo '<script type="text/javascript">location.href = "http://siteheart.com/go";</script>';

}

?>