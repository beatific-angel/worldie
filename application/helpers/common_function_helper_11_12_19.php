<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('has_permission'))
{
	function has_permission($action=null)
	{
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
