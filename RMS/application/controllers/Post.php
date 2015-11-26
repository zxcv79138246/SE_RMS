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
		$this->twig->display('posts/index.html', compact('posts'));
	}

	public function show($id)
	{
		$post = $this->post->find($id);
		$this->twig->display('posts/show.html', compact('post'));
	}

	public function create()
	{
		$this->twig->display('posts/create.html');
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
		$this->twig->display('posts/edit.html', compact('post'));
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
