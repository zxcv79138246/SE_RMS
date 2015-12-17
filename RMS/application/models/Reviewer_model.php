<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reviewer_model extends MY_Model {

	public $table = 'reviewer';
    protected $primaryKey = array('u_id','r_id');
    
	function __construct()		//constructer    繼承CI_Modle的constructer
    {
        parent::__construct();
    }

    public function all($p_id,$u_id)
    {
    	$query = $this->db->get($this->table,['p_id' => $p_id ,$this->$primaryKey[0] => $u_id]);
    	return $query->result();
    }

    public function find($u_id,$r_id)
    {
    	$query = $this->db->get_where($this->table, [$this->primaryKey[0] => $u_id , $this->primaryKey[1] => $r_id]);
    	if ($query->result())
    		return $query->result()[0];
    	else
    		return false;
    }

    
}