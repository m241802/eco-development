
<?php
function GetMessage($message = "SH_COLOR"){
    
    echo $message;
    
}
?>


<?php

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
    
?>


<style>
.sh_reg_line {
    background-color: #F4F6F9;
    margin-bottom: 2px;
    min-height: 40px;
}
.sh_reg_line.sh_button_line{
    background: none;
    overflow: hidden;
    zoom: 1;
}
.sh_reg_line.sh_reg_line_visible:after {
    clear: both;
    color: #FFFFFF;
    content: ".";
}
.sh_reg_line.sh_reg_line_visible {
    overflow: visible;
}
.sh_reg_label {
    background-color: #DEE4EE;
    color: #646464;
    float: left;
    font-size: 14px;
    min-height: 25px;
    padding-bottom: 5px;
    padding-left: 10px;
    padding-top: 10px;
    text-align: left;
    width: 250px;
}
.sh_reg_input {
    color: #646464;
    float: left;
    font-size: 14px;
    margin-left: 20px;
    margin-top: 6px;
    text-align: left;
}
.sh_reg_input input{
    border: 1px solid #A5A5A5;
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15) inset;
    color: #363636;
    font-size: 14px;
    height: 30px;
    line-height: 30px;
    padding-left: 3px;
    width: 210px;
}
.sh_reg_line label{
    font-size: 14px;
    color: #646464;
    display: block;
    margin-left: 6px;
    position: relative;
    top: 10px;
}
.sh_reg_line label input{
    margin-right: 5px;
}
.sh_reg_line label.sh_layout{
    font-size: 14px;
    color: #646464;
    margin-left: 6px;
    display: inline;
    top: 5px;
}
.sh_reg_line label.sh_layout input{
    width: 10px;
    height: 10px;
    vertical-align: text-top;
}
.sh_reg_input select{
    border: 1px solid #A5A5A5;
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15) inset;
    color: #363636;
    font-size: 14px;
    height: 30px;
    line-height: 30px;
    padding: 2px 2px 2px 3px;
    width: 210px;
}
.sh_reg_colorpicker {
    border: 1px solid #A5A5A5;
    border-radius: 1px 1px 1px 1px;
    box-shadow: 0 1px 2px #A5A5A5 inset;
    height: 22px;
    line-height: 24px;
    padding-left: 1px;
    position: relative;
    width: 41px;
}
.sh_reg_color_face {
    background: url(//siteheart.com/img/reg/select.png) no-repeat scroll 100% 50% transparent;
    height: 22px;
}
.sh_reg_color_item {
    background: none repeat scroll 0 0 #D3D3D3;
    height: 20px;
    margin-top: 1px;
    width: 20px;
}
.sh_reg_color_block {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #A5A5A5;
    left: -1px;
    padding: 0 0 1px;
    position: absolute;
    top: 23px;
    width: 41px;
}
.sh_reg_color_line {
    padding-left: 1px;
}
.sh_reg_color_line:hover{
    background: #3399ff;
}
.sh_reg_color_item {
    background: #D3D3D3;
    height: 20px;
    margin-top: 1px;
    width: 20px;
}
.sh_reg_color_item.sh_dark {
    background: #3B3B3B;
}
.sh_reg_color_item.sh_green {
    background: #68A604;
}
.sh_reg_color_item.sh_blue {
    background: #1C80E2;
}
.sh_reg_color_item.sh_purple {
    background: #A41CD0;
}
.sh_reg_color_item.sh_red {
    background: #A40404;
}
.sh_reg_color_item.sh_pink {
    background: #F32FB0;
}
.sh_reg_color_item.sh_orange {
    background: #E19104;
}
.sh_submit{
    background: #B11313;
    border-radius: 3px;
    color: #FFFFFF !important;
    cursor: pointer;
    font-size: 22px;
    height: 40px;
    line-height: 40px;
    margin: 20px 0 0 289px;
    text-align: center;
    width: 207px;
    display: block;
    text-decoration: none;
}
.sh_preloader{
    width: 100%;
    height: 200px;
    background: #fff url(//d1ytok5muqmio7.cloudfront.net/apps/workplace/img/ajax-loader.gif) no-repeat 50% 50%;
}
</style>
<div class="sh_preloader" id="sh_preloader"></div>
<div id="sh_autorizing" style="margin-top: 15px;width: 245px;text-align: center;display: none;">
    <?=_e("Authorize in Siteheart", "siteheart")?>
    <iframe src="//siteheart.com/<?php echo substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == 'ru' ? 'ru/' : 'en' ?>bitrixauth" onload="sh_getAuth();" frameborder="0" width="310" height="185" style="display: block;margin-top: 9px;"></iframe>
</div>
<form method="POST" id="sh_options_form" style="display: none;margin-top: 15px;">
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Division", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<select name="widget_id" id="sh_ent_id"></select>
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Color", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<div class="sh_reg_colorpicker">
	    <div class="sh_reg_color_face" id="sh_color_select">
		<div data-template="" class="sh_reg_color_item<?php echo $settings['template'] ? ' sh_'.$settings['template'] : '' ?>" id="sh_color_item"></div>
	    </div>
	    <div style="display: none;" class="sh_reg_color_block" id="sh_color_block">
		<div class="sh_reg_color_line">
		    <div data-template="" class="sh_reg_color_item"></div>
		</div>
		<div class="sh_reg_color_line">
			<div data-template="dark" class="sh_reg_color_item sh_dark"></div>
		</div>
		<div class="sh_reg_color_line">
			<div data-template="green" class="sh_reg_color_item sh_green"></div>
		</div>
		<div class="sh_reg_color_line">
			<div data-template="blue" class="sh_reg_color_item sh_blue"></div>
		</div>
		<div class="sh_reg_color_line">
			<div data-template="purple" class="sh_reg_color_item sh_purple"></div>
		</div>
		<div class="sh_reg_color_line">
			<div data-template="red" class="sh_reg_color_item sh_red"></div>
		</div>
		<div class="sh_reg_color_line">
			<div data-template="pink" class="sh_reg_color_item sh_pink"></div>
		</div>
		<div class="sh_reg_color_line">
			<div data-template="orange" class="sh_reg_color_item sh_orange"></div>
		</div>
	    </div>
	</div>
    </div>
</div>
<input type="hidden" name="template" id="sh_template"  value="<?=$settings['template']?>" />
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Appearance", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<select name="side" id="sh_select_side">
	    <option value="bottom" <?php echo $settings['side'] == 'bottom' ? 'selected' : '' ?>><?=_e("Bottom", "siteheart")?></option>
	    <option value="left" <?php echo $settings['side'] == 'left' ? 'selected' : '' ?>><?=_e("Left", "siteheart")?></option>
	    <option value="right" <?php echo $settings['side'] == 'right' ? 'selected' : '' ?>><?=_e("Right", "siteheart")?></option>
	    <option value="top" <?php echo $settings['side'] == 'top' ? 'selected' : '' ?>><?=_e("Top", "siteheart")?></option>
	</select>
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Position", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<select name="position"  id="sh_select_position">
	    <?php if($settings['side'] == 'bottom' || $settings['side'] == 'top' || !$settings['side']){ ?>
	    <option value="right" <?php echo $settings['position'] == 'right' ? 'selected' : '' ?>><?=_e("Right Side", "siteheart")?></option>
	    <option value="center" <?php echo $settings['position'] == 'center' ? 'selected' : '' ?>><?=_e("Center", "siteheart")?></option>
	    <option value="left" <?php echo $settings['position'] == 'left' ? 'selected' : '' ?>><?=_e("Left Side", "siteheart")?></option>
	    <?php } ?>
	    <?php if($settings['side'] == 'left' || $settings['side'] == 'right'){ ?>
	    <option value="top" <?php echo $settings['position'] == 'top' ? 'selected' : '' ?>><?=_e("Top", "siteheart")?></option>
	    <option value="center" <?php echo $settings['position'] == 'center' ? 'selected' : '' ?>><?=_e("Center", "siteheart")?></option>
	    <option value="bottom" <?php echo $settings['position'] == 'bottom' ? 'selected' : '' ?>><?=_e("Bottom", "siteheart")?></option>
	    <?php } ?>
	</select>
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Widget title online", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<input type="text" name="title" value="<?php echo $settings['title'] ? $settings['title'] : _e("Help online", "siteheart") ?>" /> 
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Widget title offline", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<input type="text" name="title_offline" value="<?php echo $settings['title_offline'] ? $settings['title_offline'] : _e("Leave a message", "siteheart") ?>" /> 
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Automatic chat invitation", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<select name="inviteTimeout">
	    <option value=""><?=_e("off", "siteheart")?></option>
	    <option value="30000" <?php echo $settings['inviteTimeout'] == '30000' ? 'selected' : '' ?>>30 <?=_e("sec", "siteheart")?></option>
	    <option value="60000" <?php echo $settings['inviteTimeout'] == '60000' ? 'selected' : '' ?>>1 <?=_e("min", "siteheart")?></option>
	    <option value="120000" <?php echo $settings['inviteTimeout'] == '120000' ? 'selected' : '' ?>>2 <?=_e("min", "siteheart")?></option>
	    <option value="180000" <?php echo $settings['inviteTimeout'] == '180000' ? 'selected' : '' ?>>3 <?=_e("min", "siteheart")?></option>
	    <option value="240000" <?php echo $settings['inviteTimeout'] == '240000' ? 'selected' : '' ?>>4 <?=_e("min", "siteheart")?></option>
	    <option value="300000" <?php echo $settings['inviteTimeout'] == '300000' ? 'selected' : '' ?>>5 <?=_e("min", "siteheart")?></option>
	    <option value="600000" <?php echo $settings['inviteTimeout'] == '600000' ? 'selected' : '' ?>>10 <?=_e("min", "siteheart")?></option>
	    <option value="1200000" <?php echo $settings['inviteTimeout'] == '1200000' ? 'selected' : '' ?>>20 <?=_e("min", "siteheart")?></option>
	    <option value="1800000" <?php echo $settings['inviteTimeout'] == '1800000' ? 'selected' : '' ?>>30 <?=_e("min", "siteheart")?></option>
	    <option value="2700000" <?php echo $settings['inviteTimeout'] == '2700000' ? 'selected' : '' ?>>45 <?=_e("min", "siteheart")?></option>
	    <option value="3600000" <?php echo $settings['inviteTimeout'] == '3600000' ? 'selected' : '' ?>>60 <?=_e("min", "siteheart")?></option>
	</select>
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Repeat chat invitation in", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<select name="inviteCancelTimeout">
	    <option value=""><?=_e("off", "siteheart")?></option>
	    <option value="30000" <?php echo $settings['inviteCancelTimeout'] == '30000' ? 'selected' : '' ?>>30 <?=_e("sec", "siteheart")?></option>
	    <option value="60000" <?php echo $settings['inviteCancelTimeout'] == '60000' ? 'selected' : '' ?>>1 <?=_e("min", "siteheart")?></option>
	    <option value="120000" <?php echo $settings['inviteCancelTimeout'] == '120000' ? 'selected' : '' ?>>2 <?=_e("min", "siteheart")?></option>
	    <option value="180000" <?php echo $settings['inviteCancelTimeout'] == '180000' ? 'selected' : '' ?>>3 <?=_e("min", "siteheart")?></option>
	    <option value="240000" <?php echo $settings['inviteCancelTimeout'] == '240000' ? 'selected' : '' ?>>4 <?=_e("min", "siteheart")?></option>
	    <option value="300000" <?php echo $settings['inviteCancelTimeout'] == '300000' ? 'selected' : '' ?>>5 <?=_e("min", "siteheart")?></option>
	    <option value="600000" <?php echo $settings['inviteCancelTimeout'] == '600000' ? 'selected' : '' ?>>10 <?=_e("min", "siteheart")?></option>
	    <option value="1200000" <?php echo $settings['inviteCancelTimeout'] == '1200000' ? 'selected' : '' ?>>20 <?=_e("min", "siteheart")?></option>
	    <option value="1800000" <?php echo $settings['inviteCancelTimeout'] == '1800000' ? 'selected' : '' ?>>30 <?=_e("min", "siteheart")?></option>
	    <option value="2700000" <?php echo $settings['inviteCancelTimeout'] == '2700000' ? 'selected' : '' ?>>45 <?=_e("min", "siteheart")?></option>
	    <option value="3600000" <?php echo $settings['inviteCancelTimeout'] == '3600000' ? 'selected' : '' ?>>60 <?=_e("min", "siteheart")?></option>
	</select>
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Invitation text", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<input type="text" name="inviteText" value="<?=$settings['inviteText']?>" /> 
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <div class="sh_reg_label"><?=_e("Invitation url of avatar", "siteheart")?>:</div>
    <div class="sh_reg_input">
	<input type="text" name="inviteImage" value="<?=$settings['inviteImage']?>" /> 
    </div>
</div>
<div class="sh_reg_line sh_reg_line_visible" style="overflow: hidden;">
    <div class="sh_reg_label">
		<?=_e("Secret key", "siteheart")?>:
		<div style="font-size: 10px; color: #848484;">(<?=_e("for transfer of user data", "siteheart")?>)</div>
	</div>
    <div class="sh_reg_input">
	<input type="text" name="secret_key" value="<?=$settings['secret_key']?>" /> 
    </div>
</div>
<div id="sh_text_layout">
    <?php if($settings['side'] == 'left' || $settings['side'] == 'right'){ ?>
    <div class="sh_reg_line sh_reg_line_visible">
	<div class="sh_reg_label"><?=_e("Layout of text", "siteheart")?>:</div>
	<div class="sh_reg_input">
	    <label class="sh_layout"><input type="radio" name="text_layout" value="" <?php echo $settings['text_layout'] ? '' : 'checked' ?> /><?=_e("Vertical", "siteheart")?></label>
	    <label class="sh_layout"><input type="radio" name="text_layout" value="trans" <?php echo $settings['text_layout'] == 'trans' ? 'checked' : '' ?> /><?=_e("Horizontal", "siteheart")?></label>
	</div>
    </div>
    <?php } ?>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <label><input type="checkbox" name="devisions" value="all" <?php echo $settings['devisions'] ? 'checked' : '' ?> /><?=_e("Client choose the division on start", "siteheart")?></label>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <label><input type="checkbox" name="track" value="1" <?php echo $settings['track'] ? 'checked' : '' ?> /><?=_e("Monitoring of visitors", "siteheart")?></label>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <label><input type="checkbox" name="hide" value="1" <?php echo $settings['hide'] ? 'checked' : '' ?> /><?=_e("Hide the widget online", "siteheart")?></label>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <label><input type="checkbox" name="hide_offline" value="1" <?php echo $settings['hide_offline'] ? 'checked' : '' ?> /><?=_e("Hide the widget offline", "siteheart")?></label>
</div>
<div class="sh_reg_line sh_reg_line_visible">
    <label><input type="checkbox" name="offline_pay" value="1" <?php echo $settings['offline_pay'] ? 'checked' : '' ?> /><?=_e("Offline pays", "siteheart")?></label>
</div>
<div class="sh_reg_line sh_button_line">
    <a href="javascript:;" class="sh_submit" onclick="document.getElementById('sh_options_form').submit();" ><?=_e("Save", "siteheart")?></a>
</div>
</form>
<script type="text/javascript">
    
    function sh_getAuth(){
	
	var sh_script = document.createElement("script");  
	sh_script.src = '//siteheart.com/esapi/me/fullinfo?callback=sh_autorize';  
	sh_script.type = 'text/javascript';  
	document.body.appendChild(sh_script); 
	
    }
    
    
    
    function sh_autorize(response){
	
	document.getElementById('sh_autorizing').style.display = 'block';
	
	document.getElementById('sh_preloader').style.display = 'none';
	
	if(response.result == 'success'){
	    
	    <?php if($settings['widget_id']){ ?>
		var widget_id = <?php echo $settings['widget_id']?>;
	    <?php }else{?>
		var widget_id = '';
	    <?php }?>
		
	    var options = '';
	    
	    for(var i = 0; i < response.divisions.length; i++){
		
		options += '<option value="' + response.divisions[i].ent_id + '"' + (widget_id == response.divisions[i].ent_id ? 'selected' : '') + '>' + (response.divisions[i].title ? response.divisions[i].title.replace('<', '&lt;').replace('>','&gt;') : '') + '</option>';
		
	    }
	    
	    document.getElementById('sh_ent_id').innerHTML = options;
	    
	    document.getElementById('sh_options_form').style.display = 'block';
	    
	    document.getElementById('sh_autorizing').style.display = 'none';
	    
	    return;
	    
	}
	
    }
    
    (function(){
	
	document.getElementById('sh_color_select').onclick = function(){

	    var color_block = document.getElementById('sh_color_block');

	    if(color_block.style.display == 'none'){
		color_block.style.display = 'block';
	    }else{
		color_block.style.display = 'none';
	    }

	}

	var sh_divs = document.getElementsByTagName('DIV');

	for(var i = 0; i < sh_divs.length; i++){

	    if(sh_divs[i].className == 'sh_reg_color_line'){

		sh_divs[i].onclick = function(){

		    document.getElementById('sh_color_item').className = 'sh_reg_color_item sh_' + this.children[0].getAttribute('data-template');

		    document.getElementById('sh_template').value = this.children[0].getAttribute('data-template');

		    document.getElementById('sh_color_block').style.display = 'none';

		}

	    }

	}
	
	function changeSide(){
	
	    var val = document.getElementById('sh_select_side').value;
	
	    if(val == 'top' || val == 'bottom'){

		document.getElementById('sh_select_position').innerHTML = '<option value="right"><?=_e("Right Side", "siteheart")?></option><option value="center"><?=_e("Center", "siteheart")?></option><option value="left"><?=_e("Left Side", "siteheart")?></option>';

		document.getElementById('sh_text_layout').innerHTML = '';

	    }else{

		document.getElementById('sh_select_position').innerHTML = '<option value="top"><?=_e("Top", "siteheart")?></option><option value="center"><?=_e("Center", "siteheart")?></option><option value="bottom"><?=_e("Bottom", "siteheart")?></option>';

		document.getElementById('sh_text_layout').innerHTML = '<div class="sh_reg_line sh_reg_line_visible"><div class="sh_reg_label"><?=_e("Layout of text", "siteheart")?>:</div><div class="sh_reg_input"><label class="sh_layout"><input type="radio" name="text_layout" value="" checked /><?=_e("Vertical", "siteheart")?></label><label class="sh_layout"><input type="radio" name="text_layout" value="trans" /><?=_e("Horizontal", "siteheart")?></label></div></div>';

	    }
	
	}
	
	<?php if(!$settings['widget_id']) { ?>
	
	    changeSide();
	
	<?php } ?>
	
	document.getElementById('sh_select_side').onchange = changeSide;
	
    })();
</script>