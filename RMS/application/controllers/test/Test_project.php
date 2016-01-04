<?php
class Test_project extends CI_Controller
{
	//	Data prepare
	private $user_model = 'user_model';
	private $user_data = [
		'email' => 'woooo@gmail.com',
		'name' => 'Yeah',
		'password' => '12345',
		'priority' => 1
	];

	private $project_model = 'project_model';
	private $update_data = [
		'name' => 'UpdateTest'
	];

	private $project_member_model = 'project_member_model';

	private $u_id;
	private $p_id;

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
		$this->load->model($this->project_member_model, 'project_member');
		//	Reset (Because the db maybe has the same record)
		//	Prepare
		if(!$this->user->where(['name'=>$this->user_data['name'],'email'=>$this->user_data['email']]))
			$user_insert = $this->user->insert($this->user_data);
		$this->u_id = $this->user->where(['name'=>$this->user_data['name'],'email'=>$this->user_data['email']])[0]->u_id;

		$project_data = [
			'name' => 'ProjectTest',
			'date' => Date('2016/01/01'),
			'description' => 'TestFirst',
			'leader' => $this->u_id
		];

		//	Test case
		$this->TEST_INSERT($project_data);
		$this->TEST_FIND_1($project_data);
		$this->TEST_WHERE($project_data);

		//GET P_ID AFTER INSERT
		$this->p_id = $this->project->where($project_data)[0]->p_id;
		//Insert project_member
		$member_data = [
			'p_id' => $this->p_id,
			'u_id' => $this->u_id,
			'priority' => '0'
		];

		if(!$this->project_member->where($member_data))
			$this->project_member->insert($member_data);

		$this->TEST_ALL($project_data);
		$this->TEST_PARTICIPATE();
		$this->TEST_FINDNOWFIRLD($project_data);

		$this->TEST_DELETEPM($member_data);
		$this->TEST_UPDATE($project_data);
		$this->TEST_DELETEP();
		$this->TEST_DELETEU($this->u_id);
		
		echo $this->unit->report();
	}

	function TEST_INSERT($data)
	{
		if(!$this->project->where(['name'=> $data['name'],'date'=>$data['date']])){
			$test = $this->project->insert($data);
			$this->unit->run($test, $data, 'PROJECT_INSERT_SUCCESS');
		}
		else
			$this->unit->run(!$this->project->where(['name'=> $data['name'],'date'=>$data['date']]), false, 'PROJECT_INSERT_FAILD');

		
	}

	//	find method will return a record which index = 0
	function TEST_FIND_1($data)
	{
		$test = $this->project->find($data['name']);
		$this->unit->run($test->name, $data['name'], 'PROJECT_FIND');
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

	function TEST_ALL($data)
	{
		$test = $this->project->all();
		for($i = 0; $i < count($test); $i++)
		{
			if(($test[$i]->name) == $data['name'])
				$loaddata = $test[$i]->leader;
		}
		$this->unit->run($loaddata,$data['leader'],'TEST_ALL');
	}

	function TEST_PARTICIPATE()
	{
		$test = $this->project->participate($this->u_id);
		$this->unit->run(count($test),1,'TEST_PARTICIPATE');
	}

	function TEST_FINDNOWFIRLD($data)
	{
		$test = $this->project->findNowField($this->p_id);
		$this->unit->run($test->leader,$data['leader'],'TEST_FINDNOWFIRLD');
	}

	function TEST_DELETEP()
	{
		$test = $this->project->destory(['name' => $this->update_data['name']]);
		$this->unit->run($test, true, 'PROJECT_DELETE');
	}

	function TEST_DELETEU($u_id)
	{
		$test = $this->user->destory(['u_id' => $u_id]);
		$this->unit->run($test, true, 'USER_DELETE');
	}

	function TEST_DELETEPM($member_data)
	{
		$test = $this->project_member->destory($member_data);
		$this->unit->run($test, true, 'PROJECT_MEMBER_DELETE');
	}
}
?>