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
		$project_model = 'project_model';
		$project_member_model = 'project_member_model';
		$this->load->model($project_member_model, 'project_member');
		$data = [
			'email' => 'borrowTese@gmail.com',
			'name' => 'YEEWEILUN',
			'password' => '1234543',
			'priority' => 3
		];
		$this->load->model($model_name, 'user');
		$this->load->model($project_model, 'project');
		
		//TEST USER AND INSERT
		$this->TEST_USER_INSERT($data);
		$this->TEST_USER_FIND($data);

		//TEST PROJECT AND INSERT
		if($this->user->where($data))
			$u_id = $this->user->where($data)[0]->u_id;
		
		$project_data = [
			'name' => 'UserProjectTest',
			'date' => Date('2016/01/02'),
			'description' => 'TestFirstUU',
			'leader' => $u_id
		];
		if(!$this->project->where($project_data))
			$this->project->insert($project_data);
		$p_id = $this->project->where($project_data)[0]->p_id;

		//TEST PM AND INSERT
		$member_data = [
			'p_id' => $p_id,
			'u_id' => $u_id,
			'priority' => '0'
		];

		$inmember = ['p_id' => $p_id,'u_id' => $u_id];
		if(!$this->project_member->where($inmember))
			$this->project_member->insert($member_data);

		$this->TEST_LOGIN($data);
		$this->TEST_LIKE_SEARCH($data['email'], 'email');
		$this->TEST_INPROJECT($u_id,$p_id);
		$this->TEST_OUTSIDEPROJECT($u_id,$p_id);

		$this->TEST_PM_DESTROY($inmember);
		$this->TEST_PROJECT_DESTROY($project_data);
		$this->TEST_USER_DESTROY($data);
		
		echo $this->unit->report();
	}

	function TEST_USER_INSERT($data){
		//	TEST INSERT
		if(!$this->user->where($data))
			$test = $this->user->insert($data);
		$this->unit->run($test, $data, 'USER_INSERT');

	}

	function TEST_USER_FIND($data){
			//TEST FIND
			$test = $this->user->where(['email' => $data['email']])[0];
			$this->unit->run($test->email,$data['email'],'USER_FIND_EMAIL');
			$this->unit->run($test->name,$data['name'],'USER_FIND_NAME');
			$this->unit->run($test->password,$data['password'],'USER_FIND_PASSWORD');
			$this->unit->run($test->priority,$data['priority'],'USER_FIND_PRIORITY');
	}

	function TEST_LOGIN($data){
		$test = $this->user->login(['email'=>$data['email'],'password'=>$data['password']]);
		$this->unit->run(!is_null($test),true,'TEST_LOGIN');
	}

	function TEST_LIKE_SEARCH($condition, $target){
		$test = $this->user->like_search($condition, $target);
		$this->unit->run(count($test)>0,true,'TEST_LIKE_SEARCH');
	}

	function TEST_INPROJECT($u_id,$p_id)
	{
		$test = $this->user->inProject($p_id);
		$this->unit->run($test[0]->uID,$u_id,'TEST_INPROJECT');
	}

	function TEST_OUTSIDEPROJECT($u_id,$p_id){
		$test = $this->user->outsideProject($p_id);
		for($i = 0; $i < count($test); $i++)
		{
			if(($test[$i]->uID) == $u_id)
			{
				$result = true;
				break;
			}
			else
			{
				$result = false;
			}

		}
		$this->unit->run($result,false,'TEST_OUTSIDEPROJECT');
	}

	function TEST_USER_DESTROY($data){
		// TEST DELETE
		$test = $this->user->destory($data);
		$this->unit->run($test, true, 'USER_DELETE');		
	}

	function TEST_PROJECT_DESTROY($data){
		$test = $this->project->destory($data);
		$this->unit->run($test, true, 'PROJECT_DELETE');	
	}

	function TEST_PM_DESTROY($data)
	{
		$test = $this->project_member->destory($data);
		$this->unit->run($test, true, 'PM_DELETE');	
	}
}
