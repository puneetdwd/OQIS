<?php
class Report_model extends CI_Model {

    function add_gmes_history($data){
        $needed_array = array('PRODUCT_ID', 'INSPECTION_ID', 'AUDIT_ID', 'ORG_ID', 'OQC_LOT_ID', 'WOID', 'INSP_ID', 'OQIS_INSP_SEQS_NO', 'INSP_DATE', 'JUDGE_RSLT_TP_CODE', 'ATTR01', 'ATTR02', 'INSUSER', 'INSDTTM');
        $data = array_intersect_key($data, array_flip($needed_array));
        
        $data['CREATED'] = date('Y-m-d H:i:s');

        return (($this->db->insert('gmes_history', $data)) ? $this->db->insert_id() : FALSE);
    }
    
    function get_seq_no($product_id, $woid, $oqc_lot_id, $org_id) {
        $sql = "SELECT count(*)+1 as seq
        FROM gmes_history 
        WHERE PRODUCT_ID = ? 
        AND WOID = ? 
        AND OQC_LOT_ID = ? 
        AND ORG_ID = ?";
        
        $pass_array = array($product_id, $woid, $oqc_lot_id, $org_id);

        $result = $this->db->query($sql, $pass_array)->row_array();
        if(!empty($result)) {
            return $result['seq'];
        }
        
        return false;
    }
    
    function get_monthly_lot_qty($start_date, $end_date, $product_id = '', $type = "Monthly") {
        $date = "DATE_FORMAT(`audit_date`, \"%b'%y\")";
        if($type == 'Dialy') {
            $date = "DATE_FORMAT(`audit_date`, \"%D %b'%y\")";
        }
        
        $sql = "SELECT cal.product_id, p.name as product_name, ".$date."
         as plan_month, COUNT(DISTINCT lot_id) as total_lot_qty, 
        COUNT(DISTINCT CASE WHEN ng_count > 0 THEN lot_id END) as NG_lot_qty
        FROM (
            SELECT a.id, a.audit_date, a.product_id, a.inspection_id, a.model_suffix,
            IF(c.inspection_type = 'Tool', (
                SELECT COUNT(DISTINCT id)FROM audits
                WHERE audit_date = a.audit_date
                AND inspection_id = a.inspection_id
                AND tool = a.tool
                AND product_id = a.product_id
                AND state = 'completed'
            ),COUNT(DISTINCT a.id)) as total_audits,
            SUM(IF(ac.result = 'NG', 1, 0)) as ng_count,
            s.lot_id, 
            IF(c.inspection_type = 'Tool', (
                SELECT SUM(no_of_samples) FROM sampling_plans
                WHERE sampling_date = a.audit_date
                AND inspection_id = a.inspection_id
                AND tool = a.tool
                AND product_id = a.product_id
            ), s.no_of_samples) as no_of_samples
            FROM audits a
            INNER JOIN (
                SELECT inspection_id, inspection_type FROM inspection_config GROUP BY inspection_id
            ) as c
            ON a.inspection_id = c.inspection_id
            INNER JOIN `sampling_plans` s
            ON (
                a.audit_date        = s.sampling_date 
                AND a.inspection_id = s.inspection_id  
                AND a.model_suffix  = s.model_suffix 
                AND a.product_id    = s.product_id     
            )
            INNER JOIN audit_checkpoints ac
            ON a.id = ac.audit_id
			INNER JOIN inspections i
            ON i.id = a.inspection_id
			
            WHERE state = 'completed'
            AND a.audit_date BETWEEN ? AND ?";
        
        $pass_array = array($start_date, $end_date);
        if(!empty($product_id)) {
            $sql .= " AND a.product_id = ?";
            $pass_array[] = $product_id;
        }
		
        
            $sql .= " GROUP BY a.audit_date, a.product_id, a.inspection_id, a.model_suffix
            HAVING no_of_samples <= COUNT(DISTINCT a.id)
        ) as cal
        INNER JOIN products as p
        ON cal.product_id = p.id
        GROUP BY product_id, ".$date."
        ORDER BY product_name, `audit_date`";
        
        $result = $this->db->query($sql, $pass_array);
        
        return $result->result_array();
    }
    function get_monthly_lot_qty_new($start_date, $end_date,$insp_type, $product_id = '', $type = "Monthly") {
        $date = "DATE_FORMAT(`audit_date`, \"%b'%y\")";
        if($type == 'Dialy') {
            $date = "DATE_FORMAT(`audit_date`, \"%D %b'%y\")";
        }
        
        $sql = "SELECT cal.product_id, p.name as product_name, ".$date."
         as plan_month, COUNT(DISTINCT lot_id) as total_lot_qty, 
        COUNT(DISTINCT CASE WHEN ng_count > 0 THEN lot_id END) as NG_lot_qty
        FROM (
            SELECT a.id, a.audit_date, a.product_id, a.inspection_id, a.model_suffix,
            IF(c.inspection_type = 'Tool', (
                SELECT COUNT(DISTINCT id)FROM audits
                WHERE audit_date = a.audit_date
                AND inspection_id = a.inspection_id
                AND tool = a.tool
                AND product_id = a.product_id
                AND state = 'completed'
            ),COUNT(DISTINCT a.id)) as total_audits,
            SUM(IF(ac.result = 'NG', 1, 0)) as ng_count,
            s.lot_id, 
            IF(c.inspection_type = 'Tool', (
                SELECT SUM(no_of_samples) FROM sampling_plans
                WHERE sampling_date = a.audit_date
                AND inspection_id = a.inspection_id
                AND tool = a.tool
                AND product_id = a.product_id
            ), s.no_of_samples) as no_of_samples
            FROM audits a
            INNER JOIN (
                SELECT inspection_id, inspection_type FROM inspection_config GROUP BY inspection_id
            ) as c
            ON a.inspection_id = c.inspection_id
            INNER JOIN `sampling_plans` s
            ON (
                a.audit_date        = s.sampling_date 
                AND a.inspection_id = s.inspection_id  
                AND a.model_suffix  = s.model_suffix 
                AND a.product_id    = s.product_id     
            )
            INNER JOIN audit_checkpoints ac
            ON a.id = ac.audit_id
			INNER JOIN inspections i
            ON i.id = a.inspection_id
			
            WHERE state = 'completed'
            AND a.audit_date BETWEEN ? AND ?";
        
        $pass_array = array($start_date, $end_date);
        if(!empty($product_id)) {
            $sql .= " AND a.product_id = ?";
            $pass_array[] = $product_id;
        }
		 if(!empty($insp_type)) {
			$insp_type = strtolower($insp_type);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }
        
            $sql .= " GROUP BY a.audit_date, a.product_id, a.inspection_id, a.model_suffix
            HAVING no_of_samples <= COUNT(DISTINCT a.id)
        ) as cal
        INNER JOIN products as p
        ON cal.product_id = p.id
        GROUP BY product_id, ".$date."
        ORDER BY product_name, `audit_date`";
        
        $result = $this->db->query($sql, $pass_array);
        
        return $result->result_array();
    }
    
