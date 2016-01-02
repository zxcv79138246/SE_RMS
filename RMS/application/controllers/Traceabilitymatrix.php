<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Traceabilitymatrix extends CI_Controller
{
	private $currentProject =0;
	function __construct()
	{
		parent::__construct();
	} 

	public function index()
	{
		$this->twig->display('rms/traceabilitymatrix/traceabilitymatrix.html');
	}
}