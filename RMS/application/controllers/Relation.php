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

	public function storeRT()
	{
		$requirement_ids = $this->input->post('requirement_to[]');
		$test_ids = $this->input->post('test_to[]');
		$data = [];
		foreach ($requirement_ids as $key => $requirement) {
			foreach ($test_ids as $key => $test) {
				$data[] = ['r_id'=>$requirement,'t_id'=>$test];
			}
		}
		$request = $this->r_t->insertBatch($data);
		if ($request){
			$this->session->set_flashdata('message','新增關聯成功');
			$this->session->set_flashdata('type','success');
			redirect('/relation');
		}
	}

	public function storeRR()
	{
		$req1_ids = $this->input->post('req1_to');
		$req2_ids = $this->input->post('req2_to');
		$data = [];
		foreach ($req1_ids as $key => $req1) {
			foreach ($req2_ids as $key => $req2) {
				$data[] = ['r_id1'=>$req1,'r_id2'=>$req2];
			}
		}
		$request = $this->r_r->insertBatch($data);
		if ($request){
			$this->session->set_flashdata('message','新增關聯成功');
			$this->session->set_flashdata('type','success');
			redirect('/relation');
		}
	}

	public function deleteRel()
	{
		$requirements = $this->requirememt->all();
		$testcases = $this->testcase->all();
		$relReqs="";
		$relTests="";
		$req="";
		$test="";
		$this->twig->display('rms/relation/del_rt.html',compact('requirements','testcases','relReqs','relTests','req','test'));
	}

	public function searchReqRel()
	{
		$r_id = $this->input->get('sel_req');
		$req=$r_id;
		$test="";
		$requirements = $this->requirememt->all();
		$testcases = $this->testcase->all();
		$relReqsReqults = $this->r_r->searchRelReq($r_id);
		$relReqs =[];
		foreach ($relReqsReqults as $key => $relReqsReqult) {
			if ($relReqsReqult->r1_id == $r_id)
			{
				$relReqs[] = ['r_id'=>$relReqsReqult->r2_id, 'name'=>$relReqsReqult->r2Name];
			}
			else
			{
				$relReqs[] = ['r_id'=>$relReqsReqult->r1_id, 'name'=>$relReqsReqult->r1Name];
			}
		}
		$relTests=$this->r_t->searchRelTest($r_id);
		$this->twig->display('rms/relation/del_rt.html',compact('requirements','testcases','relReqs','relTests','req','test'));
	}

	public function searchTestRel()
	{
		$t_id = $this->input->get('sel_test');
		$req="";
		$test=$t_id;
		$requirements = $this->requirememt->all();
		$testcases = $this->testcase->all();
		$relReqs=$this->r_t->searchRelReq($t_id);
		$relTests="";
		$this->twig->display('rms/relation/del_rt.html',compact('requirements','testcases','relReqs','relTests','req','test'));
	}
}