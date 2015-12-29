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
			'name' => 'YeWeiLun',
			'email' => 'ulsine83@gmail.com',
			'password' => '299792458',
			'priority' => '0'		
		];
		if(!$this->user->find($user_Data['email']))
			$this->user->insert($user_Data);
		
		// 	project Data
		$leader_id = $this->user->find($user_Data['email'])->u_id;
		$project_Data = 
		[
			'name' => 'TestProject',
			'date' => DATE('2015/12/17'),
			'description' => 'hello Project',
			'leader'	=> $leader_id
		];	
		if(!$this->project->find($project_Data['name']))
			$this->project->insert($project_Data);
		// 	requirement Data
		$project_id = $this->project->find($project_Data['name'])->p_id;
		$requirement_Data =
		[	
			'r_id' =>1,
			'p_id' =>$project_id,
			'name' =>'diet',
			'functional' => false,
			'description' => 'handsome',
			'version'	=> 1,
			'level'	=> 2,
			'state'	=> 'break down!',
			'owner' => $leader_id
		];

		// test requirementModel
		$this->TEST_REQUIREMENT_INSERT($requirement_Data);
		$this->TEST_REQUIREMENT_UPDATE($requirement_Data);
		$this->TEST_REQUIREMENT_FIND($requirement_Data);

		$this->RELEASE_TEST_DATA($user_Data,$project_Data,$requirement_Data);
		echo $this->unit->report();
	}

	function RELEASE_TEST_DATA($user_Data,$project_Data,$requirement_Data)
	{
		$this->requirement->destory('r_id=1');
		$this->project->destory('p_id = ' . $requirement_Data['p_id']);
		$email = $user_Data['email'];
		var_dump($email);
		$this->user->destory(['email' => $email]);
	}

	function TEST_REQUIREMENT_INSERT($data)
	{
		if(!$this->requirement->find(1)){
			$test = $this->requirement->insert($data);
			$this->unit->run($test,$data,'REQUIREMNET_INSERT');
		}
	}

	function TEST_REQUIREMENT_UPDATE($data){
		$update_Data =
		[
			'functional' => true,
			'version'	=> 4,
			'level'	=> 1,
			'state'	=> 'over',
		];
		$this->requirement->update($update_Data,'r_id'.'= 1');
		$test = $this->requirement->find($data['r_id']);

		$this->unit->run($test->functional,true,'REQUIREMENT_UPDATE_1');

		$this->unit->run($test->version,4,'REQUIREMENT_UPDATE_2');

		$this->unit->run($test->level,1,'REQUIREMENT_UPDATE_3');
		
		$this->unit->run($test->state,'over','REQUIREMENT_UPDATE_4');						
	}

	function TEST_REQUIREMENT_FIND($data)
	{
		$test = $this->requirement->find($data['r_id']);
		$this->unit->run($test->r_id,$data['r_id'],"REQUIREMENT_FIND_1");
		$this->unit->run($test->name,$data['name'],"REQUIREMENT_FIND_2");
		$this->unit->run($test->owner,$data['owner'],"REQUIREMENT_FIND_3");
	}

}