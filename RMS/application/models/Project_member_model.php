<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project_member_model extends MY_Model
{
	public $table = 'project_member';
	function __construct()
	{
		parent::__construct();
	}

	public function find($p_id)
	{
		$query = $this->db->get_where($this->table,[$primaryKey[0] => $p_id]);
		if($query->result())
			return $query->result[0];
		else
			return false;
	}

	public function isMember($u_id,$p_id)
	{
		$query = $this->db->get_where($this->table, ['p_id'=> $p_id, 'u_id' => $u_id]);
		if($query->result());
		else
			return false;
		return true;
	}

	public function dataInProject($u_id,$p_id)
    {
     	$this->db->select('user.u_id AS userID, user.name AS userName, project_member.priority AS projectPriority, project_member.p_id AS projectID');
     	$this->db->from($this->table);   
     	$this->db->join('user','user.u_id = project_member.u_id');
     	$this->db->where('project_member.u_id',$u_id);
     	$this->db->where('project_member.p_id',$p_id);
     	$query = $this->db->get();
     	if ($query)
     		return $query->result()[0];
     	else
     		return false;
    }
}