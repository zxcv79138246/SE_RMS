<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Testcasemanage extends CI_Controller
{
	private $currentProject = 135;
	function __construct()
	{
		parent::__construct();
		$this->load->model('testcase_model','testcase');
	}

	public function selectProject($p_id)
	{
		$this->currentProject=$p_id;
		$testcases = $this->testcase->where(['p_id'=>$this->currentProject]);
		$this->twig->display("rms/testcasemanage/testcasemanage.html",compact("testcases"));
	}

	public function index()
	{
		$testcases = $this->testcase->where(['p_id'=>$this->currentProject]);
		$this->twig->display("rms/testcasemanage/testcasemanage.html",compact("testcases"));
	}

	public function create()
	{
		$this->twig->display("rms/testcasemanage/create.html");	
	}

	public function store()
	{
		$data =
		[
			'name' => $this->input->post('testcaseName'),
			'description' => $this->input->post('testcaseDescription'),
			'input_data' => $this->input->post('testcaseInput_data'),
			'expected_results' => $this->input->post('testcaseExpected_results'),
			'owner'	=> $this->session->userdata('u_id'),
			'p_id'	=> $this->currentProject,
			'attachment' => $this->input->post('testcaseAttachment'),
		];
		if($this->testcase->duplicateCheck(['name'=>$data['name'],'p_id'=>$data['p_id']],1))
		 {
				$this->session->set_flashdata('message', "Testcase名稱重複");
				$this->session->set_flashdata('type', 'danger');
		}
		else 
		{
				$this->testcase->insert($data);
				$this->session->set_flashdata('message', "TestCase {$data['name']} 新增成功");
				$this->session->set_flashdata('type', 'success');
		}	
		redirect('/testcasemanage');
	}

	public function destroy($t_id)
	{	
		if ($destoried = $this->testcase->destory(['t_id'=>$t_id]))
		{
			$this->session->set_flashdata('message',"{$destoried[0]->name} 已被刪除");
			$this->session->set_flashdata('type','warning');
		}
		redirect('/testcasemanage');
	}

	public function show($t_id)
	{
		$testcase = $this->testcase->where(['t_id'=>$t_id])[0];
		$this->twig->display("rms/testcasemanage/show.html",compact('testcase'));	
	}
} 
