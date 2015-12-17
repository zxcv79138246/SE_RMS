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
		$this->load->model('post_model', 'post');
		$test = $this->post->insert($data);
		$this->unit->run($test, 1, 'POST_INSERT');

		$post = $this->post->find(6);
		$this->unit->run($post->id, 6, 'POST_FIND');

		echo $this->unit->report();
	}

}

