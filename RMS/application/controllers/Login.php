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
				'password'=>$login->password,
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

	public function changePS()
	{
		
		$u_id=$this->session->userdata('u_id');
		$account = $this->user->find($u_id);
		$password=$this->input->post('password');
		$newpassword=$this->input->post('newpassword');
		$onemore=$this->input->post('onemore');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('newpassword','NewPassword','required');
		$this->form_validation->set_rules('onemore','Onemore','required');
		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message', "有欄位為空值");
			$this->session->set_flashdata('type', 'danger');
		}else
		{
			if ($password != $account->password)
			{
				$this->session->set_flashdata('message', "原密碼錯誤");
				$this->session->set_flashdata('type', 'danger');
			}else
			{
				if ($newpassword!=$onemore)
				{
					$this->session->set_flashdata('message', "2個新密碼不同");
					$this->session->set_flashdata('type', 'danger');
				}else
				{
					$query=$this->user->update(['password'=>$newpassword],['u_id'=>$u_id]);
					if ($query){
						$this->session->unset_userdata(['u_id','name','priority','priorityInProject','p_id','password']);
						$this->session->set_flashdata('message', "密碼修改成功,請重新登入");
						$this->session->set_flashdata('type', 'success');
						redirect('/index');
					}
				}
			}
		}
		redirect('/index');
	}
}