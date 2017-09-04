<?php
class Product_model extends CI_Model {

    function add_product($data, $product_id){
        $needed_array = array( 'name', 'dir_path', 'checked_by', 'approved_by', 'checklist_active');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($product_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('products', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $product_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('products', $data)) ? $product_id : False);
        }
        
    }
        
    function get_all_products(){
        $sql = 'SELECT id, name, dir_path FROM products';
        
        return $this->db->query($sql)->result_array();
    }
        
    function get_all_products_full(){
        $sql = "SELECT p.id, p.name, p.dir_path,
        CONCAT(u1.first_name, ' ', u1.last_name) as checked_by, u1.user_type as checked_by_type,
        CONCAT(u2.first_name, ' ', u2.last_name) as approved_by, u2.user_type as approved_by_type
        FROM products p
        LEFT JOIN users u1
        ON p.checked_by = u1.id
        LEFT JOIN users u2
        ON p.approved_by = u2.id";
        
        return $this->db->query($sql)->result_array();
    }
    
    function get_product($id) {
        $this->db->where('id', $id);

        return $this->db->get('products')->row_array();
    }

    function get_all_product_lines($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->where('is_deleted', 0);
        
        return $this->db->get('product_lines')->result_array();
    }
    
    function get_product_line($product_id, $id) {
        $this->db->where('id', $id);
        $this->db->where('product_id', $product_id);
        $this->db->where('is_deleted', 0);
        
        return $this->db->get('product_lines')->row_array();
    }
    
    function get_product_line_id($product_id, $line) {
        $this->db->where('name', $line);
        $this->db->where('product_id', $product_id);
        $this->db->where('is_deleted', 0);
        
        $result = $this->db->get('product_lines')->row_array();
        if(!empty($result)) {
            return $result['id'];
        }
        
        return false;
    }
    
    function update_product_line($data, $line_id){
        $needed_array = array('name', 'product_id', 'is_deleted');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($line_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('product_lines', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $line_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('product_lines', $data)) ? $line_id : False);
        }
    }

    function get_all_model_suffixs($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('tool, model_suffix');
        
        return $this->db->get('model_suffixs')->result_array();
    }
    
    function get_model_tool($product_id, $model_suffix) {
        $this->db->where('model_suffix', $model_suffix);
        $this->db->where('product_id', $product_id);
        $this->db->where('is_deleted', 0);
        
        return $this->db->get('model_suffixs')->row_array();
    }
    
    function get_all_tools($product_id) {
        $sql = "SELECT DISTINCT tool 
        FROM model_suffixs
        WHERE product_id = ?
        AND is_deleted = 0
        AND tool != ''";
        
        return $this->db->query($sql, array($product_id))->result_array();
    }
    
    function get_all_suffixs($product_id, $tool = '') {
        $sql = "SELECT DISTINCT model_suffix as model 
        FROM model_suffixs
        WHERE product_id = ?
        AND is_deleted = 0";
        
        $pass_array = array($product_id);
        if(!empty($tool) && $tool != 'All') {
            $sql .= " AND tool = ?";
            $pass_array[] = $tool;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_model_suffix($product_id, $id) {
        $this->db->where('id', $id);
        $this->db->where('product_id', $product_id);
        $this->db->where('is_deleted', 0);
        
        return $this->db->get('model_suffixs')->row_array();
    }
    
    function update_model_suffix($data, $model_suffix_id){
        $needed_array = array('model_suffix', 'tool', 'product_id', 'is_deleted');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($model_suffix_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('model_suffixs', $data)) ? $this->db->insert_id() : False);
        } else {
            if(is_array($model_suffix_id)) {
                $this->db->where_in('id', $model_suffix_id);
            } else {
                $this->db->where('id', $model_suffix_id);
            }
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('model_suffixs', $data)) ? $model_suffix_id : False);
        }
    }

    function insert_model_suffixs($model_suffixs, $product_id) {
        $this->db->insert_batch('model_suffixs', $model_suffixs);
        
        $this->remove_dups_model_suffixs($product_id);
    }
    
    function remove_dups_model_suffixs($product_id) {
        $sql = "DELETE FROM model_suffixs WHERE id NOT IN (
            SELECT * FROM ( SELECT max(id) FROM model_suffixs WHERE product_id = ? GROUP BY product_id, model_suffix) as d
        ) AND product_id = ?";
        
        return $this->db->query($sql, array($product_id, $product_id));
    }

    function get_all_phone_numbers($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->order_by('name');
        
        return $this->db->get('phone_numbers')->result_array();
    }
    
    function get_phone_number($product_id, $id) {
        $this->db->where('id', $id);
        $this->db->where('product_id', $product_id);
        
        return $this->db->get('phone_numbers')->row_array();
    }
    
    function update_phone_number($data, $phone_number_id){
        $needed_array = array('name', 'phone_number', 'product_id');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($phone_number_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('phone_numbers', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $phone_number_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('phone_numbers', $data)) ? $phone_number_id : False);
        }
    }
    
    function delete_phone_number($product_id, $id) {
        if(!empty($product_id) && !empty($id)) {
            $this->db->where('id', $id);
            $this->db->where('product_id', $product_id);
        
            $this->db->delete('phone_numbers');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }
}