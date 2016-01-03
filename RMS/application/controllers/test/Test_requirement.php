<?php

class Test_requirement extends CI_Controller
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		// prepare Model
		$requirement_Model_Name = 'requirement_model';
		$project_Model_Name = 'project_model';
		$user_Model_Name = 'user_model';

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
			'name' =>'r_tyeest',
			'functional' => false,
			'description' => 'paawanyee',
			'version'	=> 1,
			'level'	=> 2,
			'state'	=> '待審核',
			'owner' => $u_id,
			'memo' => 'I M Memo'
		];

		$update_Data =
		[
			'functional' => true,
			'version'	=> 2,
			'level'	=> 1,
			'state'	=> 'over',
		];

		//TEST AND GET R_ID
		$this->TEST_REQUIREMENT_INSERT($requirement_Data);
		$r_id = $this->requirement->where(['p_id' => $p_id, 'owner' => $u_id,'name' => $requirement_Data['name']])[0]->r_id;
		var_dump($r_id);
		// test requirementModel
		$this->TEST_REQUIREMENT_UPDATE($requirement_Data, $r_id);
		$this->TEST_REQUIREMENT_FIND($requirement_Data,$r_id);
		$this->TEST_GETREQBYPID($requirement_Data,$p_id);
		$this->TEST_DUPLICATECHECK($update_Data);
		$this->TEST_LIKESEARCH($p_id, $update_Data['functional'], 'functional');

		$this->RELEASE_TEST_DATA($user_Data,$project_Data,$requirement_Data,$r_id);
		echo $this->unit->report();
	}

	function RELEASE_TEST_DATA($user_Data,$project_Data,$requirement_Data,$r_id)
	{
		$this->requirement->destory($r_id);
		$this->project->destory('p_id = ' . $requirement_Data['p_id']);
		$email = $user_Data['email'];
		$this->user->destory(['email' => $email]);
	}

	function TEST_REQUIREMENT_INSERT($data)
	{
		if(!$this->requirement->where(['p_id' => $data['p_id'], 'owner' => $data['owner'],'name' => $data['name']])){
			$test = $this->requirement->insert($data);
			$this->unit->run($test,$data,'REQUIREMNET_INSERT');
		}
	}

	function TEST_REQUIREMENT_UPDATE($data,$r_id){
		$update_Data =
		[
			'functional' => true,
			'version'	=> 2,
			'level'	=> 1,
			'state'	=> 'over',
		];
		$this->requirement->update($update_Data,'r_id = ' . $r_id);
		$test = $this->requirement->find($r_id);

		$this->unit->run($test->functional,true,'REQUIREMENT_UPDATE_1');

		$this->unit->run($test->version,2,'REQUIREMENT_UPDATE_2');

		$this->unit->run($test->level,1,'REQUIREMENT_UPDATE_3');
		
		$this->unit->run($test->state,'over','REQUIREMENT_UPDATE_4');						
	}

	function TEST_REQUIREMENT_FIND($data,$r_id)
	{
		$test = $this->requirement->find($r_id);
		$this->unit->run($test->r_id,$r_id,"REQUIREMENT_FIND_1");
		$this->unit->run($test->name,$data['name'],"REQUIREMENT_FIND_2");
		$this->unit->run($test->owner,$data['owner'],"REQUIREMENT_FIND_3");
	}

	function TEST_GETREQBYPID($data,$p_id)
	{
		$test = $this->requirement->getReqByPID($p_id);
		$this->unit->run($test[0]->name,$data['name'],'GETREQBYPID');
	}

	function TEST_DUPLICATECHECK($data)
	{
		$test = $this->requirement->duplicateCheck($data ,1);
		$this->unit->run($test,true,'DUPLICATECHECK');
	}

	function TEST_LIKESEARCH($p_id, $condition, $target)
	{
		$test = $this->requirement->like_search($p_id, $condition, $target);
		$this->unit->run(count($test)>0 , true , 'TEST_LIKESEARCH');
	}

}