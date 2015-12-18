<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Login extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','user');
	}

	public function login()
	{
		$account=[
			'email' => $this->input->post('email'),
			'password' => $this->input->post('password'),
		];
		$login = $this->user->login($account);
		if ($login)
		{
			$data=[
				'u_id'=>$login->u_id,
				'name'=>$login->name,
				'priority'=>$login->priority,
			];
			$this->session->set_userdata($data);
		}else
		{
			$this->session->set_flashdata('message','登入失敗,帳號或密碼錯誤');
			$this->session->set_flashdata('type','danger');
		}
		redirect('/index');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/index');
	}
}