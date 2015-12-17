<?php 

class Requirement_model extends MY_Model {

	public $table = 'requirement';
    protected $primaryKey = 'r_id';

	function __construct()		//constructer    繼承MY_Modle的constructer
    {
        parent::__construct();
    }

}