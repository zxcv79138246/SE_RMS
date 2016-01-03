<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class R_and_R_relation_model extends MY_Model{

	public $table = 'r_and_r_relation';
	protected $primaryKey = array('r_id1', 'r_id2');

	function __construct()
	{
		parent::__construct();
	}

	public function repeatCheck($data, $is_create = 0)  //驗證使否有重複內容
    {
        $sql = 'select r1.name as r1Name, r2.name as r2Name ';
        $sql .= "from {$this->table} ";
        $sql .= 'join requirement r1 on r1.r_id = r_and_r_relation.r_id1 ';
        $sql .= 'join requirement r2 on r2.r_id = r_and_r_relation.r_id2 ';
        $or_where = [];
        foreach ($data['r_and_r_relation.r_id1'] as $key => $value) {
        	$or_where[] = "r_and_r_relation.r_id1 = {$value} ";
        }
        $sql .= 'where (' . implode(' or ', $or_where) . ') ';
        $req_or_where=[];
        foreach ($data['r_and_r_relation.r_id2'] as $key => $value) {
        	$req_or_where[] = "r_and_r_relation.r_id2 = {$value}";
        }
        $sql .= 'and (' . implode(' or ',$req_or_where) . ') ';
        $query = $this->db->query($sql);
        return ($query->result()) ? $query->result()[0] : false;
    }

    public function searchRelReq($r_id)
    {
    	$sql = 'select r1.r_id as r1_id, r2.r_id as r2_id, r1.name as r1Name, r2.name as r2Name ';
    	$sql .= "from {$this->table} ";
    	$sql .= 'join requirement r1 on r1.r_id = r_and_r_relation.r_id1 ';
        $sql .= 'join requirement r2 on r2.r_id = r_and_r_relation.r_id2 ';
        $sql .= "where r_and_r_relation.r_id1 = {$r_id} or r_and_r_relation.r_id2 = {$r_id} ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function destoryRelation($r_id,$delR_ids)
    {
    	$sql = 'delete ';
    	$sql .= "from {$this->table} ";
    	$r1where = [];
    	$r2Where = [];
    	foreach ($delR_ids as $key => $delR_id) {
    		$r1where[] = "r_id1 = {$delR_id}";
    		$r2where[] = "r_id2 = {$delR_id}";
    	}
    	$sql .= "where ( r_id1 = {$r_id} and (" . implode(' or ', $r2where) . ') ) ';
    	$sql .=  "or ( r_id2 = {$r_id} and (" . implode(' or ', $r1where) . ') ) ';
    	$query = $this->db->query($sql);
    	return $query;
    }

    public function isExistRelation($r_id1,$r_id2)
    {
        $this->db->from($this->table);
        $this->db->where('r_id1', $r_id1);
        $this->db->where('r_id2', $r_id2);
        $query = $this->db->get();
        return ($query->num_rows()==1);
    }
}