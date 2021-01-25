<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('checkPermission')){
	function checkPermission($role_id,$type='view_status'){
		$CI =& get_instance();
		$CI->load->database();
		$CI->load->model('common_model','common');

		$controller_name = $CI->uri->segment(1);
		$permisssion     = $CI->common->getTabPermission($role_id,$controller_name);

		if(!empty($permisssion)){
			if($permisssion->{$type} == 1){
              return true;
			}
		}
		return false;
	}	
}