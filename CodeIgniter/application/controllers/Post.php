<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('post_model', 'post');
	}

	public function index()
	{
		$posts = $this->post->all();
		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('posts/index', compact('posts'));
		$this->load->view('layout/footer');
	}

	public function show($id)
	{
		$post = $this->post->find($id);
		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('posts/show', compact('post'));
		$this->load->view('layout/footer');
	}

	public function create()
	{
		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('posts/create');
		$this->load->view('layout/footer');
	}

	public function store()
	{
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('body', 'Body', 'required');
		if (!$this->form_validation->run()) {
			$this->create();
		} else {
			$post = [
				'title' => $this->input->post('title'),
				'body' => $this->input->post('body'),
			];
			$this->post->insert($post);
			$this->session->set_flashdata('message', "Created a new post: {$post['title']}");
			redirect('post/index');
		}
	}

	public function edit($id)
	{
		$post = $this->post->find($id);
		// $this->load->view('layout/header');
		// $this->load->view('layout/navbar');
		$this->load->view('posts/edit', compact('post'));
		// $this->load->view('layout/footer');
	}

	public function update($id)
	{
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('body', 'Body', 'required');
		if (!$this->form_validation->run()) {
			$this->edit($id);
		} else {
			$post = [
				'title' => $this->input->post('title'),
				'body' => $this->input->post('body'),
			];
			$this->post->update($post, ['id' => $id]);
			redirect('post/index');
		}
	}

	public function destory($id)
	{
		if ($posts = $this->post->destory(['id' => $id])) {
			$this->session->set_flashdata('message', "{$posts[0]->title} has been removed");
		}
		redirect('post/index');
	}

}
