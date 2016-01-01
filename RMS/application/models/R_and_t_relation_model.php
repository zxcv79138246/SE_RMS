<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class R_and_T_Relation_model extends MY_Model{

	public $table = 'r_and_t_relation';
	protected $primaryKey = array('r_id', 't_id');

	function __construct()
	{
		parent::__construct();
	}

	public function repeatCheck($data, $is_create = 0)  //驗證使否有重複內容
    {
        $sql = 'select requirement.name AS reqName, test_case.name AS testName ';
        $sql .= "from {$this->table} ";
        $sql .= 'join requirement on requirement.r_id = r_and_t_relation.r_id ';
        $sql .= 'join test_case on test_case.t_id = r_and_t_relation.t_id ';
        $or_where = [];
        foreach ($data['r_and_t_relation.t_id'] as $key => $value) {
        	$or_where[] = "r_and_t_relation.t_id = {$value} ";
        }
        $sql .= 'where (' . implode(' or ', $or_where) . ') ';
        $req_or_where=[];
        foreach ($data['r_and_t_relation.r_id'] as $key => $value) {
        	$req_or_where[] = "r_and_t_relation.r_id = {$value}";
        }
        $sql .= 'and (' . implode(' or ',$req_or_where) . ')';
        $query = $this->db->query($sql);
        return ($query->result()) ? $query->result()[0] : false;
    }
}