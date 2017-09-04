<?php
class Sampling_model extends CI_Model {
    
    function get_day_progress($sampling_date) {
        $sql = "SELECT s.`sampling_date`, s.model_suffix, s.`inspection_id`, s.`no_of_samples`, i.name as inspection_name,
            SUM(IF(a.state = 'completed', 1, 0)) as completed,
            SUM(IF(a.state != 'completed' && a.state != 'aborted', 1, 0)) as in_progess
            FROM `sampling_plans` s
            LEFT JOIN audits a
            ON (
                s.`sampling_date` = a.audit_date 
                AND s.inspection_id = a.inspection_id
                AND s.model_suffix = a.model_suffix
            )
            INNER JOIN inspections i
            ON s.inspection_id = i.id
            WHERE s.sampling_date = ?
            GROUP BY s.`sampling_date`, s.model_suffix, s.`inspection_id`";
           
        return $this->db->query($sql, array($sampling_date))->result_array();
    }
    
    function get_all_sampling_plans_concatenated() {
        $sql = "SELECT s.`sampling_date`, GROUP_CONCAT(i.name ORDER BY s.id) as inspection_name, 
        GROUP_CONCAT(s.no_of_samples ORDER BY s.id)  as no_of_samples
        FROM `sampling_plans` s 
        INNER JOIN inspections i 
        ON s.inspection_id = i.id 
        GROUP BY s.`sampling_date`
        ORDER BY s.sampling_date DESC";
        
        return $this->db->query($sql)->result_array();
    }
    
    
    //New changes
    function get_progress_for_sampling_plan($sampling_date, $inspection_id, $model_suffix, $line, $extra = false) {
        $sql = "SELECT ".($extra ? "s.lot_size, " : "")."s.`no_of_samples`,
            COUNT(DISTINCT CASE WHEN a.state = 'completed' THEN a.id ELSE NULL END) as completed,
            COUNT(DISTINCT CASE WHEN a.state != 'completed' && a.state != 'aborted' THEN a.id ELSE NULL END) as in_progess,
            SUM(IF(a.state = 'completed' AND ac.result = 'NG', 1, 0)) as ng_count
            FROM `sampling_plans` s
            INNER JOIN product_lines pl
            ON s.line = pl.name
            LEFT JOIN audits a
            ON (
                s.`sampling_date` = a.audit_date 
                AND s.inspection_id = a.inspection_id
                AND s.model_suffix = a.model_suffix
                AND s.product_id = a.product_id
                AND pl.id = a.line_id
            )
            LEFT JOIN audit_checkpoints ac
            ON a.id = ac.audit_id
            WHERE s.sampling_date = ?
            AND s.inspection_id = ?
            AND s.model_suffix = ?
            AND s.product_id = ?";
            
            if((int)$line > 0) {
                $sql .= " AND pl.id = ?";
            } else {
                $sql .= " AND s.line = ?";
            }

        $sql .= " AND s.skipped = 0 GROUP BY s.`sampling_date`, s.model_suffix, s.`inspection_id`, s.line";
           
        return $this->db->query($sql, array($sampling_date, $inspection_id, $model_suffix, $this->product_id, $line))->row_array();
    }
    
