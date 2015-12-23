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

	public function create()
	{
		$this->twig->display('rms/usermanage/create.html');
	}

	public function edit($u_id)
	{
		$user = $this->user->find($u_id);
		$this->twig->display('rms/usermanage/edit.html',compact('user'));

	}

	public function store()
	{
		if ($this->verification())
		{
			$userdata = [
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'password' => $this->input->post('password'),
				'priority' => $this->input->post('priority'),
			];
			if (!$this->user->duplicateCheck(['email'=>$userdata['email']], 1)) 
			{
				if ($users = $this->user->insert($userdata))
				{
					$this->session->set_flashdata('message', "新增使用者：{$userdata['name']} 成功");
					$this->session->set_flashdata('type', 'success');
				}
			} else {
				$this->session->set_flashdata('message', "Email重複");
				$this->session->set_flashdata('type', 'danger');

			}
		}
		redirect('/usermanage');
	}

	public function update($u_id)
	{
		if ($this->verification())
		{
			$userdata = [
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'password' => $this->input->post('password'),
				'priority' => $this->input->post('priority'),
			];
			if (!$this->user->duplicateCheck(['email'=>$userdata['email']], 0)) 
			{
				if ($users = $this->user->update($userdata,['u_id' => $u_id]))
				{
					$this->session->set_flashdata('message', "{$userdata['name']} 修改成功");
					$this->session->set_flashdata('type', 'success');
				}
			} else {
				$this->session->set_flashdata('message', "Email重複");
				$this->session->set_flashdata('type', 'danger');

			}
		}
		redirect('/usermanage');	
	}

	public function verification()
	{
		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('email','Email','required');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('priority','Priority','required');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message', "有欄位為空值");
			$this->session->set_flashdata('type', 'danger');
		}
		else
			return true;
	}

	public function search()
	{
		$condition = $this->input->get('search');
		$users = $this->user->search(['name','email'],$condition);
		if (!$users)
		{
			$this->session->set_flashdata('message', "搜尋不到相似資料或內容不存在");
			$this->session->set_flashdata('type', 'danger');

			redirect('/usermanage');
		}else
		{
			$this->twig->display('rms/usermanage/usermanage.html', compact('users'));

		}
		
	}
}