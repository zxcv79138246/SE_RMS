<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends MY_Model {

	public $table = 'requirement';
    protected $primaryKey = 'r_id';

	function __construct()		//constructer    繼承CI_Modle的constructer
    {
        parent::__construct();
    }

    public function all($p_id)
    {
    	$query = $this->db->get($this->table,['p_id' => $p_id]);
    	return $query->result();
    }

    public function find($r_id,$p_id)
    {
    	$query = $this->db->get_where($this->table, [$this->primaryKey => $r_id , 'p_id' => $p_id]);
    	if ($query->result())
    		return $query->result()[0];
    	else
    		return false;
    }
}