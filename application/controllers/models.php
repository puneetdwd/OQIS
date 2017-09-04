<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends Admin_Controller {
        
    public function __construct() {
        parent::__construct(true, 'Admin');

        //render template
        $this->template->write('title', 'OQIS | '.$this->user_type.' Dashboard');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');

    }
        
    public function index() {
        if($this->product_id) {
            redirect(base_url());
        }
        
        $data = array();
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_all_products();

        $this->template->write_view('content', 'products/index', $data);
        $this->template->render();
    }
    
    public function add_product($product_id = '') {
        if($this->product_id) {
            redirect(base_url());
        }
        
        $data = array();
        $this->load->model('Product_model');
        
        if(!empty($product_id)) {
            $product = $this->Product_model->get_product($product_id);
            if(empty($product))
                redirect(base_url().'products');

            $data['product'] = $product;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            
            $response = $this->Product_model->add_product($post_data, $product_id); 
            if($response) {
                $this->session->set_flashdata('success', 'Product successfully '.($product_id) ? 'updated' : 'added' .'.');
                redirect(base_url().'products');
            } else {
                $data['error'] = 'Something went wrong, Please try again';
            }
        }
        
        $this->template->write_view('content', 'products/add_product', $data);
        $this->template->render();
    }
    
    public function lines($product_id = '') {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $data = array();
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        
        $data['lines'] = $this->Product_model->get_all_product_lines($product_id);

        $this->template->write_view('content', 'products/lines', $data);
        $this->template->render();
    }
    
    public function add_product_line($product_id, $line_id = '') {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        
        if(!empty($line_id)) {
            $line = $this->Product_model->get_product_line($product_id, $line_id);
            if(empty($line))
                redirect(base_url().'products/lines/'.$product['id']);

            $data['line'] = $line;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['product_id'] = $product['id'];
            
            $response = $this->Product_model->update_product_line($post_data, $line_id); 
            if($response) {
                $this->session->set_flashdata('success', 'Product line successfully '.(($line_id) ? 'updated' : 'added').'.');
                redirect(base_url().'products/lines/'.$product_id);
            } else {
                $data['error'] = 'Something went wrong, Please try again';
            }
        }
        
        $this->template->write_view('content', 'products/add_product_line', $data);
        $this->template->render();
    }

    public function delete_product_line($product_id, $line_id) {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $this->load->model('Product_model');

        $line = $this->Product_model->get_product_line($product_id, $line_id);
        if(empty($line))
            redirect(base_url().'products/lines/'.$product['id']);
            
        $deleted = $this->Product_model->update_product_line(array('is_deleted' => 1), $line_id); 
        if($deleted) {
            $this->session->set_flashdata('success', 'Product Line deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'products/lines/'.$product_id);
    }
}