<?php

/**
* 
*/
class Test_user extends CI_Controller
{


	function __construct() {
		parent::__construct();
		$this->load->library('unit_test');
	}
	
	function index()
	{
		$model_name = 'user_model';
		$data = [
			'email' => 'ulsine83@gmail.com',
			'name' => 'YEWEILUN',
			'password' => '12345',
			'priority' => 3
		];
		$this->load->model($model_name, 'user');
		
		
		$this->TEST_USER_INSERT($data);
		$this->TEST_USER_FIND($data);
		$this->TEST_USER_DESTROY($data);

		echo $this->unit->report();
	}

	function TEST_USER_INSERT($data){
		//	TEST INSERT
		$test = $this->user->insert($data);
		$this->unit->run($test, $data, 'USER_INSERT');

	}

	function TEST_USER_FIND($data){
			//TEST FIND
			$test = $this->user->where(['email' => $data['email']])[];
			$this->unit->run($test->email,$data['email'],'USER_FIND_EMAIL');
			$this->unit->run($test->name,$data['name'],'USER_FIND_NAME');
			$this->unit->run($test->password,$data['password'],'USER_FIND_PASSWORD');
			$this->unit->run($test->priority,$data['priority'],'USER_FIND_PRIORITY');
	}

	function TEST_USER_DESTROY($data){
		// TEST DELETE
		$test = $this->user->destory($data);
		$this->unit->run($test, true, 'USER_DELETE');		
	}
}

