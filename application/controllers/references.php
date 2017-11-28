<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class References extends Admin_Controller {

    public function __construct() {
        parent::__construct(true, 'Admin');
        
        //render template
        $this->template->write('title', 'OQIS | '.$this->user_type.' Dashboard');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
    }
    
    public function index() {      
        $data = array();
        $this->load->model('Reference_model');
		$data['product_id'] = $this->product_id;
        $data['references'] = $this->Reference_model->get_all_references();
        $this->template->write_view('content', 'references/index', $data);
        $this->template->render();
    }
    
    public function add_reference_new($reference_id = '') {
        
        ini_set("memory_limit","10M"); 
        $data = array();
        $this->load->model('Reference_model');
        $this->load->model('Product_model');
        
        if(!empty($reference_id)) {
            $reference = $this->Reference_model->get_reference($reference_id);
            if(empty($reference))
                redirect(base_url().'references');

            $data['reference'] = $reference;
        }
        
        $tool = isset($reference['tool']) ? $reference['tool'] : null;

        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id);
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        $data['model_suffixs'] = $this->Product_model->get_all_model_suffixs($this->product_id);
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['product_id'] = $this->product_id;
            
            if(empty($post_data['mandatory'])) {
                $post_data['mandatory'] = 0;
            }
            
            if(isset($post_data['tool']) && $post_data['tool'] == 'All') {
                unset($post_data['tool']);
            }
            
            if(empty($post_data['inspection_id']) || $post_data['inspection_id'] == 'All') {
                unset($post_data['inspection_id']);
            }
            
            if(isset($post_data['model_suffix']) && $post_data['model_suffix'] == 'All') {
                unset($post_data['model_suffix']);
            }
             
            if(!empty($_FILES['reference_file']['name'])) {
                //$f_type = 'pdf|xls|xlsx|ppt|pptx|jpeg|jpg|png';
                $f_type = '*';
                $output = $this->upload_file('reference_file', str_replace(' ', '-', $post_data['name']), "assets/references/", $f_type);

                if($output['status'] == 'success') {
                    $post_data['reference_file'] = $output['file'];
                } else {
                    $data['error'] = $output['error'];
                }

            }

            if(!isset($data['error'])) {
                $id = $this->Reference_model->update_reference($post_data, $reference_id);
            
                if($id) {
                    $this->session->set_flashdata('success', 'Reference Link successfully '.(($reference_id) ? 'updated' : 'added').'.');
                    redirect(base_url().'references');
                } else {
                    $data['error'] = 'Something went wrong, Please try again';
                }
            }
        }
        
        $this->template->write_view('content', 'references/add_reference', $data);
        $this->template->render();
    }
    
    public function add_reference($reference_id = '') {
        $data = array();
        $this->load->model('Reference_model');
        $this->load->model('Product_model');
        
        if(!empty($reference_id)) {
            $reference = $this->Reference_model->get_reference($reference_id);
            if(empty($reference))
                redirect(base_url().'references');

            $data['reference'] = $reference;
        }
        
        $tool = isset($reference['tool']) ? $reference['tool'] : null;

        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id);
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        $data['model_suffixs'] = $this->Product_model->get_all_suffixs($this->product_id, $tool);
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['product_id'] = $this->product_id;
            //print_r($post_data['model_suffix']);exit;
            if(empty($post_data['mandatory'])) {
                $post_data['mandatory'] = 0;
            }
            
            if(isset($post_data['tool']) && $post_data['tool'] == 'All') {
                unset($post_data['tool']);
            }
            
            if(empty($post_data['inspection_id']) || $post_data['inspection_id'] == 'All') {
                unset($post_data['inspection_id']);
            }
            
            if(isset($post_data['model_suffix']) && $post_data['model_suffix'] == 'All') {
                unset($post_data['model_suffix']);
            }
             
            if(!empty($_FILES['reference_file']['name'])) {
                //$f_type = 'pdf|xls|xlsx|ppt|pptx|jpeg|jpg|png';
                $f_type = '*';
                $output = $this->upload_file('reference_file', str_replace(' ', '-', $post_data['name']), "assets/references/", $f_type);

                if($output['status'] == 'success') {
                    $post_data['reference_file'] = $output['file'];
                } else {
                    $data['error'] = $output['error'];
                }

            }
           if(!isset($data['error'])) {
			   /*  if(count($post_data['model_suffix']) > 1){
					// $post_data['model_suffix'] = implode(',',$post_data['model_suffix']);
					$id = $this->Reference_model->update_reference($post_data, $reference_id);
			    } */
				// print_r($post_data);exit;
				$sel_model_suffix = $post_data['model_suffix'];
				foreach($sel_model_suffix as $ms){
					$post_data['model_suffix'] = $ms;
					$id = $this->Reference_model->update_reference($post_data, $reference_id);
				}
                if($id) {
                    $this->session->set_flashdata('success', 'Reference Link successfully '.(($reference_id) ? 'updated' : 'added').'.');
                    redirect(base_url().'references');
                } else {
                    $data['error'] = 'Something went wrong, Please try again';
                }
            }
        }
        
        $this->template->write_view('content', 'references/add_reference', $data);
        $this->template->render();
    }
    
    public function checkpoint_configs() {
        $this->load->model('Reference_model');
        $data['checkpoint_configs'] = $this->Reference_model->get_all_checkpoint_configs();

        $this->template->write_view('content', 'references/checkpoint_configs', $data);
        $this->template->render();
    }
    
    public function checkpoint_config_form($id = '') {
        if(!$this->product_id){
            redirect(base_url());
        }
        $product_id = $this->product_id;
        
        $data = array();
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections();
        
        $this->load->model('Reference_model');
        $data['links'] = $this->Reference_model->get_all_links($product_id);
        
        if(!empty($id)) {
            $checkpoint_config = $this->Reference_model->get_checkpoint_config($id);
            if(empty($checkpoint_config))
                redirect(base_url().'references/checkpoint_configs');

            $data['checkpoint_config'] = $checkpoint_config;
            
            $data['checkpoints'] = $this->Inspection_model->get_inspection_checkpoints($checkpoint_config['inspection_id']);
        }
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('inspection_id', 'Inspection', 'trim|required|xss_clean');
            $validate->set_rules('reference_link', 'Reference Link', 'trim|required|xss_clean');
            $validate->set_rules('checkpoints_nos', 'Checkpoint Nos', 'required|xss_clean');
            
            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                $post_data['checkpoints_nos'] = implode(',', $post_data['checkpoints_nos']);
                
                $exists = $this->Reference_model->checkpoint_config_exists($this->input->post('inspection_id'), $this->input->post('reference_link'), $id);
                if(!$exists) {

                    $config_id = $this->Reference_model->update_checkpoint_config($post_data, $id);
                    if($config_id) {
                        $this->session->set_flashdata('success', 'Record successfully '.(($config_id) ? 'updated' : 'added').'.');
                        redirect(base_url().'references/checkpoint_configs');
                    } else {
                        $data['error'] = 'Something went wrong, Please try again';
                    }

                } else {
                    $data['error'] = 'Record already exists for this Inspection and Link.';
                }
            } else {
                $data['error'] = validation_errors();
            }
        }
        
        $this->template->write_view('content', 'references/checkpoint_config_form', $data);
        $this->template->render();
    }
    
    public function delete_checkpoint_config($id) {
        $this->load->model('Reference_model');
        $checkpoint_config = $this->Reference_model->get_checkpoint_config($id);
        if(empty($checkpoint_config))
            redirect(base_url().'references/checkpoint_configs');

        $deleted = $this->Reference_model->delete_checkpoint_config($id);

        if($deleted) {
            $this->session->set_flashdata('success', 'Record successfully deleted.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'references/checkpoint_configs');
    }
    
    public function download_references() {
        $data = array();
        $this->load->model('Reference_model');
        
        $references = $this->Reference_model->get_all_references();
        //echo "<pre>";print_r($references);exit;
        
        $this->load->library('excel');
        $this->config->load('excel');
        $row = 1;
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getDefaultStyle()->applyFromArray($this->config->item('defaultStyle'));
        $this->excel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(18);
        $this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
        
        $this->excel->getActiveSheet()->setCellValue('A1', 'Name');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Inspection');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Tool');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Model.Suffix');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Reference File');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Reference URL');
        $this->excel->getActiveSheet()->setCellValue('G1', 'Mandatory');
        
        $this->excel->getActiveSheet()->getStyle('A1:'.'G1')
                            ->applyFromArray($this->config->item('headerStyle'));
        
        $row++;
        foreach($references as $reference) {
            $this->excel->getActiveSheet()->setCellValue('A'.$row, $reference['name']);
            $this->excel->getActiveSheet()->setCellValue('B'.$row, (!empty($reference['inspection_id']) ? $reference['inspection_name'] : 'All'));
            $this->excel->getActiveSheet()->setCellValue('C'.$row, (!empty($reference['tool']) ? $reference['tool'] : 'All'));
            $this->excel->getActiveSheet()->setCellValue('D'.$row, (!empty($reference['model_suffix']) ? $reference['model_suffix'] : 'All'));
            $this->excel->getActiveSheet()->setCellValue('E'.$row, (!empty($reference['reference_file']) ? base_url().$reference['reference_file'] : ''));
            $this->excel->getActiveSheet()->setCellValue('F'.$row, $reference['reference_url']);
            $this->excel->getActiveSheet()->setCellValue('G'.$row, ($reference['mandatory'] ? 'Yes' : 'No'));
            
            $row++;
        }
        
        $name = 'References.xls';
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$name.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    public function delete_reference($reference_id) {
        $this->load->model('Reference_model');
        $reference = $this->Reference_model->get_reference($reference_id);
        if(empty($reference))
            redirect(base_url().'references');

        $deleted = $this->Reference_model->delete_reference($reference_id);

        if($deleted) {
            $this->session->set_flashdata('success', 'Record successfully deleted.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'references');
    }

    public function get_inspection_checkpoints() {
        if(!$this->input->post('inspection_id')) {
            return false;
        }
        
        $inspection_id = $this->input->post('inspection_id');
        $this->load->model('Inspection_model');
        
        if($this->input->post('existing')) {
            $inspection = $this->Inspection_model->get_excluded_checkpoint($this->input->post('existing'));
        } else {
            $inspection = $this->Inspection_model->get_inspection($inspection_id);
        }
        
        $checkpoints = $this->Inspection_model->get_inspection_checkpoints($inspection_id);
        
        $data = array(
            'inspection' => $inspection,
            'checkpoints' => $checkpoints
        );
        echo $this->load->view('references/checkpoints_config_ajax', $data, TRUE);
    }
	public function upload_reference() {
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
        //echo $_FILES['references_excel']['name'];exit;
            if(!empty($_FILES['references_excel']['name'])) {
                $output = $this->upload_file('references_excel', 'references', "assets/references/");
                if($output['status'] == 'success') {
                    $res = $this->parse_references($product_id, $output['file']);
                    $this->session->set_flashdata('success', 'Reference Links successfully uploaded.');
                    redirect(base_url().'references');
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'references/upload_reference', $data);
        $this->template->render();
    }
	
	 
    private function parse_references($product_id, $file_name) {
        $this->load->library('excel');
		$this->load->model('Inspection_model');
		$this->load->model('Product_model');

        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        
        $references = array();
        foreach($arr as $no => $row) {
            $temp = array();
            if($no == 1)
                continue;
            
            if(!trim($row['A']))
                continue;
            
			if(!empty(trim($row['C']))){
				if(trim($row['C']) != 'All')
				{
					$inspection_id = $this->Inspection_model->get_inspection_by_name(trim($row['C']));
					$insp_id = $inspection_id['id'];
				}
			}
            else
				continue;
			
			if(!empty(trim($row['D']))){
				if(trim($row['D']) != 'All'){
					$tools = $this->Product_model->get_tool($this->product_id, trim($row['D']));
					if(!empty($tools))
						$tool = $row['D'];
					else
						continue;
				}
			}
			else
				continue;
			//$non_exist = 0;
			if(!empty(trim($row['E']))){
				if(trim($row['E']) != 'All'){
					$suffix = $this->Product_model->get_suffix($this->product_id, trim($row['E']));
					if(!empty($suffix))
						$model_suffix = $row['E'];
					else
						continue;
				}
			}
			else
				continue;

			if(count($model_suffix) > 1){
				$model_suffix = implode(',',$model_suffix);			
			}
			
			$temp['product_id']     = $product_id;
			$temp['inspection_id']  = $insp_id;
			$temp['tool']  			= $tool;
			$temp['model_suffix']  	= $model_suffix;
            $temp['name']           = $row['B'];
            $temp['reference_file'] = $row['F'];
            $temp['reference_url']  = $row['G'];
            $temp['mandatory']      = $row['H'];	//	0 or 1
            $temp['created']        = date("Y-m-d H:i:s");
			
			
			/* ";//print_r($row);exit;
			print_r($temp);
			exit; */
			
			$references[]        = $temp;
        }
        //echo "<pre>";print_r($references);
		
        $this->load->model('Reference_model');
        $this->Reference_model->insert_references($references, $product_id);
        
        return TRUE;
    }
   
}