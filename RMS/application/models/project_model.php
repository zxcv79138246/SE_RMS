<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends MY_Model {

	public $table = 'project';
    protected $primaryKey = 'p_id';
	function __construct()		//constructer    繼承CI_Modle的constructer
    {
        parent::__construct();
    }
}