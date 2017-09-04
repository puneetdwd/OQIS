<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shifts extends Admin_Controller {
        
    public function __construct() {
        parent::__construct(true, 'Admin');

        //render template
        $this->template->write('title', 'OQIS | '.$this->user_type.' Dashboard');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');

    }
        
    public function index() {
        $data = array();
        $this->load->model('Shift_model');
        
        $data['shifts'] = $this->Shift_model->get_all_shifts($this->product_id);

        $this->template->write_view('content', 'shifts/index', $data);
        $this->template->render();
    }
    
    public function add_shift($shift_id = '') {
        $data = array();
        
        $this->load->model('Shift_model');
        if(!empty($shift_id)) {
            $shift = $this->Shift_model->get_shift($this->product_id, $shift_id);
            if(empty($shift))
                redirect(base_url().'shifts');

            $data['shift'] = $shift;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['product_id'] = $this->product_id;
            
            $response = $this->Shift_model->update_shift($post_data, $shift_id); 
            if($response) {
                $this->session->set_flashdata('success', 'Shift successfully '.(($shift_id) ? 'updated' : 'added').'.');
                redirect(base_url().'shifts');
            } else {
                $data['error'] = 'Something went wrong, Please try again';
            }
        }
        
        $this->template->write_view('content', 'shifts/add_shift', $data);
        $this->template->render();
    }
    
    public function delete_shift($shift_id) {
        $this->load->model('Shift_model');

        $shift = $this->Shift_model->get_shift($this->product_id, $shift_id);
            if(empty($shift))
                redirect(base_url().'shifts');
            
        $deleted = $this->Shift_model->delete_shift($this->product_id, $shift_id); 
        if($deleted) {
            $this->session->set_flashdata('success', 'Shift deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'shifts');
    }

}