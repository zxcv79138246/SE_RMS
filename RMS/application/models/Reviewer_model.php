<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reviewer_model extends MY_Model {

	public $table = 'reviewer';
    protected $primaryKey = array('u_id','r_id');
    
	function __construct()		//constructer    繼承CI_Modle的constructer
    {
        parent::__construct();
    }

    public function getReviewByUIDPID($u_id, $p_id)
    {
        $query = $this->db->get_where($this->table, ['u_id' => $u_id, 'p_id' => $p_id]);
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    public function getReviewByRID($r_id)
    {
        $query = $this->db->get_where($this->table, ['r_id' => $r_id]);
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    public function getNumDicisionByRID($r_id, $disicion)
    {
        $query = $this->db->get_where($this->table, ['r_id' => $r_id, 'decision' => $disicion]);
        return ($query->num_rows());
    }
}