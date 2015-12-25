<?php 

class MY_Model extends CI_Model
{
    
    public $table = '';
    protected $primaryKey = 'id';

    function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    public function find($id)
    {
        $query = $this->db->get_where($this->table, [$this->primaryKey => $id]);
        if ($query->result())
            return $query->result()[0];
        else
            return false;
    }

    public function where($condition)
    {
        $query = $this->db->get_where($this->table, $condition);
        if ($query)
            return $query->result();
        else 
            return [];
    }

    public function insert($post)
    {
        $query = $this->db->insert($this->table, $post);
        return $query;
    }

    public function update($post, $condition)
    {
        $query = $this->db->update($this->table, $post, $condition);
        return $query;
    }

    public function destory($condition)
    {
        $data = $this->where($condition);
        $query = $this->db->delete($this->table, $condition);
        if ($query)
            return $data;
        else
            return $query;
    }

    public function duplicateCheck($data, $is_create = 0)  //驗證使否有重複內容
    {
        $this->db->from($this->table);
        foreach ($data as $key => $value) {
            $this->db->or_where($key, $value);
        }
        $query = $this->db->get();
        return ($query->num_rows() + $is_create) > 1;
    }

    public function search($fields, $condition)   //搜尋  $fields 要搜尋的欄位 $condition 條件
    {
        $this->db->from($this->table);
        foreach ($fields as $field) {
            $this->db->or_like($field, $condition);
        }
        $query = $this->db->get();
        return ($query->result()) ? $query->result() : false;
    }


}
