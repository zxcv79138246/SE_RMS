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
		if ($this->session->userdata('priority') == null)
		{
			$this->session->set_flashdata('message', '尚未登入');
			$this->session->set_flashdata('type', 'danger');
			redirect('/index');
		}
	}

	public function index()
	{
		$u_id = $this->session->userdata('u_id');
		$projects = $this->project->participate($u_id);
		$this->twig->display('rms/projectmanage/projectmanage.html', compact('projects'));
	}

	public function destory($p_id)
	{
		if ($destoried = $this->project->destory(['p_id'=>$p_id]))
		{
			$this->session->set_flashdata('message',"專案：{$destoried[0]->name} 已被刪除");
			$this->session->set_flashdata('type','warning');
		}
		redirect('/projectmanage');
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
		$users = $this->user->editUser();
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
					$insertID=$this->db->insert_id();
					$projectMember = [
						'p_id' => $insertID,
						'u_id' => $this->session->userdata('u_id'),
						'priority' => 2,
					];
					if ($this->projectMember->insert($projectMember))
					{
						$this->session->set_flashdata('message', "新增專案：{$projectData['name']} 成功");
						$this->session->set_flashdata('type', 'success');	
					}
				}
			} else {
				$this->session->set_flashdata('message', "Name重複");
				$this->session->set_flashdata('type', 'danger');

			}
		}
		redirect('/projectmanage');
	}

	public function getProjectData($p_id)
	{
		$members = $this->projectMember->allMemberName($p_id);
		//var_dump($p_id);
		if ($members)
			echo json_encode($members);
	}

	public function addMember()
	{	
		$u_id = $this->input->post('u_id');
		$p_id = $this->input->post('p_id');
		$priority = $this->input->post('priority');
		$data = ['u_id'=>$u_id,'p_id'=>$p_id,'priority'=>$priority];
		$result = $this->projectMember->insert($data);
		$newmember = NULL;
		if ($result)
			$newmember = $this->projectMember->memberDataInProject($u_id,$p_id);
		echo json_encode($newmember);

	}

	public function removeMember($u_id , $p_id)
	{	
		$data = ['u_id'=>$u_id,'p_id'=>$p_id];
		$resault = $this->projectMember->destory($data);
		$user = $this->user->find($u_id);
		if ($resault)
			echo json_encode($user);
	}

	public function update($p_id)
	{
		if ($this->verification())
		{
			$projectData = [
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
			];

			$nowfield = $this->project->findNowField($p_id);
			$nameChange=0;
			if ($projectData['name'] != $nowfield->name){
				$nameChange = 1;
			}
			if (!$this->project->duplicateCheck(['name'=>$projectData['name']], $nameChange)) 
			{	
				if ($projects = $this->project->update($projectData,['p_id' => $p_id]))
				{
					$this->session->set_flashdata('message', "{$userdata['name']} 修改成功");
					$this->session->set_flashdata('type', 'success');
				}
			} else {
				$this->session->set_flashdata('message', "Name重複");
				$this->session->set_flashdata('type', 'danger');

			}
		}
		redirect('/projectmanage');	
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

	public function intoProject($p_id)
	{
		
		$u_id = $this->session->userdata('u_id');
		
		$priorityInProject = $this->projectMember->getPriority($u_id,$p_id);
		$data = ['p_id'=>$p_id,'priorityInProject'=>$priorityInProject->priority];
		$this->session->set_userdata($data); 
		redirect('/requirementmanage');
	}
}