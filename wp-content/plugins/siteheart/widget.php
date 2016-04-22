<?php

    if(get_option("sh_widget_id")){

	$settings = array();

	$settings['widget_id'] = get_option("sh_widget_id");
	$settings['template'] = get_option("sh_template");
	$settings['side'] = get_option("sh_side");
	$settings['position'] = get_option("sh_position");
	$settings['title'] = get_option("sh_title");
	$settings['title_offline'] = get_option("sh_title_offline");
	$settings['inviteTimeout'] = get_option("sh_inviteTimeout");
	$settings['inviteCancelTimeout'] = get_option("sh_inviteCancelTimeout");
	$settings['inviteText'] = get_option("sh_inviteText");
	$settings['inviteImage'] = get_option("sh_inviteImage");
	$settings['text_layout'] = get_option("sh_text_layout");
	$settings['devisions'] = get_option("sh_devisions");
	$settings['track'] = get_option("sh_track");
	$settings['hide'] = get_option("sh_hide");
	$settings['hide_offline'] = get_option("sh_hide_offline");
	$settings['offline_pay'] = get_option("sh_offline_pay");
	$settings['secret_key'] = get_option("sh_secret_key");

	$obj = '';
	
	global $user_ID, $current_user;
	
	function sh_get_avatar_url($get_avatar){
		preg_match("/src='(.*?)'/i", get_avatar($get_avatar), $matches);
		return $matches[1];
	}

	foreach ($settings as $key => $value) {

	   if(!$value){
	       continue;
	   }
	   
	   if($key == 'secret_key'){
			   
			if(!$user_ID){

				continue;

			}

			$user = array(
				 'nick' => $current_user->user_lastname.' '.$current_user->user_firstname,
				 'avatar' => sh_get_avatar_url($current_user->ID),
				 'id' => $current_user->ID,
				 'email' => $current_user->user_email,
			 );

			$time = time();
			
			$secret = $value;
			$user_base64 = base64_encode( json_encode($user) );
			$sign = md5($secret . $user_base64 . $time);
			$auth = $user_base64 . "_" . $time . "_" . $sign;

			$obj .= 'auth : "'.$auth.'", ';

			continue;

		}

	   if($key == 'widget_id' || $key == 'hide' || $key == 'hide_offline' || $key == 'offline_pay' || $key == 'inviteTimeout' || $key == 'inviteCancelTimeout' || $key == 'track'){

	       $obj .= $key.' : '.$value.', '; 

	   }else{

	       $obj .= $key.' : "'.$value.'", '; 

	   }

	}

	$obj .= 'widget : "Chat"';

	echo '<script type="text/javascript">_shcp = []; _shcp.push({'.$obj.'}); (function() { var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true; hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://widget.siteheart.com/apps/js/sh.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); })();</script>';

    }

?>