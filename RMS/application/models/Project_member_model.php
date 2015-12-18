<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project_member_model extends MY_Model{

	public $table = 'project_member';
	protected $primaryKey = array('p_id', 'u_id');

	function __construct()
	{
		parent::__construct();
	}

	public function find($p_id, $u_id)
	{
		$query = $this->db->getwhere($this->table, 
			[$primaryKey[0] => $p_id, $primaryKey[1] => $u_id]);
		if($query->result())
			return $query->result[0];
		else
			return false;
	}
}