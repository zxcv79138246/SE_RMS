<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model {

	public $table = 'user';
    protected $primaryKey = 'u_id';

	function __construct()		//constructer    繼承CI_Modle的constructer
    {
        parent::__construct();
    }

    public function login($account)
    {
    	$query = $this->db->get_where($this->table,['email' => $account['email'], 'password' => $account['password']]);
    	if ($query->result())
    	{
    		return $query->result()[0];
    	}else
    	{
    		return false;
    	}
    }

    public function like_search($condition, $target)
    {
        $query = $this->db->query("SELECT * FROM $this->table WHERE $target LIKE '%$condition%'");
        return $query->result();
    }

    public function editUser()
    {
        $this->db->from($this->table);
        $this->db->or_where('priority','0');
        $this->db->or_where('priority','1');
        $query=$this->db->get();
        if ($query)
            return $query->result();
        else 
            return [];
    }
}