    function get_sampling_report($start_date, $end_date, $product_id = '', $type = "Monthly") {
        $date = "DATE_FORMAT(`audit_date`, \"%b'%y\")";
        if($type == 'Dialy') {
            $date = "DATE_FORMAT(`audit_date`, \"%D %b'%y\")";
        }
        
        $sql = "SELECT cal.product_id, p.name as product_name, 
        ".$date." as plan_month,
        COUNT(DISTINCT cal.id) as total_lot_qty,
        COUNT(DISTINCT CASE WHEN ng_count > 0 THEN cal.id END) as NG_lot_qty
        FROM (
            SELECT a.id, a.audit_date, a.product_id, SUM(IF(ac.result = 'NG', 1, 0)) as ng_count
            FROM audits a
            INNER JOIN audit_checkpoints ac
            ON a.id = ac.audit_id
            WHERE state = 'completed'
            AND a.audit_date BETWEEN ? AND ?";
            
        $pass_array = array($start_date, $end_date);
        if(!empty($product_id)) {
            $sql .= " AND a.product_id = ?";
            $pass_array[] = $product_id;
        }
            
        $sql .= " GROUP BY a.id, a.product_id
        ) as cal
        INNER JOIN products as p
        ON cal.product_id = p.id
        GROUP BY product_id, ".$date."
        ORDER BY product_name, `audit_date`";
        
        $result = $this->db->query($sql, $pass_array);
        
        return $result->result_array();
    }

