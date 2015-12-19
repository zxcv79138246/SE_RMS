<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Usermanage extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model', 'user');
		if ($this->session->userdata('priority') != 2)
		{
			$this->session->set_flashdata('message', '權限不足');
			$this->session->set_flashdata('type', 'danger');
			redirict('/index');
		}
	}

	public function index()
	{
		$users = $this->user->all();
		$this->twig->display('rms/usermanage/usermanage.html', compact('users'));
	}
}