<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class R_and_T_Relation_model extends MY_Model{

	public $table = 'r_and_t_relation';
	protected $primaryKey = array('r_id', 't_id');

	function __construct()
	{
		parent::__construct();
	}

	public function find($r_id, $t_id)
	{
		$query = $this->db->getwhere($this->table, 
			[$primaryKey[0] => $r_id, $primaryKey[1] => $t_id]);
		if($query->result())
			return $query->result[0];
		else
			return false;
	}
}