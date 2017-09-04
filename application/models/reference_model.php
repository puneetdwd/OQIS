<?php
class Reference_model extends CI_Model {

    function update_reference($data, $reference_id){
        $needed_array = array('product_id', 'inspection_id', 'tool',
        'model_suffix', 'name', 'reference_file', 'reference_url', 'mandatory');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($reference_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('reference_links', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $reference_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('reference_links', $data)) ? $reference_id : False);
        }
        
    }

    function get_all_references(){
        $sql = 'SELECT r.*, i.name as inspection_name
        FROM reference_links r
        LEFT JOIN inspections as i
        ON r.inspection_id = i.id';
        
        $pass_array = array();
        if($this->product_id) {
            $sql .= ' WHERE r.product_id = ?';
            $pass_array = array($this->product_id);
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }

    function get_specific_reference_links($inspection_id, $tool, $model_suffix) {
        // All things specified
        $sql = "SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id = ? AND tool = ? AND model_suffix = ?";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        $pass_array = array($inspection_id, $tool, $model_suffix);
        
        // Inspection an Model specified and Tool All
        $sql .= " UNION SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id = ? AND tool IS NULL AND model_suffix = ?";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        $pass_array = array_merge($pass_array, array($inspection_id, $model_suffix));
        
        // Inspection an Tool specified and Model All
        $sql .= " UNION SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id = ? AND tool = ? AND model_suffix IS NULL";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        $pass_array = array_merge($pass_array, array($inspection_id, $tool));
        
        // Tool an Model specified and Inspection All
        $sql .= " UNION SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id IS NULL AND tool = ? AND model_suffix = ?";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        $pass_array = array_merge($pass_array, array($inspection_id, $tool));
        
        // Model Specified, Inspection and Tool All
        $sql .= " UNION SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id IS NULL AND tool IS NULL AND model_suffix = ?";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        $pass_array = array_merge($pass_array, array($model_suffix));
        
        // Tool Specified, Inspection and Model All
        $sql .= " UNION SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id IS NULL AND tool = ? AND model_suffix IS NULL";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        $pass_array = array_merge($pass_array, array($tool));
        
        // Tool Specified, Model Specified
        $sql .= " UNION SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id IS NULL AND tool = ? AND model_suffix = ?";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        $pass_array = array_merge($pass_array, array($tool, $model_suffix));
        
        // Inspection Specified, Tool and Model All
        $sql .= " UNION SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id = ? AND tool IS NULL AND model_suffix IS NULL";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        
        // ALL 
        $sql .= " UNION SELECT `id`, `name`, `reference_file`, `reference_url`, `mandatory` FROM reference_links WHERE inspection_id IS NULL AND tool IS NULL AND model_suffix IS NULL";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        $pass_array = array_merge($pass_array, array($inspection_id));
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_references_by_model($model_suffix) {
        $sql = "SELECT * FROM reference_links WHERE model_suffix = ?";
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        
        $sql .= " UNION SELECT * FROM reference_links WHERE model_suffix = ''";
        
        if($this->product_id) {
            $sql .= ' AND product_id = '.$this->product_id;
        }
        
        return $this->db->query($sql, array($model_suffix))->result_array();
    }
    
    function get_reference($id) {
        $sql = 'SELECT r.*, i.name as inspection_name
        FROM reference_links as r
        LEFT JOIN inspections as i
        ON r.inspection_id = i.id
        WHERE r.id = ?';
        
        $pass_array = array($id);
        if($this->product_id) {
            $sql .= ' AND r.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        return $this->db->query($sql, $pass_array)->row_array();
    }

    function delete_reference($reference_id) {
        if(!empty($reference_id)) {
            $this->db->where('id', $reference_id);
            
            if($this->product_id) {
                $this->db->where('product_id', $this->product_id);
            }
        
            $this->db->delete('reference_links');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

    function get_all_links($product_id) {
        $sql = 'SELECT DISTINCT name
        FROM reference_links r
        WHERE r.product_id = ?';
        
        $pass_array = array($product_id);
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_all_checkpoint_configs() {
        $sql = "SELECT cc.`id`, cc.`reference_link`, cc.`inspection_id`, 
        GROUP_CONCAT(c.`checkpoint_no` ORDER BY c.`checkpoint_no`) as checkpoints_nos,
        i.name as inspection_name, i.product_id
        FROM `ref_link_checkpoint_configs` cc 
        INNER JOIN `inspections` i
        ON cc.inspection_id = i.id
        LEFT JOIN checkpoints c
        ON (
            FIND_IN_SET(c.id, cc.`checkpoints_nos`)
            AND c.is_deleted = 0
        )
        WHERE cc.is_deleted = 0
        AND i.product_id = ?";
        
        $pass_array = array($this->product_id);
        $sql .= " GROUP BY cc.id";
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_checkpoint_config($id) {
        $sql = "SELECT cc.`id`, cc.`reference_link`, cc.`inspection_id`, cc.`checkpoints_nos`,
        i.name as inspection_name, i.product_id, i.checkpoint_format
        FROM `ref_link_checkpoint_configs` cc 
        INNER JOIN `inspections` i
        ON cc.inspection_id = i.id
        WHERE cc.id = ?
        AND cc.is_deleted = 0
        AND i.product_id = ?";
        
        $pass_array = array($id, $this->product_id);
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function checkpoint_config_exists($inspection_id, $reference_link, $id) {
        $this->db->where('inspection_id', $inspection_id);
        $this->db->where('reference_link', $reference_link);
        $this->db->where('is_deleted', 0);
        
        if(!empty($id)) {
            $this->db->where('id !=', $id);
        }
        
        return $this->db->get('ref_link_checkpoint_configs')->row_array();
    }
    
    function update_checkpoint_config($data, $config_id) {
        $needed_array = array('reference_link', 'inspection_id', 'checkpoints_nos');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($config_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('ref_link_checkpoint_configs', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $config_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('ref_link_checkpoint_configs', $data)) ? $config_id : False);
        }
    }
    
    function delete_checkpoint_config($id) {
        $this->db->where('id', $id);
        $this->db->set('is_deleted', 1);
        
        return $this->db->update('ref_link_checkpoint_configs');
    }
    
    function get_mandatory_links_for_checkpoint($inspection_id, $checkpoint_id) {
        $sql = "SELECT reference_link FROM `ref_link_checkpoint_configs` 
            WHERE inspection_id = ?
            AND FIND_IN_SET(?, `checkpoints_nos`)
            AND is_deleted = 0";
            
        $pass_array = array($inspection_id, $checkpoint_id);
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
}