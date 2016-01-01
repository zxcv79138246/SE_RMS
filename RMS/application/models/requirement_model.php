<?php 

class Requirement_model extends MY_Model {

	public $table = 'requirement';
    protected $primaryKey = 'r_id';

	function __construct()		//constructer    繼承MY_Modle的constructer
    {
        parent::__construct();
    }

    public function getReqByPID($p_id)
    {
    	$query = $this->db->get_where($this->table, ['p_id' => $p_id]);
        if ($query->result())
            return $query->result();
        else
            return false;
    }
}