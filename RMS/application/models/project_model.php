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

    public function findProject($id)
    {
        $query = $this->db->get_where($this->table, ['p_id' => $id]);
        if ($query->result())
            return $query->result()[0];
        else
            return false;
    }

    public function getLeaderName($p_id)
    {
                $query = $this->db->query("SELECT 
                                     U.`name` AS leaderName 
                                    FROM `project` as P 
                                    LEFT JOIN `user` as U on P.`leader` = U.`u_id`
                                    WHERE P.`p_id` = $p_id "
                                    );
        return $query->result()[0]->leaderName;
    }
}