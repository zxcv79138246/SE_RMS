<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class R_and_R_relation_model extends MY_Model{

	public $table = 'r_and_r_relation';
	protected $primaryKey = array('r_id1', 'r_id2');

	function __construct()
	{
		parent::__construct();
	}

	public function find($r_id1, $r_id2)
	{
		$query = $this->db->getwhere($this->table, 
			[$primaryKey[0] => $r_id1, $primaryKey[1] => $r_id2]);
		if($query->result())
			return $query->result[0];
		else
			return false;
	}
}