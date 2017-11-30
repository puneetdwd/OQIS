<?php

class Holiday_model extends CI_Model {

    function __construct() {
        parent::__construct();

        require_once APPPATH .'libraries/pass_compat/password.php';
        $this->load->database();
    }

    function get_all_holidays() {
        $sql = "SELECT * FROM holiday_list h";
        
        $sql .= " GROUP BY h.id";
        
        $holidays = $this->db->query($sql);
        return $holidays->result_array();
    }
	
    function get_holiday($id) {
        $sql = "SELECT * FROM holiday_list h
        WHERE h.id = ?";
        
        return $this->db->query($sql,$id)->row_array();
    }
	function update_holiday($data, $id = '') {
        //filter unwanted fields while inserting in table.
        $needed_array = array('name', 'holiday_date');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($id)) {
            //$data['created'] = date("Y-m-d H:i:s");

            return (($this->db->insert('holiday_list', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $id);
            
            return (($this->db->update('holiday_list', $data)) ? $id : False);
        }
    }
    function get_email($id) {
        $sql = "SELECT * 
        FROM emails
        WHERE id = ? GROUP BY id";
        
        return $this->db->query($sql,$id)->row_array();
    }
    
    function get_user_by_type($user_type) {
        $this->db->where('user_type', $user_type);
        
        if($this->product_id) {
            $this->db->where('product_id', $this->product_id);
        }
        
        return $this->db->get('users')->result_array();
    }

    function is_holiday_exists($holiday_date, $id = '') {
        if(!empty($id)) {
            $this->db->where('id !=', $id);
        }

        $this->db->where('holiday_date', $holiday_date);

        return $this->db->count_all_results('holiday_list');
    }
	
	
    public function delete_holiday($id){
        $sql = "DELETE FROM holiday_list WHERE id = ?" ;
        return $this->db->query($sql, $id);
    }
}