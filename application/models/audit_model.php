<?php
class Audit_model extends CI_Model {

    function get_audit_judgement($audit_id) {
        $sql = "SELECT COUNT(ac.id) as checkpoint_count, 
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count, 
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, 
        SUM(IF(ac.result IS NULL, 1, 0)) as pending_count 
        FROM audit_checkpoints ac 
        WHERE ac.audit_id = ?";
        
        return $this->db->query($sql, array($audit_id))->row_array();
    }
    
    function get_completed_audits($filters, $count = false, $limit = '') {
        $pass_array = array();
        
        $sql = "SELECT a.id, a.audit_date, a.model_suffix, a.serial_no, a.workorder,
        a.inspection_id, a.state, l.name as line, a.tool, a.modified,
        i.name as inspection_name, i.gmes_insp_id, CONCAT(u.first_name, ' ', u.last_name) as auditer
        FROM audits a
        INNER JOIN users u
        ON a.auditer_id = u.id
        INNER JOIN product_lines l
        ON a.line_id = l.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        WHERE state = 'completed'";
        
        if(!empty($filters['id'])) {
            $sql .= " AND a.id = ?";
            $pass_array[] = $filters['id'];
        }
        
        if(isset($filters['gmes_sent'])) {
            $sql .= " AND a.gmes_sent = ?";
            $pass_array[] = $filters['gmes_sent'];
        }

        if(!empty($filters['start_range']) && !empty($filters['end_range'])) {
            $sql .= " AND a.audit_date BETWEEN ? AND ?";
            $pass_array[] = $filters['start_range'];
            $pass_array[] = $filters['end_range'];
        }
        
        if(!empty($filters['tool'])) {
            $sql .= " AND a.tool = ?";
            $pass_array[] = $filters['tool'];
        }
        
        if(!empty($filters['line_id'])) {
            $sql .= " AND a.line_id = ?";
            $pass_array[] = $filters['line_id'];
        }
        
       /*  if(!empty($filters['model_suffix'])) {
            $sql .= " AND a.model_suffix = ?";
            $pass_array[] = $filters['model_suffix'];
        } */
        if(!empty($filters['model_suffix'])){
		// echo "123";exit;
			$model_suffix = implode('", "', $filters['model_suffix']);
			if(!empty($model_suffix) && $model_suffix != 'All') {
				$sql .= ' AND  (a.model_suffix IN ( "'.$model_suffix.'" ) OR a.model_suffix IS NULL)';
			   // $pass_array[] = $model_suffix;
			}
			else if($model_suffix == 'All') {
				$sql .= ' AND (a.model_suffix != (?) OR a.model_suffix IS NULL)';
				$pass_array[] = $model_suffix;
			} 
		}
        if(!empty($filters['workorder'])) {
            $sql .= " AND a.workorder = ?";
            $pass_array[] = $filters['workorder'];
        }
        
        if(!empty($filters['serial_no'])) {
            $sql .= " AND a.serial_no = ?";
            $pass_array[] = $filters['serial_no'];
        }
        
        if(!empty($filters['inspection_id'])) {
            $sql .= " AND a.inspection_id = ?";
            $pass_array[] = $filters['inspection_id'];
        }
        
        if($this->product_id) {
            $sql .= ' AND a.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        $sql .= " GROUP BY a.id
        ORDER BY a.audit_date DESC, a.id DESC";
        
        if($count) {
            $sql = "SELECT count(id) as c FROM (".$sql.") as sub";
        } else {
            $sql .= " ".$limit;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    function get_completed_audits_new($filters, $count = false) {
		// print_r($filters);exit;
        $pass_array = array();
        
        $sql = "SELECT a.id, a.audit_date, a.model_suffix, a.serial_no, a.workorder,
        a.inspection_id, a.state, l.name as line, a.tool, a.modified,
        i.name as inspection_name, i.gmes_insp_id, CONCAT(u.first_name, ' ', u.last_name) as auditer
        FROM audits a
        INNER JOIN users u
        ON a.auditer_id = u.id
        INNER JOIN product_lines l
        ON a.line_id = l.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        WHERE state = 'completed'";
        
        if(!empty($filters['id'])) {
            $sql .= " AND a.id = ?";
            $pass_array[] = $filters['id'];
        }
        
        if(isset($filters['gmes_sent'])) {
            $sql .= " AND a.gmes_sent = ?";
            $pass_array[] = $filters['gmes_sent'];
        }

        if(!empty($filters['start_range']) && !empty($filters['end_range'])) {
            $sql .= " AND a.audit_date BETWEEN ? AND ?";
            $pass_array[] = $filters['start_range'];
            $pass_array[] = $filters['end_range'];
        }
        
        if(!empty($filters['tool'])) {
            $sql .= " AND a.tool = ?";
            $pass_array[] = $filters['tool'];
        }
        
        if(!empty($filters['line_id'])) {
            $sql .= " AND a.line_id = ?";
            $pass_array[] = $filters['line_id'];
        }
        
        /* if(!empty($filters['model_suffix'])) {
            $sql .= " AND a.model_suffix = ?";
            $pass_array[] = $filters['model_suffix'];
        }
         */
		//print_r($filters);exit;
		if(!empty($filters['model_suffix'])){
		// echo "123";exit;
			$model_suffix = implode('", "', $filters['model_suffix']);
			if(!empty($model_suffix) && $model_suffix != 'All') {
				$sql .= ' AND  (a.model_suffix IN ( "'.$model_suffix.'" ) OR a.model_suffix IS NULL)';
			   // $pass_array[] = $model_suffix;
			}
			else if($model_suffix == 'All') {
				$sql .= ' AND (a.model_suffix != (?) OR a.model_suffix IS NULL)';
				$pass_array[] = $model_suffix;
			} 
		}
		 if(!empty($filters['insp-type'])) {
			$insp_type = strtolower($filters['insp-type']);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }
		if(!empty($inspection_id)) {
            $sql .= " AND s.inspection_id = ?";
            $pass_array[] = $inspection_id;
        }
        if(!empty($filters['workorder'])) {
            $sql .= " AND a.workorder = ?";
            $pass_array[] = $filters['workorder'];
        }
        
        if(!empty($filters['serial_no'])) {
            $sql .= " AND a.serial_no = ?";
            $pass_array[] = $filters['serial_no'];
        }
        
        if(!empty($filters['inspection_id'])) {
            $sql .= " AND a.inspection_id = ?";
            $pass_array[] = $filters['inspection_id'];
        }
        
        if($this->product_id) {
            $sql .= ' AND a.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        $sql .= " GROUP BY a.id
        ORDER BY a.audit_date DESC, a.id DESC";
        
        if($count) {
            $sql = "SELECT count(id) as c FROM (".$sql.") as sub";
        } 
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    //not used
    function get_all_audits_by_filter($filters, $limit = '') {
        $pass_array = array();
        $sql = "SELECT a.id, a.audit_date, a.auditer_id, a.product_id, a.model_suffix, a.serial_no, a.workorder,
        a.inspection_id, a.state, a.register_datetime, l.name as line, a.tool,
        p.name as product_name, i.name as inspection_name, CONCAT(u.first_name, ' ', u.last_name) as auditer,
        COUNT(ac.id) as checkpoint_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count,
        SUM(IF(ac.result IS NULL, 1, 0)) as pending_count
        FROM audits a
        INNER JOIN users u
        ON a.auditer_id = u.id
        INNER JOIN products p
        ON a.product_id = p.id
        INNER JOIN product_lines l
        ON (a.product_id = l.product_id AND a.line_id = l.id)
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        LEFT JOIN audit_checkpoints ac
        ON a.id = ac.audit_id
        WHERE state = 'completed'";
        
        if(!empty($filters['audit_date'])) {
            $sql .= " AND a.audit_date = ?";
            $pass_array[] = $filters['audit_date'];
        }
        
        if(!empty($filters['auditer_id'])) {
            $sql .= " AND a.auditer_id = ?";
            $pass_array[] = $filters['auditer_id'];
        }
        
        if(!empty($filters['model_suffix'])) {
            $sql .= " AND a.model_suffix = ?";
            $pass_array[] = $filters['model_suffix'];
        }
        
        if(!empty($filters['inspection_id'])) {
            $sql .= " AND a.inspection_id = ?";
            $pass_array[] = $filters['inspection_id'];
        }
        
        if($this->product_id) {
            $sql .= ' AND a.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        $sql .= " GROUP BY a.id
        ORDER BY a.audit_date DESC, a.id DESC";
        
        if(!empty($limit)) {
            $sql .= ' LIMIT '.$limit;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
        
    }
    
    function add_to_completed_audits($audit_id) {
        $sql = "INSERT INTO `audits_completed`(`audit_id`, `audit_date`, `inspection_id`, `line`, `model_suffix`, `workorder`, `serial_no`, `checkpoint_count`, `ok_count`, `ng_count`, `created`)
        SELECT a.id, a.audit_date, a.inspection_id, l.name as line, a.model_suffix, a.workorder, a.serial_no, COUNT(ac.id) as checkpoint_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, NOW()
        FROM audits a
        INNER JOIN product_lines l
        ON a.line_id = l.id
        LEFT JOIN audit_checkpoints ac
        ON a.id = ac.audit_id
        WHERE a.id = ?";
        
        $pass_array = array($audit_id);
        return $this->db->query($sql, $pass_array);
    }
    
    function get_on_hold_audits($auditer_id, $insp_type = '') {
        $sql = "SELECT a.id, a.audit_date, a.auditer_id, a.product_id, a.model_suffix, a.tool, a.serial_no,
        a.current_iteration, a.iteration_datetime, a.inspection_id, a.state, a.register_datetime, a.checkpoint_format,
        a.automate_file, a.automate_result, a.paired, a.paired_with, a.abort_requested,
        i.name as inspection_name, i.full_auto, i.automate_case, i.automate_settings, pl.name as line_name, i.insp_type
        FROM audits a
        INNER JOIN product_lines pl
        ON a.line_id = pl.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        WHERE a.auditer_id = ?
        AND on_hold = 1
        AND a.state NOT IN ('aborted', 'completed')";
        
        $pass_array = array($auditer_id);
        if(!empty($insp_type)) {
            $sql .= " AND i.insp_type = ?";
            $pass_array[] = $insp_type;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_all_running_audits($filter) {
        $sql = "SELECT a.id, a.audit_date, a.auditer_id, a.product_id, a.model_suffix, a.tool, a.serial_no, a.current_iteration, a.iteration_datetime,
        a.inspection_id, a.state, a.register_datetime, a.checkpoint_format, a.automate_file, a.automate_result, a.on_hold, a.workorder,
        i.name as inspection_name, i.full_auto, i.automate_case, i.automate_settings, pl.name as line_name, i.insp_type,
        CONCAT(u.first_name, ' ', u.last_name) as auditer
        FROM audits a
        INNER JOIN product_lines pl
        ON a.line_id = pl.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        INNER JOIN users u
        ON u.id = a.auditer_id
        WHERE a.product_id = ?
        AND a.state != 'aborted'
        AND a.state != 'completed'";
        
        $pass_array = array($this->product_id);
		if($this->product_id) {
            $sql .= ' AND a.product_id = ? ';
            $pass_array[] = $this->product_id;
        }
		if(!empty($filter['insp-type'])) {
			$insp_type = strtolower($filter['insp-type']);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }
		if(!empty($filter['start_range']) && !empty($filter['end_range'])) {
			$sql .= ' AND (a.audit_date BETWEEN ? AND ? ) ';
            $pass_array[] = $filter['start_range'];
			
			/* $day = date("l",strtotime($filter['end_range']));
			if($day == 'Saturday')
			{
				$date_new = date('Y-m-d',strtotime($filter['end_range'] .' +2 day'));
				$pass_array[] = $date_new;
			}
			if($day == 'Sunday')
			{
				$date_new = date('Y-m-d',strtotime($filter['end_range'] .' +1 day'));
				$pass_array[] = $date_new;
			}
            else */
				$pass_array[] = $filter['end_range'];
        }
        /* if(!empty($filter['model_suffix'])) {
			 $sql .= " AND a.model_suffix = ? ";
            $pass_array[] = $filter['model_suffix'];
        } */
		if(!empty($filter['model_suffix'])){
			// echo "123";
			$model_suffix = implode('", "', $filter['model_suffix']);
			if(!empty($model_suffix) && $model_suffix != 'All') {
				$sql .= ' AND  (a.model_suffix IN ( "'.$model_suffix.'" ) OR a.model_suffix IS NULL)';
			   // $pass_array[] = $model_suffix;
			}
			else if($model_suffix == 'All') {
				$sql .= ' AND (a.model_suffix != (?) OR a.model_suffix IS NULL)';
				$pass_array[] = $model_suffix;
			} 
		}
		if(!empty($filter['inspection_id'])) {
			 $sql .= " AND a.inspection_id = ? ";
            $pass_array[] = $filter['inspection_id'];
        }
		if(!empty($filter['tool'])) {
			$sql .= " AND a.tool = ? ";
            $pass_array[] = $filter['tool'];
        }
        if(!empty($filter['line_id'])) {
			$sql .= " AND a.line_id = ? ";
            $pass_array[] = $filter['line_id'];
        }
        
		
        return $this->db->query($sql, $pass_array)->result_array();
    }
	function get_consolidated_report($filter) {
       $pass_array = array();
        
        $sql = "SELECT a.id, a.audit_date, a.model_suffix, a.serial_no, a.workorder,
        a.inspection_id, a.state, l.name as line, a.tool, a.modified,i.insp_type as insp_type,
        i.name as inspection_name, i.gmes_insp_id, CONCAT(u.first_name, ' ', u.last_name) as auditer
        FROM audits a
        INNER JOIN users u
        ON a.auditer_id = u.id
        INNER JOIN product_lines l
        ON a.line_id = l.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        WHERE state = 'completed'";
        
        if($this->product_id) {
            $sql .= ' AND a.product_id = ? ';
            $pass_array[] = $this->product_id;
        }
		if(!empty($filter['insp-type'])) {
			$insp_type = strtolower($filter['insp-type']);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }
		if(!empty($filter['start_range']) && !empty($filter['end_range'])) {
			$sql .= ' AND (a.audit_date BETWEEN ? AND ? ) ';
            $pass_array[] = $filter['start_range'];
            $pass_array[] = $filter['end_range'];
        }
        /* if(!empty($filter['model_suffix'])) {
			 $sql .= " AND a.model_suffix = ? ";
            $pass_array[] = $filter['model_suffix'];
        } */
		if(!empty($filter['model_suffix'])){
			$model_suffix = implode('", "', $filter['model_suffix']);
			if(!empty($model_suffix) && $model_suffix != 'All') {
				$sql .= ' AND  (a.model_suffix IN ( "'.$model_suffix.'" ) OR a.model_suffix IS NULL)';
			   // $pass_array[] = $model_suffix;
			}
			else if($model_suffix == 'All') {
				$sql .= ' AND (a.model_suffix != (?) OR a.model_suffix IS NULL)';
				$pass_array[] = $model_suffix;
			} 
		}
		
		if(!empty($filter['inspection_id'])) {
			 $sql .= " AND a.inspection_id = ? ";
            $pass_array[] = $filter['inspection_id'];
        }
		if(!empty($filter['tool'])) {
			$sql .= " AND a.tool = ? ";
            $pass_array[] = $filter['tool'];
        }
        if(!empty($filter['line_id'])) {
			$sql .= " AND a.line_id = ? ";
            $pass_array[] = $filter['line_id'];
        }
        
		$sql .= " GROUP BY a.id 
        ORDER BY a.audit_date DESC, a.id DESC";//l.name, a.model_suffix,i.name 
        
        /* if($count) {
            $sql = "SELECT count(id) as c FROM (".$sql.") as sub";
        } else {
            $sql .= " ".$limit;
        } */
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_grouped_audit_with_plan($filters, $limit = '', $only_completed = FALSE, $type = 'model_suffix') {
		//print_r($filters);
		//exit;
        $sql = "SELECT a.id, a.audit_date, a.product_id, a.inspection_id, a.".$type." as model_suffix, a.automate_file,
        p.name as product_name, i.name as inspection_name, 
        GROUP_CONCAT(DISTINCT a.id ORDER BY a.id) as audit_ids,  
        GROUP_CONCAT(DISTINCT a.serial_no ORDER BY a.id) as serial_nos,
        GROUP_CONCAT(DISTINCT CONCAT(if(a.workorder IS NULL, '', a.workorder), '|', a.serial_no) ORDER BY a.id) as workorders,
        GROUP_CONCAT(DISTINCT CONCAT(a.model_suffix, '\n', a.serial_no) ORDER BY a.id) as model_suffixs,
        COUNT(DISTINCT a.id) as total_audits, 
        COUNT(ac.id) as checkpoint_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count,
        SUM(IF(ac.result = 'NA', 1, 0)) as na_count,
        SUM(IF(ac.result != 'NG', 1, 0)) as correct_count,
        SUM(IF(ac.result IS NULL, 1, 0)) as pending_count,
        s.no_of_samples
        FROM audits a
        INNER JOIN products p
        ON a.product_id = p.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        LEFT JOIN (
            SELECT inspection_id, inspection_type FROM inspection_config GROUP BY inspection_id
        ) as c
        ON a.inspection_id = c.inspection_id
        LEFT JOIN audit_checkpoints ac
        ON a.id = ac.audit_id
        LEFT JOIN `sampling_plans` s
        ON (
            a.audit_date        = s.`sampling_date`    
            AND a.inspection_id = s.inspection_id  
            AND a.".$type."  = s.".$type." 
            AND a.product_id    = s.product_id     
        )
        WHERE state = 'completed'";
        if(!empty($filters['insp-type'])) {
			$insp_type = strtolower($filters['insp-type']);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }
        if($type == 'tool') {
            $sql .= " AND c.inspection_type = 'Tool'";
        } else {
            $sql .= " AND (c.inspection_type = 'Model.Suffix' OR c.inspection_type IS NULL)";
        }
        
        if(!empty($filters['audit_date'])) {
            $sql .= " AND a.audit_date = ?";
            $pass_array[] = $filters['audit_date'];
        }
        
        if(!empty($filters['start_range']) && !empty($filters['end_range'])) {
            $sql .= " AND a.audit_date BETWEEN ? AND ?";
            $pass_array[] = $filters['start_range'];
            $pass_array[] = $filters['end_range'];
        }
        
        if(!empty($filters['tool'])) {
            $sql .= " AND a.tool = ?";
            $pass_array[] = $filters['tool'];
        }
        
        /* if(!empty($filters['model_suffix'])) {
            // $sql .= " AND a.model_suffix = ?";
            // $pass_array[] = $filters['model_suffix']; 
			$sql .= ' AND  (a.model_suffix IN ( "'.$model_suffix.'" ) OR a.model_suffix IS NULL)';
           
        } */
		if(!empty($filters['model_suffix'])){
			$model_suffix = implode('", "', $filters['model_suffix']);
			if(!empty($model_suffix) && $model_suffix != 'All') {
				$sql .= ' AND  (a.model_suffix IN ( "'.$model_suffix.'" ) OR a.model_suffix IS NULL)';
			   // $pass_array[] = $model_suffix;
			}
			else if($model_suffix == 'All') {
				$sql .= ' AND (a.model_suffix != (?) OR a.model_suffix IS NULL)';
				$pass_array[] = $model_suffix;
			} 
		}
        
        if(!empty($filters['inspection_id'])) {
            $sql .= " AND a.inspection_id = ?";
            $pass_array[] = $filters['inspection_id'];
        }
        
        if(!empty($filters['product_id'])) {
            $sql .= " AND a.product_id = ?";
            $pass_array[] = $filters['product_id'];
        }
        
        $sql .= " GROUP BY a.audit_date, a.product_id, a.inspection_id, a.".$type;
        
        if($only_completed) {
            $sql .= ' HAVING s.no_of_samples <= COUNT(DISTINCT a.id)';
        }
        
        $sql .= " ORDER BY a.audit_date DESC";
        
        if(!empty($limit)) {
            $sql .= ' LIMIT '.$limit;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_audit($auditer_id, $state = '', $date = '', $id = '', $on_hold = 0) {
        
        $sql = "SELECT a.id, a.audit_date, a.auditer_id, a.product_id, a.model_suffix, a.tool, a.serial_no, a.inspection_id, a.state, a.register_datetime, a.checkpoint_format, a.automate_file, a.automate_result, a.current_iteration, a.iteration_datetime, a.paired_with, a.paired,
        p.name as product_name, p.checklist_active, i.name as inspection_name, i.full_auto, i.automate_case, i.automate_settings, i.insp_type,
        COUNT(ac.id) as checkpoint_count, pl.name as line_name, p.dir_path, i.gmes_insp_id, i.attach_report
        FROM audits a
        INNER JOIN products p
        ON a.product_id = p.id
        INNER JOIN product_lines pl
        ON a.line_id = pl.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        LEFT JOIN audit_checkpoints ac
        ON a.id = ac.audit_id";
        
        $wheres = array();
        $pass_array = array();
        
        if(!empty($auditer_id)) {
            $wheres[] = 'a.auditer_id = ?';
            $pass_array[] = $auditer_id;
        }
        
        if($on_hold !== null) {
            $wheres[] = "on_hold = ?";
            $pass_array[] = $on_hold;
        }
        
        if(!empty($state)) {
            if(!is_array($state)) {
                $wheres[] = "a.state = ?";
                $pass_array[] = $state;
            } else {
                $wheres[] = "a.state IN (". implode(',', array_fill(0, count($state), '?')).")";
                $pass_array = array_merge($pass_array, $state);
            }
        }
        if(!empty($date)) {
            $wheres[] = "a.audit_date = ?";
            $pass_array[] = $date;
        }
        if(!empty($id)) {
            $wheres[] = "a.id = ?";
            $pass_array[] = $id;
        }
        
        if(!empty($wheres)) {
            $sql .= " WHERE ".implode(' AND ', $wheres);
        }
        
        $sql .= " GROUP BY a.id ORDER BY a.audit_date";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_only_audit_details($id) {
        $this->db->where('id', $id);
        
        if($this->product_id) {
            $this->db->where('product_id', $this->product_id);
        }
        
        return $this->db->get('audits')->row_array();
    }
    
    function get_audit_for_download($id) {
        $pass_array = array($id);
        
        $sql = "SELECT a.id, a.audit_date, i.name as inspection_name, p.name as product_name,
        a.model_suffix, a.serial_no,
        COUNT(ac.id) as checkpoint_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count
        FROM audits a
        INNER JOIN products p
        ON a.product_id = p.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        LEFT JOIN audit_checkpoints ac
        ON a.id = ac.audit_id
        WHERE state = 'completed'
        AND a.id = ?";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function check_already_inspected($data) {
        $this->db->where('audit_date'   , $data['audit_date']);
        $this->db->where('product_id'   , $this->product_id);
        $this->db->where('model_suffix' , $data['model_suffix']);
        $this->db->where('inspection_id', $data['inspection_id']);
        $this->db->where('serial_no'    , $data['serial_no']);
        $this->db->where('state !='     , 'aborted');
        
        return $this->db->count_all_results('audits');
    }
    
    function update_audit($data, $audit_id) {
        $needed_array = array('audit_date', 'auditer_id', 'product_id', 'line_id', 'tool', 'model_suffix', 'workorder',
        'serial_no', 'inspection_id', 'checkpoint_format', 'state', 'register_datetime', 'automate_file', 'automate_result', 'gmes_sent', 
        'current_iteration', 'iteration_datetime', 'paired_with', 'paired', 'on_hold', 'abort_requested', 'abort_request_datetime');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($audit_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('audits', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $audit_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('audits', $data)) ? $audit_id : False);
        }
        
    }
    
    function update_automate_result($data, $id = '') {
        $needed_array = array('audit_id', 'automate_data');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('audit_automate_data', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('audit_automate_data', $data)) ? $id : False);
        }
    }
    
    function get_audit_automate_result($audit_id) {
        $this->db->where('audit_id', $audit_id);
        
        return $this->db->get('audit_automate_data')->row_array();
    }
    
    function hold_resume_audit($audit_id, $on_hold = 1) {
        $this->db->where('id', $audit_id);
        $this->db->set('on_hold', $on_hold);
        $this->db->set('modified', date("Y-m-d H:i:s"));
        
        return $this->db->update('audits');
    }
    
    function change_state($audit_id, $auditer_id, $state) {
        $allowed_state = array('registered','aborted','started','finished','completed');
        if(!in_array($state, $allowed_state))
            return false;
        
        $this->db->where('id', $audit_id);
        $this->db->where('auditer_id', $auditer_id);
        $this->db->set('state', $state);
        $this->db->set('modified', date("Y-m-d H:i:s"));
        
        $response = $this->db->update('audits');
        return $response;
    }
    
    function create_audit_checkpoints($inspection_id, $model_suffix, $audit_id, $exclude = array(), $iteration_set = '', $iteration_no = '') {
        $this->db->where('audit_id', $audit_id);
        if(!empty($iteration_set)) {
            $this->db->where('iteration_no', $iteration_no);
        }
        $this->db->delete('audit_checkpoints');
        
        $pass_array = array($model_suffix, $inspection_id);
        
        $sql = "INSERT INTO audit_checkpoints(`org_checkpoint_id`, `audit_id`, `gmes_code`, `checkpoint_no_real`, `checkpoint_no`, `iteration_no`, `insp_item`, `insp_item2`, `insp_item3`, `insp_item4`, `spec`, `lsl`, `usl`, `tgt`, `unit`, `guideline_image`, `automate_result_row`, `automate_result_col`,`is_na`, `result`, `created`)
        SELECT c.id, ".$audit_id." as audit_id, c.gmes_code, c.checkpoint_no, c.checkpoint_no, ".(($iteration_no) ? $iteration_no : 'null' )." as iteration_no, c.insp_item, c.insp_item2, c.insp_item3, c.insp_item4, c.spec,
        if(cs.lsl IS NULL, c.lsl, cs.lsl) as lsl,
        if(cs.usl IS NULL, c.usl, cs.usl) as usl,
        if(cs.tgt IS NULL, c.tgt, cs.tgt) as tgt,
        if(cs.unit IS NULL, c.unit, cs.unit) as unit,
        c.guideline_image,  `automate_result_row`, `automate_result_col`,";

        if(!empty($exclude)) {
            $sql .= "IF(c.checkpoint_no IN (". implode(',', array_fill(0, count($exclude), '?'))."), 1, 0) as is_na, ";
            $sql .= "IF(c.checkpoint_no IN (". implode(',', array_fill(0, count($exclude), '?'))."), 'NA', null) as result, ";
            $pass_array = array_merge($exclude, $exclude, $pass_array);
        } else {
            $sql .= "0, null, ";
        }
        
        $sql .= "'".date("Y-m-d H:i:s")."' as created
        FROM checkpoints c
        LEFT JOIN checkpoint_specs cs
        ON (c.id = cs.checkpoint_id AND cs.model_suffix = ? AND c.has_multiple_specs = 1)
        WHERE c.inspection_id = ?
        AND c.is_deleted = 0";
        
        if(!empty($iteration_set)) {
            $sql .= " AND FIND_IN_SET(c.id, ?)";
            $pass_array[] = $iteration_set;
        }
        
        $sql .= " ORDER BY c.checkpoint_no";
        
        $this->db->query($sql, $pass_array);
        
        return TRUE;
    }

    function get_required_checkpoint_nos($audit_id, $iteration = null) {
        $sql = "SELECT GROUP_CONCAT(`checkpoint_no` ORDER BY id) as nos,
        MAX(IF (pointer = 1, checkpoint_no, null)) as last
        FROM `audit_checkpoints` 
        WHERE audit_id = ? 
        AND is_na = 0";
        
        $pass_array = array($audit_id);
        if(!empty($iteration)) {
            $sql .= " AND iteration_no = ?";
            $pass_array[] = $iteration;
        }
        
        $sql .= " GROUP BY audit_id";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_all_audit_checkpoints($audit_id, $is_na = 0, $only_result = false) {
        if($only_result) {
            $this->db->select('remark, audit_value, result, result_datetime');
        }
        $this->db->where('audit_id', $audit_id);
        
        if(!empty($is_na)) {
            $this->db->where('is_na', $is_na);
        }
        
        $this->db->order_by('iteration_no, checkpoint_no_real');
        
        return $this->db->get('audit_checkpoints')->result_array();
    }
    
    function get_checkpoint_for_download($audit_id) {
        $sql = "SELECT `checkpoint_no_real` as checkpoint_no, `insp_item`, `insp_item2`, `insp_item3`, `insp_item4`, `spec`, 
        lsl, usl, tgt, unit,
        `remark`, `audit_value`, `result`
        FROM audit_checkpoints
        WHERE audit_id = ?
        ORDER BY checkpoint_no_real";
        
        return $this->db->query($sql, array($audit_id))->result_array();
    }
    
    function get_checkpoint($audit_id, $checkpoint_no, $iteration_no = '') {
        $this->db->where('audit_id', $audit_id);
        $this->db->where('checkpoint_no', $checkpoint_no);
        
        if(!empty($iteration_no)) {
            $this->db->where('iteration_no', $iteration_no);
        }
        
        return $this->db->get('audit_checkpoints')->row_array();
    }
    
    function get_count_checkpoint_by_result($audit_id, $result) {
        $this->db->where('audit_id', $audit_id);
        if($result) {
            $this->db->where('result', $result);
        } else {
            $this->db->where('result IS NULL');
        }
        return $this->db->count_all_results('audit_checkpoints');
    }
    
    function record_checkpoint_result($data, $checkpoint_id, $audit_id) {
        $needed_array = array('remark', 'audit_value', 'result', 'defect_image');
        $data = array_intersect_key($data, array_flip($needed_array));
        
        $this->db->where('id', $checkpoint_id);
        $this->db->where('audit_id', $audit_id);
        $data['result_datetime'] = date("Y-m-d H:i:s");
        $data['pointer'] = 1;
        $data['modified'] = date("Y-m-d H:i:s");
        
        return (($this->db->update('audit_checkpoints', $data)) ? $audit_id : False);
    }
    
    function change_na_checkpoint_status($na_approved, $checkpoint_id, $audit_id) {
        $this->db->where('id', $checkpoint_id);
        $data['na_approved'] = $na_approved;
        $data['modified'] = date("Y-m-d H:i:s");
        
        return (($this->db->update('audit_checkpoints', $data)) ? $audit_id : False);
    }

    function add_notification($data, $noti_id = '') {
        $needed_array = array('noti_type', 'product_id', 'audit_checkpoint_id', 'action_by', 'action_datetime');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($noti_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('notifications', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $noti_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('notifications', $data)) ? $noti_id : False);
        }
    }
    
    function check_not_approved_na($audit_id) {        
        $this->db->where('audit_id', $audit_id);
        $this->db->where('result', 'NA');
        $this->db->where('is_na', 0);
        $this->db->where('na_approved !=', 1);
        
        return $this->db->count_all_results('audit_checkpoints');
    }
    
    function check_slippage($audit_id) {        
        $this->db->where('audit_id', $audit_id);
        $this->db->where('result IS NULL');
        
        return $this->db->count_all_results('audit_checkpoints');
    }
    
    function check_notifications($product_id, $id = '') {
        $sql = "SELECT n.id, n.product_id, n.audit_checkpoint_id,
        a.audit_date, a.model_suffix, a.serial_no, a.checkpoint_format,
        ac.audit_id, ac.checkpoint_no, ac.insp_item, ac.insp_item2, ac.insp_item3, ac.insp_item4,
        ac.spec, i.name as inspection_name, a.inspection_id
        FROM notifications n
        INNER JOIN audit_checkpoints ac
        ON n.audit_checkpoint_id = ac.id
        INNER JOIN audits a
        ON ac.audit_id = a.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        WHERE n.product_id = ?
        AND action_by IS NULL";
        
        $pass_array = array($product_id);
        if(!empty($id)) {
            $sql .= " AND n.id = ?";
            $pass_array[] = $id;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function pending_checkpoints($product_id, $audit_checkpoint_id = '') {
        $sql = "SELECT ac.id, a.product_id, ac.id as audit_checkpoint_id, a.audit_date, 
        a.model_suffix, a.serial_no, a.checkpoint_format,
        ac.audit_id, ac.checkpoint_no, ac.insp_item, ac.insp_item2, ac.insp_item3, ac.insp_item4,
        ac.spec, i.name as inspection_name, a.inspection_id
        FROM audit_checkpoints ac
        INNER JOIN audits a
        ON ac.audit_id = a.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        WHERE ac.result = 'NA'
        AND ac.is_na = 0
        AND ac.na_approved = 0
        AND a.state != 'aborted'
        AND a.state != 'completed'
        AND a.product_id = ?";
        
        $pass_array = array($product_id);
        if(!empty($audit_checkpoint_id)) {
            $sql .= " AND ac.id = ?";
            $pass_array[] = $audit_checkpoint_id;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    function pending_checkpoints_new($filters, $product_id, $audit_checkpoint_id = '') {
		// print_r($filters);exit;
        $sql = "SELECT ac.id, a.product_id, ac.id as audit_checkpoint_id, a.audit_date, 
        a.model_suffix, a.serial_no, a.checkpoint_format,
        ac.audit_id, ac.checkpoint_no, ac.insp_item, ac.insp_item2, ac.insp_item3, ac.insp_item4,
        ac.spec, i.name as inspection_name, a.inspection_id
        FROM audit_checkpoints ac
        INNER JOIN audits a
        ON ac.audit_id = a.id
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        WHERE ac.result = 'NA'
        AND ac.is_na = 0
        AND ac.na_approved = 0
        AND a.state != 'aborted'
        AND a.state != 'completed'
        AND a.product_id = ?";
        
        $pass_array = array($product_id);
        if(!empty($audit_checkpoint_id)) {
            $sql .= " AND ac.id = ?";
            $pass_array[] = $audit_checkpoint_id;
        }
		if(!empty($filters['start_range']) && !empty($filters['end_range'])) {
            $sql .= " AND a.audit_date BETWEEN ? AND ?";
            $pass_array[] = $filters['start_range'];
            $pass_array[] = $filters['end_range'];
        }
		/* if(!empty($filters['model_suffix'])) {
            $sql .= " AND a.model_suffix = ?";
            $pass_array[] = $filters['model_suffix'];
        } */
		if(!empty($filters['model_suffix'])){
		// echo "123";exit;
			$model_suffix = implode('", "', $filters['model_suffix']);
			if(!empty($model_suffix) && $model_suffix != 'All') {
				$sql .= ' AND  (a.model_suffix IN ( "'.$model_suffix.'" ) OR a.model_suffix IS NULL)';
			   // $pass_array[] = $model_suffix;
			}
			else if($model_suffix == 'All') {
				$sql .= ' AND (a.model_suffix != (?) OR a.model_suffix IS NULL)';
				$pass_array[] = $model_suffix;
			} 
		}
		if(!empty($filters['inspection_id'])) {
            $sql .= " AND a.inspection_id = ?";
            $pass_array[] = $filters['inspection_id'];
        }
        if(!empty($filters['insp-type'])) {
			$insp_type = strtolower($filters['insp-type']);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }
        
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function pending_abort_requests($product_id, $audit_id = '') {
        $sql = "SELECT a.id, a.audit_date, a.auditer_id, a.product_id, a.model_suffix, a.tool, a.serial_no,
        a.current_iteration, a.iteration_datetime, a.inspection_id, a.state, a.register_datetime, a.checkpoint_format,
        a.automate_file, a.automate_result, a.paired, a.paired_with, a.abort_requested, a.abort_request_datetime,
        i.name as inspection_name, i.full_auto, i.automate_case, i.automate_settings, i.insp_type,
        CONCAT(u.first_name, ' ', u.last_name) as auditer
        FROM audits a
        INNER JOIN inspections i
        ON a.inspection_id = i.id
        INNER JOIN users u
        ON u.id = a.auditer_id
        WHERE abort_requested = 1
        AND i.insp_type = 'interval'
        AND a.state != 'aborted'
        AND a.product_id = ?";
        
        $pass_array = array($product_id);
        if(!empty($audit_id)) {
            $sql .= " AND a.id = ?";
            $pass_array[] = $audit_id;
        }
        
        if(!empty($audit_id)) {
            return $this->db->query($sql, $pass_array)->row_array();
        } else {
            return $this->db->query($sql, $pass_array)->result_array();
        }
    }

    function get_all_audit_models($auditer_id = '') {
        $sql = "SELECT DISTINCT model_suffix 
        FROM audits WHERE product_id = ?";
        
        $pass_array = array($this->product_id);
        
        if(!empty($auditer_id)) {
            $sql .= " AND auditer_id = ?";
            $pass_array[] = $auditer_id;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_all_audit_workorders($auditer_id = '') {
        $sql = "SELECT DISTINCT workorder 
        FROM audits WHERE product_id = ?";
        
        $pass_array = array($this->product_id);
        
        if(!empty($auditer_id)) {
            $sql .= " AND auditer_id = ?";
            $pass_array[] = $auditer_id;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_all_audit_serial_numbers($auditer_id = '') {
        $sql = "SELECT DISTINCT serial_no 
        FROM audits WHERE product_id = ?";
        
        $pass_array = array($this->product_id);
        
        if(!empty($auditer_id)) {
            $sql .= " AND auditer_id = ?";
            $pass_array[] = $auditer_id;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function user_wise_on_hold_count() {
        $sql = "SELECT a.auditer_id, u.first_name, u.last_name,
            SUM(IF(i.insp_type = 'regular', 1, 0)) as regular_count,
            SUM(IF(i.insp_type = 'interval', 1, 0)) as interval_count
            FROM audits a 
            INNER JOIN inspections i 
            ON a.inspection_id = i.id 
            INNER JOIN users u
            ON a.auditer_id = u.id
            WHERE a.product_id = ?
            AND a.state != 'aborted'
            AND a.state != 'completed'
            AND a.on_hold = 1
            GROUP BY a.auditer_id
            HAVING count(a.id) > 0";

        $pass_array = array($this->product_id);
        return $this->db->query($sql, $pass_array)->result_array();
    }
	
	function regular_pending_counts() {
        $sql = "SELECT a.auditer_id, u.first_name, u.last_name,
            SUM(IF(i.insp_type = 'regular', 1, 0)) as regular_pending_count,
            FROM audits a 
            INNER JOIN inspections i 
            ON a.inspection_id = i.id 
            INNER JOIN users u
            ON a.auditer_id = u.id
            WHERE a.product_id = ?
            AND a.state != 'aborted'
            AND a.state = 'started'
            AND a.on_hold != 1
            GROUP BY a.auditer_id
            HAVING count(a.id) > 0";

        $pass_array = array($this->product_id);
        return $this->db->query($sql, $pass_array)->result_array();
    }
	
	function user_wise_inprocess_insp_count() {
		$start_date = date('Y-m-01');
		//$end_date = date('Y-m-d');
        $sql = "SELECT a.auditer_id, u.first_name, u.last_name,
            SUM(IF(i.insp_type = 'regular', 1, 0)) as regular_inprogress_count,
            SUM(IF(i.insp_type = 'interval', 1, 0)) as interval_inprogress_count
            FROM audits a 
            INNER JOIN inspections i 
            ON a.inspection_id = i.id 
            INNER JOIN users u
            ON a.auditer_id = u.id
            WHERE a.product_id = ?
            AND a.state != 'aborted'
            AND a.state = 'started'
            AND a.on_hold != 1
			
			AND a.audit_date >= ? 
            
			GROUP BY a.auditer_id
            HAVING count(a.id) > 0";

        $pass_array = array($this->product_id,$start_date);
        return $this->db->query($sql, $pass_array)->result_array();
    }
	
	function get_pending_audits_count($start_date, $end_date, $product_id, $inspection_id = '', $modelwise = false) {
        $sql = "SELECT DATE_FORMAT(s.`sampling_date`, \"%D %b'%y\") as sampling_date, s.`inspection_id`,
            s.model_suffix, i.name as inspection_name,i.insp_type as insp_type,
            s.no_of_samples as samples, COUNT(a.id) as total_audits 
            FROM `sampling_plans` s
            INNER JOIN inspections i
            ON s.inspection_id = i.id
            LEFT JOIN audits as a
            ON ( 
                a.audit_date = s.`sampling_date` 
                AND a.inspection_id = s.inspection_id 
                AND a.model_suffix = s.model_suffix 
                AND a.state = 'completed' 
            )";
            
        $sql .= " WHERE s.product_id = ?";
        $pass_array = array($product_id); 
        
        if(empty($end_date)) {
            $sql .= " AND s.sampling_date = ?";
            $pass_array[] = $start_date; 
        } else {
            $sql .= " AND s.sampling_date BETWEEN ? AND ?";
            $pass_array[] = $start_date; 
            $pass_array[] = $end_date; 
        }
                
        if(!empty($inspection_id)) {
            $sql .= " AND s.inspection_id = ?";
            $pass_array[] = $inspection_id;
        }
        
        $sql .= " GROUP BY s.sampling_date, s.`inspection_id`, s.model_suffix
            HAVING COUNT(a.id) < s.no_of_samples";
        
        if(!$modelwise) {
            $sql = "SELECT sampling_date, inspection_id, inspection_name,insp_type,
                SUM(samples) as samples, 
                SUM(total_audits) as total_audits
                FROM (".$sql.") as f
                GROUP BY sampling_date, inspection_id";
        }

        $result = $this->db->query($sql, $pass_array);
        return $result->result_array();
    }
    
   
    function get_all_paired_audits($audit_id) {
        $this->db->where('paired_with', $audit_id);
        $this->db->where('paired', 1);
        
        if($this->product_id) {
            $this->db->where('product_id', $this->product_id);
        }
        
        return $this->db->get('audits')->result_array();
    }
    
    function copy_checkpoints_results($from, $to) {
        $this->db->where('audit_id', $to);
        $this->db->delete('audit_checkpoints');
        
        $sql = "INSERT INTO audit_checkpoints(`org_checkpoint_id`, `audit_id`, `checkpoint_no_real`, `checkpoint_no`, `iteration_no`, `insp_item`, `insp_item2`, `insp_item3`, `insp_item4`, `spec`, `lsl`, `usl`, `tgt`, `unit`, `guideline_image`, `automate_result_row`, `automate_result_col`, `pointer`, `remark`, `audit_value`, `result`, `result_datetime`, `na_approved`, `is_na`, `created`, `modified`)
        SELECT org_checkpoint_id, ".$to." as audit_id, `checkpoint_no_real`, `checkpoint_no`, `iteration_no`, `insp_item`, `insp_item2`, `insp_item3`, `insp_item4`, `spec`, `lsl`, `usl`, `tgt`, `unit`, `guideline_image`, `automate_result_row`, `automate_result_col`, `pointer`, `remark`, `audit_value`, `result`, `result_datetime`, `na_approved`, `is_na`, `created`, `modified`
        FROM audit_checkpoints
        WHERE audit_id = ?";
        
        return $this->db->query($sql, array($from));
    }
}