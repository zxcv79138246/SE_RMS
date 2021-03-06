<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_model extends CI_Model {

    private $table = 'comments';
    private $fillable = ['post_id', 'body'];
    private $primaryKey = 'id';
    private $data;

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
    	return $query->result();
    }

    public function insert($post)
    {
        $result = $this->db->insert($this->table, $post);
        return $result;
    }

    public function update($post, $condition)
    {
        $result = $this->db->update($this->table, $post, $condition);
        return $result;
    }

	public function destory($condition)
    {
        $data = $this->where($condition);
        $result = $this->db->delete($this->table, $condition);
        if ($result)
            return $data;
        else
            return $result;
    }

    public __get($key)
    {
        if (in_array($key, $fillable))
        {
            return $data[$key];
        }
    }

    public function comments()
    {
        
    }
}