<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Projectmanage extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('project_model', 'project');
		if ($this->session->userdata('priority') != 2)
		{
			$this->session->set_flashdata('message', '權限不足');
			$this->session->set_flashdata('type', 'danger');
			//redirect('/index');
		}
	}

	public function index()
	{
		$projects = $this->project->all();
		$this->twig->display('rms/projectmanage/projectmanage.html', compact('projects'));
	}

	public function destory($p_id)
	{
		if ($destoried = $this->project->destory(['p_id'=>$p_id]))
		{
			$this->session->set_flashdata('message',"{$destoried[0]->name} 已被刪除");
			$this->session->set_flashdata('type','warning');
		}
		redirect('/index');
	}

	public function create()
	{	
		if ($this->session->userdata('priority') != 1)
		{
			$this->session->set_flashdata('message', '權限不足');
			$this->session->set_flashdata('type', 'danger');
		}
		else 
			$this->twig->display('rms/projectmanage/create.html');
	}

	public function edit($p_id)
	{
		$project = $this->project->where(['p_id' =>$p_id])[0];
		$this->twig->display('rms/projectmanage/edit.html',compact('project'));

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

	public function update($p_id)
	{
		if ($this->verification())
		{
			$projectData = [
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
			];
			if (!$this->project->duplicateCheck(['name'=>$projectData['name']], 0)) 
			{	
				if ($projects = $this->project->update($projectData,['p_id' => $p_id]))
				{
					$this->session->set_flashdata('message', "{$userdata['name']} 修改成功");
					$this->session->set_flashdata('type', 'success');
				}
			} else {
				$this->session->set_flashdata('message', "Email重複");
				$this->session->set_flashdata('type', 'danger');

			}
		}
		redirect('/index');	
	}

	public function verification()
	{
		$this->form_validation->set_rules('name','Name','required');
		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message', "有欄位為空值");
			$this->session->set_flashdata('type', 'danger');
		}
		else
			return true;
	}
}