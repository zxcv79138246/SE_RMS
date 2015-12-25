<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Index extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('project_model','project');
		$this->load->model('project_member_model','projectMember');	

	}

	public function index()
	{
		$projects=[];
		if(!is_null($this->session->userdata['u_id'])){
			$Data = $this->projectMember->where(['u_id'=>$this->session->userdata['u_id']]);
			foreach ($Data as $get_id)
			{
				$project=$this->project->findProject($get_id->p_id);
				$project->leaderName = $this->project->getLeaderName($get_id->p_id);
				$projects[count($projects)]=$project;
			}
		} 		
		$this->twig->display('rms/projectlist/projectlist.html', compact('projects'));
	}
}