    function get_no_of_samples_for_sampling_plan($sampling_date, $inspection_id, $model_suffix, $line) {
        $this->db->where('inspection_id', $inspection_id);
        $this->db->where('model_suffix', $model_suffix);
        $this->db->where('sampling_date', $sampling_date);
        $this->db->where('line', $line);
        
        $result = $this->db->get('sampling_plans');
        if($result->num_rows() > 0) {
            $result = $result->row_array();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_all_inspections_for_sampling_plan($plan_date, $only_interval = false) {
        $sql = "SELECT s.inspection_id, s.config_type, i.name as inspection_name, i.insp_text, s.sampling_type
        FROM sampling_plans s
        INNER JOIN inspections i
        ON s.inspection_id = i.id
        WHERE sampling_date = ?
        AND s.product_id = ?";
        
        if($only_interval) {
            $sql .= " AND s.sampling_type = 'Interval'";
        }
        
        $sql .= " GROUP BY s.inspection_id
        ORDER BY -i.sort_index DESC";
        
        return $this->db->query($sql, array($plan_date, $this->product_id))->result_array();
    }
    
    function get_sampling_plan_dimensions($plan_date) {
        $sql = "SELECT DISTINCT line, tool, model_suffix, lot_size, original_lot_size, product_id, group_count
        FROM sampling_plans s
        WHERE sampling_date = ?
        AND product_id = ?
        ORDER BY tool, line, model_suffix";
        
        return $this->db->query($sql, array($plan_date, $this->product_id))->result_array();
    }
    
    function get_all_models_for_sampling_plan($plan_date) {
        $sql = "SELECT s.model_suffix, p.lot_size
        FROM sampling_plans s
        INNER JOIN production_plans p
        ON (
            p.plan_date = s.sampling_date 
            AND p.product_id = s.product_id 
            AND p.model_suffix = s.model_suffix
        )
        WHERE s.sampling_date = ?
        AND s.product_id = ?
        GROUP BY s.model_suffix
        ORDER BY s.model_suffix";
        
        return $this->db->query($sql, array($plan_date, $this->product_id))->result_array();
    }
    
    function insert_sampling_plan($data, $sampling_date) {
        $this->db->where('sampling_date', $sampling_date);
        $this->db->where('product_id', $this->product_id);
        $this->db->delete('sampling_plans');

        $this->db->insert_batch('sampling_plans', $data);
    }
    
    function get_lot_template() {
        return $this->db->get('lot_template')->result_array();
    }
    
    function get_all_inspection_config($product_id) {
        $sql = "SELECT c.*, i.name as inspection_name
        FROM inspection_config c
        INNER JOIN inspections i
        ON c.inspection_id = i.id
        WHERE c.product_id = ?
        AND c.model_suffix IS NULL
        AND c.tool IS NULL
        AND c.line IS NULL
        AND i.is_deleted = 0
        AND i.is_active = 1";
        
        $pass_array = array($product_id);
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_specific_inspection_config($product_id, $model_suffix, $tool, $line) {
        $sql = "SELECT c.*, i.name as inspection_name
        FROM inspection_config c
        INNER JOIN inspections i
        ON c.inspection_id = i.id
        LEFT JOIN product_lines pl
        on c.line = pl.id
        WHERE c.product_id = ?
        AND i.is_deleted = 0
        AND i.is_active = 1";
        
        $pass_array = array($product_id);
        if(!empty($model_suffix)) {
            $sql .= " AND c.model_suffix = ?";
            $pass_array[] = $model_suffix;
        } else {
            $sql .= " AND c.model_suffix IS NULL";
        }
        
        if(!empty($tool)) {
            $sql .= " AND c.tool = ?";
            $pass_array[] = $tool;
        } else {
            $sql .= " AND c.tool IS NULL";
        }
        
        if(!empty($line)) {
            $sql .= " AND pl.name = ?";
            $pass_array[] = $line;
        } else {
            $sql .= " AND c.line IS NULL";
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_model_specific_inspection_config($product_id, $model_suffix) {
         $sql = "SELECT c.*, i.name as inspection_name
        FROM inspection_config c
        INNER JOIN inspections i
        ON c.inspection_id = i.id
        WHERE c.product_id = ?
        AND model_suffix = ?";
        
        return $this->db->query($sql, array($product_id, $model_suffix))->result_array();
    }
    
    function get_configs($inspection_id, $line, $tool, $model_suffix) {
        $sql = "SELECT c.*, pl.name as line_name, i.name as inspection_name
        FROM inspection_config c
        INNER JOIN inspections i
        ON c.inspection_id = i.id
        LEFT JOIN product_lines pl
        ON c.line = pl.id
        WHERE c.product_id = ?";
        
        $pass_array = array($this->product_id);
        
        if(!empty($inspection_id) && $inspection_id != 'All') {
            $sql .= ' AND c.inspection_id = ?';
            $pass_array[] = $inspection_id;
        }
        
        if(!empty($line) && $line != 'All') {
            $sql .= ' AND (c.line = ? OR c.line IS NULL)';
            $pass_array[] = $line;
        }
        
        if(!empty($tool) && $tool != 'All') {
            $sql .= ' AND (c.tool = ? OR c.tool IS NULL)';
            $pass_array[] = $tool;
        }
        
        if(!empty($model_suffix) && $model_suffix != 'All') {
            $sql .= ' AND (c.model_suffix = ? OR c.model_suffix IS NULL)';
            $pass_array[] = $model_suffix;
        }
        
        $sql .= " ORDER BY i.name, pl.name, c.tool, c.model_suffix";
        
        $configs = $this->db->query($sql, $pass_array)->result_array();
        
        foreach($configs as $key => $config) {
            if($config['sampling_type'] == 'User Defined' || $config['sampling_type'] == 'Interval') {
                $lots = $this->get_lot_range_samples($config['id']);
                
                $configs[$key]['lots'] = $lots;
            }
        }
        
        return $configs;
    }
    
    function get_inspection_config_by_id($config_id, $product_id) {
        $this->db->where('id', $config_id);
        $this->db->where('product_id', $product_id);
        
        return $this->db->get('inspection_config')->row_array();
    }
    
    function get_inspection_config_type($inspection_id) {
        $sql = "SELECT inspection_type 
        FROM inspection_config
        WHERE inspection_id = ?
        GROUP BY inspection_id";
        
        $result = $this->db->query($sql, array($inspection_id))->row_array();
        if(!empty($result)) {
            return $result['inspection_type'];
        }
        
        return '';
    }
    
    function delete_config($config_id) {
        $this->db->where('id', $config_id);
        
        $this->db->delete('inspection_config');
        if($this->db->affected_rows() > 0) {
            return true;
        }
        
        return false;
    }
    
    function get_inspection_config($inspection_id) {
        $sql = "SELECT c.*, pl.name as line_name
        FROM inspection_config c
        LEFT JOIN product_lines pl
        ON c.line = pl.id
        WHERE c.inspection_id = ?";
        
        $pass_array = array($inspection_id);
        if($this->product_id) {
            $sql .= ' AND c.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        $configs = $this->db->query($sql, $pass_array)->result_array();
        
        foreach($configs as $key => $config) {
            if($config['sampling_type'] == 'User Defined' || $config['sampling_type'] == 'Interval') {
                $lots = $this->get_lot_range_samples($config['id']);
                
                $configs[$key]['lots'] = $lots;
            }
        }
        
        return $configs;
    }
    
    function delete_if_exists_inspection_config($product_id, $inspection_id, $model_suffix) {
        $this->db->where('product_id', $product_id);
        $this->db->where('inspection_id', $inspection_id);
        $this->db->where('model_suffix', $model_suffix);
        
        $this->db->delete('inspection_config');
    }
    
    function update_inspection_config($data, $config_id){
        $needed_array = array('product_id', 'inspection_id', 'inspection_type', 'line', 'tool', 'model_suffix', 
        'sampling_type', 'inspection_level', 'acceptable_quality', 'no_of_months', 'no_of_times');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($config_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('inspection_config', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $config_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('inspection_config', $data)) ? $config_id : False);
        }
        
    }
    
    function check_sampling_done_interval_insp($start_date, $end_date, $inspection_id, $model_suffix, $line) {
        $sql = "SELECT s.`no_of_samples`,
            SUM(IF(a.state = 'completed', 1, 0)) as completed
            FROM `sampling_plans` s
            INNER JOIN product_lines pl
            ON s.line = pl.name
            LEFT JOIN audits a
            ON (
                s.`sampling_date` = a.audit_date 
                AND s.inspection_id = a.inspection_id
                AND s.model_suffix = a.model_suffix
                AND s.product_id = a.product_id
                AND pl.id = a.line_id
            )
            WHERE s.sampling_date BETWEEN ? AND ?
            AND s.inspection_id = ?
            AND s.model_suffix = ?
            AND s.product_id = ?
            AND s.line = ?
            AND s.sampling_date != '".date('Y-m-d')."'
            GROUP BY s.sampling_date";
            
        return $this->db->query($sql, array($start_date, $end_date, $inspection_id, $model_suffix, $this->product_id, $line))->result_array();
    }
    
    function get_no_of_samples($config_id, $lot_size) {
        $sql = "SELECT no_of_samples
        FROM `inspection_lot_range` 
        WHERE lower_val = (
            SELECT max(lower_val) FROM `inspection_lot_range` WHERE `lower_val` <= ? AND config_id = ?
        )
        AND config_id = ?";
        
        $result = $this->db->query($sql, array($lot_size, $config_id, $config_id));

        if($result->num_rows() > 0) {
            $result = $result->row_array();
            return $result['no_of_samples'];
        } else {
            return FALSE;
        }
    }
    
    function get_acceptance_qualities() {
        $sql = "SELECT DISTINCT acceptable_quality as quality FROM auto_code_acceptance_sample_mapping";
        
        return $this->db->query($sql)->result_array();
    }
    
    function get_no_of_samples_auto($lot_size, $inspection_level, $acceptable_quality) {
        $sql = "
        SELECT no_of_samples FROM auto_code_acceptance_sample_mapping
        WHERE code = (
            SELECT code
            FROM `auto_lot_code_mapping` 
            WHERE lower_val = (
                SELECT max(lower_val) FROM `auto_lot_code_mapping` WHERE `lower_val` <= ?
            )
            AND inspection_level = ?
        ) AND acceptable_quality = ?";
        
        $result = $this->db->query($sql, array($lot_size, $inspection_level, $acceptable_quality));
        
        if($result->num_rows() > 0) {
            $result = $result->row_array();
            return $result['no_of_samples'];
        } else {
            return FALSE;
        }
    }
   
    function get_lot_range_samples($config_id) {
        $this->db->where('config_id', $config_id);
        
        return $this->db->get('inspection_lot_range')->result_array();
    }
    
    function delete_lot_range_samples($config_id) {
        $this->db->where('config_id', $config_id);
        
        return $this->db->delete('inspection_lot_range');
    }
    
    function insert_lot_range_samples($lots, $config_id) {
        $this->db->where('config_id', $config_id);
        $this->db->delete('inspection_lot_range');

        $this->db->insert_batch('inspection_lot_range', $lots);
    }

    function get_production_plans() {
        $sql = "SELECT DISTINCT plan_date FROM production_plans WHERE product_id = ? ORDER BY plan_date DESC";
        
        return $this->db->query($sql, array($this->product_id))->result_array();
    }
    
    function get_production_plan($plan_date) {
        $this->db->where('plan_date', $plan_date);
        $this->db->where('product_id', $this->product_id);
        $this->db->order_by('tool, line, model_suffix desc');
        
        return $this->db->get('production_plans')->result_array();
    }
    
    function get_production_plan_monthly($plan_month) {
        $this->db->where('plan_month', $plan_month);
        $this->db->where('product_id', $this->product_id);
        
        return $this->db->get('production_plans_monthly')->result_array();
    }
    
    function get_production_plan_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('product_id', $this->product_id);
        $this->db->order_by('tool, line, model_suffix desc');
        
        return $this->db->get('production_plans')->row_array();
    }
    
    function get_production_plan_monthly_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('product_id', $this->product_id);
        
        return $this->db->get('production_plans_monthly')->row_array();
    }
    
    function get_model_planned_n_produced($model_suffix, $month) {
        $sql = "SELECT SUM(ppm.lot_size) as planned, SUM(pp.lot_size) as produced
        FROM production_plans_monthly ppm
        LEFT JOIN production_plans pp 
        ON (
            pp.model_suffix = ppm.model_suffix 
            AND DATE_FORMAT(pp.`plan_date`, '%Y-%m-01') = ? 
            AND ppm.product_id = pp.product_id
        )
        WHERE ppm.plan_month = ?
        AND ppm.model_suffix = ?
        AND ppm.product_id = ?";
        
        $pass_array = array($month, $month, $model_suffix, $this->product_id);
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_sampling_plan_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('product_id', $this->product_id);
        
        return $this->db->get('sampling_plans')->row_array();
    }
    
    function get_sampling_plan($sampling_date, $line, $model_suffix, $inspection_id) {
        $sql = "SELECT s.lot_id, s.sampling_date, s.line, s.tool,
        s.model_suffix, s.inspection_id, s.lot_size, s.no_of_samples
        FROM sampling_plans s
        INNER JOIN product_lines pl
        ON s.line = pl.name
        WHERE s.sampling_date = ?
        AND pl.id = ?
        AND s.model_suffix = ?
        AND s.inspection_id = ?
        AND s.product_id = ?";
        
        return $this->db->query($sql, array($sampling_date, $line, $model_suffix, $inspection_id, $this->product_id))->row_array();
    }
    
    function delete_production_plan($plan_id) {
        if(!empty($plan_id)) {
            $this->db->where('id', $plan_id);
            
            if($this->product_id) {
                $this->db->where('product_id', $this->product_id);
            }
        
            $this->db->delete('production_plans');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }
    
    function delete_production_plan_monthly($plan_id) {
        if(!empty($plan_id)) {
            $this->db->where('id', $plan_id);
            $this->db->where('product_id', $this->product_id);
        
            $this->db->delete('production_plans_monthly');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }
    
    function update_product_plan($data, $plan_id = ''){
        $needed_array = array('product_id', 'plan_date', 'line', 'tool', 'model_suffix', 'lot_size', 'original_lot_size', 'is_user_defined');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($plan_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('production_plans', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $plan_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('production_plans', $data)) ? $plan_id : False);
        }
        
    }
    
    function update_production_plan_monthly($data, $plan_id = ''){
        $needed_array = array('product_id', 'plan_month', 'tool', 'model_suffix', 'lot_size');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($plan_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('production_plans_monthly', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $plan_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('production_plans_monthly', $data)) ? $plan_id : False);
        }
        
    }
    
    function update_sampling_plan($no_of_samples, $plan_id = ''){
        $this->db->set('no_of_samples', $no_of_samples);
        $this->db->set('skipped', 0);
        $this->db->where('id', $plan_id);
        $data['modified'] = date("Y-m-d H:i:s");
            
        return (($this->db->update('sampling_plans', $data)) ? $plan_id : False);
    }
    
    function delete_sampling_plan($plan_id) {
        $this->db->where('id', $plan_id);
            
        return (($this->db->delete('sampling_plans')) ? $plan_id : False);
    }
    
    function skip_sampling_plan($plan_id) {
        $data = array();
        $this->db->set('no_of_samples', null);
        $this->db->set('skipped', 1);
        
        $this->db->where('id', $plan_id);
        $data['modified'] = date("Y-m-d H:i:s");
            
        return (($this->db->update('sampling_plans', $data)) ? $plan_id : False);
    }
    
    function insert_production_plan($production_plans, $production_plan_date) {
        $this->db->where('plan_date', $production_plan_date);
        $this->db->where('product_id', $this->product_id);
        $this->db->delete('production_plans');

        $this->db->insert_batch('production_plans', $production_plans);
        
        $this->update_production_tool($production_plan_date);
    }
    
    function insert_production_plan_monthly($production_plans) {
        $this->db->insert_batch('production_plans_monthly', $production_plans);
        
        $this->remove_dups_production_plan_monthly($this->product_id);
        $this->update_production_tool_monthly();
    }
    
    function remove_dups_production_plan_monthly($product_id) {
        $sql = "DELETE FROM production_plans_monthly WHERE id NOT IN (
            SELECT * FROM ( 
                SELECT MAX(id) 
                FROM production_plans_monthly 
                WHERE product_id = ? 
                GROUP BY product_id, plan_month, model_suffix
            ) as d
        ) AND product_id = ?";
        
        return $this->db->query($sql, array($product_id, $product_id));
    }
    
    function insert_production_plan_automatic($production_plans) {
        $this->db->empty_table('production_plan_automatic'); 

        $this->db->insert_batch('production_plan_automatic', $production_plans);
    }
    
    function create_automatic_production_plan($plan_date, $product_date_formatted) {
        $this->db->where('plan_date', $plan_date);
        $this->db->where('product_id', $this->product_id);
        $this->db->delete('production_plans');
        
        $sql = "INSERT INTO production_plans(`product_id`, `plan_date`, `line`, `tool`, `model_suffix`, `lot_size`, `original_lot_size`,            
            `is_user_defined`)
            SELECT pl.product_id, STR_TO_DATE(pa.`production_date`, '%Y%m%d') as plan_date, 
            pa.line, NULL as tool, CONCAT(pa.`model_name`, '.', pa.`suffix_name`) as model_suffix, SUM(pa.lot_qty), SUM(pa.lot_qty), 0 as is_user_defined
            FROM `production_plan_automatic` pa 
            INNER JOIN product_lines pl 
            ON pa.line = pl.name
            WHERE product_id = ?
            AND production_date = ?
            AND pa.`suffix_name` != ''
            AND pa.lot_qty > 0
            AND pl.is_deleted = 0
            GROUP BY CONCAT(pa.`model_name`, '.', pa.`suffix_name`), pa.line";
            
        $this->db->query($sql, array($this->product_id, $product_date_formatted));
        //echo $this->db->last_query();exit;
        $this->update_production_tool($product_date_formatted);
    }
    
    function update_production_tool($plan_date = '') {
        $sql = "UPDATE `production_plans` p 
        INNER JOIN model_suffixs m 
        ON p.model_suffix = m.model_suffix 
        SET p.tool = m.tool
        WHERE p.product_id = ?";
        
        $pass_array = array($this->product_id);
        if(!empty($plan_date)) {
            $sql .= " AND p.plan_date = ?";
            $pass_array[] = $plan_date;
        }
        
        $this->db->query($sql, $pass_array);
        
        if(!empty($plan_date)) {
            $this->add_missing_models_from_production_plan($plan_date);
        }
        
        return TRUE;
    }
    
    function update_production_tool_monthly($plan_month) {
        $sql = "UPDATE `production_plans_monthly` p 
        INNER JOIN model_suffixs m 
        ON p.model_suffix = m.model_suffix 
        SET p.tool = m.tool
        WHERE p.product_id = ?";
        
        $pass_array = array($this->product_id);
        if(!empty($plan_month)) {
            $sql .= " AND p.plan_month = ?";
            $pass_array[] = $plan_month;
        }
        
        $this->db->query($sql, $pass_array);
        
        return TRUE;
    }
    
    function add_missing_models_from_production_plan($plan_date) {
        $sql = "INSERT INTO `model_suffixs`(`product_id`, `model_suffix`, `created`)
        SELECT p.product_id, p.model_suffix, p.created FROM production_plans p
        LEFT JOIN model_suffixs m 
        ON ( 
            p.model_suffix = m.model_suffix 
            AND p.product_id = m.product_id 
        )
        WHERE m.id IS NULL
        AND p.plan_date = ?
        AND p.product_id = ?";
        
        return $this->db->query($sql, array($this->product_id, $plan_date));
    }
    
    function get_automate_sampling_plan($date) {
        $sql = "";
    }
    
    function get_lot_id($product_id, $plan_date, $line, $model_suffix) {
        $this->db->where('product_id', $product_id);
        $this->db->where('plan_date', $plan_date);
        $this->db->where('line', $line);
        $this->db->where('model_suffix', $model_suffix);
        
        return $this->db->get('production_plans')->row_array();
    }
    
    public function get_dashboard_status($dashboard_date) {
        $this->db->where('dashboard_date', $dashboard_date);
        $this->db->where('product_id', $this->product_id);
        
        return $this->db->get('dashboard_status')->row_array();
    }
    
    function update_dashboard_status($data, $dashboard_date){
        $needed_array = array('user_id', 'product_id', 'status', 'dashboard_date');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($dashboard_date)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('dashboard_status', $data)) ? $dashboard_date : False);
        } else {
            $this->db->where('dashboard_date', $dashboard_date);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('dashboard_status', $data)) ? $dashboard_date : False);
        }
    }

    function get_lot_produced_in_range($start_date, $end_date, $product_id, $line = '', $model_suffix = '', $tool = '') {
        $sql = "SELECT SUM(lot_size) as lot
        FROM production_plans
        WHERE product_id = ?
        AND plan_date BETWEEN ? AND ?";
        
        $pass_array = array($product_id, $start_date, $end_date);
        if(!empty($line)) {
            $sql .= ' AND line = ?';
            $pass_array[] = $line;
        }
        if(!empty($model_suffix)) {
            $sql .= ' AND model_suffix = ?';
            $pass_array[] = $model_suffix;
        }
        
        if(!empty($tool)) {
            $sql .= ' AND tool = ?';
            $pass_array[] = $tool;
        }
        
        $result = $this->db->query($sql, $pass_array)->row_array();
        
        if(!empty($result)) {
            return $result['lot'];
        }
        
        return FALSE;
    }
    
    function get_lot_planned_in_range($start_date, $end_date, $product_id, $model_suffix = '', $tool = '') {
        $sql = "SELECT SUM(lot_size) as lot
        FROM production_plans_monthly
        WHERE product_id = ?
        AND plan_month BETWEEN ? AND ?";
        
        $pass_array = array($product_id, $start_date, $end_date);
        if(!empty($model_suffix)) {
            $sql .= ' AND model_suffix = ?';
            $pass_array[] = $model_suffix;
        }
        
        if(!empty($tool)) {
            $sql .= ' AND tool = ?';
            $pass_array[] = $tool;
        }
        
        $result = $this->db->query($sql, $pass_array)->row_array();
        
        if(!empty($result)) {
            return $result['lot'];
        }
        
        return FALSE;
    }
}