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
			redirect('/index');
		}
	}

	public function index()
	{
		$users = $this->user->all();
		$this->twig->display('rms/usermanage/usermanage.html', compact('users'));
	}

	public function destory($u_id)
	{
		if ($destoried = $this->user->destory(['u_id'=>$u_id]))
		{
			$this->session->set_flashdata('message',"{$destoried[0]->name} 已被刪除");
			$this->session->set_flashdata('type','warning');
		}
		redirect('/usermanage');
	}
}