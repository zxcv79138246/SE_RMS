<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Index extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('project_model','project');	
	}

	public function index()
	{
		$projects = $this->project->all();
		//$projects = '';
		$this->twig->display('rms/projectlist/projectlist.html', compact('projects'));
	}
}