    function get_serial_no_report_new($start_date, $end_date, $filters = array()) {
        $sql = "SELECT p.name as product_name, pl.name as line_name,
        a.model_suffix, GROUP_CONCAT(a.serial_no SEPARATOR ', ') as serial_no
        FROM audits a
        INNER JOIN products p
        ON a.product_id = p.id
        INNER JOIN product_lines pl
        ON a.line_id = pl.id
		INNER JOIN inspections i
        ON a.inspection_id = i.id
        WHERE state = 'completed'
        AND a.audit_date BETWEEN ? AND ?";
        
        $pass_array = array($start_date, $end_date);
        /* if(!empty($filters['model_suffix'])) {
            $sql .= " AND a.model_suffix = ?";
            $pass_array[] = $filters['model_suffix'];
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
        if(!empty($filters['insp-type'])) {
			$insp_type = strtolower($filters['insp-type']);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }
        $sql .= " GROUP BY pl.name, a.model_suffix";
        
        $result = $this->db->query($sql, $pass_array);
        return $result->result_array();
    }
	function get_serial_no_report($start_date, $end_date, $filters = array()) {
        $sql = "SELECT p.name as product_name, pl.name as line_name,
        a.model_suffix, GROUP_CONCAT(a.serial_no SEPARATOR ', ') as serial_no
        FROM audits a
        INNER JOIN products p
        ON a.product_id = p.id
        INNER JOIN product_lines pl
        ON a.line_id = pl.id
        WHERE state = 'completed'
        AND a.audit_date BETWEEN ? AND ?";
        
        $pass_array = array($start_date, $end_date);
        /* if(!empty($filters['model_suffix'])) {
            $sql .= " AND a.model_suffix = ?";
            $pass_array[] = $filters['model_suffix'];
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
        
        $sql .= " GROUP BY pl.name, a.model_suffix";
        
        $result = $this->db->query($sql, $pass_array);
        return $result->result_array();
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
            $sql = "SELECT sampling_date, inspection_id, inspection_name,
                SUM(samples) as samples, 
                SUM(total_audits) as total_audits
                FROM (".$sql.") as f
                GROUP BY sampling_date, inspection_id";
        }

        $result = $this->db->query($sql, $pass_array);
        return $result->result_array();
    }
    function get_pending_audits_count_new($start_date, $end_date,$insp_type, $product_id, $inspection_id = '', $modelwise = false) {
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
		if(!empty($insp_type)) {
			$insp_type = strtolower($insp_type);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }
        
        $sql .= " GROUP BY s.sampling_date, s.`inspection_id`, s.model_suffix
            HAVING COUNT(a.id) < s.no_of_samples";
        
        if(!$modelwise) {
            $sql = "SELECT sampling_date, inspection_id, inspection_name,
                SUM(samples) as samples, 
                SUM(total_audits) as total_audits
                FROM (".$sql.") as f
                GROUP BY sampling_date, inspection_id";
        }

        $result = $this->db->query($sql, $pass_array);
        return $result->result_array();
    }
    
    function modelwise_pending_count($date, $inspection_id) {
        
    }

    function get_day_completed_inspection($start_date, $end_date, $product_id, $inspection_id = '') {
        $sql = "SELECT `sampling_date`, product_id, `inspection_id`, i.name as inspection_name,
        SUM(not_complete_flag) as not_complete_count,
        IF(SUM(not_complete_flag) > 0, 'Not Complete', 'Complete') as status
        FROM (
            SELECT s.`id`, s.`sampling_date`, s.`line`, s.`model_suffix`, s.`inspection_id`, s.`no_of_samples`,
            COUNT(DISTINCT a.audit_id) as c,
            IF(s.no_of_samples > COUNT(DISTINCT a.audit_id), 1, 0) as not_complete_flag
            FROM `sampling_plans` as s
            LEFT JOIN approved_checked as ac
            ON (
                s.sampling_date = ac.date
                AND s.inspection_id = ac.inspection_id
            )
            LEFT JOIN `audits_completed` as a
            ON (
                s.sampling_date = a.audit_date
                AND s.model_suffix = a.model_suffix
                AND s.line = a.line
                AND s.inspection_id = a.inspection_id
            )
            WHERE (ac.checked_by IS NULL OR ac.approved_by IS NULL)
            AND s.product_id = ? ";
        
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
        
        $sql .= " GROUP BY s.`sampling_date`, s.line, s.inspection_id, s.`model_suffix`
        ) as s
        INNER JOIN inspections i
        ON s.inspection_id = i.id
        GROUP BY `sampling_date`, `inspection_id`
        HAVING SUM(not_complete_flag) > 0
        ORDER BY sampling_date DESC";
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
	function get_day_completed_inspection_new($start_date, $end_date, $insp_type,$product_id, $inspection_id = '') {
        $sql = "SELECT `sampling_date`, product_id, `inspection_id`, i.name as inspection_name,
        SUM(not_complete_flag) as not_complete_count,
        IF(SUM(not_complete_flag) > 0, 'Not Complete', 'Complete') as status
        FROM (
            SELECT s.`id`, s.`sampling_date`, s.`line`, s.`model_suffix`, s.`inspection_id`, s.`no_of_samples`,
            COUNT(DISTINCT a.audit_id) as c,
            IF(s.no_of_samples > COUNT(DISTINCT a.audit_id), 1, 0) as not_complete_flag
            FROM `sampling_plans` as s            
			Inner join inspections i ON i.id = s.inspection_id
			LEFT JOIN approved_checked as ac
            ON (
                s.sampling_date = ac.date
                AND s.inspection_id = ac.inspection_id
            )
            LEFT JOIN `audits_completed` as a
            ON (
                s.sampling_date = a.audit_date
                AND s.model_suffix = a.model_suffix
                AND s.line = a.line
                AND s.inspection_id = a.inspection_id
            )
            WHERE (ac.checked_by IS NULL OR ac.approved_by IS NULL)
            AND s.product_id = ? ";
        
        $pass_array = array($product_id); 
        
        if(empty($end_date)) {
            $sql .= " AND s.sampling_date = ?";
            $pass_array[] = $start_date; 
        } else {
            $sql .= " AND s.sampling_date BETWEEN ? AND ?";
            $pass_array[] = $start_date; 
            $pass_array[] = $end_date; 
        }
        if(!empty($insp_type)) {
			$insp_type = strtolower($insp_type);
            $sql .= " AND i.insp_type = ? ";
            $pass_array[] = $insp_type;
        }     
        if(!empty($inspection_id)) {
            $sql .= " AND s.inspection_id = ?";
            $pass_array[] = $inspection_id;
        }
        
        $sql .= " GROUP BY s.`sampling_date`, s.line, s.inspection_id, s.`model_suffix`
        ) as s
        INNER JOIN inspections i
        ON s.inspection_id = i.id
        GROUP BY `sampling_date`, `inspection_id`
        HAVING SUM(not_complete_flag) > 0
        ORDER BY sampling_date DESC";
        
        return $this->db->query($sql, $pass_array)->result_array();
    }

    function get_day_completed_inspection_details($date, $product_id, $inspection_id) {
        $sql = "SELECT s.`id`, s.`sampling_date`, s.`line`, s.`model_suffix`, s.`inspection_id`, s.`no_of_samples`,
            COUNT(DISTINCT a.audit_id) as completed, MAX(a.audit_id) as audit_id,
            IF(s.no_of_samples > COUNT(DISTINCT a.audit_id), 1, 0) as not_complete_flag
            FROM `sampling_plans` as s
            LEFT JOIN `audits_completed` as a
            ON (
                s.sampling_date = a.audit_date
                AND s.model_suffix = a.model_suffix
                AND s.line = a.line
                AND s.inspection_id = a.inspection_id
            )
            WHERE s.product_id = ?
            AND s.sampling_date = ?
            AND s.inspection_id = ?";
                
        $sql .= " GROUP BY s.`sampling_date`, s.line, s.inspection_id, s.`model_suffix`";
        
        $pass_array = array($product_id, $date,  $inspection_id); 

        return $this->db->query($sql, $pass_array)->result_array();
    }

    function add_to_approve_check($data, $id) {
        $needed_array = array('date', 'inspection_id', 'approved_by', 'approved_datetime', 'checked_by', 'checked_datetime');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('approved_checked', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('approved_checked', $data)) ? $id : False);
        }
    }
    
    function get_approved_checked($date, $inspection_id) {
        $sql = "SELECT ac.*, 
        CONCAT(u1.first_name, ' ', u1.last_name) as checked_by_user, u1.user_type as checked_by_type,
        CONCAT(u2.first_name, ' ', u2.last_name) as approved_by_user, u2.user_type as approved_by_type
        FROM approved_checked ac
        LEFT JOIN users u1
        ON ac.checked_by = u1.id
        LEFT JOIN users u2
        ON ac.approved_by = u2.id
        WHERE date = ?
        AND inspection_id = ?";
        
        return $this->db->query($sql, array($date, $inspection_id))->row_array();
    }
    
    function get_product_status_report($start_date, $end_date, $product_id) {
        $sql = "SELECT s.`id`, s.`sampling_date`, DATE_FORMAT(`sampling_date`, \"%D %b'%y\") as `date`, s.`inspection_id`,
        i.name as inspection_name, ac.checked_by, ac.approved_by
        FROM `sampling_plans` as s
        INNER JOIN inspections i
        ON s.inspection_id = i.id
        LEFT JOIN approved_checked as ac
        ON (
            s.sampling_date = ac.date
            AND s.inspection_id = ac.inspection_id
        )
        WHERE s.product_id = ?
        AND s.sampling_date BETWEEN ? AND ?
        GROUP BY s.`sampling_date`, s.`inspection_id`";
        
        $pass_array = array($product_id, $start_date, $end_date); 
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
}