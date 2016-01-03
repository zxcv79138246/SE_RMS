<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class ReviewRequirement extends CI_Controller
	{
		private $current_user = 0;
		private $current_project = 0;
		function __construct()
		{
			parent::__construct();
			$this->load->model('requirement_model', 'requirement');
			$this->load->model('reviewer_model', 'reviewer');
			$this->load->model('user_model', 'user');
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
			$reviewerlist = $this->reviewer->getReviewByUIDPID($this->current_user, $this->current_project);
			if($reviewerlist != false)
			{
				for($i=0; $i < count($reviewerlist); $i++)
				{
					//	Get requirement name
					$requirement = $this->requirement->find($reviewerlist[$i]->r_id);
					$reviewerlist[$i]->requirementName = $requirement->name;
					//	Get Decision (Agree)
					$reviewerlist[$i]->agree = $this->reviewer->getNumDicisionByRID($requirement->r_id, 2);
					$reviewerlist[$i]->disagree = $this->reviewer->getNumDicisionByRID($requirement->r_id, 1);
					$reviewerlist[$i]->unknow = $this->reviewer->getNumDicisionByRID($requirement->r_id, 0);
				}
			}
			$decision_display = ['未決定', '否決', '同意'];
			$this->twig->display('rms/reviewrequirement/reviewrequirement.html', compact('reviewerlist', 'decision_display'));
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