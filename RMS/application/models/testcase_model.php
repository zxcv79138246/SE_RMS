<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testcase_model extends MY_Model {

	public $table = 'test_case';
    protected $primaryKey = 't_id';

    public function duplicateCheck($data, $is_create = 0)  //驗證使否有重複內容
    {
        $this->db->from($this->table);
        foreach ($data as $key => $value) {
            $this->db->where($key);
        }
        $query = $this->db->get();
        return ($query->num_rows() + $is_create) > 1;
    }
    
    public function getTestByPID($p_id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('p_id',$p_id);
        $query = $this->db->get();
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    public function searchTestcase($p_id , $likeCondition)
    {
        $query = $this->db->query("SELECT A.* 
                                    FROM 
                                    (SELECT * FROM $this->table WHERE  `p_id` = $p_id) as A
                                     WHERE A.`name` LIKE '%$likeCondition%'   
                                    ");
        return $query->result();
    }
}