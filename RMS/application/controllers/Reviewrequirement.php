<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class ReviewRequirement extends CI_Controller
	{
		private $current_user = 0;
		private $current_project = 0;
		private $priority = 0;
		function __construct()
		{
			parent::__construct();
			$this->load->model('requirement_model', 'requirement');
			$this->load->model('reviewer_model', 'reviewer');
			$this->load->model('project_member_model', 'project_member');
			$this->load->model('user_model', 'user');
			$this->current_user = $this->session->userdata('u_id');
			$this->current_project = $this->session->userdata('p_id');
			$this->priority = $this->session->userdata('priorityInProject');
		}

		public function index()
		{
			$priority = $this->priority;
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
			$templist = [];
			if($this->priority == 2)
			{
				$requirementlist = $this->requirement->where(['p_id' => $this->current_project]);
				for($i = 0; $i < count($requirementlist); $i++)
				{
					if($requirementlist[$i]->state == '審核中')
					{
						$requirementlist[$i]->agree = $this->reviewer->getNumDicisionByRID($requirementlist[$i]->r_id, 2);
						$requirementlist[$i]->disagree = $this->reviewer->getNumDicisionByRID($requirementlist[$i]->r_id, 1);
						$requirementlist[$i]->unknow = $this->reviewer->getNumDicisionByRID($requirementlist[$i]->r_id, 0);
						$templist = array_merge($templist, array($requirementlist[$i]));
					}
				}
			}
			else
			{
				$reviewerlist = $this->reviewer->getReviewByUIDPID($this->current_user, $this->current_project);
				if($reviewerlist != false)
				{
					for($i=0; $i < count($reviewerlist); $i++)
					{
						//	Get requirement name
						$requirement = $this->requirement->where(['r_id' => $reviewerlist[$i]->r_id]);
						if($requirement[0]->state == '審核中')
						{
							$requirement[0]->agree = $this->reviewer->getNumDicisionByRID($requirement[0]->r_id, 2);
							$requirement[0]->disagree = $this->reviewer->getNumDicisionByRID($requirement[0]->r_id, 1);
							$requirement[0]->unknow = $this->reviewer->getNumDicisionByRID($requirement[0]->r_id, 0);
							$requirement[0]->decision = $reviewerlist[$i]->decision;
							$templist = array_merge($templist, $requirement);
						}
					}
				}
			}
			$decision_display = ['未決定', '否決', '同意'];
			$this->twig->display('rms/reviewrequirement/reviewrequirement.html', compact('templist', 'decision_display', 'priority'));
		}

		public function powerpage()
		{
			if($this->priority != 2)
			{
				$this->session->set_flashdata('message', '權限不足');
				$this->session->set_flashdata('type', 'danger');
				redirect('/reviewrequirement');
			}
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
			$requirement_list1 = $this->requirement->where(['p_id' => $this->current_project, 'state' => '待審核']);
			$requirement_list2 = $this->requirement->where(['p_id' => $this->current_project, 'state' => '審核中']);
			$requirement_list = array_merge($requirement_list1, $requirement_list2);
			$this->twig->display('rms/reviewrequirement/power.html', compact('requirement_list'));
		}

		public function review_start($r_id)
		{
			$requirement = $this->requirement->find($r_id);
			if($requirement->state == '待審核')
			{
				$data = ['state' => '審核中'];
				$this->requirement->update($data, ['r_id' => $r_id]);
				$this->session->set_flashdata('message', "Requirement名稱 {$requirement->name} 審核開始");
				$this->session->set_flashdata('type', 'success');
			}
			else
			{
				$this->session->set_flashdata('message', "Requirement名稱 {$requirement->name} 審核中");
				$this->session->set_flashdata('type', 'danger');
			}
			redirect('/reviewrequirement/powerpage');
		}

		public function end($r_id)
		{
			$requirement = $this->requirement->find($r_id);
			$reviewerlist = $this->reviewer->getReviewByRID($r_id);
			if($reviewerlist != false)
			{
				for($i=0; $i < count($reviewerlist); $i++)
				{
					//	Get requirement name
					$user = $this->user->find($reviewerlist[$i]->u_id);
					$reviewerlist[$i]->userName = $user->name;
				}
			}
			$decision_display = ['未決定', '否決', '同意'];
			$this->twig->display('rms/reviewrequirement/end.html', compact('reviewerlist', 'requirement', 'decision_display'));
		}

		public function review_end($r_id)
		{
			$requirement = $this->requirement->find($r_id);
			if($requirement->state != '待審核')
			{
				$decision = $this->input->post('decision');
				$decision_display = ['', '審核失敗', '審核通過'];
				$data = ['state' => $decision_display[$decision]];
				$this->requirement->update($data, ['r_id' => $r_id]);
				$this->reviewer->destory(['r_id' => $r_id]);
				$this->session->set_flashdata('message', "Requirement名稱 {$requirement->name} 審核結束");
				$this->session->set_flashdata('type', 'success');
			}
			else
			{
				$this->session->set_flashdata('message', "Requirement名稱 {$requirement->name} 審核尚未開始");
				$this->session->set_flashdata('type', 'danger');
			}
			redirect('/reviewrequirement/powerpage');
		}

		public function info($r_id)
		{
			$requirement = $this->requirement->find($r_id);
			$reviewerlist = $this->reviewer->getReviewByRID($r_id);
			if($reviewerlist != false)
			{
				for($i=0; $i < count($reviewerlist); $i++)
				{
					//	Get requirement name
					$user = $this->user->find($reviewerlist[$i]->u_id);
					$reviewerlist[$i]->userName = $user->name;
				}
			}
			$decision_display = ['未決定', '否決', '同意'];
			$this->twig->display('rms/reviewrequirement/info.html', compact('reviewerlist', 'requirement', 'decision_display'));
		}

		public function review($r_id)
		{
			$requirement = $this->requirement->find($r_id);
			$decision_display = ['未決定', '否決', '同意'];
			$this->twig->display('rms/reviewrequirement/review.html', compact('requirement', 'decision_display'));
		}

		public function power($r_id)
		{
			$project = $this->current_project;
			$requirement = $this->requirement->find($r_id);
			$user_list = $this->project_member->where(['p_id' => $this->current_project]);
			$is_reviewer = [];
			$not_reviewer = [];
			for ($i = 0, $j = 0, $k = 0; $i < count($user_list); $i++)
			{
				$array = array($user_list[$i]);
				$user = $this->user->where(['u_id' => $user_list[$i]->u_id])[0];
				if($user_list[$i]->priority != 2 && $user->priority != 2)
				{
					if(count($this->reviewer->where(['u_id' => $user_list[$i]->u_id, 'r_id' => $r_id])) != 0)
					{
						$is_reviewer = array_merge($is_reviewer, $array);
						$is_reviewer[$j]->name = $user->name;
						$j++;
					}
					else
					{
						$not_reviewer = array_merge($not_reviewer, $array);
						$not_reviewer[$k]->name = $user->name;
						$k++;
					}
				}
			}
			$this->twig->display('rms/reviewrequirement/review_power.html', compact('requirement', 'project', 'is_reviewer', 'not_reviewer'));
		}

		public function add_reviewer()
		{
			$u_id = $this->input->post('u_id');
			if(!is_null($u_id))
			{
				$r_id = $this->input->post('r_id');
				$p_id = $this->input->post('p_id');
				$data = ['u_id' => $u_id, 'r_id' => $r_id, 'p_id' => $p_id];
				$result = $this->reviewer->insert($data);
				if($result)
					$user = $this->user->where(['u_id' => $u_id])[0];
				echo json_encode($user);
			}
		}

		public function remove_reviewer($u_id , $r_id)
		{	
			$data = ['u_id'=>$u_id,'r_id'=>$r_id];
			$resault = $this->reviewer->destory($data);
			$user = $this->user->find($u_id);
			if ($resault)
				echo json_encode($user);
		}

		public function update($r_id)
		{
			$requirement = $this->requirement->find($r_id);
			if($this->verification())
			{
				$review_data = [
					'decision' => $this->input->post('decision'),
					'comment' => $this->input->post('comment')
				];
				$this->reviewer->update($review_data, ['u_id' => $this->current_user, 'r_id' => $r_id]);
				$this->session->set_flashdata('message', "Requirement名稱 {$requirement->name} 審核成功");
				$this->session->set_flashdata('type', 'success');
			}
			redirect('/reviewrequirement');
		}

		public function verification()
		{
			$this->form_validation->set_rules('decision','Decision','required');
			$this->form_validation->set_rules('comment','Comment','required');
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