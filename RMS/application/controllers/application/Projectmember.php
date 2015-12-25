<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Projectmanage extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('project_member_model','projectMember');
	}

	public function create($data){
		$this->projectMember->insert($data);
	}
}