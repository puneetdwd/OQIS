<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday_planning extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->template->write('title', 'OQIS | '.$this->user_type.' Email Module');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
    }

    public function index() {
		//$pid = $this->product_id;
		$this->is_admin_user();
        $this->load->model('Holiday_model');
        $data['holidays'] = $this->Holiday_model->get_all_holidays();

        $this->template->write_view('content', 'holiday_planning/index', $data);
        $this->template->render();
    }

    public function add($id = '') {
		$this->is_admin_user();
        $data = array();
        $this->load->model('Holiday_model');

        if(!empty($id)) {
            $holiday = $this->Holiday_model->get_holiday($id);
            if(!$holiday)
                redirect(base_url().'holiday_planning');

            $data['holiday'] = $holiday;
        }

        if($this->input->post()) {
			//print_r($this->input->post());exit;
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('name', 'Name', 'trim|required|xss_clean');
            $validate->set_rules('holiday_date', 'Holiday', 'required');
            
			if($validate->run() === TRUE) {
                $post_data                  = $this->input->post();
                if(empty($id))
					$exists = $this->Holiday_model->is_holiday_exists($post_data['holiday_date'], $id);
				// print_r($exists);exit;
                if(!$exists){

					//$post_data['product_id'] = $pid;
                    $holiday_id = $this->Holiday_model->update_holiday($post_data, $id);
                    if($holiday_id) {

                        $this->session->set_flashdata('success', 'Holiday successfully added.');
                        redirect(base_url().'holiday_planning');
                    } else {
                        $data['error'] = 'Something went wrong, Please try again.';
                    }

                } else {
                    $data['error'] = 'Holiday data already exists for '.$post_data['holiday_date'];
                }

            } else {
                $data['error'] = validation_errors();
            }
        }

        $this->template->write_view('content', 'holiday_planning/add_holiday', $data);
        $this->template->render();
    }

    public function view($id) {
        $this->load->model('Holiday_model');
        $holiday = $this->Holiday_model->get_holiday($id);

		if(!$holiday)
			redirect(base_url().'holiday_planning');

        $data['holiday'] = $holiday;

        $this->template->write_view('content', 'holiday_planning/view_holiday', $data);
        $this->template->render();
    }

    public function delete_holiday($id) {
        $this->is_admin_user();
        $this->load->model('Holiday_model');
        $holiday = $this->Holiday_model->delete_holiday($id);
		$this->session->set_flashdata('success', 'Holiday data deleted successfully.');
		redirect(base_url().'holiday_planning');
			
    }    
}