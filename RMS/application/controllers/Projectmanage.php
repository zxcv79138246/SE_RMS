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
		$this->load->model('project_member_model','projectMember');
		$this->load->model('user_model','user');
		if ($this->session->userdata('priority') != 1)
		{
			$this->session->set_flashdata('message', '權限不足');
			$this->session->set_flashdata('type', 'danger');
			redirect('/index');
		}
	}

	public function index()
	{
		$projects = $this->project->where(['leader' => $this->session->userdata['u_id']]);
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
		$users = $this->user->where(['priority' => '0','priority' => '1']);
		$memberUser=[];
		$notMemberUser=[];
		foreach($users as $user)
		{
			if($this->projectMember->isMember($user->u_id,$p_id))
				$memberUser[count($memberUser)]=$user;
			else
				$notMemberUser[count($notMemberUser)]=$user;
		}
		$this->twig->display('rms/projectmanage/edit.html',compact('project','notMemberUser','memberUser'));
	}

	public function store()
	{
		if ($this->verification())
		{
			$projectData = [
				'leader' => $this->session->userdata['u_id'],
				'name' =>  $this->input->post('name'),
				'description' => $this->input->post('description'),
			];
			if (!$this->project->duplicateCheck(['name'=>$projectData['name']], 1)) 
			{
				if ($projects = $this->project->insert($projectData))
				{
					$this->session->set_flashdata('message', "新增專案：{$projectData['name']} 成功");
					$this->session->set_flashdata('type', 'success');
				}
			} else {
				$this->session->set_flashdata('message', "Name重複");
				$this->session->set_flashdata('type', 'danger');

			}
		}
		redirect('/usermanage');
	}
	public function addMember($u_id , $p_id)
	{	
		$data = ['u_id'=>$u_id,'p_id'=>$p_id];
		$this->projectMember->insert($data);
		redirect('/projectmanage');
	}

	public function removeMember($u_id , $p_id)
	{	
		$data = ['u_id'=>$u_id,'p_id'=>$p_id];
		$this->projectMember->destory($data);
		redirect('/projectmanage');
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