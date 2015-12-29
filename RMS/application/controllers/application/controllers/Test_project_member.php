<?php
class Test_project_member extends CI_Controller
{
	private $user_model = 'user_model';
	
	private $user_data = [
		'email' => 'projectTest@gmail.com',
		'name' => 'TestAccount',
		'password' => 'passwd',
		'priority' => 2
	];
	
	private $project_model = 'project_model';
	
	private $project_data = [
		'name' => 'TestProject',
		'date' => Date('2015/12/18'),
		'description' => 'Testing',
		'leader' => $this->u_id
	];

	private $project_member_model = 'project_member_model';

	private $update_data = [
		'name' => 'Update test'
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
		
	}

}
?>