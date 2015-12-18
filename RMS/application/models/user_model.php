<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model {

	public $table = 'user';
    protected $primaryKey = 'email';

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
}