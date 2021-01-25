<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('has_permission')){
	function has_permission($action=null){
		$CI =& get_instance();
		
		$CI->config->load('user_permission', TRUE);
		$user_roles  = $CI->config->item('user_roles', 'user_permission');
		$permissions = $CI->config->item('user_roles_permissions', 'user_permission');
		$user_roleid = $CI->session->userdata('role_id');
		
		$action_array = explode('-', $action);
		
		$currentaction = $action_array[0];
		$control       = $action_array[1];
		
		//get through each section(users, category etc.) with it's action (view, edit)
		foreach($permissions as $section => $actions) {		
			if($section == $control){
				//get all the user roles ids defined for each action of each section
				foreach($actions as $action => $roles_ids){
					//build/define a ability(rule) based on action+section (e.g. view-user, edit-category)
					//therefore, the rules are used in controllers and views to restrict the access of view/edit for specified user roles
						//verify if the logged user role has access
					if($action == $currentaction){
						return in_array($user_roleid, $roles_ids);
					}
				}
			}
		} 
	}	
}

if ( ! function_exists('user_inforamtion'))
{
	function user_inforamtion($name)
	{
		$CI =& get_instance();
		if (!$CI->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		
		if($name == 'name'){
			$user = $CI->session->userdata('user_name');
			//$user = $CI->ion_auth->user('user_name');
			return $user;
		}
		if($name == 'gender'){
			$user = $CI->session->userdata('gender');
			if($user == 1){
				return 'male.jpg';
			}else if($user == 2){
				return 'female.jpg';
			}else{
				return 'user.png';
			}
			
		}
		
		
		
	}	
}

if ( ! function_exists('web_url'))
{
	function web_url()
	{
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front');
		$url = $base_url_front;
		return $url;
	}	
}

if ( ! function_exists('media_channel_url'))
{
	function media_channel_url()
	{	
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front');
		$url = $base_url_front.'media/channel/';
		return $url;  
	}	
}

if ( ! function_exists('user_media_video'))
{
	function user_media_video()
	{	
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front');
		$url = $base_url_front.'media/viewvideo/';
		return $url;
	}	
}

if ( ! function_exists('user_profile'))
{
	function user_profile()
	{	
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front');
		$url = $base_url_front.'profile/';
		return $url;
	}	
}

if ( ! function_exists('user_art_wall'))
{
	function user_art_wall()
	{
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front');
		$url = $base_url_front.'art/art_wall/';
		return $url;
	}	
}

if ( ! function_exists('user_wall_art'))
{
	function user_wall_art()
	{
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front');
		$url = $base_url_front.'art/viewart/';
		return $url;
	}	
}

if ( ! function_exists('view_event_detail'))
{
	function view_event_detail()
	{	
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front');
		$url = $base_url_front.'events/event_detail/';
		return $url;
	}	
}
if ( ! function_exists('page_exists'))
{
	function page_exists($name)
	{
		$CI =& get_instance();
		$CI->config->load('user_permission', TRUE);
		if (!$CI->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		
		
		$page_names  = $CI->config->item('page_type' , 'user_permission');
		if (in_array($name, $page_names))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}	

if( ! function_exists('truncate_text')){
	function truncate_text($text, $length, $suffix = '&hellip;', $isHTML = true) {
	  $i = 0;
    $simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
	  //$simpleTags=array('br'=>true,'link'=>true);
    $tags = array();
    if($isHTML){
        preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach($m as $o){
            if($o[0][1] - $i >= $length)
                break;
            $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
            // test if the tag is unpaired, then we mustn't save them
            if($t[0] != '/' && (!isset($simpleTags[$t])))
                $tags[] = $t;
            elseif(end($tags) == substr($t, 1))
                array_pop($tags);
            $i += $o[1][1] - $o[0][1];
        }
    }
    // output without closing tags
    $output = substr($text, 0, $length = min(strlen($text),  $length + $i));
    // closing tags
    $output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
    // Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
    //$pos = (int)end(end(preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE)));
    $pos = (int)end(end(preg_split('/<.*>|/', $output, -1, PREG_SPLIT_OFFSET_CAPTURE)));
    // Append closing tags to output
    $output.=$output2;
	// Get everything until last space
    $one = substr($output, 0, $pos);
    // Get the rest
    $two = substr($output, $pos, (strlen($output) - $pos));
    // Extract all tags from the last bit
    preg_match_all('/<(.*?)>/s', $two, $tags);
    // Add suffix if needed
    if (strlen($text) > $length) { $one .= $suffix; }
    // Re-attach tags
    $output = $one . implode($tags[0]);

    //added to remove  unnecessary closure
    $output = str_replace('</!-->','',$output); 

    return $output;
	}
}



if( ! function_exists('generateCommentHtml')){
	function generateCommentHtml($comments, $element_id, $content_type, $comm_count, $showLimit = 4){
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front'); 
		$loggeduser = $CI->session->userdata('user_id');
		$type = getContentTypeId($content_type);
		// echo $type;die;
		// $type = 1; // 1 => post

		$content_type_id = getCommentTypeId('comment');
		$comment_reply = getCommentTypeId('comment_reply');
		$html = '';

		if($comm_count > 0 && $comm_count >= 5){ 
			$html .= '<div class="comment_section_all" style="width:100%;"><div class="'.$type.'_main_comment_div_'.$element_id.'">';
		}else{
			$html .= '<div class="comment_section_all"><div class="'.$type.'_main_comment_div_'.$element_id.'">';
		}
		
		if(!empty($comments)){ 

			$commentCounter = 0;
			foreach($comments as $comment){
				$commentCounter++;
				$html .='<div class="'.$type.'_fur_comment_'.$comment->comment_id.' comment-thread '.( $commentCounter > $showLimit ? "hideCommentBox":"").'"><ul class="my_comment"><li>
				<section>
					<div class="dp-image">';
						$comment_user_image = '';
						if($comment->cu_thumb_image != ''){
							$comment_user_image = $comment->cu_thumb_image;
						}elseif($comment->cu_image != ''){
							$comment_user_image = $comment->cu_image;
						}else{
							if($comment->cu_gender == 1){
								$comment_user_image = 'assets/images/male.jpg';
							}else{
								$comment_user_image = 'assets/images/female.jpg';
							}
						}
						$html .='<a href="'.$base_url_front.'profile/'.$comment->cu_userid.'"><img src="'.$base_url_front.''.$comment_user_image.'" alt="img" /></a>
					</div>';
				
				$html .='
				<div class="right-content">
					<a href="'.$base_url_front.'profile/'.$comment->cu_userid.'"><b>'.$comment->cu_f_name.' '.$comment->cu_l_name.'</b></a>
					<div class="edit_input mobComment">
						<p id="'.$type.'_current_comment_text_'.$comment->comment_id.'">'.$comment->comment_text.'</p>
						<div class="clear"></div>
					
					<div class="actions">';
						$html .='<a href="javascript:;"><img border="0" alt="img" src="'.$base_url_front.'assets/images/cancel_icon_new.png" onclick="deleteComment('.$comment->comment_id.', '.$element_id.', '.$type.', '.$content_type_id.', '.$comment->parent_id.', this);" title="Delete"></a>';
					$html .='</div>';
				
					if(count($comment->reply) ==  0){
						$html .='<div class="view-hide '.$type.'_show_hide_reply_div_button_'.$comment->comment_id.'" style="display:none;"><a href="javascript:;" onclick="showHideReplyArea('.$comment->comment_id.', '.$type.');">View all replies</a></div>';
					}else{
						$html .='<div class="view-hide '.$type.'_show_hide_reply_div_button_'.$comment->comment_id.'"><a href="javascript:;" onclick="showHideReplyArea('.$comment->comment_id.', '.$type.');">View all '.$comment->reply_count.' replies</a></div>';
					}
					$html .='</div>';

					
					$html .='<div class="'.$type.'_append_reply_div_'.$comment->comment_id.'" style="display:none;">';
					if(count($comment->reply) > 0){ 
						foreach($comment->reply as $reply){
							$html .= getCommentReplyHtml($reply, $element_id, $type, 2);
						}}
						$html .= '</div></ul></li></div>';
					} }
					$html .='</div>
					

				</section></div>';

				$html .='</div>';
				$html .='</div>';
				return $html;
	}
}

if( ! function_exists('getCommentReplyHtml')){
	function getCommentReplyHtml($reply, $element_id, $type, $content_type_id){
		$reply_reply_content_type_id = 3;
		$CI =& get_instance();
		$base_url_front = $CI->config->item('base_url_front'); 
		$loggeduser = $CI->session->userdata('user_id');
		$html = '';
		$html .= '<ul class="'.$type.'_comment_reply_div_'.$reply->comment_id.' my_comment"><li>
				<section>
					<div class="dp-image">';
						$reply_user_image = '';
						if($reply->cu_thumb_image != ''){
							$reply_user_image = $reply->cu_thumb_image;
						}elseif($reply->cu_image != ''){
							$reply_user_image = $reply->cu_image;
						}else{
							if($reply->cu_gender == 1){
								$reply_user_image = 'assets/images/male.jpg';
							}else{
								$reply_user_image = 'assets/images/female.jpg';
							}
						}
						$html .='<a href="'.$base_url_front.'profile/'.$reply->cu_userid.'"><img src="'.$base_url_front.''.$reply_user_image.'" alt="img" /></a>
					</div>
						<div class="right-content">
							<a href="'.$base_url_front.'profile/'.$reply->cu_userid.'"><b>'.$reply->cu_f_name.' '.$reply->cu_l_name.'</b></a>
							<div class="edit_input mobComment">
								<p id="'.$type.'_current_comment_text_'.$reply->comment_id.'">'.$reply->comment_text.'</p>
								<div class="clear"></div>
							</div>';
						$html .='<div class="actions">';
								$html .='<a href="javascript:;"><img border="0" alt="img" src="'.$base_url_front.'assets/images/cancel_icon_new.png" onclick="deleteComment('.$reply->comment_id.', '.$element_id.', '.$type.', '.$content_type_id.', '.$reply->parent_id.',this);" title="Delete"></a>
							</div>';
		$html .='</div></section></li></ul>';
						if(count($reply->reply) > 0){ 
							foreach($reply->reply as $reply){
								$html .=getCommentReplyHtml($reply, $element_id, $type, 3);
						}}
		return $html;	
	}
}

if( ! function_exists('getCommentTypeId')){
	function getCommentTypeId($comment_type){
        $CI =& get_instance();
		$CI->config->load('user_permission', TRUE);
		$c_type  = $CI->config->item('comment_type', 'user_permission');
		return $c_type[$comment_type];
	}
}

if( ! function_exists('checkAndGetScreenshoot')){
	function checkAndGetScreenshoot($item_id = null, $type = null, $comment_for = null){
        $CI =& get_instance();
        $base_url_front = $CI->config->item('base_url_front'); 
        if($type == 1){
        	$data = $CI->db->query('SELECT * FROM link_images WHERE item_id='.$item_id.' AND item_type= 1')->result();
        }else{
        	$data = $CI->db->query('SELECT * FROM link_images WHERE item_id='.$item_id.' AND item_type= 2 AND comment_for='.$comment_for)->result();
        }
		if(count($data) > 0){
			if($type == 1){
				$html = '';
				$html .='<div class="clear"></div>
							<a class="screenshot_redirect_anchor" href="'.$data[0]->url.'" target="_blank"><div class="post-screnshot-div"><div class="post_screenshot_image" style="background-image:url('.base_url($data[0]->image_url).')">
							
							</div><div class="post-screnshot-text">';
							if(!is_null($data[0]->link_title)){
				$html .=		'<h2>'.$data[0]->link_title.'</h2>';
							}
							if(!is_null($data[0]->link_description)){
				$html .=		'<p>'.$data[0]->link_description.'</p>';
							}
				$html .='</div></div></a>';
				return $html;
			}else{
				$html = '';
				$html .='<div class="clear"></div>
							<a class="screenshot_redirect_anchor" href="'.$data[0]->url.'" target="_blank">
							<div class="comment-screnshot-div">
								<img src="'.$base_url_front.''.$data[0]->image_url.'" alt="Screen Shot Image">
							<div class="comment-screnshot-text">';
							if(!is_null($data[0]->link_title)){
				$html .=		'<h2>'.$data[0]->link_title.'</h2>';
							}
							if(!is_null($data[0]->link_description)){
				$html .=		'<p>'.$data[0]->link_description.'</p>';
							}
				$html .='</div></div></a>';
				return $html;
			}			 
		}
	}
}

if( ! function_exists('getContentTypeId')){
	function getContentTypeId($content_type){
        $CI =& get_instance();
		$CI->config->load('user_permission', TRUE);
		$c_type  = $CI->config->item('ss_content_type', 'user_permission');
		return $c_type[$content_type];
	}
}