<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Color_setting extends Admin_Controller {

    public function __construct() {
        parent::__construct(true, 'Admin');
        
        //render template
        $this->template->write('title', 'OQIS | '.$this->user_type.' Color Setting');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
    }
    
    public function index() {      
        $data = array();
        $this->load->model('Color_Model');
        $data['color_setting'] = $this->Color_Model->get_color_setting();
	
		if($this->input->post()) {
			$is_color = $this->input->post('on_off_color');
			$res = $this->Color_Model->update_color_setting($is_color);
			if($res){
				$color_setting = $this->Color_Model->get_color_setting();
				if(!empty($color_setting))
				{
					$this->session->set_flashdata('success', 'Color setting successfully Updated.');
					$data['color_setting'] = $color_setting;
					redirect(base_url().'color_setting');
				}				
			}
			
		}
        $this->template->write_view('content', 'color_setting/index', $data);
        $this->template->render();
    }
}