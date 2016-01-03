<?php

class Test_r_and_t_relation extends CI_Controller
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		// prepare Model
		$r_and_t_relation_Model_Name = 'r_and_t_relation_model';
		$testcase_Model_Name = 'testcase_model';
		$requirement_Model_Name = 'requirement_model';
		$project_Model_Name = 'project_model';
		$user_Model_Name = 'user_model';

		$this->load->model($r_and_t_relation_Model_Name,'r_and_t_relation');
		$this->load->model($testcase_Model_Name,'test_case');
		$this->load->model($project_Model_Name,'project');
		$this->load->model($requirement_Model_Name,'requirement');
		$this->load->model($user_Model_Name,'user');
		
		// prepare TEST DATA
		// 	user Data
		$user_Data = 
		[
			'name' => 'TestingGod',
			'email' => 'God@gmail.com',
			'password' => 'max9999',
			'priority' => '0'		
		];
		if(!$this->user->where(['email'=> $user_Data['email'],'name'=> $user_Data['name']]))
		{
			$this->user->insert($user_Data);
		}
		$nowuser = $this->user->where(['email'=> $user_Data['email'],'name'=> $user_Data['name']]);
		$u_id = $nowuser[0]->u_id;
		
		// 	project Data
		$project_Data = 
		[
			'name' => 'GodTestProject',
			'date' => DATE('2016/01/01'),
			'description' => 'hello Project God',
			'leader'	=> $u_id
		];	
		if(!$this->project->find($project_Data['name']))
			$this->project->insert($project_Data);
		$nowproject = $this->project->find($project_Data['name']);
		$p_id = $nowproject->p_id;

		// 	requirement Data
		$requirement_Data =
		[	
			'p_id' =>$p_id,
			'name' =>'r1_tyeest',
			'functional' => false,
			'description' => 'paawanyee',
			'version'	=> 1,
			'level'	=> 2,
			'state'	=> '待審核',
			'owner' => $u_id,
			'memo' => 'I M Memo1'
		];
		if(!$this->requirement->where($requirement_Data)){
			$this->requirement->insert($requirement_Data);
		}
		$r_id = $this->requirement->where($requirement_Data)[0]->r_id;

		$testcase_Data =
		[	
			'name' =>'t_tyeest',
			'description' => 'paawanyee',
			'input_data' => 'ininput',
			'expected_results' => 'paaandyee',
			'owner' => $u_id,
			'attachment' => '翻譯翻譯',
			'p_id' =>$p_id
		];
		if(!$this->test_case->where($testcase_Data)) {
			$test = $this->test_case->insert($testcase_Data);
		}
		$t_id = $this->test_case->where($testcase_Data)[0]->t_id;

		//INSERT
		$this->TEST_R_AND_T_INSERT($r_id,$t_id);

		//$this->TEST_REPEATCHECK($r_id,$t_id);
		$this->TEST_SEARCHRELTEST($r_id);
		$this->TEST_SEARCHRELREQ($t_id);
		$this->TEST_ISEXISTRELEATION($r_id,$t_id);
		//$this->TEST_DESTORYRELEATION($r1_id,$r2_id);

		$this->RELEASE_TEST_DATA($user_Data,$project_Data,$requirement_Data,$testcase_Data,$r_id,$t_id);
		echo $this->unit->report();
	}

	function RELEASE_TEST_DATA($user_Data,$project_Data,$requirement_Data,$testcase_Data,$r_id,$t_id)
	{
		$this->r_and_t_relation->destory(['r_id'=>$r_id,'t_id'=>$t_id]);
		$this->requirement->destory($r_id);
		$this->test_case->destory($t_id);
		$this->project->destory('p_id = ' . $requirement_Data['p_id']);
		if($this->project->where('p_id = ' . $testcase_Data['p_id']))
			$this->project->destory('p_id = ' . $testcase_Data['p_id']);
		$this->user->destory(['email' => $user_Data['email']]);
	}

	function TEST_R_AND_T_INSERT($r_id,$t_id)
	{
		if(!$this->r_and_t_relation->where(['r_id'=>$r_id,'t_id'=>$t_id]))
			$this->r_and_t_relation->insert(['r_id'=>$r_id,'t_id'=>$t_id]);
	}

	function TEST_REPEATCHECK($r_id,$t_id)
	{
		$data = [
			'r_and_r_relation.r_id'=>$r_id,
			'r_and_t_relation.t_id'=>$t_id
		];
		$test = $this->r_and_t_relation->repeatCheck($data, 1); 
		$this->unit->run($test,true,'REPEATCHECK');
	}

	function TEST_SEARCHRELTEST($r_id)
	{
		$test = $this->r_and_t_relation->searchRelTest($r_id);
		$this->unit->run((count($test)>0),true,'SEARRELTEST');
	}

	function TEST_SEARCHRELREQ($t_id)
	{
		$test = $this->r_and_t_relation->searchRelReq($t_id);
		$this->unit->run((count($test)>0),true,'SEARCHRELREQ');
	}

	function TEST_ISEXISTRELEATION($r_id,$t_id)
	{
		$test = $this->r_and_t_relation->isExistRelation($r_id,$t_id);
		$this->unit->run($test,true,'ISEXISTRELEATION');
	}
	
	function TEST_DESTORYRELEATION($r_id,$delR_ids)
	{
		$test = $this->r_and_t_relation->destoryRelation($r_id,$delR_ids);
	}

}