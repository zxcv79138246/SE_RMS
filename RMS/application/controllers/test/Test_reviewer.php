<?php
class Test_reviewer extends CI_Controller
{
	//	Load model
	private $user_model = 'user_model';
	private $reviewer_model = 'reviewer_model';
	//	data
	private $user_data;
	private $project_data;
	private $reviewer_data;
	//	Id
	private $u_id;
	function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
	}

	function prepareUser()
	{
		$this->load->model($user_model, 'user');
		//	Data prepare : user
		$this->user_data = [
			'email' => 'wsx741405@gmail.com',
			'name' => 'WeiCheng',
			'password' => '123456789',
			'priority' => 1
		];
		$result = $this->user->find($this->user_data)
		if()
	}

	function prepareProject()
	{
		$this->load->model($project_model, 'project')
		//	Data prepare : project
		$this->project_data = [
			'name' => 'Project',
			'date' => Date('2015/12/17'),
			'description' => 'Test',
			'leader' => $this->u_id
		];
	}
	
	function prepareReviewer()
	{
		//	Data prepare : reviewer
		$this->reviewer_data = [
			'decision' => 0;
			'comment' => "Time isn't enough";
		];
	}
}
?>