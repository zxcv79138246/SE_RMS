<?php
class Test_project_member extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		$user_model = 'user_model';	
		$project_model = 'project_model';
		$project_member_model = 'project_member_model';

		$user_data = [
			'email' => 'TestMan@gmail.com',
			'name' => 'TestManS',
			'password' => 'passwd',
			'priority' => 0
		];

		$update_data = [
			'name' => 'Update test'
		];
		//1 33 36 46  //2 46 49
		//load model
		$this->load->model($user_model, 'user');
		$this->load->model($project_model, 'project');
		$this->load->model($project_member_model, 'project_member');

		//	Insert user 
		if(!$this->user->where(['email' => $user_data['email']]))
			$this->user->insert($user_data);
		$u_id = $this->user->where(['email' => $user_data['email']])[0]->u_id;
		$name = $this->user->where(['u_id'=>$u_id])[0]->name;

		//	Insert project
		$project_data = [
			'name' => 'TestProject',
			'date' => Date('2015/12/18'),
			'description' => 'Testing',
			'leader' => $u_id
		];

		if(!$this->project->where(['name'=> $project_data['name']]))
			 $this->project->insert($project_data);
		$p_id = $this->project->where(['name'=> $project_data['name']])[0]->p_id;

		//Insert project_member
		$member_data = [
			'p_id' => $p_id,
			'u_id' => $u_id,
			'priority' => '0'
		];

		$inmember = ['p_id' => $p_id,'u_id' => $u_id];
		if(!$this->project_member->where(['p_id'=>$p_id,'u_id'=>$u_id]))
			$this->project_member->insert($member_data);

		$this->TEST_FIND($p_id);
		$this->TEST_GETPRIORITY($member_data);
		$this->TEST_ISMEMBER($member_data);
		$this->TEST_MEMBERDATAINPROJECT($member_data,$user_data);
		$this->TETS_ALLMEMBERNAME($p_id,$name);

		//$this->TEST_DESTROY($member_data);
		echo $this->unit->report();
		
	}

	function TEST_FIND($p_id){
		$test = $this->project_member->find($p_id);
		$this->unit->run($test->p_id,$p_id,"TEST_FIND");
	}

	function TEST_GETPRIORITY($member_data)
	{
		$test = $this->project_member->getPriority($member_data['u_id'],$member_data['p_id']);
		$this->unit->run($test->priority,$member_data['priority'],"TEST_GETPRIORITY");
	}

	function TEST_ISMEMBER($data){
		$test = $this->project_member->isMember($data['u_id'],$data['p_id']);
		$this->unit->run($test,true,"TEST_ISMEMBER");
	}

	function TEST_MEMBERDATAINPROJECT($data,$udata){
		$alldata = [
			'userID' => $data['u_id'],
			'userName' => $udata['name'],
			'projectPriority' => $data['priority'],
			'projectID' => $data['p_id']
		];
		$test = $this->project_member->memberDataInProject($data['u_id'],$data['p_id']);
		$this->unit->run($test->userName,$alldata['userName'],"TEST_MEMBERDATAINPROJECT");
	}	

	function TETS_ALLMEMBERNAME($p_id,$name){
		$test = $this->project_member->allMemberName($p_id)[0]->userName;
		$this->unit->run($test,$name,'ALLMEMBERNAME');
	}

	function TEST_DESTROY($data){
		$this->project_member->destory('p_id = ' . $data['p_id'] . ' AND u_id = ' . $data['u_id']);
		$this->project->destory('p_id = ' . $data['p_id']);
		$this->user->destory('u_id = '. $data['u_id']);
	}

}
?>