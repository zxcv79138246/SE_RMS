<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends MY_Model {

	public $table = 'project';
    protected $primaryKey = 'name';
    
	function __construct()		//constructer    繼承CI_Modle的constructer
    {
        parent::__construct();
    }

    public function all()
    {
    	$query = $this->db->query("SELECT 
    								P.*, U.`name` AS leaderName 
    								FROM `project` as P 
    								LEFT JOIN `user` as U on P.`leader` = U.`u_id`");
    	return $query->result();
    }

}