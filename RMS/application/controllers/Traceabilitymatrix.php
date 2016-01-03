<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Traceabilitymatrix extends CI_Controller
{
	private $currentProject =0;
	private $currentUser =0;
	function __construct()
	{
		parent::__construct();
		$this->currentProject = $this->session->userdata('p_id');
		$this->currentUser = $this->session->userdata('u_id');		
		$this->load->model('testcase_model','testcase');
		$this->load->model('requirement_model','requirement');
		$this->load->model('R_and_t_relation_model','RTModel');
		$this->load->model('R_and_r_relation_model','RRModel');
	} 

	public function index()
	{
		if(is_null($this->currentUser))
		{
			$this->session->set_flashdata('message', '尚未登入');
			$this->session->set_flashdata('type', 'danger');
			redirect('/index');
		}
		if(is_null($this->currentProject))
		{
			$this->session->set_flashdata('message', '尚未選擇專案');
			$this->session->set_flashdata('type', 'danger');
			redirect('/index');
		}
		$title ='Traceablity Matrix';
		$this->twig->display('rms/traceabilitymatrix/traceabilitymatrix.html',compact('title'));		
	}

	public function R_R_relation()
	{
		$title = 'Requirement and Requirement';
		$row_headers = $this->requirement->where(['p_id'=>$this->currentProject]);
		$col_headers = $this->requirement->where(['p_id'=>$this->currentProject]); 
		foreach ($col_headers as $col) {
			$relations=[];
			$col->id=$col->r_id;
			foreach($row_headers as $row)
			{
				$row->id=$row->r_id;
				if($this->RRModel->isExistRelation($col->r_id,$row->r_id)|| $this->RRModel->isExistRelation($row->r_id,$col->r_id))
					$relations[$row->r_id] = 'O';
				else if($col->r_id == $row->r_id)
					$relations[$row->r_id] = '--';				
				else  
					$relations[$row->r_id] = 'X';
			}
			$col->relations=$relations;
			# code...
		}
		$this->twig->display('rms/traceabilitymatrix/traceabilitymatrix.html',compact('title','row_headers','col_headers'));		
	}

	public function R_T_relation()
	{
		$title = 'Requirement and Testcase';
		$row_headers = $this->testcase->where(['p_id'=>$this->currentProject]);
		$col_headers = $this->requirement->where(['p_id'=>$this->currentProject]);
		foreach ($col_headers as $col) {
			$relations=[];
			$col->id=$col->r_id;
			foreach($row_headers as $row)
			{
				$row->id=$row->t_id;
				if($this->RTModel->isExistRelation($col->r_id,$row->t_id))
					$relations[$row->t_id] = 'O';
				else
					$relations[$row->t_id] = 'X';
			}
			$col->relations=$relations;
			# code...
		}
		$this->twig->display('rms/traceabilitymatrix/traceabilitymatrix.html',compact('title','row_headers','col_headers'));
	}
}