<?php
class Inspection_model extends CI_Model {

    function add_inspection($data, $inspection_id){
        $needed_array = array('name', 'product_id', 'gmes_insp_id', 'checkpoints_excel', 'insp_text', 'insp_type', 'interval_type', 'sort_index',
        'checkpoint_format', 'automate_result', 'full_auto', 'automate_case', 'automate_settings', 'directory_name', 'attach_report', 'inspection_duration');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($inspection_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('inspections', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $inspection_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('inspections', $data)) ? $inspection_id : False);
        }
        
    }
        
    function get_all_inspections($type = '') {
        $sql = "SELECT i.id, i.name, i.product_id, i.checkpoint_format, i.directory_name, i.insp_type,
        i.full_auto, i.automate_case, i.automate_result, i.is_active, i.gmes_insp_id, i.attach_report,
        p.name as product, 
        SUM(IF (c.is_deleted = 0, 1, 0)) as checkpoints_count,
        SUM(
            IF (c.is_deleted = 0 && (c.guideline_image IS NULL || c.guideline_image = ''), 1, 0)
        ) as checkpoint_without_guideline
        FROM inspections i
        INNER JOIN products p
        ON i.product_id = p.id
        LEFT JOIN checkpoints c
        ON i.id = c.inspection_id
        WHERE i.is_deleted = 0";
        
        $pass_array = array();
        if($this->product_id) {
            $sql .= ' AND i.product_id = ?';
            $pass_array = array($this->product_id);
        }
        
        if($type) {
            $sql .= ' AND i.insp_type = ?';
            $pass_array = array($type);
        }
        
        $sql .= " GROUP BY i.id";
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_all_inspections_by_product($product_id, $is_deleted = 0, $is_active = 1, $insp_type = '') {
        $this->db->where('product_id', $product_id);
        
        if(isset($is_deleted)) {
            $this->db->where('is_deleted', $is_deleted);
        }
        if(isset($is_active)) {
            $this->db->where('is_active', $is_active);
        }
        
        if($insp_type) {
            $this->db->where('insp_type', $insp_type);
        }
        
        return $this->db->get('inspections')->result_array();
    }
    function get_all_inspections_by_product_type($product_id,$insp_type){
		$this->db->where('product_id', $product_id);
        $this->db->where('is_deleted', 0);
        $this->db->where('is_active',1);
        $this->db->where('insp_type', $insp_type);
       
        return $this->db->get('inspections')->result_array();
    }
    
    function get_inspection($id, $is_deleted = 0) {
        $sql = 'SELECT i.id, i.name, i.product_id, i.checkpoints_excel, i.checkpoint_format, i.directory_name, i.gmes_insp_id,
        i.automate_result, i.full_auto, i.automate_case, i.automate_settings, i.insp_text, i.insp_type, i.interval_type, i.attach_report, i.inspection_duration,
        p.name as product
        FROM inspections i
        INNER JOIN products p
        ON i.product_id = p.id
        WHERE i.id = ?
        AND is_deleted = ?';
        
        $pass_array = array($id, $is_deleted);
        if($this->product_id) {
            $sql .= ' AND i.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    function get_inspection_by_name($name) {
        $sql = 'SELECT * FROM inspections i  
        WHERE i.is_active = 1 AND i.is_deleted = 0 AND i.name = ? ';
        
        $pass_array = array($name);
        if($this->product_id) {
            $sql .= ' AND i.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_inspection_checkpoints($inspection_id, $exclude = array()) {
        $this->db->where('inspection_id', $inspection_id);
        $this->db->where('is_deleted', '0');
        if(!empty($exclude)) {
            $this->db->where_not_in('checkpoint_no', $exclude);
        }

        $this->db->order_by('checkpoint_no');
        return $this->db->get('checkpoints')->result_array();
    }
    
    function get_checkpoint_for_download($inspection_id , $level, $full = false) {
        $sql = "SELECT checkpoint_no";
        if(!$full) {
            $sql .= ", insp_item";
            if($level >= 3) {
                $sql .= ", insp_item2";
            }
            if($level == 4) {
                $sql .= ", insp_item4";
            }
        }
        
        $sql .= ", insp_item3, spec, lsl, usl, tgt, unit, 'OK' as result
        FROM checkpoints
        WHERE inspection_id = ?
        AND is_deleted = 0
        ORDER BY checkpoint_no";
        
        return $this->db->query($sql, array($inspection_id))->result_array();
    }
    
    function get_inspection_checkpoints_model_specific($inspection_id, $model_suffix, $exclude = array()) {
        $sql = "SELECT c.inspection_id, c.checkpoint_no, c.insp_item, c.insp_item2, c.insp_item3, c.insp_item4, c.spec, c.has_multiple_specs,
            if(cs.lsl IS NULL, c.lsl, cs.lsl) as lsl,
            if(cs.usl IS NULL, c.usl, cs.usl) as usl,
            if(cs.tgt IS NULL, c.tgt, cs.tgt) as tgt,
            if(cs.unit IS NULL, c.unit, cs.unit) as unit,
            c.guideline_image, c.automate_result_row, c.automate_result_col, c.is_deleted
            FROM checkpoints c
            LEFT JOIN checkpoint_specs cs
            ON (c.id = cs.checkpoint_id AND cs.model_suffix = ? AND c.has_multiple_specs = 1)
            WHERE c.inspection_id = ?
            AND c.is_deleted = 0";
            
        $pass_array = array($model_suffix, $inspection_id);
        if(!empty($exclude)) {
            $sql .= " AND c.checkpoint_no NOT IN (". implode(',', array_fill(0, count($exclude), '?')).")";
            $pass_array = array_merge($pass_array, $exclude);
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function delete_inspection($id) {
        $this->db->where('id', $id);
        $this->db->set('is_deleted', 1);
        
        return $this->db->update('inspections');
    }
    
    function change_inspection_status($inspection_id, $status) {
        if(!empty($inspection_id) && !empty($status)) {
            $active = ($status == 'active') ? 1 : 0;
            
            $this->db->where('id', $inspection_id);
            if($this->product_id) {
                $this->db->where('product_id', $this->product_id);
            }
            $this->db->set('is_active', $active);
            $this->db->update('inspections');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }
    
    function is_checkpoint_no_exists($inspection_id, $checkpoint_no, $id = '') {
        $this->db->where('inspection_id', $inspection_id);
        $this->db->where('checkpoint_no', $checkpoint_no);
        $this->db->where('is_deleted', 0);
        
        if(!empty($id)) {
            $this->db->where('id !=', $id);
        }
        
        return $this->db->count_all_results('checkpoints');
    }
    
    function move_checkpoints($inspection_id, $checkpoint_no) {
        $sql = "UPDATE checkpoints SET 
        checkpoint_no = checkpoint_no + 1 
        WHERE inspection_id = ? 
        AND checkpoint_no >= ? 
        AND checkpoint_no <= (
            SELECT * FROM (
                SELECT min(c1.checkpoint_no)
                FROM `checkpoints` as c1
                LEFT JOIN `checkpoints` as c2
                ON (
                    c1.checkpoint_no = c2.checkpoint_no-1
                    AND c1.`inspection_id` = c2.`inspection_id`
                )
                WHERE c1.inspection_id = ? 
                AND c1.checkpoint_no >= ?
                AND c2.id IS NULL
            ) as sub
        )";
        
        $pass_array = array($inspection_id, $checkpoint_no, $inspection_id, $checkpoint_no);
        return $this->db->query($sql, $pass_array);
    }
    
    function move_checkpoints_down($inspection_id, $checkpoint_no) {
        $sql = "UPDATE checkpoints 
        SET checkpoint_no = checkpoint_no - 1
        WHERE inspection_id = ?
        AND checkpoint_no > ?";
        
        $pass_array = array($inspection_id, $checkpoint_no);
        return $this->db->query($sql, $pass_array);
    }
    
    function get_existing_checkpoints_no($inspection_id, $id = '') {
        $sql = "SELECT GROUP_CONCAT(checkpoint_no) as nos
        FROM checkpoints
        WHERE inspection_id = ?";

        $pass_array = array($inspection_id);
        if(!empty($id)) {
            $sql .= " AND id != ?";
            $pass_array[] = $id;
        }

        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_checkpoint($id) {
        $sql = "SELECT c.id, c.gmes_code, c.inspection_id, c.checkpoint_no, c.insp_item, c.insp_item2, 
        c.insp_item3, c.insp_item4, c.spec, c.has_multiple_specs, c.guideline_image, c.lsl, c.usl, c.tgt, c.unit,
        i.name as inspection_name, i.directory_name, p.name as product_name
        FROM checkpoints c
        INNER JOIN inspections i
        ON c.inspection_id = i.id
        INNER JOIN products p
        ON i.product_id = p.id
        WHERE c.id = ?
        AND c.is_deleted = 0";
        
        $pass_array = array($id);
        if($this->product_id) {
            $sql .= ' AND i.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_checkpoint_by_inspection_no($inspection_id, $checkpoint_no) {
        $this->db->where('inspection_id', $inspection_id);
        $this->db->where('checkpoint_no', $checkpoint_no);
        $this->db->where('is_deleted', 0);
        
        return $this->db->get('checkpoints')->row_array();
    }
    
    function insert_checkpoints($checkpoints, $inspection_id) {
        $this->db->where('inspection_id', $inspection_id);
        $this->db->delete('checkpoints');

        $this->db->insert_batch('checkpoints', $checkpoints);
    }
    
    function update_checkpoint($data, $checkpoint_id){
        $needed_array = array('gmes_code', 'inspection_id', 'checkpoint_no', 'insp_item', 'insp_item2', 'insp_item3', 'insp_item4', 
        'spec', 'guideline_image', 'lsl', 'usl', 'tgt', 'unit', 'automate_result_row', 'automate_result_col', 'has_multiple_specs');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($checkpoint_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('checkpoints', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $checkpoint_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('checkpoints', $data)) ? $checkpoint_id : False);
        }
        
    }
    
    function get_all_excluded_checkpoints() {
        $sql = "SELECT ex.`id`, ex.`model`, ex.`inspection_id`, 
        GROUP_CONCAT(c.`checkpoint_no` ORDER BY c.`checkpoint_no`) as checkpoints_nos,
        i.name as inspection_name, i.product_id, p.name as product_name
        FROM `excluded_checkpoints` ex 
        INNER JOIN `inspections` i
        ON ex.inspection_id = i.id
        INNER JOIN products p
        ON i.product_id = p.id
        LEFT JOIN checkpoints c
        ON (
            FIND_IN_SET(c.id, ex.`checkpoints_nos`)
            AND c.is_deleted = 0
        )
        WHERE ex.is_deleted = 0";
        
        $pass_array = array();
        if($this->product_id) {
            $sql .= ' AND i.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        $sql .= " GROUP BY ex.id";
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_excluded_checkpoint($id) {
        $sql = "SELECT ex.`id`, ex.`model`, ex.`inspection_id`, ex.`checkpoints_nos`,
        i.name as inspection_name, i.product_id, i.checkpoint_format,
        p.name as product_name
        FROM `excluded_checkpoints` ex 
        INNER JOIN `inspections` i
        ON ex.inspection_id = i.id
        INNER JOIN products p
        ON i.product_id = p.id
        WHERE ex.id = ?
        AND ex.is_deleted = 0";
        
        $pass_array = array($id);
        if($this->product_id) {
            $sql .= ' AND i.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_excluded_checkpoint_by_model_inspection($model, $inspection_id) {
        $sql = "SELECT ex.id, ex.inspection_id, ex.model, ex.is_deleted, ex.created, ex.modified,
        GROUP_CONCAT(c.`checkpoint_no` ORDER BY c.`checkpoint_no`) as checkpoints_nos
        FROM `excluded_checkpoints` ex 
        INNER JOIN checkpoints c
        ON (
            FIND_IN_SET(c.id, ex.`checkpoints_nos`)
            AND c.is_deleted = 0
        )
        WHERE ex.is_deleted = 0
        AND ex.model = ?
        AND ex.inspection_id = ?
        GROUP BY ex.id";
        
        return $this->db->query($sql, array($model, $inspection_id))->row_array();
        /* 
        $this->db->where('model', $model);
        $this->db->where('inspection_id', $inspection_id);
        $this->db->where('is_deleted', '0');
        
        return $this->db->get('excluded_checkpoints')->row_array(); */
    }
    
    function excluded_checkpoint_exists($inspection_id, $model, $id) {
        $this->db->where('inspection_id', $inspection_id);
        $this->db->where('model', $model);
        $this->db->where('is_deleted', 0);
        
        if(!empty($id)) {
            $this->db->where('id !=', $id);
        }
        
        return $this->db->get('excluded_checkpoints')->row_array();
    }
    
    function update_excluded_checkpoints($data, $excluded_id) {
        $needed_array = array('model', 'inspection_id', 'checkpoints_nos');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($excluded_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('excluded_checkpoints', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $excluded_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('excluded_checkpoints', $data)) ? $excluded_id : False);
        }
    }
    
    function delete_exclude_checkpoint($id) {
        $this->db->where('id', $id);
        $this->db->set('is_deleted', 1);
        
        return $this->db->update('excluded_checkpoints');
    }

    function delete_checkpoint($inspection_id, $checkpoint_id) {
        $this->db->where('id', $checkpoint_id);
        $this->db->where('inspection_id', $inspection_id);
        $this->db->set('is_deleted', 1);
        $this->db->set('modified', date("Y-m-d H:i:s"));
        
        return $this->db->update('checkpoints');
    }
    
    function delete_inspection_permanent($inspection_id) {
        if(!empty($inspection_id)) {
            $this->db->where('id', $inspection_id);
            
            if($this->product_id) {
                $this->db->where('product_id', $this->product_id);
            }
        
            $this->db->delete('inspections');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

    function add_history($data) {
        $needed_array = array('version', 'type', 'gmes_code', 'inspection_id', 'checkpoint_no', 'insp_item', 'insp_item2', 'insp_item3', 'insp_item4', 'spec', 'lsl', 'usl', 'tgt', 'unit', 'guideline_image', 'automate_result_row', 'automate_result_col', 'change_type', 'changed_on', 'remark');
        $data = array_intersect_key($data, array_flip($needed_array));
        
        $data['created'] = date("Y-m-d H:i:s");
        return (($this->db->insert('inspection_checkpoint_history', $data)) ? $this->db->insert_id() : False);
    }
    
    function get_revision_version($inspection_id) {
        $sql = "SELECT MAX(version) as version
        FROM inspection_checkpoint_history h
        WHERE h.inspection_id = ?";
        
        $version = $this->db->query($sql, array($inspection_id))->row_array();
        return $version['version'];
    }
    
    function get_history($inspection_id, $revision_date = '') {
        $sql = "SELECT h.*
        FROM inspection_checkpoint_history h
        WHERE h.inspection_id = ? ";
        
        $pass_array = array($inspection_id);
        if($revision_date) {
            $sql .= " AND DATE_FORMAT(changed_on, '%Y-%m-%d') = ? ";
            $pass_array[] = $revision_date;
        }
        $sql .= " ORDER BY version ASC, `Type` ASC";
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    
    function get_all_iterations($inspection_id) {
        $sql = "SELECT it.`id`, it.`inspection_id`, it.iteration_no, it.checkpoints, it.iteration_time, it.iter_time_type,
        GROUP_CONCAT(c.`checkpoint_no` ORDER BY c.`checkpoint_no`) as checkpoints_nos,
        i.name as inspection_name
        FROM `iterations` it 
        INNER JOIN `inspections` i
        ON it.inspection_id = i.id
        INNER JOIN checkpoints c
        ON (
            FIND_IN_SET(c.id, it.`checkpoints`)
            AND c.is_deleted = 0
        )
        WHERE it.inspection_id = ?";
        
        $pass_array = array($inspection_id);
        
        $sql .= " GROUP BY it.id";
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_iteration($inspection_id, $id) {
        $sql = "SELECT it.`id`, it.`inspection_id`, it.iteration_no, it.checkpoints, it.iteration_time, it.iter_time_type,
        GROUP_CONCAT(c.`checkpoint_no` ORDER BY c.`checkpoint_no`) as checkpoints_nos,
        i.name as inspection_name
        FROM `iterations` it 
        INNER JOIN `inspections` i
        ON it.inspection_id = i.id
        INNER JOIN checkpoints c
        ON (
            FIND_IN_SET(c.id, it.`checkpoints`)
            AND c.is_deleted = 0
        )
        WHERE it.inspection_id = ?
        AND it.id = ?";
        
        $pass_array = array($inspection_id, $id);
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_next_iteration_checkpoints($inspection_id, $iteration_no) {
        $sql = "SELECT it.checkpoints, it.iteration_time, it.iter_time_type
        FROM `iterations` it 
        WHERE inspection_id = ? AND iteration_no > ?
        ORDER BY iteration_no
        LIMIT 1";
        
        $pass_array = array($inspection_id, $iteration_no);
        $result = $this->db->query($sql, $pass_array)->row_array();
        return $result;
    }
    
    function get_last_iteration_no($inspection_id) {
        $sql = "SELECT MAX(iteration_no) as last_iteration_no 
        FROM `iterations` it 
        WHERE inspection_id = ?";
        
        $result = $this->db->query($sql, array($inspection_id))->row_array();
        
        return $result['last_iteration_no'];
    }
    
    function update_iteration($data, $iteration_id) {
        $needed_array = array('iteration_no', 'inspection_id', 'checkpoints', 'iteration_time', 'iter_time_type');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($iteration_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('iterations', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $iteration_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('iterations', $data)) ? $iteration_id : False);
        }
    }
    
    function get_all_specs($checkpoint_id) {
        $sql = "SELECT cs.* 
        FROM checkpoint_specs as cs
        WHERE cs.checkpoint_id = ?";

        return $this->db->query($sql, array($checkpoint_id))->result_array();
    }
    
    function get_spec($checkpoint_id, $id) {
         $sql = "SELECT cs.* 
        FROM checkpoint_specs as cs
        WHERE cs.checkpoint_id = ?
        AND cs.id = ?";
        
        return $this->db->query($sql, array($checkpoint_id, $id))->row_array();
    }
    
    function update_specs($data, $spec_id) {
        $needed_array = array('checkpoint_id', 'model_suffix', 'lsl', 'usl', 'tgt', 'unit');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($spec_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('checkpoint_specs', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $spec_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('checkpoint_specs', $data)) ? $spec_id : False);
        }
    }
    
    function remove_dups_specs($checkpoint_id) {
        $sql = "DELETE FROM checkpoint_specs WHERE id NOT IN (
            SELECT * FROM ( SELECT max(id) FROM checkpoint_specs WHERE checkpoint_id = ? GROUP BY checkpoint_id, model_suffix) as d
        ) AND checkpoint_id = ?";
        
        return $this->db->query($sql, array($checkpoint_id, $checkpoint_id));
    }
    
    function insert_specs($specs, $checkpoint_id) {
        $this->db->insert_batch('checkpoint_specs', $specs);
        
        $this->remove_dups_specs($checkpoint_id);
    }
}