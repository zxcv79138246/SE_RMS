<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Requirementmanage extends CI_Controller
{
	private $u_id;
	private $p_id;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Requirement_model', 'requirement');
	}

	public function index()
	{
		$u_id = $this->session->userdata('u_id');
		$p_id = $this->session->userdata('p_id');
		$requirements = $this->requirement->getReqByPID($p_id);
		//	Functional value is 1, Non-functional value is 0 (in database)
		$functional_display = ['Non-functional', 'Functional'];
		$this->twig->display('rms/requirementmanage/requirementmanage.html', compact('requirements', 'functional_display'));
	}

	public function create()
	{
		$this->twig->display('rms/requirementmanage/create.html');
	}

	public function storeNormal()
	{
		if ($this->verification())
		{
			$requirementData = [
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
		redirect('/requirementmanage');
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
?>