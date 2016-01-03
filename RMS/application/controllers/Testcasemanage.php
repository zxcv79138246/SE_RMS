<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Testcasemanage extends CI_Controller
{
	private $currentProject =0;
	private $currentUser =0;
	function __construct()
	{
		parent::__construct();
		$this->load->model('testcase_model','testcase');
		$this->load->model('R_and_t_relation_model','RTrelation');
		$this->load->model('Requirement_model','requirement');
		$this->load->model('User_model','user');
		$this->currentProject = $this->session->userdata('p_id');
		$this->currentUser = $this->session->userdata('u_id');	
	}

	public function index()
	{
		if(is_null($this->currentUser))
		{
			$this->session->set_flashdata('message', '尚未登入');
			$this->session->set_flashdata('type', 'danger');
			redirect('/index');
		}
		if(is_null($this->currentProject))
		{
			$this->session->set_flashdata('message', '尚未選擇專案');
			$this->session->set_flashdata('type', 'danger');
			redirect('/index');
		}		
		$testcases = $this->testcase->where(['p_id'=>$this->currentProject]);
		for($i=0 ;$i<count($testcases);$i++){
			$ownerName = $this->user->where(['u_id'=>$testcases[$i]->owner])[0]->name;
			$testcases[$i]->ownerName = $ownerName;
		}		
		$this->twig->display("rms/testcasemanage/testcasemanage.html",compact("testcases"));
	}

	public function create()
	{
		$this->twig->display("rms/testcasemanage/create.html");	
	}

	public function store()
	{
		if($this->verification()){
			$data =
			[
				'name' => $this->input->post('testcaseName'),
				'description' => $this->input->post('testcaseDescription'),
				'input_data' => $this->input->post('testcaseInput_data'),
				'expected_results' => $this->input->post('testcaseExpected_results'),
				'owner'	=> $this->session->userdata('u_id'),
				'p_id'	=> $this->currentProject,
				'attachment' => $this->input->post('testcaseAttachment'),
			];
			if($this->testcase->duplicateCheck(['name'=>$data['name']],1))
			 {
					$this->session->set_flashdata('message', "Testcase名稱重複");
					$this->session->set_flashdata('type', 'danger');
			}
			else 
			{
					$this->testcase->insert($data);
					$this->session->set_flashdata('message', "TestCase {$data['name']} 新增成功");
					$this->session->set_flashdata('type', 'success');
			}
		}	
		redirect('/testcasemanage');
	}

	public function destroy($t_id)
	{	
		$relationCounts =count($this->RTrelation->where(['t_id'=>$t_id])); 
		$response=[];
		if( $relationCounts > 0 )
		{
			$response['message'] = "尚有 {$relationCounts} 個關聯存在";
			$response['messageType'] = 'warning';
		}
		else if ($destoried = $this->testcase->destory(['t_id'=>$t_id]))
		{	
			$response['message'] = "{$destoried[0]->name} 已被刪除";
			$response['messageType'] = 'warning';			
			$response['t_id']=$t_id;
		}
		echo json_encode($response);
	}

	public function show($t_id)
	{
		$testcase = $this->testcase->where(['t_id'=>$t_id])[0];
		$relations = $this->RTrelation->where(['t_id'=>$t_id]);
		$relationList = [];
		foreach($relations as $relation)
		{
			$relationNames =[];
			$requirement = $this->requirement->where(['r_id'=>$relation->r_id])[0];
			$relationNames['name'] = $requirement->name;
			$relationList[count($relationList)] = $relationNames;
		}
		$this->twig->display("rms/testcasemanage/show.html",compact('testcase','relationList'));	
	}

	public function edit($t_id)
	{
		$testcase = $this->testcase->where(['t_id'=>$t_id])[0];
		$this->twig->display("rms/testcasemanage/edit.html",compact('testcase'));			
	}

	public function update($t_id)
	{
		$testcase=
		[
			'name' => $this->input->post('testcaseName'),
			'description' => $this->input->post('testcaseDescription'),
			'input_data' => $this->input->post('testcaseInput_data'),
			'expected_results' => $this->input->post('testcaseExpected_results'),
			'attachment' => $this->input->post('testcaseAttachment'),
		];
		$isCreate =0;
		if($this->testcase->where(['t_id'=>$t_id])[0]->name != $testcase['name'])
			$isCreate =1;
		if($this->testcase->duplicateCheck(['name'=>$testcase['name'],'p_id'=>$this->currentProject],$isCreate))
		 {
				$this->session->set_flashdata('message', "Testcase名稱重複");
				$this->session->set_flashdata('type', 'danger');
		}
		else 
		{
				$this->testcase->update($testcase,['t_id'=>$t_id,'p_id'=>$this->currentProject]);
				$this->session->set_flashdata('message', "TestCase 修改成功");
				$this->session->set_flashdata('type', 'success');
		}
		redirect('/testcasemanage');	
	}

	public function search()
	{	
		if($this->input->post('searchTarget') =='owner')			
		 	$searchCondition =$this->user->where(['name'=>$this->input->post('searchCondition')])[0]->u_id;
		else if($this->input->post('searchTarget') =='name')
		 	$searchCondition = $this->input->post('searchCondition');
		$result = $this->testcase->searchTestcase($this->currentProject,$searchCondition ,$this->input->post('searchTarget'));
		 echo json_encode($result);
	}

	public function verification()
	{
		$this->form_validation->set_rules('testcaseName','Name','required');
		$this->form_validation->set_rules('testcaseDescription','Description','required');
		$this->form_validation->set_rules('testcaseInput_data','Input_data','required');
		$this->form_validation->set_rules('testcaseExpected_results','Expected_results','required');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message', "有欄位為空值");
			$this->session->set_flashdata('type', 'danger');
		}
		else
			return true;
	}
} 
