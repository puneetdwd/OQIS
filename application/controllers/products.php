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
        if(!$this->session->userdata('is_super_admin')) {
            redirect(base_url());
        }
        
        $data = array();
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_all_products_full();

        $this->template->write_view('content', 'products/index', $data);
        $this->template->render();
    }
    
    public function add_product($product_id = '') {
        if(!$this->session->userdata('is_super_admin')) {
            redirect(base_url());
        }
        
        $data = array();
        $this->load->model('Product_model');
        $this->load->model('User_model');
        $data['users'] = $this->User_model->get_all_users();
        
        if(!empty($product_id)) {
            $product = $this->Product_model->get_product($product_id);
            if(empty($product))
                redirect(base_url().'products');

            $data['product'] = $product;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            if(!empty($post_data['dir_path'])) {
                $post_data['dir_path'] = ($post_data['dir_path'][strlen($post_data['dir_path'])-1] == '\\' ? $post_data['dir_path'] : $post_data['dir_path'].'\\');
            }
            
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
        if($this->product_id != '') {
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

    public function model_suffixs($product_id = '') {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $data = array();
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        
        $data['model_suffixs'] = $this->Product_model->get_all_model_suffixs($product_id);

        $this->template->write_view('content', 'products/model_suffixs', $data);
        $this->template->render();
    }
    
    public function download_model_suffixs() {
        $data = array();
        $this->load->model('Product_model');
        
        $model_suffixs = $this->Product_model->get_all_model_suffixs($this->product_id);
        
        $this->load->library('excel');
        $this->config->load('excel');
        $row = 1;
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getDefaultStyle()->applyFromArray($this->config->item('defaultStyle'));
        $this->excel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(22);
        $this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
        
        $this->excel->getActiveSheet()->setCellValue('A1', 'Model.Suffix');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Tool');
        
        $row++;
        foreach($model_suffixs as $model_suffix) {
            $this->excel->getActiveSheet()->setCellValue('A'.$row, $model_suffix['model_suffix']);
            $this->excel->getActiveSheet()->setCellValue('B'.$row, $model_suffix['tool']);
            
            $row++;
        }
        
        $name = 'Model_Suffixs.xls';
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$name.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    public function upload_model_suffixs($product_id) {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($product_id);
        if(empty($product))
            redirect(base_url().'products');
        
        $data['product'] = $product;
        
        if($this->input->post()) {
             
            if(!empty($_FILES['model_suffixs_excel']['name'])) {
                $output = $this->upload_file('model_suffixs_excel', 'model_suffixs', "assets/model_suffixs/");

                if($output['status'] == 'success') {
                    $res = $this->parse_model_suffixs($product_id, $output['file']);
                    $this->session->set_flashdata('success', 'Model.Suffixs successfully uploaded.');
                    redirect(base_url().'products/model_suffixs/'.$product_id);
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'products/upload_model_suffixs', $data);
        $this->template->render();
    }
    
    public function add_model_suffix($product_id, $model_suffix_id = '') {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        
        if(!empty($model_suffix_id)) {
            $model_suffix = $this->Product_model->get_model_suffix($product_id, $model_suffix_id);
            if(empty($model_suffix))
                redirect(base_url().'products/model_suffixs/'.$product['id']);

            $data['model_suffix'] = $model_suffix;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['product_id'] = $product['id'];
            
            $response = $this->Product_model->update_model_suffix($post_data, $model_suffix_id); 
            if($response) {
                $this->Product_model->remove_dups_model_suffixs($product_id);
                $this->session->set_flashdata('success', 'Product line successfully '.(($model_suffix_id) ? 'updated' : 'added').'.');
                redirect(base_url().'products/model_suffixs/'.$product_id);
            } else {
                $data['error'] = 'Something went wrong, Please try again';
            }
        }
        
        $this->template->write_view('content', 'products/add_model_suffix', $data);
        $this->template->render();
    }
    
    public function delete_model_suffix($product_id, $model_suffix_id) {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $this->load->model('Product_model');

        $model_suffix = $this->Product_model->get_model_suffix($product_id, $model_suffix_id);
        if(empty($model_suffix))
            redirect(base_url().'products/model_suffixs/'.$product['id']);
            
        $deleted = $this->Product_model->update_model_suffix(array('is_deleted' => 1), $model_suffix_id); 
        if($deleted) {
            $this->session->set_flashdata('success', 'Model Suffix deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'products/model_suffixs/'.$product_id);
    }
    
    public function delete_model_suffixs_multi() {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        if(!$this->input->post()) {
            redirect(base_url().'products/model_suffixs/'.$product_id);
        }
        
        $model_suffixs = $this->input->post('model_suffixs');
        
        $this->load->model('Product_model');
        $deleted = $this->Product_model->update_model_suffix(array('is_deleted' => 1), $model_suffixs); 
        if($deleted) {
            $this->session->set_flashdata('success', 'Deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'products/model_suffixs/'.$product_id);
    }
    
    public function phone_numbers($product_id = '') {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $data = array();
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        
        $data['phone_numbers'] = $this->Product_model->get_all_phone_numbers($product_id);

        $this->template->write_view('content', 'products/phone_numbers', $data);
        $this->template->render();
    }
    
    public function add_phone_number($product_id, $phone_number_id = '') {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        
        if(!empty($phone_number_id)) {
            $phone_number = $this->Product_model->get_phone_number($product_id, $phone_number_id);
            if(empty($phone_number))
                redirect(base_url().'products/phone_numbers/'.$product['id']);

            $data['phone_number'] = $phone_number;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['product_id'] = $product['id'];
            
            $response = $this->Product_model->update_phone_number($post_data, $phone_number_id); 
            if($response) {
                $this->session->set_flashdata('success', 'Phone Number successfully '.(($phone_number_id) ? 'updated' : 'added').'.');
                redirect(base_url().'products/phone_numbers/'.$product_id);
            } else {
                $data['error'] = 'Something went wrong, Please try again';
            }
        }
        
        $this->template->write_view('content', 'products/add_phone_number', $data);
        $this->template->render();
    }
    
    public function delete_phone_number($product_id, $phone_number_id) {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $this->load->model('Product_model');

        $phone_number = $this->Product_model->get_phone_number($product_id, $phone_number_id);
        if(empty($phone_number))
            redirect(base_url().'products/phone_numbers/'.$product['id']);
            
        $deleted = $this->Product_model->delete_phone_number($product_id, $phone_number_id); 
        if($deleted) {
            $this->session->set_flashdata('success', 'Phone Number deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'products/phone_numbers/'.$product_id);
    }
    
    public function get_all_tools() {
        $this->load->model('Product_model');
        $tools = $this->Product_model->get_all_tools($this->product_id);
        
        echo json_encode($tools);
    }
    
    public function get_models_by_tool() {
        $data = array('models' => array());
        
        if($this->input->post('tool')) {
            $this->load->model('Product_model');
            $data['models'] = $this->Product_model->get_all_suffixs($this->product_id, $this->input->post('tool'));
        }
        
        echo json_encode($data);
    }
    
    private function parse_model_suffixs($product_id, $file_name) {
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        
        $model_suffixs = array();
        foreach($arr as $no => $row) {
            if($no == 1)
                continue;
            
            if(!trim($row['A']))
                continue;
            
            $temp = array();
            $temp['product_id']     = $product_id;
            $temp['model_suffix']   = $row['A'];
            $temp['tool']           = $row['B'];
            $temp['created']        = date("Y-m-d H:i:s");
            
            $model_suffixs[]        = $temp;
        }
        //$this->print_array($model_suffixs);
        $this->load->model('Product_model');
        $this->Product_model->insert_model_suffixs($model_suffixs, $product_id);
        
        return TRUE;
    }
}