<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Requirementmanage extends CI_Controller
{
	private $current_user = 0;
	private $current_project = 0;
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Requirement_model', 'requirement');
		$this->load->model('Testcase_model', 'testcase');
		$this->load->model('R_and_r_relation_model', 'r_r_relation');
		$this->load->model('R_and_t_relation_model', 'r_t_relation');
		$this->current_user = $this->session->userdata('u_id');
		$this->current_project = $this->session->userdata('p_id');
	}

	public function index()
	{
		if(is_null($this->current_user))
		{
			$this->session->set_flashdata('message', '尚未登入');
			$this->session->set_flashdata('type', 'danger');
			redirect('/index');
		}
		if(is_null($this->current_project))
		{
			$this->session->set_flashdata('message', '尚未選擇專案');
			$this->session->set_flashdata('type', 'danger');
			redirect('/index');
		}
		$requirements = $this->requirement->getReqByPID($this->current_project);
		if($requirements != false)
		{
			for($i=0 ;$i<count($requirements);$i++){
				$ownerName = $this->user->where(['u_id'=>$requirements[$i]->owner])[0]->name;
				$requirements[$i]->ownerName = $ownerName;
			}
		}
		//	Functional value is 1, Non-functional value is 0 (in database)
		$functional_display = ['Non-functional', 'Functional'];
		$this->twig->display('rms/requirementmanage/requirementmanage.html', compact('requirements', 'functional_display'));
	}

	public function create()
	{
		$this->twig->display('rms/requirementmanage/create.html');
	}

	public function edit($r_id)
	{
		$requirement = $this->requirement->where(['r_id'=>$r_id])[0];
		$functional_seleted; $type_selected;
		//	Defult Select (functional)
		if($requirement->functional == 1)
			$functional_seleted = ['selected', ''];
		else
			$functional_seleted = ['', 'selected'];
		//	Defult Tab (Type)
		if($requirement->type == 'normal')
			$type_selected = ['active', ''];
		else
			$type_selected = ['', 'active'];
		$this->twig->display("rms/requirementmanage/edit.html",compact('requirement', 'functional_seleted', 'type_selected'));
	}

	public function store($type)
	{
		if($this->verification($type))
		{
			$state = '待審核';
			$tempDate = date('Y-m-d H:i:s');
			$reqData;
			if($type == 'normal')
			{
				$reqData = [
					'p_id' => $this->current_project,
					'name' => $this->input->post('name'),
					'functional' => $this->input->post('functional'),
					'type' => $type,
					'description' => $this->input->post('description'),
					'version' => $this->input->post('version'),
					'level' => $this->input->post('level'),
					'state' => $state,
					'owner' => $this->current_user,
					'last_edit_date' => $tempDate,
					'memo' => $this->input->post('memo')
				];
			}
			else
			{
				$reqData = [
					'p_id' => $this->current_project,
					'name' => $this->input->post('name'),
					'functional' => $this->input->post('functional'),
					'type' => $type,
					'description' => $this->input->post('description'),
					'version' => $this->input->post('version'),
					'level' => $this->input->post('level'),
					'state' => $state,
					'owner' => $this->current_user,
					'last_edit_date' => $tempDate,
					'memo' => $this->input->post('memo'),
					'target' => $this->input->post('target'),
					'precondition' => $this->input->post('precondition'),
					'postcondition' => $this->input->post('postcondition'),
					'main_flow' => $this->input->post('main_flow'),
					'alter_flow' => $this->input->post('alter_flow')
				];
			}
			if($this->requirement->duplicateCheck(['name' => $reqData['name'],'p_id' => $reqData['p_id']], 1))
			 {
					$this->session->set_flashdata('message', "Requirement名稱重複");
					$this->session->set_flashdata('type', 'danger');
			}
			else 
			{
					$this->requirement->insert($reqData);
					$this->session->set_flashdata('message', "Requirement名稱 {$reqData['name']} 新增成功");
					$this->session->set_flashdata('type', 'success');
			}
		}
		redirect('/requirementmanage');
	}

	public function info($r_id)
	{
		$requirement = $this->requirement->where(['r_id' => $r_id])[0];
		$functional_display = ['Non-functional', 'Functional'];
		$r_r_relations1 = $this->r_r_relation->where(['r_id1' => $r_id]);
		$r_r_relations2 = $this->r_r_relation->where(['r_id2' => $r_id]);
		$r_t_relations = $this->r_t_relation->where(['r_id' => $r_id]);
		$r_r_relationList1 = [];
		$r_r_relationList2 = [];
		$r_t_relationList = [];
		foreach($r_r_relations1 as $relation)
		{
			$relationNames =[];
			$requirements = $this->requirement->where(['r_id' => $relation->r_id2])[0];
			$relationNames['name'] = $requirements->name;
			$r_r_relationList1[count($r_r_relationList1)] = $relationNames;
		}
		foreach($r_r_relations2 as $relation)
		{
			$relationNames =[];
			$requirements = $this->requirement->where(['r_id' => $relation->r_id1])[0];
			$relationNames['name'] = $requirements->name;
			$r_r_relationList2[count($r_r_relationList2)] = $relationNames;
		}
		foreach($r_t_relations as $relation)
		{
			$relationNames =[];
			$testcases = $this->testcase->where(['t_id' => $relation->t_id])[0];
			$relationNames['name'] = $testcases->name;
			$r_t_relationList[count($r_t_relationList)] = $relationNames;
		}
		if($requirement->type == 'normal')
			$this->twig->display('rms/requirementmanage/info_normal.html', compact('requirement', 'r_r_relationList1', 'r_r_relationList2', 'r_t_relationList', 'functional_display'));
		else
			$this->twig->display('rms/requirementmanage/info_usecase.html', compact('requirement', 'r_r_relationList1', 'r_r_relationList2', 'r_t_relationList', 'functional_display'));
	}

	public function update($type,$r_id)
	{
		if($this->verification($type))
		{
			$state = '待審核';
			$tempDate = date('Y-m-d H:i:s');
			$reqData;
			if($type == 'normal')
			{
				$reqData = [
					'name' => $this->input->post('name'),
					'functional' => $this->input->post('functional'),
					'type' => $type,
					'description' => $this->input->post('description'),
					'version' => $this->input->post('version'),
					'level' => $this->input->post('level'),
					'state' => $state,
					'owner' => $this->current_user,
					'last_edit_date' => $tempDate,
					'memo' => $this->input->post('memo'),
					'target' => NULL,
					'precondition' => NULL,
					'postcondition' => NULL,
					'main_flow' => NULL,
					'alter_flow' => NULL
				];
			}
			else
			{
				$reqData = [
					'name' => $this->input->post('name'),
					'functional' => $this->input->post('functional'),
					'type' => $type,
					'description' => $this->input->post('description'),
					'version' => $this->input->post('version'),
					'level' => $this->input->post('level'),
					'state' => $state,
					'owner' => $this->current_user,
					'last_edit_date' => $tempDate,
					'memo' => $this->input->post('memo'),
					'target' => $this->input->post('target'),
					'precondition' => $this->input->post('precondition'),
					'postcondition' => $this->input->post('postcondition'),
					'main_flow' => $this->input->post('main_flow'),
					'alter_flow' => $this->input->post('alter_flow')
				];
			}
			$current_requirement = $this->requirement->find($r_id);
			$change = 0;
			if ($reqData['name'] != $current_requirement->name){
				$change = 1;
			}
			if($this->requirement->duplicateCheck(['name' => $reqData['name'],'p_id' => $this->current_project], $change))
			{
					$this->session->set_flashdata('message', "名字重複");
					$this->session->set_flashdata('type', 'danger');
			}
			else 
			{
					$this->requirement->update($reqData, ['r_id' => $r_id]);
					$this->session->set_flashdata('message', "Requirement名稱 {$reqData['name']} 修改成功");
					$this->session->set_flashdata('type', 'success');
			}
		}
		redirect('/requirementmanage');
	}

	public function destroy($r_id)
	{
		$numberOf_r_r_relations1 = count($this->r_r_relation->where(['r_id1' => $r_id]));
		$numberOf_r_r_relations2 = count($this->r_r_relation->where(['r_id2' => $r_id]));
		$numberOf_r_t_relations = count($this->r_t_relation->where(['r_id' => $r_id]));
		$numberOf_relation = $numberOf_r_r_relations1 + $numberOf_r_r_relations2 + $numberOf_r_t_relations;
		$response=[];
		if($numberOf_relation > 0)
		{
			$response['message'] = "尚有 {$numberOf_relation} 個關聯存在";
			$response['messageType'] = 'warning';
		}
		else if ($result = $this->requirement->destory(['r_id'=>$r_id]))
		{	
			$response['message'] = "{$result[0]->name} 已被刪除";
			$response['messageType'] = 'warning';			
			$response['r_id']=$r_id;
		}
		echo json_encode($response);
	}

	public function search()
	{	
		if($this->input->post('searchTarget') =='owner')			
		 	$searchCondition = $this->user->where(['name'=>$this->input->post('searchCondition')])[0]->u_id;
		else if($this->input->post('searchTarget') =='functional')
			$searchCondition = $this->input->post('searchCondition') == 'Functional' ? 1 : 0;
		else
		 	$searchCondition = $this->input->post('searchCondition');
		$result = $this->requirement->like_search($this->current_project, $searchCondition, $this->input->post('searchTarget'));
		 echo json_encode($result);
	}

	public function verification($type)
	{
		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('version','Version','required');
		$this->form_validation->set_rules('level','Level','required');
		$this->form_validation->set_rules('description','Description','required');
		if($type == 'normal')
		{
		}
		else if($type == 'usecase')
		{
			$this->form_validation->set_rules('target','Target','required');
			$this->form_validation->set_rules('precondition','Precondition','required');
			$this->form_validation->set_rules('postcondition','Postcondition','required');
			$this->form_validation->set_rules('main_flow','Main flow','required');
			$this->form_validation->set_rules('alter_flow','Alter flow','required');
		}
		else
		{
			$this->session->set_flashdata('message', "狀態(Type)異常");
			$this->session->set_flashdata('type', 'danger');
		}
		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message', "有欄位為空值");
			$this->session->set_flashdata('type', 'danger');
		}
		else
			return true;
	}
}
?>