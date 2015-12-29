<?php
class Test_project extends CI_Controller
{
	//	Data prepare
	private $user_model = 'user_model';
	private $user_data = [
		'email' => 'wsx741405@gmail.com',
		'name' => 'WeiCheng',
		'password' => '123456789',
		'priority' => 1
	];
	private $project_model = 'project_model';
	private $update_data = [
		'name' => 'Update test'
	];
	private $u_id;

	function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		//	Load Model
		$this->load->model($this->user_model, 'user');
		$this->load->model($this->project_model, 'project');
		//	Reset (Because the db maybe has the same record)
		//	Prepare
		$this->PREPARE();
		$project_data = [
			'name' => 'Project',
			'date' => Date('2015/12/17'),
			'description' => 'Test',
			'leader' => $this->u_id
		];
		//	Test case
		$this->TEST_INSERT($project_data);
		$this->TEST_FIND_1($project_data);
		$this->TEST_FIND_2($project_data);
		$this->TEST_WHERE($project_data);
		$this->TEST_UPDATE($project_data);
		$this->TEST_DELETE();
		echo $this->unit->report();
	}

	function PREPARE()
	{
		//	Insert user (Because attribute leader in table project is f.k.)
		$result = $this->user->find($this->user_data['email']);
		if(!$result)
			$user_insert = $this->user->insert($this->user_data);
		$this->u_id = $result->u_id;
	}

	function TEST_INSERT($data)
	{
		$test = $this->project->insert($data);
		$this->unit->run($test, $data, 'PROJECT_INSERT');
	}

	//	find method will return a record which index = 0
	function TEST_FIND_1($data)
	{
		$test = $this->project->find($data['name']);
		$this->unit->run($test->description, $data['description'], 'PROJECT_FIND');
	}

	function TEST_FIND_2($data)
	{
		$test = $this->project->find($data['name']);
		$this->unit->run($test->leader, $this->u_id, 'RPOJECT_FIND');
	}

	//	where method will return a table, maybe has multi-record
	function TEST_WHERE($data)
	{
		$condition = ['name' => $data['name']];
		$test = $this->project->where($condition);
		$this->unit->run($test[0]->description, $data['description'], 'PROJECT_WHERE');
	}

	function TEST_UPDATE($data)
	{
		$condition = ['name' => $data['name']];
		$test = $this->project->update($this->update_data, $condition);	
		$this->unit->run($test, true, 'PROJECT_UPDATE');
	}

	function TEST_DELETE()
	{
		$test = $this->project->destory(['name' => $this->update_data['name']]);
		$this->unit->run($test, true, 'PROJECT_DELETE');
	}
}
?>