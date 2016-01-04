<?php

class Test_r_and_r_relation extends CI_Controller
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		// prepare Model
		$r_and_r_relation_Model_Name = 'r_and_r_relation_model';
		$requirement_Model_Name = 'requirement_model';
		$project_Model_Name = 'project_model';
		$user_Model_Name = 'user_model';

		$this->load->model($r_and_r_relation_Model_Name,'r_and_r_relation');
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
		$requirement1_Data =
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
		if(!$this->requirement->where($requirement1_Data)){
			$this->requirement->insert($requirement1_Data);
		}
		$r1_id = $this->requirement->where($requirement1_Data)[0]->r_id;

		$requirement2_Data =
		[	
			'p_id' =>$p_id,
			'name' =>'r2_tyeest',
			'functional' => false,
			'description' => 'paawantwo',
			'version'	=> 1,
			'level'	=> 2,
			'state'	=> '待審核',
			'owner' => $u_id,
			'memo' => 'I M Memo2'
		];
		if(!$this->requirement->where($requirement2_Data)){
			$this->requirement->insert($requirement2_Data);
		}
		$r2_id = $this->requirement->where($requirement2_Data)[0]->r_id;

		//INSERT
		$this->R_AND_R_INSERT($r1_id,$r2_id);

		//TEST
		//$this->TEST_REPEATCHECK($r1_id,$r2_id);
		$this->TEST_SEARCHRELREQ($r1_id);
		$this->TEST_ISEXISTRELEATION($r1_id,$r2_id);
		//$this->TEST_DESTORYRELEATION($r1_id,$r2_id);

		//DELETE DATA
		$this->RELEASE_TEST_DATA($user_Data,$project_Data,$requirement1_Data,$requirement2_Data,$r1_id,$r2_id);
		echo $this->unit->report();
	}

	function RELEASE_TEST_DATA($user_Data,$project_Data,$requirement1_Data,$requirement2_Data,$r1_id,$r2_id)
	{
		if($this->r_and_r_relation->where(['r_id1'=>$r1_id,'r_id2'=>$r2_id]))
			$this->r_and_r_relation->destory(['r_id1'=>$r1_id,'r_id2'=>$r2_id]);
		$this->requirement->destory($r1_id);
		$this->requirement->destory($r2_id);
		$this->project->destory('p_id = ' . $requirement1_Data['p_id']);
		if($this->project->where('p_id = ' . $requirement2_Data['p_id']))
			$this->project->destory('p_id = ' . $requirement2_Data['p_id']);
		$this->user->destory(['email' => $user_Data['email']]);
	}

	function R_AND_R_INSERT($r1_id,$r2_id)
	{
		if(!$this->r_and_r_relation->where(['r_id1'=>$r1_id,'r_id2'=>$r2_id]))
			$this->r_and_r_relation->insert(['r_id1'=>$r1_id,'r_id2'=>$r2_id]);
	}

	function TEST_REPEATCHECK($r1_id,$r2_id)
	{
		$data = [
			'r_and_r_relation.r_id1'=>$r1_id,
			'r_and_r_relation.r_id2'=>$r2_id
		];
		$test = $this->r_and_r_relation->repeatCheck($data, 1); 
		$this->unit->run($test,true,'REPEATCHECK');
	}

	function TEST_SEARCHRELREQ($r_id)
	{
		$test = $this->r_and_r_relation->searchRelReq($r_id);
		$this->unit->run((count($test)>0),true,'SEARCHRELREQ');
	}

	function TEST_ISEXISTRELEATION($r_id1,$r_id2)
	{
		$test = $this->r_and_r_relation->isExistRelation($r_id1,$r_id2);
		$this->unit->run($test,true,'ISEXISTRELEATION');
	}
	
	function TEST_DESTORYRELEATION($r_id,$delR_ids)
	{
		$test = $this->r_and_r_relation->destoryRelation($r_id,$delR_ids);
	}
	
}