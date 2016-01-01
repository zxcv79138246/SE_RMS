<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Requirementmanage extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Requirement_model', 'requirement');
	}

	public function index()
	{
		$u_id = $this->session->userdata('u_id');
		$requirements = $this->requirement->all();
		//	Functional value is 1, Non-functional value is 0 (in database)
		$functional_display = ['Non-functional', 'Functional'];


		$this->twig->display('rms/requirementmanage/requirementmanage.html', compact('requirements', 'functional_display'));
	}

	public function create()
	{
		$this->twig->display('rms/requirementmanage/create.html');
	}
}
?>