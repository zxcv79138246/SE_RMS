<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $model = '';
	private $priority=0;
	protected $folder = '';
	protected $viewName = '';
	
	function __construct()
	{
		parent::__construct();
		$this->load->model($model, 'controll');
		if ($this->session->userdata('priority')!=$this->$priority)
		{
			redirect('/index');     //權限不足頁面
		}
	}

	public function index()
	{
		$controlls = $this->controll->all();
		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view($folder.'/'.$viewName, compact('controlls'));
		$this->load->view('layout/footer');
	}

}