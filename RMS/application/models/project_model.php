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

    public function participate($u_id)
    {
        $query = $this->db->query("SELECT P.*, U.`name` AS leaderName , P_M.`priority` AS projectPriority
                                    FROM `project` AS P
                                    LEFT JOIN `user` AS U ON P.`leader` = U.`u_id`
                                    LEFT JOIN `project_member` AS P_M ON P_M.`p_id` = P.`p_id`
                                    WHERE P_M.`u_id` = $u_id");
        return $query->result();
    }

    public function findNowField($p_id)
    {
        $query = $this->db->get_where($this->table, ['p_id' => $p_id]);
        if ($query->result())
            return $query->result()[0];
        else
            return false;
    }

}