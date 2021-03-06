<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function __construct() {
        parent::__construct(true);

        $page = '';
        if($this->router->fetch_method() == 'realtime') {
            $page = 'realtime';
        }
        //render template
        $this->template->write('title', 'OQIS | '.$this->user_type.' Dashboard');
        $this->template->write_view('header', 'templates/header', array('page' => $page));
        $this->template->write_view('footer', 'templates/footer');

    }

    public function index() {
        if(!$this->session->userdata('dashboard_date')) {
            $this->session->set_userdata('dashboard_date', date('Y-m-d'));
        }

        if($this->user_type == 'Admin') {
            $data = $this->admin_dashboard();
            //render template
            $this->template->write_view('content', 'admin_dashboard', $data);
            $this->template->render();
        } else if($this->user_type == 'Audit') {
            $data = $this->auditer_dashboard();
            //render template
            $this->template->write_view('content', 'auditer_dashboard', $data);
            $this->template->render();

        } else if($this->user_type == 'Dashboard') {
            $data = $this->dashboard();
            //render template
            $this->template->write_view('content', 'dashboard', $data);
            $this->template->render();

        }
    }
    
    public function show_day_progress() {
        $data = array();
        $data['sampling_plan'] = $this->display_day_progress($this->session->userdata('dashboard_date'));
        
        echo $this->load->view('day_progress', $data, true);
    }
    
    public function export_excel() {
        $data = array();
        $data['sampling_plan'] = $this->display_day_progress($this->session->userdata('dashboard_date'));
        $data['export'] = true;
        
        $str = $this->load->view('day_progress', $data, true);
        
        header('Content-Type: application/force-download');
        header('Content-disposition: attachment; filename=Day_Progress_'.$this->session->userdata('dashboard_date').'.xls');
        // Fix for crappy IE bug in download.
        header("Pragma: ");
        header("Cache-Control: ");
        echo $str;
    }
    
    public function realtime() {
        $this->is_dashboard_user();
        $data = array();
        $this->load->model('Sampling_model');
        
        $data['sampling_plan'] = $this->display_day_progress(date('Y-m-d'));
        
        if (!$this->input->is_ajax_request()) {
            //render template
            $this->template->write_view('content', 'realtime_dashboard', $data);
            $this->template->render();
        } else {
            $data['sampling_plan'] = $this->Sampling_model->get_day_progress(date('Y-m-d'));
            $this->load->view('realtime_dashboard_ajax', $data);
        }
    }
    
    public function set_dashboard_date($date) {
        $this->session->set_userdata('dashboard_date', $date);
        
        redirect(base_url());
    }

    public function show_day_progress_initiator() {
        $data = array();
        $data['sampling_plan'] = $this->display_day_progress($this->session->userdata('initiator_date'));
        
        echo $this->load->view('day_progress_initiator', $data, true);
    }

    public function set_initiator_date($date) {
        $this->session->set_userdata('initiator_date', $date);
        
        redirect(base_url()."dashboard/initiator");
    }
    
    public function notification_action($id, $audit_flag = false) {
        if($audit_flag != 'direct') {
            $redirect_url = base_url();
        } else {
            $redirect_url = base_url().'dashboard/na_checkpoints';
        }
        
        $status = $this->input->get('status');
        if(empty($status)) {
            redirect($redirect_url);
        }
        
        $this->load->model('Audit_model');
        if($audit_flag != 'direct') {
            $notification = $this->Audit_model->check_notifications($this->product_id, $id);
        } else {
            $notification = $this->Audit_model->pending_checkpoints($this->product_id, $id);
        }
        
        if(!count($notification)) {
            redirect($redirect_url);
        }
        
        $notification = $notification[0];
        
        $na_approved = 0;
        if(strpos($status, 'approve') === 0) {
            $na_approved = 1;
        } else if($status == 'decline') {
            $na_approved = 2;
        }
        
        $noti_data = array();
        $noti_data['action_by'] = $this->id;
        $noti_data['action_datetime'] = date('Y-m-d H:i:s');
        
        $this->Audit_model->add_notification($noti_data, $id);
        $this->Audit_model->change_na_checkpoint_status($na_approved, $notification['audit_checkpoint_id'], $notification['audit_id']);
        
        if($status == 'approve_always') {
            $this->load->model('Inspection_model');
            $checkpoint = $this->Inspection_model->get_checkpoint_by_inspection_no($notification['inspection_id'], $notification['checkpoint_no']);
            if(!empty($checkpoint)) {

                $exists = $this->Inspection_model->excluded_checkpoint_exists($notification['inspection_id'], $notification['model_suffix'], '');
                if(!$exists) {

                    $post_data = array();
                    $post_data['model'] = $notification['model_suffix'];
                    $post_data['inspection_id'] = $notification['inspection_id'];
                    $post_data['checkpoints_nos'] = $checkpoint['id'];
                    $excluded_id = $this->Inspection_model->update_excluded_checkpoints($post_data, '');
                } else {

                    $checkpoints_nos = $exists['checkpoints_nos'];
                    $check = explode(',', $checkpoints_nos);
                    
                    if(!in_array($checkpoint['id'], $check)) {
                        $checkpoints_nos = $checkpoints_nos.','.$checkpoint['id'];
                        $post_data['checkpoints_nos'] = $checkpoints_nos;
                        
                        $excluded_id = $this->Inspection_model->update_excluded_checkpoints($post_data, $exists['id']);
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Something went wrong please try again.');
                redirect($redirect_url);
            }
        }
        
        $this->session->set_flashdata('success', 'Notification successfully updated');
        
        redirect($redirect_url);
    }
    
    public function na_checkpoints() {
		$this->load->model('Inspection_model');
        $data = array();
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id, 0, null);
        
        $this->load->model('Audit_model');
		$data['models'] = $this->Audit_model->get_all_audit_models();
	    $data['insp_type'] = array(
								'regular'=>array('type'=>'Regular'),
								'interval'=>array('type'=>'Interval')
							);
        $this->load->model('Product_model');
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        $data['lines'] = $this->Product_model->get_all_product_lines($this->product_id);
        
        $filters = $this->input->post() ? $this->input->post() : array();
        $filters = array_filter($filters);
        $_SESSION['audit_checkpoints_filters'] = $filters;
		//if(count($filters) > 1)  
			$model_s = $this->input->post('model_suffix');
			$data['selected_model'] = $model_s;
            
			$data['audit_checkpoints'] = $this->Audit_model->pending_checkpoints_new($filters , $this->product_id);

        $this->template->write_view('content', 'na_checkpoints', $data);
        $this->template->render();
    }
    
    public function status($status) {
        $dashboard_date = $this->session->userdata('dashboard_date');
        
        $update_data = array();
        $update_data['status'] = $status;
        $update_data['product_id'] = $this->product_id;
        $update_data['user_id'] = $this->id;
        $update_data['dashboard_date'] = $dashboard_date;
        
        $this->load->model('Sampling_model');
        $exists = $this->Sampling_model->get_dashboard_status($dashboard_date);
        if($exists) {
            $this->Sampling_model->update_dashboard_status($update_data, $dashboard_date);
        } else {
            $this->Sampling_model->update_dashboard_status($update_data, '');
        }
        
        redirect(base_url());
    }
    
    private function auditer_dashboard() {
        $data = array();
        $this->load->model('Audit_model');
        $this->load->model('Sampling_model');
        
        $data['on_going'] = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'));
        $data['on_holds'] = $this->Audit_model->get_on_hold_audits($this->id);
        
        return $data;
    }
    
    private function admin_dashboard() {
        $data = array();
        $this->load->model('Sampling_model');
        $exists = $this->Sampling_model->get_dashboard_status($this->session->userdata('dashboard_date'));
        $data['exists'] = $exists;
        
        $this->load->model('Audit_model');
        
        $data['on_going'] = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'));
        $data['on_holds'] = $this->Audit_model->get_on_hold_audits($this->id);
        
        $data['abort_requests'] = $this->Audit_model->pending_abort_requests($this->product_id);
        $data['on_hold_counts'] = $this->Audit_model->user_wise_on_hold_count();
        $data['inprocess_insp_counts'] = $this->Audit_model->user_wise_inprocess_insp_count();
        $data['pending_counts'] = $this->pending_counts();
        
        return $data;
    }

    public function initiator() {
        $data = array();
        //$data = $this->auditer_dashboard();

        if(!$this->session->userdata('initiator_date')){

            $this->session->set_userdata('initiator_date', date('Y-m-d'));
        }

        $data['date'] = $this->session->userdata('initiator_date');
        
        $this->template->write_view('content', 'dashboard/initiator', $data);
        $this->template->render();
        
        return $data;
    }
    
	private function get_days_for_range($start_range, $end_range) {
        $days = array();
        
        while($start_range <= $end_range) {
            $days[] = date("jS M'y", strtotime($start_range));
            
            $start_range = date('Y-m-d', strtotime("+1 day", strtotime($start_range)));
        }
        
        return $days;
    }
    private function pending_counts() {
		$this->load->model('Audit_model');
	    $start_range = date('Y-m-01');//,time()-60*60*24
        $end_range = date('Y-m-d',time()-60*60*24);
       // $inspection_id = $post_data['inspection_id'];

        $counts = $this->Audit_model->get_pending_audits_count($start_range, $end_range, $this->product_id);
        //echo "<pre>";echo $this->db->last_query();exit;
		//echo 'Counts=><pre>';print_r($counts);
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
            if($count['insp_type'] == 'regular'){
				$reports[$count['inspection_id']]['insp_type'] = 'Regular';
			}
            if($count['insp_type'] == 'interval'){
				$reports[$count['inspection_id']]['insp_type'] = 'Interval';
            }
         
			$reports[$count['inspection_id']][$count['sampling_date']] = ($pending < 0) ? 0 : $pending;
            
            $totals[$count['sampling_date']] += $pending;
        }
        
        //$reports[] = $totals;
        return $reports;
        
        //echo "reports=><pre>";print_r($reports);exit;
	}
    private function dashboard() {
        $data = array();
        $this->load->model('Audit_model');
        $data['audits'] = array();
        
        $this->load->model('Sampling_model');
        $data['sampling_plan'] = $this->display_day_progress(date('Y-m-d'));

        $data['sampling_plan_yesterday'] = $this->display_day_progress(date('Y-m-d', strtotime("-1 day")));
        
        return $data;
    }

    public function view_notification($id, $audit_flag = '') {
        $this->load->model('Audit_model');
        
        if($audit_flag != 'direct') {
            $notification = $this->Audit_model->check_notifications($this->product_id, $id);
        } else {
            $notification = $this->Audit_model->pending_checkpoints($this->product_id, $id);
        }
        
        if(count($notification)) {
            $notification = $notification[0];
        }
        $data['notification'] = $notification;
        $data['audit_flag'] = $audit_flag;
        
        echo $this->load->view('notifications/view_notification_ajax', $data);
    }

    public function abort_status($audit_id, $status) {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->pending_abort_requests($this->product_id, $audit_id);
        if(empty($audit)) {
            redirect(base_url());
        }
        
        if($status == 'Approve') {
            $response = $this->Audit_model->change_state($audit['id'], $audit['auditer_id'], 'aborted');
        } else if($status == 'Reject') {
            $audit_up_data                              = array();
            $audit_up_data['abort_requested']           = 0;
            
            $response = $this->Audit_model->update_audit($audit_up_data, $audit['id']);
        }
        
        if($response) {
            $this->session->set_flashdata('success', 'Inspection '.$status.' successfully.');
        } else {
            $this->session->set_flashdata('errpr', 'Something went wrong please try again.');
        }
        
        redirect(base_url());
    }
}