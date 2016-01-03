<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class ReviewRequirement extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model('reviewer_model', 'reviewer');
		}

		public function index()
		{
			
		}
	}
?>