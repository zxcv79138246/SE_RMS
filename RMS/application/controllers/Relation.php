<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Relation extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('requirement_model','requirememt');
		$this->load->model('testcase_model','testcase');
		$this->load->model('r_and_r_relation_model','r_r');
		$this->load->model('r_and_t_relation_model','r_t');
		$this->load->model('project_member_model','projectmember');
		$this->load->model('project_model');
	}

	public function index()				//初始 (需求＆Test case)
	{
		$requirements = $this->requirememt->all();
		$testcases = $this->testcase->all();
		$this->twig->display('rms/relation/r_and_t.html',compact('requirements','testcases'));
	}

	public function reqAndReqPqge()
	{
		$requirements = $this->requirememt->all();
		$this->twig->display('rms/relation/r_and_r.html',compact('requirements','testcases'));
	}

	public function repeatRTCheck()
	{
		$requirement_ids = $this->input->get('requirement');
		$test_ids = $this->input->get('tests');
		$requirements = explode(',', $requirement_ids);
		$tests = explode(',', $test_ids);
		$message = [];
		if (!($test_ids == '' || $requirement_ids == '')) {
			if ($result = $this->r_t->repeatCheck(['r_and_t_relation.r_id'=>$requirements,'r_and_t_relation.t_id'=>$tests], 1))
			{
				$message[] = $result->reqName . ' 跟 ' . $result->testName . ' 衝突';
			}
		}
		echo json_encode([
			'message' => implode('<br>', $message),
			'state' => (count($message)) ? 'danger' : 'safe',
		]);
	}

	public function repeatRRCheck()
	{
		$req1_ids = $this->input->get('req1');
		$req2_ids = $this->input->get('req2');
		$req1s = explode(',', $req1_ids);
		$req2s = explode(',', $req2_ids);
		$message = [];
		$isSame=0;
		foreach ($req1s as $key => $req1) {
			foreach ($req2s as $key => $req2) {
				if ($req1 == $req2)
				{
					$message[] = '相同需求不能建立關聯';
					$isSame = 1;
					break;
				}			
			}
			if ($isSame == 1) break;
		}
		
		if (!($req2_ids == '' || $req1_ids == '')) {
			if ($result = $this->r_r->repeatCheck(['r_and_r_relation.r_id1'=>$req1s,'r_and_r_relation.r_id2'=>$req2s], 1))
			{
				$message[] = $result->r1Name . ' 跟 ' . $result->r2Name . ' 衝突';
			}
		}
		echo json_encode([
			'message' => implode('<br>', $message),
			'state' => (count($message)) ? 'danger' : 'safe',
		]);
	}
}