<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Errors extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
	}

	/* Permission error */
	public function index(){
		$this->data['title'] = 'Permission Error';
		$this->load->view('errors/error_permission',$this->data);
    }
}
?>
