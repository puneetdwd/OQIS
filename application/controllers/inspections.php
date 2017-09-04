<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inspections extends Admin_Controller {

    public function __construct() {
        parent::__construct(true, 'Admin');
        
        $this->template->write('title', 'OQIS | '.$this->user_type.' Dashboard');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
    }

    public function index() {
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections();

        $this->template->write_view('content', 'inspections/index', $data);
        $this->template->render();
    }

    public function add_inspection($inspection_id = '') {
        $data = array();
        $this->load->model('Inspection_model');
        
        if(!$this->product_id) {
            $this->load->model('Product_model');
            $data['products'] = $this->Product_model->get_all_products();
        }
        
        if(!empty($inspection_id)) {
            $inspection = $this->Inspection_model->get_inspection($inspection_id);
            if(empty($inspection))
                redirect(base_url().'inspections');

            if(!empty($inspection['full_auto']) && $inspection['automate_case'] == 'With Checkpoints') {
                $settings = unserialize($inspection['automate_settings']);
                
                $inspection = array_merge($inspection, $settings);
            }
            $data['inspection'] = $inspection;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            if($this->product_id) {
                $post_data['product_id'] = $this->product_id;
            }
            
            if(empty($post_data['automate_result'])) {
                $post_data['automate_result'] = 0;
            }
            
            if(empty($post_data['full_auto'])) {
                $post_data['full_auto'] = 0;
            } else {
                $post_data['automate_result'] = 0;
            }
            
            if($post_data['insp_type'] == 'interval') {
                $post_data['full_auto'] = 0;
                $post_data['automate_result'] = 0;
                $post_data['automate_case'] = null;
                
                if(!empty($post_data['attach_report'])) {
                    $post_data['inspection_duration'] = $post_data['inspection_duration'].' '.$post_data['inspection_duration_type'];
                }
            } else {
                $post_data['interval_type'] = null;
            }
            
            if(!empty($post_data['full_auto']) && $post_data['automate_case'] == 'With Checkpoints') {
                $settings = array();
                $settings['a_start_row'] = isset($post_data['a_start_row']) ? $post_data['a_start_row'] : '';
                $settings['a_checkpoint_col'] = isset($post_data['a_checkpoint_col']) ? $post_data['a_checkpoint_col'] : '';
                $settings['a_reading_col'] = isset($post_data['a_reading_col']) ? $post_data['a_reading_col'] : '';
                $settings['a_judgement_col'] = isset($post_data['a_judgement_col']) ? $post_data['a_judgement_col'] : '';
                
                $post_data['automate_settings'] = serialize($settings);
            } else {
                $post_data['automate_settings'] = null;
            }
             
            $inspection_dir = !empty($inspection['directory_name']) ? $inspection['directory_name'] : strtotime('now');
            if(!empty($_FILES['checkpoints_excel']['name'])) {
                $output = $this->upload_file('checkpoints_excel', 'checkpoints', "assets/inspections/".$inspection_dir."/");

                if($output['status'] == 'success') {
                    $post_data['checkpoints_excel'] = $output['file'];
                    $post_data['directory_name'] = $inspection_dir;
                } else {
                    $data['error'] = $output['error'];
                }

            }

            if(!isset($data['error'])) {
                $id = $this->Inspection_model->add_inspection($post_data, $inspection_id);

                if($id) {
                    $output = TRUE;
                    if(!empty($post_data['checkpoints_excel'])) {
                        $output = $this->parse_checkpoints_excel($id, $post_data['checkpoints_excel'], $post_data['checkpoint_format'], $inspection_dir);
                    }
                    
                   
                    if(!$output || $output === 'DUPS') {
                        if(empty($inspection_id)) {
                            $this->Inspection_model->delete_inspection_permanent($id);
                        }
                        
                        if($output === 'DUPS') {
                            $data['error'] = 'Incorrect Excel. Duplicate checkpoints exists.';
                        } else {
                            $data['error'] = 'Incorrect Excel format. Please check';
                        }
                        
                    } else {
                        $this->session->set_flashdata('success', 'Inspection successfully '.(($inspection_id) ? 'updated' : 'added').'.');
                        redirect(base_url().'inspections');
                    }
                    
                } else {
                    $data['error'] = 'Something went wrong, Please try again';
                }
            }
        }
        
        $this->template->write_view('content', 'inspections/add_inspection', $data);
        $this->template->render();
    }
    
    public function add_checkpoint($inspection_id, $checkpoint_id = '') {
        $data = array();

        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($inspection))
            redirect(base_url().'inspections');

        $data['inspection'] = $inspection;
        
        $data['existing_checkpoints'] = $this->Inspection_model->get_existing_checkpoints_no($inspection_id, $checkpoint_id)['nos'];
        //echo "<pre>";print_r($data['existing_checkpoints']);exit;
        if(!empty($checkpoint_id)) {
            $checkpoint = $this->Inspection_model->get_checkpoint($checkpoint_id);
            if(empty($checkpoint))
                redirect(base_url().'inspections/checkpoints/'.$inspection_id);

            $data['checkpoint'] = $checkpoint;
        }

        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('checkpoint_no', 'Inspection', 'trim|required|xss_clean');
            
            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                $post_data['inspection_id'] = $inspection['id'];
                $id = !empty($checkpoint['id']) ? $checkpoint['id'] : '';
                $checkpoint_no = $this->input->post('checkpoint_no');
            
                $exists = $this->Inspection_model->is_checkpoint_no_exists($inspection['id'], $checkpoint_no, $id);
                if($exists) {
                    $this->Inspection_model->move_checkpoints($inspection_id, $checkpoint_no);
                }

                $checkpoint_id = $this->Inspection_model->update_checkpoint($post_data, $id);
                if($checkpoint_id) {
                    $type = !empty($id) ? 'Updated' : 'Added';
                    $before = !empty($checkpoint) ? $checkpoint : array();
                    
                    $this->add_history($before, $inspection['id'], $checkpoint_id, $type, $this->input->post('remark'));
                    $this->session->set_flashdata('success', 'Checkpoint successfully '.(($checkpoint_id) ? 'updated' : 'added').'.');
                    redirect(base_url().'inspections/checkpoints/'.$inspection_id);
                } else {
                    $data['error'] = 'Something went wrong, Please try again';
                }
            } else {
                $data['error'] = validation_errors();
            }
            
        }

        $this->template->write_view('content', 'inspections/add_checkpoint', $data);
        $this->template->render();
    }
    
    public function delete_inspection($inspection_id) {
        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($inspection))
            redirect(base_url().'inspections');

        $deleted = $this->Inspection_model->delete_inspection($inspection_id);

        if($deleted) {
            $this->session->set_flashdata('success', 'Inspection successfully deleted.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'inspections');
    }
    
    public function status($inspection_id, $status) {
        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($inspection))
            redirect(base_url().'inspections');
        
        if($this->Inspection_model->change_inspection_status($inspection_id, $status)) {
            $this->session->set_flashdata('success', 'Inspection marked as '.$status);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, Please try again.');
        }

        redirect(base_url().'inspections');
    }
    
    public function delete_checkpoint($inspection_id, $checkpoint_id) {
        $this->load->model('Inspection_model');
        $checkpoint = $this->Inspection_model->get_checkpoint($checkpoint_id);
        if(empty($checkpoint))
            redirect(base_url().'inspections/checkpoints/'.$inspection_id);

        $deleted = $this->Inspection_model->delete_checkpoint($inspection_id, $checkpoint_id);

        if($deleted) {
            $this->add_history($checkpoint, $inspection_id, $checkpoint_id, 'Deleted');
            $this->Inspection_model->move_checkpoints_down($inspection_id, $checkpoint['checkpoint_no']);
            $this->session->set_flashdata('success', 'Checkpoint deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'inspections/checkpoints/'.$inspection_id);
    }
    
    public function checkpoints($inspection_id) {
        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($inspection)) {
            redirect(base_url().'inspections');
        }
        $data['inspection'] = $inspection;
        $data['checkpoints'] = $this->Inspection_model->get_inspection_checkpoints($inspection_id);

        $this->template->write_view('content', 'inspections/checkpoints', $data);
        $this->template->render();
    }

    public function excluded_checkpoints() {
        if(!$this->product_id){
            redirect(base_url());
        }
        $this->load->model('Inspection_model');
        $data['excluded_checkpoints'] = $this->Inspection_model->get_all_excluded_checkpoints();

        $this->template->write_view('content', 'inspections/excluded_checkpoints', $data);
        $this->template->render();
    }

    public function exclude_checkpoint_form($id = '') {
        if(!$this->product_id){
            redirect(base_url());
        }
        $product_id = $this->product_id;
        
        $data = array();
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections();
        
        $this->load->model('Product_model');
        $data['model_suffixs'] = $this->Product_model->get_all_model_suffixs($product_id);
        
        if(!empty($id)) {
            $excluded_checkpoint = $this->Inspection_model->get_excluded_checkpoint($id);
            if(empty($excluded_checkpoint))
                redirect(base_url().'inspections/excluded_checkpoints');

            $data['excluded_checkpoint'] = $excluded_checkpoint;
            
            $data['checkpoints'] = $this->Inspection_model->get_inspection_checkpoints($excluded_checkpoint['inspection_id']);
        }
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('inspection_id', 'Inspection', 'trim|required|xss_clean');
            $validate->set_rules('model', 'Model', 'trim|required|xss_clean');
            $validate->set_rules('checkpoints_nos', 'Checkpoint Nos', 'required|xss_clean');
            
            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                $post_data['checkpoints_nos'] = implode(',', $post_data['checkpoints_nos']);
                
                $exists = $this->Inspection_model->excluded_checkpoint_exists($this->input->post('inspection_id'), $this->input->post('model'), $id);
                if(!$exists) {

                    $excluded_id = $this->Inspection_model->update_excluded_checkpoints($post_data, $id);
                    if($excluded_id) {
                        $this->session->set_flashdata('success', 'Record successfully '.(($excluded_id) ? 'updated' : 'added').'.');
                        redirect(base_url().'inspections/excluded_checkpoints');
                    } else {
                        $data['error'] = 'Something went wrong, Please try again';
                    }

                } else {
                    $data['error'] = 'Record already exists for this Inspection and Model.';
                }
            } else {
                $data['error'] = validation_errors();
            }
        }
        
        $this->template->write_view('content', 'inspections/exclude_checkpoint_form', $data);
        $this->template->render();
    }
    
    public function delete_exclude_checkpoint($id) {
        if(!$this->product_id){
            redirect(base_url());
        }
        
        $this->load->model('Inspection_model');
        $excluded_checkpoint = $this->Inspection_model->get_excluded_checkpoint($id);
        if(empty($excluded_checkpoint))
            redirect(base_url().'inspections/excluded_checkpoints');

        $deleted = $this->Inspection_model->delete_exclude_checkpoint($id);

        if($deleted) {
            $this->session->set_flashdata('success', 'Record successfully deleted.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'inspections/excluded_checkpoints');
    }
    
    public function iterations($inspection_id) {
        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($inspection)) {
            redirect(base_url().'inspections');
        }
        $data['inspection'] = $inspection;
        
        $data['iterations'] = $this->Inspection_model->get_all_iterations($inspection_id);

        $this->template->write_view('content', 'inspections/iterations', $data);
        $this->template->render();
    }
    
    public function add_iteration($inspection_id, $id = '') {
        $data = array();
        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($inspection)) {
            redirect(base_url().'inspections');
        }
        $data['inspection'] = $inspection;
        
        if(!empty($id)) {
            $iteration = $this->Inspection_model->get_iteration($inspection_id, $id);
            if(empty($iteration))
                redirect(base_url().'inspections/iterations/'.$inspection_id);

            $data['iteration'] = $iteration;
        }
        
        $data['checkpoints'] = $this->Inspection_model->get_inspection_checkpoints($inspection_id);
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('iteration_no', 'Iteration No', 'trim|required|xss_clean');
            $validate->set_rules('checkpoints', 'Checkpoints', 'required|xss_clean');
            $validate->set_rules('iteration_time', 'Iteration Time', 'required|xss_clean');
            
            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                $post_data['checkpoints'] = implode(',', $post_data['checkpoints']);
                $post_data['inspection_id'] = $inspection_id;
                
                $iteration_id = $this->Inspection_model->update_iteration($post_data, $id);
                if($iteration_id) {
                    $this->session->set_flashdata('success', 'Record successfully '.(($id) ? 'updated' : 'added').'.');
                    redirect(base_url().'inspections/iterations/'.$inspection_id);
                } else {
                    $data['error'] = 'Something went wrong, Please try again';
                }
                
            } else {
                $data['error'] = validation_errors();
            }
        }
        
        $this->template->write_view('content', 'inspections/iteration_form', $data);
        $this->template->render();
    }
    
    public function specs($inspection_id, $checkpoint_id) {
        $this->load->model('Inspection_model');
        $checkpoint = $this->Inspection_model->get_checkpoint($checkpoint_id);
        if(empty($checkpoint)) {
            redirect(base_url().'inspections/checkpoints/'.$inspection_id);
        }
        
        $data['checkpoint'] = $checkpoint;
        $data['inspection_id'] = $inspection_id;
        $data['specs'] = $this->Inspection_model->get_all_specs($checkpoint_id);

        $this->template->write_view('content', 'inspections/specs', $data);
        $this->template->render();
    }
    
    public function upload_specs($inspection_id, $checkpoint_id) {
        $this->load->model('Inspection_model');
        $checkpoint = $this->Inspection_model->get_checkpoint($checkpoint_id);
        if(empty($checkpoint)) {
            redirect(base_url().'inspections/checkpoints/'.$inspection_id);
        }
        
        $data['checkpoint'] = $checkpoint;
        
        if($this->input->post()) {
             
            if(!empty($_FILES['specs_excel']['name'])) {
                $output = $this->upload_file('specs_excel', 'Specs', "assets/Temp Sheets/");

                if($output['status'] == 'success') {
                    $res = $this->parse_model_specs($checkpoint_id, $output['file']);
                    $this->session->set_flashdata('success', 'Specs successfully uploaded.');
                    redirect(base_url().'inspections/specs/'.$inspection_id.'/'.$checkpoint_id);
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'inspections/upload_specs', $data);
        $this->template->render();
    }
    
    public function add_spec($checkpoint_id, $spec_id = '') {
        $data = array();
        $this->load->model('Inspection_model');
        $checkpoint = $this->Inspection_model->get_checkpoint($checkpoint_id);
        if(empty($checkpoint))
            redirect(base_url().'inspections');
        
        $data['checkpoint'] = $checkpoint;
        
        if(!empty($spec_id)) {
            $spec = $this->Inspection_model->get_spec($checkpoint_id, $spec_id);
            if(empty($spec))
                redirect(base_url().'inspections/checkpoints/'.$checkpoint['inspection_id']);

            $data['spec'] = $spec;
        }
        
        $this->load->model('Product_model');
        $data['model_suffixs'] = $this->Product_model->get_all_model_suffixs($this->product_id);
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $tools = $this->input->post('tool');
            $model_suffixs = $this->input->post('model_suffix');

            if(!empty($tools) || !empty($model_suffixs)) {
                $models = array();
                if(!empty($tools)) {
                    foreach($tools as $tool) {
                        $temp = $this->Product_model->get_all_suffixs($this->product_id, $tool);
                        $models = array_merge($models, $temp);
                    }
                }
                
                foreach($models as $model) {
                    $model_suffixs[] = $model['model'];
                }
                
                if(!$checkpoint['has_multiple_specs']) {
                    $this->Inspection_model->update_checkpoint(array('has_multiple_specs' => 1), $checkpoint['id']);
                }
                
                if(empty($spec)) {
                    unset($post_data['model_suffix']);

                    foreach($model_suffixs as $model_suffix) {
                        $up_data = array();
                        $up_data = $post_data;
                        $up_data['model_suffix'] = $model_suffix;
                        $up_data['checkpoint_id'] = $checkpoint_id;
                        
                        $this->Inspection_model->update_specs($up_data, '');
                        $this->Inspection_model->remove_dups_specs($checkpoint_id);
                    }

                } else {
                    $this->Inspection_model->update_specs($post_data, $spec['id']);
                }
                
                $this->session->set_flashdata('success', 'Spec successfully '.(($spec) ? 'updated' : 'added').'.');
                redirect(base_url().'inspections/specs/'.$checkpoint['inspection_id'].'/'.$checkpoint['id']);
            } else {
                $data['error'] = 'Please select tool or model for which you want to add the specs';
            }
        }
        
        $this->template->write_view('content', 'inspections/spec_form', $data);
        $this->template->render();
    }
    
    public function attach_guideline($checkpoint_id) {
        $data = array();
        $this->load->model('Inspection_model');
        $checkpoint = $this->Inspection_model->get_checkpoint($checkpoint_id);
        if(empty($checkpoint))
            return false;
        
        $data['checkpoint_id'] = $checkpoint_id;
        
        if($this->input->post()) {
            if(!empty($_FILES['guideline']['name'])) {
                $inspection_dir = $checkpoint['directory_name'];
                $result = $this->upload_photo('guideline', 'assets/inspections/'.$inspection_dir.'/', 'checkpoint-'.$checkpoint['checkpoint_no']);
                
                if($result['status'] == 'success') {
                    $this->load->model('Inspection_model');
                    $response = $this->Inspection_model->update_checkpoint(array('guideline_image' => $result['file']), $checkpoint_id);
                    
                    if(!$response) {
                        $result = array('status' => 'error', 'error' => 'Unable to save the guideline. Please try again');
                    }
                }
                
                echo json_encode($result);
            }
        } else {
            echo $this->load->view('inspections/attach_guideline', $data);
        }
    }
    
    public function clear_automate_settings($checkpoint_id) {
        $this->load->model('Inspection_model');
        $update_data = array();
        $update_data['automate_result_row'] = '';
        $update_data['automate_result_col'] = '';
        $response = $this->Inspection_model->update_checkpoint($update_data, $checkpoint_id);
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function automate_settings($checkpoint_id) {
        $data = array();
        $data['checkpoint_id'] = $checkpoint_id;
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $this->load->model('Inspection_model');
            $response = $this->Inspection_model->update_checkpoint($post_data, $checkpoint_id);
            if($response) {
                $result = array('status' => 'success', 'row' => $this->input->post('automate_result_row'), 'col' => $this->input->post('automate_result_col'));
            } else {
                $result = array('status' => 'error');
            }
            
            echo json_encode($result);
        } else {
            echo $this->load->view('inspections/automate_settings', $data);
        }
    }
    
    public function view_revision_history($inspection_id) {
        $data = array();

        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($inspection))
            redirect(base_url().'inspections');

        $data['inspection'] = $inspection;
        
        $data['histories'] = $this->Inspection_model->get_history($inspection_id, $this->input->post('revision_date'));

        $this->template->write_view('content', 'inspections/history', $data);
        $this->template->render();
    }
    
    private function parse_checkpoints_excel($inspection_id, $file_name, $level, $directory_name) {
        $required_col = 7 + $level;
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        
        if(empty($arr) || !isset($arr[2]) || count($arr[2]) < $required_col) {
            return FALSE;
        }

        $checkpoints = array();
        $checkpoint_nos = array();
        $break = FALSE;
        foreach($arr as $no => $row) {
            if($no == 1)
                continue;
            
            if(!trim($row['A']))
                continue;
            
            $temp = array();
            $temp['inspection_id'] = $inspection_id;
            $checkpoint_no = $row['A'];
            if(in_array($checkpoint_no, $checkpoint_nos)) {
                $break = TRUE;
                break;
            }
            $checkpoint_nos[] = $checkpoint_no;
            
            $temp['checkpoint_no'] = $checkpoint_no;
            $temp['insp_item'] = $row['B'];
            
            if($level == 4) {
                $temp['insp_item2'] = $row['C'];
                $temp['insp_item4'] = $row['D'];
                $temp['insp_item3'] = $row['E'];
                $temp['spec'] = $row['F'];
                $temp['lsl'] = trim($row['G']) ? $row['G'] : null ;
                $temp['usl'] = trim($row['H']) ? $row['H'] : null ;
                $temp['tgt'] = trim($row['I']) ? $row['I'] : null ;
                $temp['unit'] = trim($row['J']) ? $row['J'] : null ;
                
                $guideline = $row['L'];
            } else if($level == 3) {
                $temp['insp_item2'] = $row['C'];
                $temp['insp_item3'] = $row['D'];
                $temp['spec'] = $row['E'];
                $temp['lsl'] = trim($row['F']) ? $row['F'] : null ;
                $temp['usl'] = trim($row['G']) ? $row['G'] : null ;
                $temp['tgt'] = trim($row['H']) ? $row['H'] : null ;
                $temp['unit'] = trim($row['I']) ? $row['I'] : null ;
                
                $guideline = $row['K'];
            } else {
                $temp['insp_item3'] = $row['C'];
                $temp['spec'] = $row['D'];
                
                $temp['lsl'] = trim($row['E']) ? $row['E'] : null ;
                $temp['usl'] = trim($row['F']) ? $row['F'] : null ;
                $temp['tgt'] = trim($row['G']) ? $row['G'] : null ;
                $temp['unit'] = trim($row['H']) ? $row['H'] : null ;
                
                $guideline = $row['J'];
            }
            
            $temp['created'] = date("Y-m-d H:i:s");
            
            //attach_guideline from Excel
            if($guideline && is_file($guideline)) {
                $info = pathinfo($guideline);
                if(!empty($info)) {
                    $upload_path = 'assets/inspections/'.$directory_name.'/';
                    $filename = 'checkpoint-'.$checkpoint_no.'.'.$info['extension'];
                    
                    if(rename($guideline, $upload_path.$filename)) {
                        $temp['guideline_image'] = $upload_path.$filename;
                    }
                }
            }
            
            
            $checkpoints[] = $temp;
        }
        
        if($break) {
            return "DUPS";
        }
        
        $this->load->model('Inspection_model');
        $this->Inspection_model->insert_checkpoints($checkpoints, $inspection_id);
        
        return TRUE;
    }

    private function add_history($before, $inspection_id, $checkpoint_id, $type, $remark = '') {
        $this->load->model('Inspection_model');
        $version = $this->Inspection_model->get_revision_version($inspection_id);
        
        if(!empty($before)) {
            $before['version']          = $version+1;
            $before['type']             = 'Before';
            
            $before['change_type']      = $type;
            $before['changed_on']       = date('Y-m-d H:i:s');
            
            if(!empty($remark)) {
                $before['remark']     = $remark;
            }
            
            $added = $this->Inspection_model->add_history($before);
        }
        
        
        $after = $this->Inspection_model->get_checkpoint($checkpoint_id);
        /* echo "<pre>";
        print_r($after);
        exit; */
        $after['version']          = $version+1;
        $after['type']             = 'After';
        
        $after['change_type']      = $type;
        $after['changed_on']       = date('Y-m-d H:i:s');

        if(!empty($remark)) {
            $data['remark']     = $remark;
        }
        
        $added = $this->Inspection_model->add_history($after);
        return $added;
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
        echo $this->load->view('inspections/excluded_checkpoints_ajax', $data, TRUE);
    }
    
    public function get_inspection_ajax() {
        if(!$this->input->post('inspection_id')) {
            return false;
        }
        
        $inspection_id = $this->input->post('inspection_id');
        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        echo json_encode($inspection);
    }

    public function download_checkpoint($inspection_id) {
        $this->load->model('Inspection_model');
        $inspection = $this->Inspection_model->get_inspection($inspection_id);
        if(empty($inspection)) {
            redirect(base_url().'inspections');
        }
        
        $checkpoints = $this->Inspection_model->get_checkpoint_for_download($inspection_id, $inspection['checkpoint_format'], !empty($inspection['full_auto']));
        
        $headers = array('No');
        if(empty($inspection['full_auto'])) {
            $headers[] = 'Insp. Item';
            if($inspection['checkpoint_format'] >= 3) {
                $headers[] = 'Insp. Item';
            }
            
            if($inspection['checkpoint_format'] == 4) {
                $headers[] = 'Insp. Item';
            }
        }
        
        $headers = array_merge($headers, array('Insp. Item', 'Spec', 'LSL', 'USL', 'TGT', 'Unit', 'Result'));
        
        $this->load->library('excel');
        $this->config->load('excel');
        $this->excel->setActiveSheetIndex(0);

        $row = 1;
        $this->excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(8);
        
        if($inspection['checkpoint_format'] >= 3) {
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
        } else if($inspection['checkpoint_format'] == 4) {
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(28);
        } else {
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
        }
        
        $last_letter =  PHPExcel_Cell::stringFromColumnIndex(count($headers)-1);
        $this->excel->getActiveSheet()->fromArray($headers, NULL, 'A'.$row);
        $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$last_letter.$row)
                                    ->applyFromArray($this->config->item('headerStyle'));
                                    
        $row++;
        
        foreach($checkpoints as $checkpoint) {
            $row_data = array_values($checkpoint);

            $this->excel->getActiveSheet()->fromArray($row_data, NULL, 'A'.$row, true);
            $row++;
        }
        
        $lastColumn = $this->excel->getActiveSheet()->getHighestColumn();
        $lastRow = $this->excel->getActiveSheet()->getHighestRow();
        
        $this->excel->getActiveSheet()->getStyle('B1:F'.$lastRow)->getAlignment()->setWrapText(true);
        
        $borderArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $this->excel->getActiveSheet()->getStyle('A1:'.$lastColumn.$lastRow)->applyFromArray($borderArray);
        
        $filename = time().'.xlsx';

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    private function parse_model_specs($checkpoint_id, $file_name) {
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        
        $specs = array();
        foreach($arr as $no => $row) {
            if($no == 1)
                continue;
            
            if(!trim($row['A']))
                continue;
            
            $temp = array();
            $temp['checkpoint_id']     = $checkpoint_id;
            $temp['model_suffix']      = $row['A'];
            $temp['lsl']               = $row['B'];
            $temp['usl']               = $row['C'];
            $temp['tgt']               = $row['D'];
            $temp['unit']               = $row['E'];
            $temp['created']        = date("Y-m-d H:i:s");
            
            $specs[]        = $temp;
        }
        //$this->print_array($model_suffixs);
        $this->load->model('Inspection_model');
        $this->Inspection_model->insert_specs($specs, $checkpoint_id);
        $this->Inspection_model->remove_dups_specs($checkpoint_id);
        
        return TRUE;
    }
}