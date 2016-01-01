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

}