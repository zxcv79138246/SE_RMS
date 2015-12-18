<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testcase_model extends MY_Model {

	public $table = 'testcase';
    protected $primaryKey = 't_id';

	function __construct()		//constructer    繼承CI_Modle的constructer
    {
        parent::__construct();
    }

    public function all($p_id)
    {
    	$query = $this->db->get($this->table,['p_id' => $p_id]);
    	return $query->result();
    }

    public function find($t_id,$p_id)
    {
    	$query = $this->db->get_where($this->table, [$this->primaryKey => $t_id , 'p_id' => $p_id]);
    	if ($query->result())
    		return $query->result()[0];
    	else
    		return false;
    }
}