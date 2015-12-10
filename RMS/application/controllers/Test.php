<?php

/**
* 
*/
class Test extends CI_Controller
{
	
	function __construct() {
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		$model = 'post';
		$data = [
			'title' => 'title',
			'body' => 'body',
		];
		$this->load->model('post_modal', 'post');
		$test = $this->post->insert($data);
		$this->unit->run($test, 1, 'Post_Insert');
	}

}