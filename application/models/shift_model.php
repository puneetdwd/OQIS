<?php
class Shift_model extends CI_Model {

    function get_all_shifts($product_id) {
        $this->db->where('product_id', $product_id);
        
        return $this->db->get('shifts')->result_array();
    }
    
    function get_shift($product_id, $id) {
        $this->db->where('id', $id);
        $this->db->where('product_id', $product_id);
        
        return $this->db->get('shifts')->row_array();
    }
    
    function update_shift($data, $shift_id){
        $needed_array = array('name', 'detail', 'product_id');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($shift_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('shifts', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $shift_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('shifts', $data)) ? $shift_id : False);
        }
    }
    
    function delete_shift($product_id, $shift_id) {
        if(!empty($product_id) && !empty($shift_id)) {
            $this->db->where('id', $shift_id);
            $this->db->where('product_id', $product_id);
        
            $this->db->delete('shifts');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }
}