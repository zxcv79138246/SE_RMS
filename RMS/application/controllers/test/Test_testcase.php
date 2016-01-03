<?php

class Test_testcase extends CI_Controller
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		// prepare Model
		$testcase_Model_Name = 'testcase_model';
		$project_Model_Name = 'project_model';
		$user_Model_Name = 'user_model';

		$this->load->model($project_Model_Name,'project');
		$this->load->model($testcase_Model_Name,'test_case');
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

		// 	test_case Data
		$testcase_Data =
		[	
			'name' =>'t_tyeest',
			'description' => 'paawanyee',
			'input_data' => 'ininput',
			'expected_results' => 'paaandyee',
			'owner' => $u_id,
			'attachment' => '翻譯翻譯',
			'p_id' =>$p_id,
		];

		$update_Data =
		[
			'description' => 'MCpaawanyee',
			'input_data' => 'threeeeeee',
			'expected_results' => 'yeeandpaa',
			'attachment'	=> 'Yes'
		];

		//TEST AND GET T_ID
		$this->TEST_TESTCASE_INSERT($testcase_Data);
		$t_id = $this->test_case->where($testcase_Data)[0]->t_id;

		// test test_caseModel
		//$this->TEST_TESTCASE_UPDATE($testcase_Data, $t_id);
		$this->TEST_TESTCASE_FIND($testcase_Data,$t_id);
		$this->TEST_GETTESTBYPID($testcase_Data,$p_id);
		$this->TEST_DUPLICATECHECK($testcase_Data);
		$this->TEST_SEARCHTESTCASE($p_id, $testcase_Data['expected_results'], 'expected_results');

		$this->RELEASE_TEST_DATA($user_Data,$project_Data,$testcase_Data,$t_id);
		echo $this->unit->report();
	}

	function RELEASE_TEST_DATA($user_Data,$project_Data,$testcase_Data,$t_id)
	{
		$this->test_case->destory($t_id);
		$this->project->destory('p_id = ' . $testcase_Data['p_id']);
		$email = $user_Data['email'];
		$this->user->destory(['email' => $email]);
	}

	function TEST_TESTCASE_INSERT($data)
	{
		if(!$this->test_case->where(['p_id' => $data['p_id'], 'owner' => $data['owner'],'name' => $data['name']])){
			$test = $this->test_case->insert($data);
			$this->unit->run($test,$data,'REQUIREMNET_INSERT');
		}
	}

	function TEST_TESTCASE_UPDATE($data,$t_id){
		$update_Data =
		[
			'description' => 'MCpaawanyee',
			'input_data' => 'threeeeeee',
			'expected_results' => 'yeeandpaa',
			'attachment'	=> 'Yes'
		];
		$this->test_case->update($update_Data,'t_id = ' . $t_id);
		$test = $this->test_case->find($t_id);

		$this->unit->run($test->description,'MCpaawanyee','TESTCASE_UPDATE_1');

		$this->unit->run($test->input_data,'threeeeeee','TESTCASE_UPDATE_2');

		$this->unit->run($test->expected_results,'yeeandpaa','TESTCASE_UPDATE_3');
		
		$this->unit->run($test->attachment,'Yes','TESTCASE_UPDATE_4');						
	}

	function TEST_TESTCASE_FIND($data,$t_id)
	{
		$test = $this->test_case->find($t_id);
		$this->unit->run($test->t_id,$t_id,"TESTCASE_FIND_1");
		$this->unit->run($test->name,$data['name'],"TESTCASE_FIND_2");
		$this->unit->run($test->owner,$data['owner'],"TESTCASE_FIND_3");
	}

	function TEST_GETTESTBYPID($data,$p_id)
	{
		$test = $this->test_case->getTestByPID($p_id);
		$this->unit->run($test[0]->name,$data['name'],'GETTESTBYPID');
	}

	function TEST_DUPLICATECHECK($data)
	{
		$test = $this->test_case->duplicateCheck($data ,1);
		$this->unit->run($test,true,'DUPLICATECHECK');
	}

	function TEST_SEARCHTESTCASE($p_id, $condition, $target)
	{
		$test = $this->test_case->searchTestcase($p_id, $condition, $target);
		$this->unit->run(count($test)>0 , true , 'TEST_SEARCHTESTCASE');
	}

}