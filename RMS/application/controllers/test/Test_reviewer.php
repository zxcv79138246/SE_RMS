<?php
class Test_reviewer extends CI_Controller
{
	//review   all()  &   find($uid rid) ($p_K = array(u_id,r_id))
	//	Load model
	private $user_model = 'user_model';
	private $project_model = 'project_model';
	private $requirement_model = 'requirement_model';
	private $reviewer_model = 'reviewer_model';
	//	data
	private $user_data;
	private $project_data;
	private $requirement_data;
	private $reviewer_data;
	//	Id
	private $u_id;
	private $p_id;
	private $r_id;

	private $rp_key;
	function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		$this->load->model($this->user_model, 'user');
		$this->load->model($this->project_model, 'project');
		$this->load->model($this->requirement_model,'requirement');
		$this->load->model($this->reviewer_model,'reviewer');
		$this->prepareUser();
		$this->prepareProject();
		$this->prepareRequirement();
		$this->prepareReviewer();

		$this->TEST_GETREVIEWBYUIDPID();
		$this->TEST_GETREVIEWBYRID();
		$this->TEST_GETNUMDICISIONBYRID();

		$this->deleteData();

		echo $this->unit->report();
	}

	function prepareUser()
	{
		//	Data prepare : user
		$this->user_data = [
			'email' => 'TestUseAccount@gmail.com',
			'name' => 'TestingMan',
			'password' => '123test456',
			'priority' => 1
		];
		
		if(!$this->user->where(['email'=> $this->user_data['email'],'name'=> $this->user_data['name']]))
		{
			$this->user->insert($this->user_data);
		}
		$nowuser = $this->user->where(['email'=> $this->user_data['email'],'name'=> $this->user_data['name']]);
		$this->u_id = $nowuser[0]->u_id;
	}

	function prepareProject()
	{
		//	Data prepare : project
		$this->project_data = [
			'name' => 'TestingProjectYa',
			'date' => Date('2015/12/31'),
			'description' => 'Testingingder',
			'leader' => $this->u_id
		];

		if(!$this->project->where(['name'=> $this->project_data['name']]))
			 $this->project->insert($this->project_data);
		$this->p_id = $this->project->where(['name'=> $this->project_data['name']])[0]->p_id;

	}

	function prepareRequirement()
	{
		//Data prepare : requirement
		$this->requirement_data =
		[	
			'p_id' =>$this->p_id,
			'name' =>'rr_tyeest',
			'functional' => false,
			'description' => 'paawanyeela',
			'version'	=> 1,
			'level'	=> 2,
			'state'	=> '待審核',
			'owner' => $this->u_id,
			'memo' => 'We R Memo'
		];
		if(!$this->requirement->where($this->requirement_data))
			$this->requirement->insert($this->requirement_data);
		$this->r_id = $this->requirement->where($this->requirement_data)[0]->r_id;
	}
	
	function prepareReviewer()
	{
		//	Data prepare : reviewer
		$this->reviewer_data = [
			'u_id' => $this->u_id,
			'r_id' => $this->r_id,
			'p_id' => $this->p_id,
			'decision' => 0,
			'comment' => "Oh,no!Time isn't enough"
		];
		if(!$this->reviewer->where($this->reviewer_data))
			$this->reviewer->insert($this->reviewer_data);
	}

	function deleteData()
	{
		$this->reviewer->destory('u_id = ' . $this->u_id . ' AND r_id = ' . $this->r_id);
		$this->requirement->destory('r_id = ' . $this->r_id);
		$this->project->destory('p_id = ' . $this->p_id);
		$this->user->destory('u_id = '. $this->u_id);
	}

	function TEST_GETREVIEWBYUIDPID()
	{
		$test = $this->reviewer->getReviewByUIDPID($this->u_id, $this->p_id);
		$result = [
			'u_id' => $test[0]->u_id,
			'r_id' => $test[0]->r_id,
			'p_id' => $test[0]->p_id,
			'decision' =>$test[0]->decision,
			'comment' => $test[0]->comment
		];
		$this->unit->run($result,$this->reviewer_data,'GETREVIEWBYUIDPID');
	}

	function TEST_GETREVIEWBYRID()
	{
		$test = $this->reviewer->getReviewByRID($this->r_id);
		$result = [
			'u_id' => $test[0]->u_id,
			'r_id' => $test[0]->r_id,
			'p_id' => $test[0]->p_id,
			'decision' =>$test[0]->decision,
			'comment' => $test[0]->comment
		];
		$this->unit->run($result,$this->reviewer_data,'GETREVIEWBYRID');
	}

	function TEST_GETNUMDICISIONBYRID()
	{
		$test = $this->reviewer->getNumDicisionByRID($this->r_id, $this->reviewer_data['decision']);
		$this->unit->run($test,1,'GETNUMDICISIONBYRID');
	}

}
?>