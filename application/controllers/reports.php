<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Admin_Controller {

    public function __construct() {
        parent::__construct(true);
        
        $this->template->write_view('header', 'templates/header', array('page' => 'reports'));
        $this->template->write_view('footer', 'templates/footer');
    }

    public function index() {
        $data = array();
        $this->load->model('Audit_model');
        $id = ($this->user_type == 'Audit') ? $this->id : '';
        
        $data['models'] = $this->Audit_model->get_all_audit_models();
        
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id, 0, null);
        //echo $this->db->last_query();exit;
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            if($this->product_id) {
                $post_data['product_id'] = $this->product_id;
            }
            
            $audits = $this->Audit_model->get_grouped_audit_with_plan($post_data, '');
            $audits = array_merge($audits, $this->Audit_model->get_grouped_audit_with_plan($post_data, '', false, 'tool'));
            $data['audits'] = $audits;
        }
        
        $this->template->write('title', 'OQIS | Product Inspection | Reports');
        $this->template->write_view('content', 'reports/reports', $data);
        $this->template->render();
    }
    
    public function pending() {
        $data = array();
        $this->load->model('Audit_model');
        
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id, 0, null);
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $start_range = $post_data['start_range'];
            $end_range = $post_data['end_range'];
            $inspection_id = $post_data['inspection_id'];

            $this->load->model('Report_model');
            $counts = $this->Report_model->get_pending_audits_count($start_range, $end_range, $this->product_id, $inspection_id);
            //echo "<pre>";echo $this->db->last_query();exit;
            $days = $this->get_days_for_range($start_range, $end_range);
            $days = array_fill_keys(($days), '-');
            $data['days'] = $days;
            
            $reports = array();
            $totals = array_merge(array('Total' => 'Total'), $days);
            foreach($counts as $count) {
                if(!isset($reports[$count['inspection_id']])) {
                    $reports[$count['inspection_id']] = array_merge(array('inspection_name' => $count['inspection_name']), $days);
                }
                
                $pending = $count['samples']-$count['total_audits'];
                $reports[$count['inspection_id']][$count['sampling_date']] = ($pending < 0) ? 0 : $pending;
                
                $totals[$count['sampling_date']] += $pending;
            }
            
            $reports[] = $totals;
            $data['reports'] = $reports;
            
            //echo "<pre>";print_r($reports);exit;
        }
        
        $this->template->write('title', 'OQIS | Product Inspection | Reports');
        $this->template->write_view('content', 'reports/pending', $data);
        $this->template->render();
    }
    
    public function modelwise_pending($inspection_id, $date) {
        $data = array();
        $date = date('Y-m-d', strtotime($date));
        $data['date'] = $date;
        
        $this->load->model('Inspection_model');
        $data['inspection'] = $this->Inspection_model->get_inspection($inspection_id);
        $this->load->model('Report_model');
        $reports = $this->Report_model->get_pending_audits_count($date, '', $this->product_id, $inspection_id, true);
        //echo $this->db->last_query();exit;
        $data['reports'] = $reports;
        
        echo $this->load->view('reports/modelwise_pending', $data, true);
    }
    
    public function check_judgement() {
        $response = array('status' => 'error');
        if($this->input->post('audit_id')) {
            $audit_id = $this->input->post('audit_id');
            
            $this->load->model('Audit_model');
            $res = $this->Audit_model->get_audit_judgement($audit_id);
            
            $response = array('status' => 'success', 'judgement' => ($res['ng_count'] > 0 ? 'NG' : 'OK'));
        }
        
        echo json_encode($response);
    }
    
    public function gmes() {
        $data = array();
        $this->load->model('Audit_model');
        $data['models'] = $this->Audit_model->get_all_audit_models();
        $data['workorders'] = $this->Audit_model->get_all_audit_workorders();
        
        $this->load->model('Product_model');
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        $data['lines'] = $this->Product_model->get_all_product_lines($this->product_id);
        
        $filters = $this->input->post() ? $this->input->post() : array();
        $filters = array_filter($filters);
        $data['page_no'] = 1;
        if(count($filters) > 1) {
            $filters['gmes_sent'] = 0;

            $per_page = 25;
            $page_no = $this->input->post('page_no');
            
            $limit = 'LIMIT '.($page_no-1)*$per_page.' ,'.$per_page;
            
            $data['page_no'] = $page_no;
            
            $count = $this->Audit_model->get_completed_audits($filters, true);
            $count = $count[0]['c'];
            $data['total_page'] = ceil($count/50);
            
            $data['audits'] = $this->Audit_model->get_completed_audits($filters, false, $limit);
        }
        
        $this->template->write('title', 'OQIS | GMES Integration');
        $this->template->write_view('content', 'reports/gmes', $data);
        $this->template->render();
    }
    
    public function edit() {
        $data = array();
        $this->load->model('Audit_model');
        $data['models'] = $this->Audit_model->get_all_audit_models();
        $data['workorders'] = $this->Audit_model->get_all_audit_workorders();
        $data['serial_nos'] = array();
        
        $this->load->model('Product_model');
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        $data['lines'] = $this->Product_model->get_all_product_lines($this->product_id);
        
        $filters = $this->input->post() ? $this->input->post() : array();
        $filters = array_filter($filters);
        $data['page_no'] = 1;
        if(count($filters) > 1) {
            //$filters = $this->input->post();
            
            $per_page = 25;
            $page_no = $this->input->post('page_no');
            
            $limit = 'LIMIT '.($page_no-1)*$per_page.' ,'.$per_page;
            
            $data['page_no'] = $page_no;
            
            $count = $this->Audit_model->get_completed_audits($filters, true);
            $count = $count[0]['c'];
            $data['total_page'] = ceil($count/50);
            
            $data['audits'] = $this->Audit_model->get_completed_audits($filters, false, $limit);
        }
        
        $this->template->write('title', 'OQIS | GMES Integration');
        $this->template->write_view('content', 'reports/edit', $data);
        $this->template->render();
    }
    
    public function send_to_gmes($id) {
        require_once APPPATH .'libraries/lg_oracle.php';
        
        $this->load->model('Report_model');
        $this->load->model('Audit_model');
        $this->load->model('Sampling_model');
        
        $filters = array('start_date' => '2016-05-26', 'gmes_sent' => 0, 'id' => $id);
        
        $audits = $this->Audit_model->get_completed_audits($filters);
        if(!count($audits)) {
            redirect(base_url().'reports/gmes');
        }
        
        $headers = array('ORG_ID', 'OQC_LOT_ID', 'WOID', 'INSP_ID', 'OQIS_INSP_SEQS_NO',
        'INSP_DATE', 'JUDGE_RSLT_TP_CODE', 'ATTR01', 'ATTR02', 'INSUSER', 'INSDTTM');
        
        $gmes_headers = array('OQC_LOT_ID', 'WOID', 'INSP_ID', 'OQIS_INSP_SEQS_NO',
        'INSP_DATE', 'JUDGE_RSLT_TP_CODE', 'ATTR01', 'ATTR02', 'INSUSER', 'INSDTTM');

        $insert_data = array();
        $data_without_org = array();

        $org_id = $this->session->userdata('org_id');

        foreach($audits as $audit) {
            $judgement = $this->Audit_model->get_audit_judgement($audit['id']);
            $lot_id = $this->Sampling_model->get_lot_id($this->product_id, $audit['audit_date'], $audit['line'], $audit['model_suffix']);
            
            $woid = get_woid($org_id, $audit['workorder']);
            if(!$woid) {
                $this->session->set_flashdata('error', 'WOID not found.');
                redirect(base_url().'reports/gmes');
            }
            $oqc_lot_id = $this->input->post('oqc_lot_id');
            
            $oqis_insp_seqs_no = $this->Report_model->get_seq_no($this->product_id, $woid, $oqc_lot_id, $org_id);
            
            $temp       = array();
            $oqis_data  = array();
            $oqis_data['PRODUCT_ID'] = $this->product_id;
            $oqis_data['AUDIT_ID'] = $audit['id'];
            $oqis_data['INSPECTION_ID'] = $audit['inspection_id'];
            
            $temp[]     = $org_id;
            $oqis_data['ORG_ID'] = $org_id;
            
            $temp[]     = $oqc_lot_id;
            $oqis_data['OQC_LOT_ID'] = $oqc_lot_id;
            
            $temp[]     = $woid;
            $oqis_data['WOID'] = $woid;
            
            $temp[]     = $audit['gmes_insp_id'];
            $oqis_data['INSP_ID'] = $audit['gmes_insp_id'];
            
            $temp[]     = $oqis_insp_seqs_no;
            $oqis_data['OQIS_INSP_SEQS_NO'] = $oqis_insp_seqs_no;
            
            $temp[]     = date('d-M-Y', strtotime($audit['audit_date']));
            $oqis_data['INSP_DATE'] = $audit['audit_date'];
            
            $temp[]     = $judgement['ng_count'] > 0 ? 'NG' : 'OK';
            $oqis_data['JUDGE_RSLT_TP_CODE'] = $judgement['ng_count'] > 0 ? 'NG' : 'OK';
            
            $temp[]     = $audit['workorder'];
            $oqis_data['ATTR01'] = $audit['workorder'];
            
            $temp[]     = $audit['serial_no'];
            
            $temp[]     = $audit['auditer'];
            $oqis_data['INSUSER'] = $audit['auditer'];
            
            $temp[]     = date('d-M-Y h:i:s A', strtotime($audit['modified']));
            $oqis_data['INSDTTM'] = $audit['modified'];

            $insert_data[] = $temp;
            
            unset($temp[0]);
            $data_without_org[] = $temp;

            $result = $this->Report_model->add_gmes_history($oqis_data);

            $result = gmes_insert($headers, $insert_data, 'INTF.TB_OQC_OQIS_INSP_HIST_S');
            $result = gmes_insert($gmes_headers, $data_without_org, 'MES_LINK.TB_OQC_OQIS_INSP_HIST@OQIS_GMESPP');
        
            if(!$result) {
                $this->session->set_flashdata('error', 'Please provide proper OQC LOT ID as it is in GMES');
            } else {
                $update_audit = array('gmes_sent' => 1);
                $this->Audit_model->update_audit($update_audit, $audit['id']);
            }
        }

        redirect(base_url().'reports/gmes');
    }
    
    public function send_to_gmes_view($id) {
        $data = array('id' => $id);
        echo $this->load->view('reports/send_to_gmes_view', $data);
    }
    
    public function download_report($audit_id) {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_only_audit_details($audit_id);
        if(empty($audit)) {
            $this->session->set_flashdata('error', 'Invalid request');
            redirect(base_url().'reports');
        }
        
        $this->load->model('Sampling_model');
        $config_type = $this->Sampling_model->get_inspection_config_type($audit['inspection_id']);

        $checking_data = array(
            'audit_date'    => $audit['audit_date'],
            'inspection_id' => $audit['inspection_id'],
            'product_id'    => $audit['product_id']
        );
        
        if($config_type == 'Tool') {
            $checking_data['tool'] = $audit['tool'];
            $is_complete = $this->Audit_model->get_grouped_audit_with_plan($checking_data, '', false, 'tool');
        } else {
            $checking_data['model_suffix'] = $audit['model_suffix'];
            $is_complete = $this->Audit_model->get_grouped_audit_with_plan($checking_data, '');
        }

        //echo "<pre>";print_r($is_complete);exit;
        if(empty($is_complete)) {
            $this->session->set_flashdata('error', 'Invalid request');
            redirect(base_url().'reports');
        }
        $is_complete = $is_complete[0];
        
        if(!$this->input->get('view') && $is_complete['total_audits'] < $is_complete['no_of_samples']) {
            $this->session->set_flashdata('error', 'Invalid request');
            redirect(base_url().'reports');
        }
        
        $this->load->model('Report_model');
        $status = $this->Report_model->get_approved_checked($audit['audit_date'], $audit['inspection_id']);

        $top_row = array(
            date('jS M,Y', strtotime($is_complete['audit_date'])), $is_complete['product_name'],
            $is_complete['inspection_name'], $is_complete['model_suffix'], $is_complete['total_audits']
        );
        
        if($this->input->get('view') && $is_complete['total_audits'] < $is_complete['no_of_samples']) {
            $top_row[] = 'Pending';
        } else {
            $top_row[] = ($is_complete['checkpoint_count'] === $is_complete['correct_count']) ? 'OK' : 'NG';
        }
        
        $top_row[] = !empty($status['checked_by_user']) ? $status['checked_by_user'] : '';
        $top_row[] = !empty($status['approved_by_user']) ? $status['approved_by_user'] : '';
        
        $top_headers = array('Inspect Date', 'Product', 'Inspection',
            'Model.Suffix/Tool', 'Samples', 'Judgement', 'Checked By', 'Approved By');

        $checkpoints = $this->Audit_model->get_checkpoint_for_download($audit['id']);
        
        if($config_type == 'Tool') {
            $model_suffixs = explode(',', $is_complete['model_suffixs']);
        }
        
        $serial_nos = explode(',', $is_complete['serial_nos']);
        $workorders = explode(',', $is_complete['workorders']);
        $audit_ids = explode(',', $is_complete['audit_ids']);
        if(count($serial_nos) !== count($audit_ids)) {
            $this->session->set_flashdata('error', 'Error: Same serial no inspected twice.');
            redirect(base_url().'reports');
        }
        
        $headers = array('', '', '', '', '', '', '', '', '');
        $wo_header = array('', '', '', '', '', '', '', '', '');
        $result_header = array('', '', '', '', '', '', '', '', '');
        $sub_headers = array('Checkpoint No.', 'Insp Item', 'Insp Item', 'Insp Item',
            'Spec', 'LSL', 'USL', 'TGT', 'Unit'
        );
        
        $audit_checkpoints = array();
        foreach($audit_ids as $key => $val) {
            $audit_checkpoints[$val] = $this->Audit_model->get_all_audit_checkpoints($val, '', true);
            
            $ng_points = array_filter($audit_checkpoints[$val], function($a) { return $a['result'] == 'NG'; });
            
            if($config_type == 'Tool') {
                $h = $model_suffixs[$key];
            } else {
                $h = $serial_nos[$key];
            }
            
            $headers[]          = $h;
            $headers[]          = $h;
            $headers[]          = $h;
            
            $wo_header[]        = explode('|',$workorders[$key])[0];
            $wo_header[]        = explode('|',$workorders[$key])[0];
            $wo_header[]        = explode('|',$workorders[$key])[0];
                
            $result_header[]    = count($ng_points) ? 'NG' : 'OK';
            $result_header[]    = count($ng_points) ? 'NG' : 'OK';
            $result_header[]    = count($ng_points) ? 'NG' : 'OK';
            
            $sub_headers[]      = 'Value';
            $sub_headers[]      = 'Result';
            $sub_headers[]      = 'Remark';
        }
        
        $report_data = array();
        foreach($checkpoints as $chk_key => $checkpoint) {
            $temp = array();
            $temp['checkpoint_no']      = $checkpoint['checkpoint_no'];
            $temp['insp_item']          = $checkpoint['insp_item'];
            $temp['insp_item2']         = $checkpoint['insp_item2'];
            $temp['insp_item3']         = $checkpoint['insp_item3'];
            $temp['spec']               = $checkpoint['spec'];
            $temp['lsl']                = $checkpoint['lsl'];
            $temp['usl']                = $checkpoint['usl'];
            $temp['tgt']                = $checkpoint['tgt'];
            $temp['unit']               = $checkpoint['unit'];
            
            foreach($audit_ids as $id) {
                $temp['audit_value'.$id]    = $audit_checkpoints[$id][$chk_key]['audit_value'];
                $temp['result'.$id]         = $audit_checkpoints[$id][$chk_key]['result'];
                $temp['remark'.$id]         = $audit_checkpoints[$id][$chk_key]['remark'];
            }
            
            $report_data[] = $temp;
        }
        
        unset($audit['id']);
        
        if($this->input->get('view')) {
            $data = array();
            $data['top_headers'] = $top_headers;
            $data['top_row'] = $top_row;
            $data['sub_headers'] = $sub_headers;
            $data['headers'] = $headers;
            $data['wo_header'] = $wo_header;
            $data['result_header'] = $result_header;

            $data['reports'] = $report_data;
            
            $this->template->write('title', 'OQIS | Product Inspection | View Reports');
            $this->template->write_view('content', 'reports/view_reports', $data);
            $this->template->render();
        } else {
            $filename = 'Report_'.time().'.xls' ;
            $this->create_excel($top_headers, $top_row, $headers, $wo_header, $result_header, $sub_headers, $report_data, $filename);
        }
    }

    public function lar_report() {
        //echo "<pre>";print_r($_SESSION);exit;
        $data = array();
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $start_range = $post_data['start_range'];
            $end_range = $post_data['end_range'];
            $type = $post_data['type'];
            
            if($type == 'Dialy') {
                $months = $this->get_days_for_range($start_range, $end_range);
                $months = array_fill_keys(($months), '-');
            } else {
                $months = $this->get_months_from_range($start_range, $end_range);
                $months = array_fill_keys(($months), '-');
            }
            
            $this->load->model('Report_model');
            if(!$this->session->userdata('is_super_admin')) {
                $raw_data = $this->Report_model->get_monthly_lot_qty($start_range, $end_range, $this->product_id, $type);
            } else {
                $raw_data = $this->Report_model->get_monthly_lot_qty($start_range, $end_range, '', $type);
            }
            
            $reports = array();
            $totals = array('product_name' => 'Pune');
            $totals['defect'] =  array_merge(array('type' => 'Defect QTY'), $months);
            $totals['qty'] =  array_merge(array('type' => 'Lot QTY'), $months);
            $totals['perc'] =  array_merge(array('type' => 'OQA LAR'), $months);
            foreach($raw_data as $raw) {
                if(!isset($reports[$raw['product_id']])) {
                    $reports[$raw['product_id']] = array('product_name' => $raw['product_name']);
                }
                
                if(!isset($reports[$raw['product_id']]['defect'])) {
                    $reports[$raw['product_id']]['defect'] = array_merge(array('type' => 'Defect QTY'), $months);
                }
                if(!isset($reports[$raw['product_id']]['qty'])) {
                    $reports[$raw['product_id']]['qty'] = array_merge(array('type' => 'Lot QTY'), $months);
                }
                if(!isset($reports[$raw['product_id']]['perc'])) {
                    $reports[$raw['product_id']]['perc'] = array_merge(array('type' => 'OQA LAR'), $months);
                }
                
                $defact = $raw['NG_lot_qty'];
                $qty = $raw['total_lot_qty'];
                $perc = round((($qty-$defact)/$qty)*100, 1);
                
                $reports[$raw['product_id']]['qty'][$raw['plan_month']] = $qty;
                $reports[$raw['product_id']]['defect'][$raw['plan_month']] = $defact;
                $reports[$raw['product_id']]['perc'][$raw['plan_month']] = $perc;
                
                $totals['defect'][$raw['plan_month']] += $defact;
                $totals['qty'][$raw['plan_month']] += $qty;
            }
            
            if($this->session->userdata('is_super_admin')) {
                foreach($totals['perc'] as $month => $no) {
                    if($month == 'type') { continue; }
                    $qty = $totals['qty'][$month];
                    $defact = $totals['defect'][$month];
                    if($qty > 0) {
                        $perc = round((($qty-$defact)/$qty)*100, 1);
                    } else {
                        $perc = '-';
                    }
                    
                    $totals['perc'][$month] = $perc;
                }
                
                $reports[] = $totals;
            }
            $data['reports'] = $reports;

            $data['months'] = $months;
            
            if($this->input->post('download')) {
                //echo "<pre>";print_r($reports);exit;
                
                $title = 'LAR Report for Period '.date('jS M, Y', strtotime($start_range)).' to '.date('jS M, Y', strtotime($end_range));
                $this->create_lar_excel($months, $reports, 'LAR_Report_'.time().'.xls', $title);
            }
        }
        
        $this->template->write('title', 'OQIS | LAR Reports');
        $this->template->write_view('content', 'reports/lar_reports', $data);
        $this->template->render();
    }
    
    public function sampling_ppm_report() {
        $data = array();
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $start_range = $post_data['start_range'];
            $end_range = $post_data['end_range'];
            $type = $post_data['type'];
            
            if($type == 'Dialy') {
                $months = $this->get_days_for_range($start_range, $end_range);
                $months = array_fill_keys(($months), '-');
            } else {
                $months = $this->get_months_from_range($start_range, $end_range);
                $months = array_fill_keys(($months), '-');
            }
            
            $this->load->model('Report_model');
            if(!$this->session->userdata('is_super_admin')) {
                $raw_data = $this->Report_model->get_sampling_report($start_range, $end_range, $this->product_id, $type);
            } else {
                $raw_data = $this->Report_model->get_sampling_report($start_range, $end_range, '', $type);
            }
            
            $reports = array();
            $totals = array('product_name' => 'Pune');
            $totals['defect'] =  array_merge(array('type' => 'Defect QTY'), $months);
            $totals['qty'] =  array_merge(array('type' => 'Lot QTY'), $months);
            $totals['perc'] =  array_merge(array('type' => 'OQA LAR'), $months);
            foreach($raw_data as $raw) {
                if(!isset($reports[$raw['product_id']])) {
                    $reports[$raw['product_id']] = array('product_name' => $raw['product_name']);
                }
                
                
                if(!isset($reports[$raw['product_id']]['defect'])) {
                    $reports[$raw['product_id']]['defect'] = array_merge(array('type' => 'Defect QTY'), $months);
                }
                if(!isset($reports[$raw['product_id']]['qty'])) {
                    $reports[$raw['product_id']]['qty'] = array_merge(array('type' => 'Inspection QTY'), $months);
                }
                if(!isset($reports[$raw['product_id']]['perc'])) {
                    $reports[$raw['product_id']]['perc'] = array_merge(array('type' => 'OQA Sample (PPM)'), $months);
                }
                
                $defact = $raw['NG_lot_qty'];
                $qty = $raw['total_lot_qty'];
                $perc = round((($qty-$defact)/$qty)*100, 1);
                
                $reports[$raw['product_id']]['qty'][$raw['plan_month']] = $qty;
                $reports[$raw['product_id']]['defect'][$raw['plan_month']] = $defact;
                $reports[$raw['product_id']]['perc'][$raw['plan_month']] = $perc;
                
                $totals['defect'][$raw['plan_month']] += $defact;
                $totals['qty'][$raw['plan_month']] += $qty;
            }
            
            if($this->session->userdata('is_super_admin')) {
                foreach($totals['perc'] as $month => $no) {
                    if($month == 'type') { continue; }
                    $qty = $totals['qty'][$month];
                    $defact = $totals['defect'][$month];
                    if($qty > 0) {
                        $perc = round((($qty-$defact)/$qty)*100, 1);
                    } else {
                        $perc = '-';
                    }
                    
                    $totals['perc'][$month] = $perc;
                }
                
                $reports[] = $totals;
            }
            $data['reports'] = $reports;

            $data['months'] = $months;
            
            if($this->input->post('download')) {
                //echo "<pre>";print_r($reports);exit;
                
                $title = 'Sampling PPM Report for Period '.date('jS M, Y', strtotime($start_range)).' to '.date('jS M, Y', strtotime($end_range));
                $this->create_lar_excel($months, $reports, 'PPM_Report_'.time().'.xls', $title);
            }
            
        }
        
        $this->template->write('title', 'OQIS | Sampling PPM Reports');
        $this->template->write_view('content', 'reports/sampling_ppm_report', $data);
        $this->template->render();
    }
    
    public function serial_nos() {
        $data = array();
        $this->load->model('Audit_model');
        $data['models'] = $this->Audit_model->get_all_audit_models();
        
        $this->load->model('Inspection_model');
        if($this->product_id) {
            $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id, 0, null);
        } else {
            $this->load->model('Product_model');
            $data['inspections'] = $this->Inspection_model->get_all_inspections();
            $data['products'] = $this->Product_model->get_all_products();
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $start_range = $post_data['start_range'];
            $end_range = $post_data['end_range'];
            if($this->product_id) {
                $post_data['product_id'] = $this->product_id;
            }
            
            $this->load->model('Report_model');
            $data['serial_nos'] = $this->Report_model->get_serial_no_report($start_range, $end_range, $post_data);
        }

        $this->template->write('title', 'OQIS | Serial Number');
        $this->template->write_view('content', 'reports/serial_nos_reports', $data);
        $this->template->render();
    }
    
    public function mpat_status() {
        $this->load->model('Audit_model');
        $data['audits'] = $this->Audit_model->get_all_running_audits('interval');

        $this->template->write('title', 'OQIS | MPAT Inspection Status');
        $this->template->write_view('content', 'reports/mpat_status', $data);
        $this->template->render();
    }
    
    public function completed_inspections() {
        if(!$this->session->userdata('is_super_admin')) {
            $this->load->model('Product_model');
            $product = $this->Product_model->get_product($this->product_id);
            
            if($this->id != $product['checked_by'] && $this->id != $product['approved_by']) {
                $this->session->set_flashdata('error', 'Access Denied.');
                redirect(base_url());
            }
        }
        
        $data = array();

        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id, 0, null);

        if($this->input->post()) {
            $post_data = $this->input->post();
            $start_range = $post_data['start_range'];
            $end_range = $post_data['end_range'];
            $inspection_id = $post_data['inspection_id'];
            
            $this->load->model('Report_model');
            $data['audits'] = $this->Report_model->get_day_completed_inspection($start_range, $end_range, $this->product_id, $inspection_id);
        }
        
        $this->template->write('title', 'OQIS | Completed Inspections');
        $this->template->write_view('content', 'reports/completed_inspections', $data);
        $this->template->render();
    }
    
    public function completed_inspection_details() {
        $data = array();

        if(!$this->session->userdata('is_super_admin')) {
            $this->load->model('Product_model');
            $product = $this->Product_model->get_product($this->product_id);
            
            if($this->id != $product['checked_by'] && $this->id != $product['approved_by']) {
                $this->session->set_flashdata('error', 'Access Denied.');
                redirect(base_url());
            }
            
            $data['product'] = $product;
        }
        
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id, 0, null);
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $date = $post_data['date'];
            $inspection_id = $post_data['inspection_id'];
        } else {
            $post_data = $this->input->get();
            $date = $post_data['date'];
            $inspection_id = $post_data['insp'];
        }
        
        if(empty($date) || empty($inspection_id)) {
            redirect(base_url().'reports/completed_inspections');
        }
        $data['date'] = $date;

        $insp = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($insp)) {
            redirect(base_url().'reports/completed_inspections');
        }
        $data['insp'] = $insp;

        $this->load->model('Report_model');
        $data['audits'] = $this->Report_model->get_day_completed_inspection_details($date, $this->product_id, $inspection_id);
        $data['status'] = $this->Report_model->get_approved_checked($date, $inspection_id);

        $this->template->write('title', 'OQIS | Completed Inspection Details');
        $this->template->write_view('content', 'reports/completed_inspection_details', $data);
        $this->template->render();
    }
    
    public function status_progress() {
        if(!$this->session->userdata('is_super_admin')) {
            $this->load->model('Product_model');
            $product = $this->Product_model->get_product($this->product_id);
            
            if($this->id != $product['checked_by'] && $this->id != $product['approved_by']) {
                $this->session->set_flashdata('error', 'Access Denied.');
                redirect(base_url());
            }
        }

        if(!$this->input->get('status') || !$this->input->get('date') || !$this->input->get('insp')) {
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        $status = $this->input->get('status');
        $date = $this->input->get('date');
        $insp = $this->input->get('insp');
        
        $this->load->model('Report_model');
        $is_complete = $this->Report_model->get_day_completed_inspection($date, '', $this->product_id, $insp);
        if(!$is_complete) {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $exists = $this->Report_model->get_approved_checked($date, $insp);
        $id = isset($exists['id']) ? $exists['id'] : '';
        
        $up_data = array();
        $up_data['date'] = $date;
        $up_data['inspection_id'] = $insp;
        if($status === 'Approve') {
            $up_data['approved_by'] = $this->id;
            $up_data['approved_datetime'] = date("Y-m-d H:i:s");
        } else {
            $up_data['checked_by'] = $this->id;
            $up_data['checked_datetime'] = date("Y-m-d H:i:s");
        }
        
        $response = $this->Report_model->add_to_approve_check($up_data, $id);
        if($response) {
            $this->session->set_flashdata('success', 'Status successfully updated.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, Please try again.');
        }

        redirect(base_url().'reports/completed_inspection_details?date='.$date.'&insp='.$insp);
    }
    
    public function product_status() {
        if(!$this->session->userdata('is_super_admin')) {
            $this->session->set_flashdata('error', 'Access Denied.');
            redirect(base_url());
        }
        
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_all_products();
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $start_range = $post_data['start_range'];
            $end_range = $post_data['end_range'];
            $product_id = $post_data['product_id'];
            
            $months = $this->get_days_for_range($start_range, $end_range);
            $months = array_fill_keys(($months), '');
            
            $this->load->model('Report_model');
            $raw_data = $this->Report_model->get_product_status_report($start_range, $end_range, $product_id);
            
            $reports = array();
            foreach($raw_data as $raw) {
                if(!isset($reports[$raw['inspection_id']])) {
                    $reports[$raw['inspection_id']] = array_merge(array('inspection_name' => $raw['inspection_name']), $months);
                }
                
                $str = '';
                if(!empty($raw['checked_by'])) {
                    $str .= 'Checked';
                }
                if(!empty($raw['approved_by'])) {
                    $str .= 'Approved';
                }
                
                $reports[$raw['inspection_id']][$raw['date']] = $str;
            }
            
            $data['reports'] = $reports;
            $data['months'] = $months;
        }
        
        $this->template->write('title', 'OQIS | Product Status');
        $this->template->write_view('content', 'reports/product_status', $data);
        $this->template->render();
    }
    
    private function create_excel($audit_headers, $audit, $headers, $wo_header, $result_header, $sub_headers, $checkpoints, $name) {
        //$this->pr($sub_headers);
        $static_headers = 9;
        $serial_nos = (int)(count($headers)-9)/3;

        $this->load->library('excel');
        $this->config->load('excel');
        $row = 1;
        $last_letter =  PHPExcel_Cell::stringFromColumnIndex(count($headers)-1);
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getDefaultStyle()->applyFromArray($this->config->item('defaultStyle'));
        $this->excel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        $this->excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
        

        $last_letter =  PHPExcel_Cell::stringFromColumnIndex(count($audit_headers)-1);
        $this->excel->getActiveSheet()->fromArray($audit_headers, NULL, 'A'.$row);
        $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$last_letter.$row)
                                    ->applyFromArray($this->config->item('headerStyle'));
        $row++;

        $this->excel->getActiveSheet()->fromArray($audit, NULL, 'A'.$row, true);
        $row++;
        $row++;

        $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(18);
        $last_letter =  PHPExcel_Cell::stringFromColumnIndex(count($headers)-1);
        $this->excel->getActiveSheet()->fromArray($headers, NULL, 'A'.$row);
        $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$last_letter.$row)
                                    ->applyFromArray($this->config->item('headerStyle'));

        for($i = 0; $i < $serial_nos; $i++) {
            $start_letter = PHPExcel_Cell::stringFromColumnIndex($static_headers+$i*3);
            $end_letter = PHPExcel_Cell::stringFromColumnIndex($static_headers+2+$i*3);

            $this->excel->getActiveSheet()->mergeCells($start_letter.$row.':'.$end_letter.$row);
            $this->excel->getActiveSheet()->mergeCells($start_letter.($row+1).':'.$end_letter.($row+1));
            $this->excel->getActiveSheet()->mergeCells($start_letter.($row+2).':'.$end_letter.($row+2));
        }
        
        $row++;
        $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(14);
        $last_letter =  PHPExcel_Cell::stringFromColumnIndex(count($wo_header)-1);
        $this->excel->getActiveSheet()->fromArray($wo_header, NULL, 'A'.$row);
        $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$last_letter.$row)
                                    ->applyFromArray($this->config->item('headerStyle'));
        $row++;
        
        $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(14);
        $last_letter =  PHPExcel_Cell::stringFromColumnIndex(count($result_header)-1);
        $this->excel->getActiveSheet()->fromArray($result_header, NULL, 'A'.$row);
        $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$last_letter.$row)
                                    ->applyFromArray($this->config->item('headerStyle'));
        $row++;

        $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        foreach($sub_headers as $k_sh => $v_sh) {
            if($k_sh < $static_headers) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($k_sh, 4, $v_sh);
                $this->excel->getActiveSheet()->mergeCells($alpha[$k_sh].'4:'.$alpha[$k_sh].'7');
            } else {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($k_sh, $row, $v_sh);
            }
        }

        $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$last_letter.$row)
                                    ->applyFromArray($this->config->item('headerStyle'));

        $row++;
        
        foreach($checkpoints as $checkpoint) {
            $row_data = array_values($checkpoint);

            $this->excel->getActiveSheet()->fromArray($row_data, NULL, 'A'.$row, true);
            $row++;
        }
        
        $this->excel->getActiveSheet()->getStyle('A1'.':'.$last_letter.$row)
                                    ->getAlignment()->setWrapText(true);

        $filename = $name ;

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    private function get_months_from_range($start_range, $end_range) {
        $months = array();
        
        while($start_range <= $end_range) {
            $months[] = date("M'y", strtotime($start_range));
            
            $start_range = date('Y-m-d', strtotime("+1 month", strtotime($start_range)));
        }
        
        return $months;
    }
    
    private function get_days_for_range($start_range, $end_range) {
        $days = array();
        
        while($start_range <= $end_range) {
            $days[] = date("jS M'y", strtotime($start_range));
            
            $start_range = date('Y-m-d', strtotime("+1 day", strtotime($start_range)));
        }
        
        return $days;
    }

    private function create_lar_excel($months, $reports, $name, $title) {
        $this->load->library('excel');
        $this->config->load('excel');
        $row = 1;
        
        $last_letter =  PHPExcel_Cell::stringFromColumnIndex(count($months)+1);
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getDefaultStyle()->applyFromArray($this->config->item('defaultStyle'));
        $this->excel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
        $this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
        
        $this->excel->getActiveSheet()->mergeCells('A1:B1');
        $this->excel->getActiveSheet()->setCellValue('A1', 'OQA LAR (%)');
        
        $this->excel->getActiveSheet()->fromArray(array_keys($months), NULL, 'C'.$row);
        $row++;
        
        foreach($reports as $report) {
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':A'.($row+2));
            $this->excel->getActiveSheet()->setCellValue('A'.$row, $report['product_name']);

            $this->excel->getActiveSheet()->fromArray(array_values($report['defect']), NULL, 'B'.$row);

            $row++;
            $this->excel->getActiveSheet()->fromArray(array_values($report['qty']), NULL, 'B'.$row);

            $row++;
            $this->excel->getActiveSheet()->fromArray(array_values($report['perc']), NULL, 'B'.$row);
            $this->excel->getActiveSheet()->getStyle('B'.$row.':'.$last_letter.$row)
                            ->applyFromArray($this->config->item('warningRow'));
            
            $row++;
        }
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$name.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
}