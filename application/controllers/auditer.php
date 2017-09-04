<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditer extends Admin_Controller {

    public function __construct() {
        parent::__construct(true);
        
        $page = 'inspections';
        if($this->router->fetch_method() == 'reports') {
            $page = 'reports';
        } 
        
        if($this->router->fetch_method() != 'checkpoint_screen') {
            $this->template->write_view('header', 'templates/header', array('page' => $page));
        }
        
        $this->template->write_view('footer', 'templates/footer');
    }

    public function checklist() {
        if($this->input->get('status') !== 'done') {
            $data = array();
            $this->load->model('Checklist_model');
            
            $data['checklists'] = $this->Checklist_model->get_all_checklists($this->product_id);

            $this->template->write('title', 'OQIS | Inspection | Checklist');
            $this->template->write_view('content', 'auditer/checklist', $data);
            $this->template->render();
        } else {
            $this->load->model('User_model');
            $this->User_model->update_user(array('checklist_checked' => date('Y-m-d')), $this->id);
            
            redirect(base_url().'register_inspection');
        }
    }
    
    public function wait_screen($audit_id) {
        $data = array();
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, 'started', '', $audit_id, null);

        if(empty($audit)) {
            $this->check_inspection();
        }
        
        if($audit['insp_type'] != 'interval') {
            $this->check_inspection();
        }
        
        $data['audit'] = $audit;
        
        $this->template->write('title', 'OQIS | Product Inspection | Wait Screen');
        $this->template->write_view('content', 'auditer/wait_screen', $data);
        $this->template->render();
    }
    
    public function register_inspection() {
        $data = array();
        $this->load->model('Audit_model');
        
        $audit = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'));
        if(!empty($audit)) {
            $this->check_inspection($audit);
        }
        
        $this->destroy_checkpoint_session();
        
        $this->load->model('Product_model');
        $p = $this->Product_model->get_product($this->product_id);
        if($p['checklist_active']) {
            $this->load->model('User_model');
            $user = $this->User_model->get_user($this->username);
            if($user['checklist_checked'] != date('Y-m-d')) {
                $this->load->model('Checklist_model');
                $checklists = $this->Checklist_model->get_all_checklists($this->product_id);
                if(!empty($checklists)) {
                    redirect(base_url().'auditer/checklist');
                }
            }
        }
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('audit_date', 'Production Plan Date', 'trim|required|xss_clean');
            $validate->set_rules('line_id', 'Product Line', 'trim|required|xss_clean');
            $validate->set_rules('model_suffix', 'Model Suffix', 'trim|required|xss_clean');
            $validate->set_rules('serial_no', 'Serial No', 'trim|required|xss_clean');
            $validate->set_rules('inspection_id', 'Inspection', 'trim|required|xss_clean');

            if($validate->run() === TRUE) {
                $post_data = $this->input->post();

                $this->load->model('Inspection_model');
                $inspection = $this->Inspection_model->get_inspection($post_data['inspection_id']);
                if(empty($inspection)) {
                    $this->session->set_flashdata('error', 'Invalid Inspection.');
                    redirect(base_url().'register_inspection');
                }
                
                $this->load->model('Product_model');
                $model = $this->Product_model->get_model_tool($this->product_id, $post_data['model_suffix']);
                if(empty($model)) {
                    $this->session->set_flashdata('error', 'Invalid Model.Suffix');
                    redirect(base_url().'register_inspection');
                }
                
                $already = $this->Audit_model->check_already_inspected($post_data);
                if($already) {
                    $this->session->set_flashdata('error', 'Inspection for this Model.Suffix and Serial No has already been done.');
                    redirect(base_url().'register_inspection');
                }
                
                $post_data['product_id']        = $this->product_id;
                if(!empty($model['tool'])) {
                    $post_data['tool']          = $model['tool'];
                }
                
                $post_data['register_datetime'] = date('Y-m-d H:i:s');

                $post_data['checkpoint_format'] = $inspection['checkpoint_format'];
                if($inspection['insp_type'] == 'interval') {
                    $post_data['current_iteration'] = 0;
                    
                    if($inspection['attach_report']) {
                        $post_data['iteration_datetime'] = date('Y-m-d H:i:s', strtotime('+'.$inspection['inspection_duration'], strtotime($post_data['register_datetime'])));
                    }
                }

                
                $post_data['auditer_id']        = $this->id;
                
                if($this->input->post('paired')) {
                    $post_data['paired']            = 1;
                    $post_data['on_hold']           = 1;
                }
                
                $audit_id = $this->Audit_model->update_audit($post_data, '');
                if($audit_id) {
                    if(!$this->input->post('paired')) {
                        $this->session->set_flashdata('success', 'Product Inspection successfully registered. Please review and click Start Inspection');
                        redirect(base_url().'auditer/inspection_start_screen');
                    } else {
                        $this->session->set_flashdata('success', 'Product Inspection successfully registered. Add Paired inspection details');
                        redirect(base_url().'auditer/paired_inspection/'.$audit_id);
                    }

                } else {
                    $data['error'] = 'Something went wrong. Please try again.';
                }
                
            } else {
                $data['error'] = validation_errors();
            }

        }
        
        $this->load->model('Product_model');
        $data['lines'] = $this->Product_model->get_all_product_lines($this->product_id);
        
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections();

        $this->template->write('title', 'OQIS | Product Inspection | Register Screen');
        $this->template->write_view('content', 'auditer/register_inspection', $data);
        $this->template->render();
    }
    
    public function paired_inspection($audit_id) {
        $data = array();
        $this->load->model('Audit_model');
        
        $audit = $this->Audit_model->get_audit($this->id, 'registered', '', $audit_id, 1);
        if(empty($audit)) {
            $this->check_inspection($audit);
        }
        
        $this->destroy_checkpoint_session();
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('line_id', 'Product Line', 'trim|required|xss_clean');
            $validate->set_rules('model_suffix', 'Model Suffix', 'trim|required|xss_clean');
            $validate->set_rules('serial_no', 'Serial No', 'trim|required|xss_clean');

            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                
                $this->load->model('Product_model');
                $model = $this->Product_model->get_model_tool($this->product_id, $post_data['model_suffix']);
                if(empty($model)) {
                    $this->session->set_flashdata('error', 'Invalid Model.Suffix');
                    redirect(base_url().'register_inspection');
                }
                
                $post_data['product_id']            = $this->product_id;
                if(!empty($model['tool'])) {
                    $post_data['tool']              = $model['tool'];
                }

                $post_data['checkpoint_format']     = $audit['checkpoint_format'];
                $post_data['current_iteration']     = $audit['current_iteration'];

                $post_data['register_datetime']     = date('Y-m-d H:i:s');
                $post_data['auditer_id']            = $this->id;
                $post_data['paired']                = 1;
                $post_data['on_hold']               = 1;
                $post_data['audit_date']            = $audit['audit_date'];
                $post_data['inspection_id']         = $audit['inspection_id'];
                $post_data['paired_with']           = $audit['id'];
                
                $paired_audit_id = $this->Audit_model->update_audit($post_data, '');
                if($paired_audit_id) {
                    if(!$this->input->post('pair_more')) {
                        $this->Audit_model->hold_resume_audit($audit['id'], 0);
                        
                        $this->session->set_flashdata('success', 'Product Inspection successfully registered. Please review and click Start Inspection');
                        redirect(base_url().'auditer/inspection_start_screen');
                    } else {
                        $this->session->set_flashdata('success', 'Product Inspection successfully registered. Add Another Paired inspection details');
                        redirect(base_url().'auditer/paired_inspection/'.$audit_id);
                    }
                } else {
                    $data['error'] = 'Something went wrong. Please try again.';
                }
                
            } else {
                $data['error'] = validation_errors();
            }

        }
        
        $this->load->model('Product_model');
        $data['lines'] = $this->Product_model->get_all_product_lines($this->product_id);

        $this->template->write('title', 'OQIS | Product Inspection | Paired Inspection Screen');
        $this->template->write_view('content', 'auditer/paired_inspection', $data);
        $this->template->render();
    }
    
    public function inspection_start_screen() {
        $data = array();
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, 'registered');
        if(empty($audit)) {
            $this->check_inspection();
        }

        $this->load->model('Inspection_model');
        $excluded_checkpoints = $this->Inspection_model->get_excluded_checkpoint_by_model_inspection($audit['model_suffix'], $audit['inspection_id']);
        
        $exclude_checkpoints_nos = array();
        if(!empty($excluded_checkpoints)) {
            $exclude_checkpoints_nos = explode(',', $excluded_checkpoints['checkpoints_nos']);
        }
        
        $checkpoints = $this->Inspection_model->get_inspection_checkpoints_model_specific($audit['inspection_id'], $audit['model_suffix'], $exclude_checkpoints_nos);

        $data['audit'] = $audit;
        $data['checkpoints'] = $checkpoints;
        $data['excluded_count'] = count($exclude_checkpoints_nos);
        
        $this->template->write('title', 'OQIS | Product Inspection | Start Screen');
        $this->template->write_view('content', 'auditer/inspection_start_screen', $data);
        $this->template->render();
    }
    
    public function start_inspection() {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, 'registered');
        if(empty($audit)) {
            $this->check_inspection();
        }
        
        $this->load->model('Inspection_model');
        $excluded_checkpoints = $this->Inspection_model->get_excluded_checkpoint_by_model_inspection($audit['model_suffix'], $audit['inspection_id']);
        
        $exclude_checkpoints_nos = array();
        if(!empty($excluded_checkpoints)) {
            $exclude_checkpoints_nos = explode(',', $excluded_checkpoints['checkpoints_nos']);
        }
        
        $iteration = '';
        $iteration_no = '';
        if($audit['insp_type'] == 'interval') {
            $iteration_row = $this->Inspection_model->get_next_iteration_checkpoints($audit['inspection_id'], $audit['current_iteration']);
            $iteration = $iteration_row['checkpoints'];
            
            $iteration_datetime = date('Y-m-d H:i:s', strtotime('+'.$iteration_row['iteration_time'].' '.$iteration_row['iter_time_type'], strtotime($audit['register_datetime'])));
            if($iteration_row['iter_time_type'] != 'hours') {
                $iteration_datetime = date('Y-m-d', strtotime($iteration_datetime)).' 08:00:00';
            }
            
            $iteration_no = $audit['current_iteration']+1;
            $audit_up_data = array(
                'current_iteration' => $iteration_no,
                'iteration_datetime' => $iteration_datetime,
            );
            
            $this->Audit_model->update_audit($audit_up_data, $audit['id']);
        }
        
        $this->Audit_model->create_audit_checkpoints($audit['inspection_id'], $audit['model_suffix'], $audit['id'], $exclude_checkpoints_nos, $iteration, $iteration_no);
        $this->Audit_model->change_state($audit['id'], $this->id, 'started');
        
        $this->set_checkpoint_session($audit['id'], $iteration_no);
        
        redirect(base_url().'auditer/checkpoint_screen');
    }
    
    public function checkpoint_screen() {
        $data = array();
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, 'started');
        if(empty($audit)) {
            $this->check_inspection();
        }
        
        if($audit['insp_type'] == 'interval') {
            if(strtotime($audit['iteration_datetime']) > strtotime('now')) {
                redirect(base_url().'auditer/wait_screen/'.$audit['id']);
            }
        }
        
        $data['audit'] = $audit;
        $this->load->model('Reference_model');
        if(!$this->session->userdata('references')) {
            $raw_refs = $this->Reference_model->get_specific_reference_links($audit['inspection_id'], $audit['tool'], $audit['model_suffix']);
            $refs = array();
            $references = array();
            
            foreach($raw_refs as $ref) {
                if(in_array($ref['name'], $refs)) {
                    continue;
                }
                $refs[] = $ref['name'];

                $references[] = $ref;
            }

            $header['references'] = $references;
            
            $this->session->set_userdata('references', $references);
            
        } else {
            $header['references'] = $this->session->userdata('references');
        }
        
        $iteration_no = '';
        if($audit['insp_type'] == 'interval') {
            $iteration_no = $audit['current_iteration'];
        }
        
        if(!$this->session->userdata('current_checkpoint')) {
            $this->set_checkpoint_session($audit['id'], $iteration_no, 'find');
        }
        
        $checkpoint = $this->Audit_model->get_checkpoint($audit['id'], $this->session->userdata('current_checkpoint'), $iteration_no);
        $data['checkpoint'] = $checkpoint;
        
        $mandatories = array();
        $mand = $this->Reference_model->get_mandatory_links_for_checkpoint($audit['inspection_id'], $checkpoint['org_checkpoint_id']);
        if($this->session->userdata('username') == 'raju1.bhosale'){
            //echo $checkpoint['org_checkpoint_id']." ".$audit['inspection_id']; exit;
            //echo "<pre>"; print_r($mand); exit;
        }
        foreach($mand as $mand_link) {
            $mandatories[] = $mand_link['reference_link'];
        }
        
        $this->session->set_userdata('mandatories', $mandatories);
        $header['mandatories'] = $this->session->userdata('mandatories');
        
        $this->template->write('title', 'OQIS | Product Inspection | Checkpoint Screen');
        $this->template->write_view('header', 'templates/header', $header);
        $this->template->write_view('content', 'auditer/checkpoint_screen', $data);
        $this->template->render();
    }
    
    public function record_result($checkpoint_id) {
        if($this->input->post()) {
            $this->load->model('Audit_model');
            $post_data = $this->input->post();
            $audit = $this->Audit_model->get_audit($this->id, 'started');
            if(empty($audit)) {
                $this->check_inspection();
            }
            
            $iteration_no = '';
            if($audit['insp_type'] == 'interval') {
                $iteration_no = $audit['current_iteration'];
            }
            
            $checkpoint = $this->Audit_model->get_checkpoint($audit['id'], $this->session->userdata('current_checkpoint'), $iteration_no);
            if(!empty($checkpoint)) {

                if($checkpoint['id'] != $checkpoint_id) {
                    $this->session->set_flashdata('error', 'Something went wrong. Please try again.');
                    redirect(base_url().'auditer/checkpoint_screen');
                }
                
                $opened = $this->session->userdata('opened_link');
                if(empty($opened)) {
                    $opened = array();
                }
                $mandatories = $this->session->userdata('mandatories');
                
                if(array_diff($mandatories, $opened)) {
                    $this->session->set_flashdata('error', 'Please open the mandatory link before proceeding ahead.');
                    redirect(base_url().'auditer/checkpoint_screen');
                }
                
                if(isset($post_data['automate'])) {
                    $file = $audit['dir_path'].$audit['gmes_insp_id'].'/'.$audit['serial_no'].'.xlsx';
                    //$file = 'assets/Automate/'.$this->product_id.'/'.$audit['serial_no'].'.xlsx';
                    //echo $file;exit;
                    $post_data['audit_value'] = $this->get_automatic_value($file, 
                    $checkpoint['automate_result_row'], $checkpoint['automate_result_col']);
                }

                if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) {
                    if(!isset($post_data['audit_value'])) {
                        $this->session->set_flashdata('error', 'Something went wrong will storing result. Please try again');
                        redirect(base_url().'auditer/checkpoint_screen');
                    }
                    
                    if(!isset($post_data['result']) || $post_data['result'] != 'NA') {
                        $exploded_value = explode(',', $post_data['audit_value']);
                        
                        $lower_res = TRUE;
                        $upper_res = TRUE;
                        foreach($exploded_value as $ex_val) {
                            $audit_value = (float)$ex_val;
                            
                            if(!empty($checkpoint['lsl']) && $audit_value < $checkpoint['lsl']) {
                                $lower_res = FALSE;
                                break;
                            }
                            
                            if(!empty($checkpoint['usl']) && $audit_value > $checkpoint['usl']) {
                                $upper_res = FALSE;
                                break;
                            }
                        }
                        
                        if($lower_res && $upper_res) {
                            $post_data['result'] = 'OK';
                        } else {
                            $post_data['result'] = 'NG';
                        }
                    }
                }
                
                if(!empty($_FILES['defect_image']['name'])) {
                    $output = $this->upload_file('defect_image', time(), "assets/defects_images/".$this->product_id.'/', '*');

                    if($output['status'] == 'success') {
                        $post_data['defect_image'] = $output['file'];
                    } else {
                        $this->session->set_flashdata('error', $output['error']);
                        redirect(base_url().'auditer/checkpoint_screen');
                    }

                }
                
                $response = $this->Audit_model->record_checkpoint_result($post_data, $checkpoint_id, $audit['id']);
                if($response) {
                    $this->session->set_userdata('opened_link', array());
                    
                    if($post_data['result'] == 'NG' && base_url() != 'http://localhost/OQIS/') {
                        $this->load->model('Product_model');
                        $phone_numbers = $this->Product_model->get_all_phone_numbers($audit['product_id']);
                        if(!empty($phone_numbers)) {
                            $to = array();
                            
                            foreach($phone_numbers as $phone_number) {
                                $to[] = $phone_number['phone_number'];
                            }
                            
                            $to = implode(',', $to);
                            
                            $sms = $audit['product_name']." OQA- Inspn Rslt NG\nModel-".$audit['model_suffix']."(".$audit['line_name'];
                            $sms .= ")\nDefect-".$post_data['remark'];
                            
                            if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) {
                                $sms .= "\nAct:".$audit_value.$checkpoint['unit'];
                                
                                if(empty($checkpoint['usl'])) {
                                    $sms .= "\nSpec:>=".$checkpoint['lsl'].$checkpoint['unit'];
                                } else if(empty($checkpoint['lsl'])) {
                                    $sms .= "\nSpec:<=".$checkpoint['usl'].$checkpoint['unit'];
                                } else {
                                    $sms .= "\nSpec:".$checkpoint['lsl'].'~'.$checkpoint['usl'].$checkpoint['unit'];
                                }
                            }
                            
                            $this->send_sms($to, $sms);
                        }
                        
                    } else if($post_data['result'] == 'NA') {
                        $noti_data = array();
                        $noti_data['noti_type'] = 'NA_NOTI';
                        $noti_data['product_id'] = $audit['product_id'];
                        $noti_data['audit_checkpoint_id'] = $checkpoint_id;
                        
                        //$this->Audit_model->add_notification($noti_data);
                    }
                    
                    $this->session->set_flashdata('success', 'Result recorded successfully');
                    
                    $new_checkpoint = $this->get_n_set_checkpoint_session();
                    if($new_checkpoint === 'Completed') {
                        if($audit['insp_type'] == 'interval') {
                            $this->load->model('Inspection_model');
                            $iteration_row = $this->Inspection_model->get_next_iteration_checkpoints($audit['inspection_id'], $audit['current_iteration']);
                            
                            if(!empty($iteration_row)) {
                                $iteration = $iteration_row['checkpoints'];
                                $iteration_no = $audit['current_iteration']+1;

                                $iteration_datetime = date('Y-m-d H:i:s', strtotime('+'.$iteration_row['iteration_time'].' '.$iteration_row['iter_time_type'], strtotime($audit['register_datetime'])));
                                if($iteration_row['iter_time_type'] != 'hours') {
                                    $last_iteration_no = $this->Inspection_model->get_last_iteration_no($audit['inspection_id']);
                                    
                                    if($last_iteration_no != $iteration_no) {
                                        $iteration_datetime = date('Y-m-d', strtotime($iteration_datetime)).' 08:00:00';
                                    }
                                }

                                $audit_up_data = array(
                                    'current_iteration' => $iteration_no,
                                    'iteration_datetime' => $iteration_datetime,
                                );

                                $excluded_checkpoints = $this->Inspection_model->get_excluded_checkpoint_by_model_inspection($audit['model_suffix'], $audit['inspection_id']);

                                $exclude_checkpoints_nos = array();
                                if(!empty($excluded_checkpoints)) {
                                    $exclude_checkpoints_nos = explode(',', $excluded_checkpoints['checkpoints_nos']);
                                }
                            
                                $this->Audit_model->update_audit($audit_up_data, $audit['id']);
                            
                                $this->Audit_model->create_audit_checkpoints($audit['inspection_id'], $audit['model_suffix'], $audit['id'], $exclude_checkpoints_nos, $iteration, $iteration_no);
                                
                                $this->destroy_checkpoint_session();
                                $this->Audit_model->hold_resume_audit($audit['id']);
                                redirect(base_url().'auditer/wait_screen/'.$audit['id']);
                            }
                        }
                        
                        $this->Audit_model->change_state($audit['id'], $this->id, 'finished');
                        redirect(base_url().'auditer/finish_screen');
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong will storing result. Please try again');
                }
            }
        }
        
        redirect(base_url().'auditer/checkpoint_screen');
    }
    
    public function navigate_checkpoint($type) {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, 'started');
        if(empty($audit)) {
            $this->check_inspection();
        }
        
        $currect_checkpoint = $this->session->userdata('current_checkpoint');
        $nos = $this->session->userdata('nos');
        
        if($currect_checkpoint == $nos[0] && $type == 'prev') {
            redirect(base_url().'auditer/checkpoint_screen');
        }
        
        if($currect_checkpoint == $nos[count($nos)-1] && $type == 'next') {
            redirect(base_url().'auditer/checkpoint_screen');
        }

        $new_checkpoint_no = $this->get_n_set_checkpoint_session($type);
        
        if($new_checkpoint_no === 'Completed') {
            $opened = $this->session->userdata('opened_link');
            if(empty($opened)) {
                $opened = array();
            }
            $mandatories = $this->session->userdata('mandatories');
            
            if(array_diff($mandatories, $opened)) {
                $this->session->set_flashdata('error', 'You cannot complete the inspection because you have not opened the mandatory links.');
            } else {
                $this->Audit_model->change_state($audit['id'], $this->id, 'finished');
                redirect(base_url().'auditer/finish_screen');
            }
        }
        
        redirect(base_url().'auditer/checkpoint_screen');
    }
    
    public function finish_screen($audit_id = '') {
        $data = array();
        $this->load->model('Audit_model');
        
        if(!$audit_id) {
            $audit = $this->Audit_model->get_audit($this->id, 'finished');
        } else {
            $data['admin_edit_audit'] = $audit_id;
            $audit = $this->Audit_model->get_audit('', 'completed', '', $audit_id);
        }

        if(empty($audit)) {
            $this->check_inspection();
        }
        
        if(empty($audit['automate_file'])) {
            $checkpoints = $this->Audit_model->get_all_audit_checkpoints($audit['id']);
            
            $data['audit'] = $audit;
            $data['checkpoints'] = $checkpoints;
            $data['checkpoints_OK'] = $this->Audit_model->get_count_checkpoint_by_result($audit['id'], 'OK');
            $data['checkpoints_NG'] = $this->Audit_model->get_count_checkpoint_by_result($audit['id'], 'NG');
            $data['checkpoints_PD'] = $this->Audit_model->get_count_checkpoint_by_result($audit['id'], null);
            
            $this->template->write('title', 'OQIS | Product Inspection | Review Screen');
            $this->template->write_view('content', 'auditer/finish_screen', $data);
            $this->template->render();
        } else {
            $data['audit'] = $audit;
            
            $result = $this->Audit_model->get_audit_automate_result($audit['id']);
            $data['result'] = unserialize($result['automate_data']);
            
            $this->template->write('title', 'OQIS | Product Inspection | Review Screen');
            $this->template->write_view('content', 'auditer/automate_finish_screen', $data);
            $this->template->render();
        }
    }
    
    public function review_checkpoint($checkpoint_no, $iteration_no = '', $audit_id = '') {
        $data = array();
        $this->load->model('Audit_model');

        if(!$audit_id) {
            $audit = $this->Audit_model->get_audit($this->id, 'finished');
        } else {
            $data['admin_edit_audit'] = $audit_id;
            $audit = $this->Audit_model->get_audit('', 'completed', '', $audit_id);
        }
        
        if(empty($audit)) {            
            echo "<div class='modal-body'>Something went wrong. Please refresh your screen.</div>";
            return;
        }
        
        $checkpoint = $this->Audit_model->get_checkpoint($audit['id'], $checkpoint_no, $iteration_no);
        $data['checkpoint'] = $checkpoint;
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) {
                if(!isset($post_data['audit_value'])) {
                    $this->session->set_flashdata('error', 'Something went wrong will storing result. Please try again');
                    redirect(base_url().'auditer/checkpoint_screen');
                }
                
                $audit_value = (float)$post_data['audit_value'];
                
                $lower_res = TRUE;
                $upper_res = TRUE;
                if(!empty($checkpoint['lsl']) && $audit_value < $checkpoint['lsl']) {
                    $lower_res = FALSE;
                }
                
                if(!empty($checkpoint['usl']) && $audit_value > $checkpoint['usl']) {
                    $upper_res = FALSE;
                }
                
                if($lower_res && $upper_res) {
                    $post_data['result'] = 'OK';
                } else {
                    $post_data['result'] = 'NG';
                }
            }
            
            $response = $this->Audit_model->record_checkpoint_result($post_data, $checkpoint['id'], $audit['id']);
            if($response) {
                
                if(!$audit_id) {
                    if($post_data['result'] == 'NG' && base_url() != 'http://localhost/OQIS/') {
                        $this->load->model('Product_model');
                        $phone_numbers = $this->Product_model->get_all_phone_numbers($audit['product_id']);
                        if(!empty($phone_numbers)) {
                            $to = array();
                            
                            foreach($phone_numbers as $phone_number) {
                                $to[] = $phone_number['phone_number'];
                            }
                            
                            $to = implode(',', $to);
                            
                            $sms = $audit['product_name']." OQA: Inspn Rslt NG\nModel:".$audit['model_suffix']."(".$audit['line_name'];
                            $sms .= ")\nDefect-".$post_data['remark'];
                            
                            if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) {
                                $sms .= "\nAct:".$audit_value.$checkpoint['unit'];
                                
                                if(empty($checkpoint['usl'])) {
                                    $sms .= "\nSpec:>=".$checkpoint['lsl'].$checkpoint['unit'];
                                } else if(empty($checkpoint['lsl'])) {
                                    $sms .= "\nSpec:<=".$checkpoint['usl'].$checkpoint['unit'];
                                } else {
                                    $sms .= "\nSpec:".$checkpoint['lsl'].'~'.$checkpoint['usl'].$checkpoint['unit'];
                                }
                            }
                            
                            $this->send_sms($to, $sms);
                        }
                        
                    } else if($post_data['result'] == 'NA') {
                        $noti_data = array();
                        $noti_data['noti_type'] = 'NA_NOTI';
                        $noti_data['product_id'] = $audit['product_id'];
                        $noti_data['audit_checkpoint_id'] = $checkpoint_id;
                        
                        //$this->Audit_model->add_notification($noti_data);
                    }
                }
                
                $this->session->set_flashdata('success', 'Result recorded successfully');
            } else {
                $this->session->set_flashdata('error', 'Unable to update the result. Please try again.');
            }
            
            if($audit_id) {
                redirect(base_url().'auditer/finish_screen/'.$audit_id);
            } else {
                redirect(base_url().'auditer/finish_screen');
            }
        }
        
        echo $this->load->view('auditer/review_checkpoint_modal', $data, true);
    }
    
    public function mark_as_complete() {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, 'finished');
        if(empty($audit)) {
            $this->check_inspection();
        }
        
        $check = $this->Audit_model->check_slippage($audit['id']);
        if($check) {
            $this->session->set_flashdata('error', 'Inspection can\'t be completed as there are 1 or more checkpoints with No Result.');
            redirect(base_url().'auditer/finish_screen');
        }
        
        $check = $this->Audit_model->check_not_approved_na($audit['id']);
        if($check) {
            $this->session->set_flashdata('error', 'Inspection can\'t be completed as there are 1 or more unapproved checkpoints marked as NA. Please ask Admin to approve the checkpoint.');
            redirect(base_url().'auditer/finish_screen');
        }

        $response = $this->Audit_model->change_state($audit['id'], $this->id, 'completed');
        if($response) {
            $this->Audit_model->add_to_completed_audits($audit['id']);
            if(!empty($audit['paired'])) {
                $paired_audits = $this->Audit_model->get_all_paired_audits($audit['id']);

                foreach($paired_audits as $paired_audit) {
                    $this->Audit_model->change_state($paired_audit['id'], $this->id, 'completed');
                    $this->Audit_model->hold_resume_audit($paired_audit['id'], 0);
                    $this->Audit_model->copy_checkpoints_results($audit['id'], $paired_audit['id']);
                    
                    $this->Audit_model->add_to_completed_audits($paired_audit['id']);
                }
            }
            
            $this->destroy_checkpoint_session();
            $this->session->set_flashdata('success', 'Inspection successfully marked completed. You can download the report from the list below.');
            redirect(base_url().'reports');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
            redirect(base_url().'auditer/finish_screen');
        }
        
    }
    
    public function mark_as_abort($audit_id = '', $on_hold = 0) {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'), '', $audit_id, $on_hold);
        if(empty($audit)) {
            $this->check_inspection();
        }
        
        if($audit['insp_type'] == 'interval') {
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        $response = $this->Audit_model->change_state($audit['id'], $this->id, 'aborted');
        if($response) {
            if(!empty($audit['paired'])) {
                $paired_audits = $this->Audit_model->get_all_paired_audits($audit['id']);

                foreach($paired_audits as $paired_audit) {
                    $this->Audit_model->change_state($paired_audit['id'], $this->id, 'aborted');
                }
            }
            
            $this->destroy_checkpoint_session();
            $this->session->set_flashdata('success', 'Inspection successfully marked aborted.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function abort_request($audit_id = '') {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'), '', $audit_id, $on_hold);
        if(empty($audit)) {
            $this->check_inspection();
        }
        
        if($audit['insp_type'] != 'interval') {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $audit_up_data                              = array();
        $audit_up_data['abort_requested']           = 1;
        $audit_up_data['on_hold']                   = 1;
        $audit_up_data['abort_request_datetime']    = date('Y-m-d H:i:s');
        
        $response = $this->Audit_model->update_audit($audit_up_data, $audit['id']);
        if($response) {
            $this->destroy_checkpoint_session();
            $this->session->set_flashdata('success', 'Abort request has been sent to Admin.');
            
            redirect(base_url());
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function on_hold($audit_id = '') {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'), '', $audit_id);

        if(empty($audit)) {
            $this->check_inspection();
        }
        
        if($audit['insp_type'] != 'interval') {
            $currently_on_holds = $this->Audit_model->get_on_hold_audits($this->id, 'regular');
            
            //On Hold limit
            $limit = 200;
            if($this->product_id == 1) {
                $limit = 4;
            }
            
            if(count($currently_on_holds) >= $limit) {
                $this->session->set_flashdata('error', 'Can\'t hold more than 4 inspection at a same time. Please complete on hold inspection before moving ahead.');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        $response = $this->Audit_model->hold_resume_audit($audit['id']);
        if($response) {
            $this->destroy_checkpoint_session();
            $this->session->set_flashdata('success', 'Inspection successfully marked on hold.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function resume($audit_id) {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'), '', $audit_id);
        
        $current = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'));
        if(!empty($current)) {
            if($current['insp_type'] == 'regular' && $audit['insp_type'] == 'interval') {
                $this->session->set_flashdata('success', 'There is already 1 on going REGULAR inspection. Please complete it or mark it on hold to resume this inspection.');
                redirect(base_url());
            } else {
                $this->Audit_model->hold_resume_audit($current['id']);
            }
        }
        
        $response = $this->Audit_model->hold_resume_audit($audit_id, 0);

        if($response) {
            $this->destroy_checkpoint_session();
            $this->session->set_flashdata('success', 'Inspection successfully resumed.');
            redirect(base_url().'register_inspection');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
            
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function get_inspections_by_product() {
        $data = array('inspections' => array());
        
        if($this->input->post('product')) {
            $this->load->model('Inspection_model');
            $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->input->post('product'));
        }
        
        echo json_encode($data);
    }

    public function get_model_suggestions() {
        $this->load->model('Product_model');
        $models = $this->Product_model->get_all_suffixs($this->product_id);
        $models[] = 'ABC';
        echo json_encode($models);
    }
    
    public function get_barcode_details() {
        $output = array();
        if($this->input->post('barcode')) {
            require_once APPPATH .'libraries/lg_oracle.php';
            
            $barcode = $this->input->post('barcode');
            if(strlen($barcode) === 19) {
                $barcode = substr($barcode, 4, -2);
            }
            
            if(strlen($barcode) === 13) {
                $output = get_barcode_details_set($barcode);
            } else if(strlen($barcode) === 20) {
                $output = get_barcode_details($barcode);
            } else if(strlen($barcode) === 15 && strpos($barcode, '-')) {
                $wo_serial  = explode('-', $barcode);
                $wo_name    = $wo_serial[0];
                $serial_no  = $wo_serial[1];
                
                $output = get_barcode_details_wo_serial($wo_name, $serial_no);
            }
            
            if($this->session->userdata('username') == 'raju1.bhosale'){
                //echo "<pre> hi"; print_r($output); exit;
            }
            
            if(!empty($output['PCSGNAME'])) {
                $line = $output['PCSGNAME'];
                $this->load->model('Product_model');
                $output['LINE'] = $this->Product_model->get_product_line_id($this->product_id, $line);
            }
            
            //$output['LINE'] = '';
        }
        
        echo json_encode($output);
    }
    
    public function get_model_sampling_details() {
        $output = array();
        if($this->input->post()) {
            $model_suffix = $this->input->post('model_suffix');
            $audit_date = $this->input->post('audit_date');
            $inspection_id = $this->input->post('inspection_id');
            $line_id = $this->input->post('line_id');

            $this->load->model('Sampling_model');
            $output = $this->Sampling_model->get_progress_for_sampling_plan($audit_date, $inspection_id, $model_suffix, $line_id, true);
        }
        
        echo json_encode($output);
    }

    public function get_automatic_value($file_name, $row, $col) {
        $this->load->library('excel');
        
        if(!is_file($file_name)) {
            $this->session->set_flashdata('error', 'File Not Found');
            redirect(base_url().'auditer/checkpoint_screen');
        }
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        $val = $objPHPExcel->getActiveSheet()->getCell($col.$row)->getValue();
        return $val;
    }

    public function upload_automate_excel() {
        if($this->product_id) {
            $product_id = $this->product_id;
        }
        
        $data = array();
        if($this->input->post()) {
            $this->load->model('Product_model');
            $product = $this->Product_model->get_product($product_id);
            $path = $product['dir_path'];
            
            $serial_no = $this->input->post('serial_no');
            $type = $this->input->post('type');
            if(!empty($_FILES['automate_excel']['name'])) {
                
                $path = "assets/Automate/".$product_id;
                if($type === 'VISION') {
                    $path .= 'INSP000VI';
                }

                $output = $this->upload_file('automate_excel', $serial_no, $path, 'xls|xlsx|csv');

                if($output['status'] == 'success') {
                    $this->session->set_flashdata('success', 'File successfully uploaded.');
                    redirect(base_url().'auditer/upload_automate_excel');
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'auditer/upload_automate_excel', $data);
        $this->template->render();
    }

    public function automatic_inspection_result() {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit($this->id, 'registered', date('Y-m-d'));
        if(empty($audit)) {
            $this->check_inspection();
        }
        
        $from_location = $audit['dir_path'].$audit['gmes_insp_id'].'\\';
        $file = glob($from_location."*".$audit['serial_no']."*");
        if(empty($file)) {
            $this->session->set_flashdata('error', 'File Not Found');
            redirect(base_url().'auditer/inspection_start_screen');
        }
        
        $filename = basename($file[0]);

        $to_location = 'assets/Inspection Automate/'.$this->product_id.'/';
        if(!is_dir($to_location)) {
            mkdir($to_location);
        }
        
        if($audit['automate_case'] === 'VISION') {
            if(!strpos($filename, '.csv')) {
                $this->session->set_flashdata('error', 'Invalid Format');
                redirect(base_url().'auditer/inspection_start_screen');
            }
            
            $csv = array_map('str_getcsv', file($from_location.$filename));
            if(!isset($csv[4][1])) {
                $this->session->set_flashdata('error', 'Invalid Format');
                redirect(base_url().'auditer/inspection_start_screen');
            }
            
            rename($from_location.$filename, $to_location.$filename);
            
            $update_data = array();
            $update_data['automate_file'] = $to_location.$filename;
            $update_data['automate_result'] = $csv[4][1];
            
            $csv = array_slice($csv, 4);
            foreach($csv as $k => $v) {
                if($k === 0)
                    continue;
                
                unset($csv[$k][6]);
            }
           
            $this->Audit_model->update_audit($update_data, $audit['id']);
            $this->Audit_model->update_automate_result(array('audit_id' => $audit['id'], 'automate_data' => serialize($csv)));
            $this->Audit_model->change_state($audit['id'], $this->id, 'finished');
            redirect(base_url().'auditer/finish_screen');
        } else if($audit['automate_case'] === 'With Checkpoints') {
            $settings = unserialize($audit['automate_settings']);
            $result = $this->read_excel($from_location.$filename, $audit['serial_no'], $settings);
            if(empty($result)) {
                $this->session->set_flashdata('error', 'Invalid Inspection Settings');
                redirect(base_url().'auditer/inspection_start_screen');
            }
            
            $this->Audit_model->create_audit_checkpoints($audit['inspection_id'], $audit['model_suffix'], $audit['id']);
            $checkpoints = $this->Audit_model->get_all_audit_checkpoints($audit['id']);
            
            foreach($checkpoints as $checkpoint) {
                if(!isset($result[$checkpoint['checkpoint_no']])) {
                    continue;
                }
                $r = $result[$checkpoint['checkpoint_no']];
                
                $update_data = array();
                $update_data['result'] = $r['judgement'];
                $update_data['audit_value'] = $r['result'];
                
                $response = $this->Audit_model->record_checkpoint_result($update_data, $checkpoint['id'], $audit['id']);
            }
            
            $this->Audit_model->change_state($audit['id'], $this->id, 'finished');
            redirect(base_url().'auditer/finish_screen');
        } else {
            echo "Don't know what to do? Please report this to the concerned person.";exit;
        }
    }
    
    public function attach_report($audit_id) {
        if($this->input->post()) {
            $this->load->model('Audit_model');
            $audit = $this->Audit_model->get_audit($this->id, array('registered'), '', $audit_id);

            if(empty($audit)) {
                $this->check_inspection();
            }
            
            if($audit['insp_type'] != 'interval' || empty($audit['attach_report'])) {
                $this->session->set_flashdata('error', 'Invalid Request');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
            $audit_up_data = array();
            if(!empty($_FILES['attach_report']['name'])) {
                //$f_type = 'pdf|xls|xlsx|ppt|pptx|jpeg|jpg|png';
                $f_type = '*';
                $output = $this->upload_file('attach_report', $audit['serial_no'], "assets/reports/", $f_type);

                if($output['status'] == 'success') {
                    $audit_up_data['automate_file'] = $output['file'];
                    
                    $response = $this->Audit_model->update_audit($audit_up_data, $audit['id']);
                    
                    $this->Audit_model->change_state($audit['id'], $this->id, 'completed');
                } else {
                    $this->session->set_flashdata('error', $output['error']);
                }

            } else {
                $this->session->set_flashdata('error', 'Please upload file');
            }
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function open_reference() {
        $name = urldecode($this->input->get('name'));
        $url = $this->input->get('url');
        
        $opened = $this->session->userdata('opened_link');
        if(!in_array($name, $opened)) {
            $opened[] = $name;
            $this->session->set_userdata('opened_link', $opened);
        }
        
        redirect($url);
    }
    
    public function get_paired_audit_details() {
        $output = array();
        if($this->input->post('audit_id')) {
            $this->load->model('Audit_model');
            $output = $this->Audit_model->get_all_paired_audits($this->input->post('audit_id'));
        }
        
        echo json_encode($output);
    }
    
    private function destroy_checkpoint_session() {
        //echo "here";exit;
        $this->session->unset_userdata('current_checkpoint');
        $this->session->unset_userdata('nos');
        $this->session->unset_userdata('mandatory_popup');
        $this->session->unset_userdata('references');
        $this->session->unset_userdata('mandatories');
        $this->session->unset_userdata('opened_link');
    }
    
    private function set_checkpoint_session($audit_id, $iteration_no = '', $type = '') {
        $this->load->model('Audit_model');
        $checkpoint_nos = $this->Audit_model->get_required_checkpoint_nos($audit_id, $iteration_no);
        /* echo "<pre>";
        echo $this->db->last_query();
        print_r($checkpoint_nos);
        exit; */
        $nos = explode(',', $checkpoint_nos['nos']);
        
        $this->session->set_userdata('nos', $nos);
        if($type == 'find') {
            $last = $checkpoint_nos['last'];

            $currect_key = array_search($last, $nos);
            $this->session->set_userdata('current_key', $currect_key+1);

            if($currect_key !== false && isset($nos[$currect_key+1])) {
                $currect_checkpoint = $this->session->set_userdata('current_checkpoint', $nos[$currect_key+1]);
                $this->session->set_userdata('current_key', $currect_key+2);
            } else {
                $currect_checkpoint = $this->session->set_userdata('current_checkpoint', $nos[$currect_key]);
            }
        } else {
            $this->session->set_userdata('current_checkpoint', $nos[0]);
            $this->session->set_userdata('current_key', 1);
        }
        
        $this->session->set_userdata('mandatory_popup', 1);
    }
    
    private function get_n_set_checkpoint_session($type = 'next') {
        $currect_checkpoint = $this->session->userdata('current_checkpoint');
        $nos = $this->session->userdata('nos');
        
        $currect_key = array_search($currect_checkpoint, $nos);
        if($type == 'prev') {
            if(isset($nos[$currect_key-1])) {
                $currect_checkpoint = $this->session->set_userdata('current_checkpoint', $nos[$currect_key-1]);
                $this->session->set_userdata('current_key', $currect_key);
                return $nos[$currect_key-1];
            }
        } else if($type == 'next') {
            if(isset($nos[$currect_key+1])) {
                $currect_checkpoint = $this->session->set_userdata('current_checkpoint', $nos[$currect_key+1]);
                $this->session->set_userdata('current_key', $currect_key+2);
                return $nos[$currect_key+1];
            } else {
                return 'Completed';
            }
        }
        
    }
    
    private function check_inspection($audit = '') {
        $audit = ($audit) ? $audit : $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'));
        
        if(empty($audit)) {
            $this->session->set_flashdata('info', 'Please register an inspection, before moving ahead');
            redirect(base_url().'register_inspection');
        } else if($audit['state'] === 'registered') {
            $this->session->set_flashdata('info', 'You have already registered an inspection. Please complete it before starting a new registration.');
            redirect(base_url().'auditer/inspection_start_screen');
        } else if($audit['state'] === 'started') {
            $this->session->set_flashdata('info', 'You have one on going inspection. Please complete it.');
            redirect(base_url().'auditer/checkpoint_screen');
        } else if($audit['state'] === 'finished') {
            $this->session->set_flashdata('info', 'You have one inspection in finished queue. Please mark it complete before proceeding ahead.');
            redirect(base_url().'auditer/finish_screen');
        }
    }
    
    private function read_excel($filename, $serial_no, $settings) {
        $needed_array = array('a_start_row', 'a_checkpoint_col', 'a_reading_col', 'a_judgement_col');
        $data = array_intersect_key($settings, array_flip($needed_array));
        
        if(count($data) != 4) {
            return FALSE;
        }
        
        require_once APPPATH."/third_party/class.phpexcel.php";
        
        $this->load->library('excel');
        //read file from path
        $fileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($fileType);

        $objPHPExcel = $objReader->load($filename);
        
        $sheet = $objPHPExcel->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        
        $serial_cols = explode(',', $settings['a_reading_col']);
        $result_col = '';
        foreach($serial_cols as $serial_col) {
            if($serial_no == $sheet->getCell($serial_col.($settings['a_start_row']-1))->getValue()) {
                $result_col = $serial_col;
                break;
            }
        }

        $result = array();
        for($row = $settings['a_start_row']; $row <= $highestRow; $row++) {
            $temp = array();
            $checkpoint_no = $sheet->getCell($settings['a_checkpoint_col'].$row)->getValue();
            if(empty($checkpoint_no)) {
                continue;
            }
            $temp['result'] = $sheet->getCell($result_col.$row)->getValue();
            if (0 === strpos($temp['result'], '=')) {
               $temp['result'] = $sheet->getCell($result_col.$row)->getOldCalculatedValue();
            }
            
            $temp['result'] = round($temp['result'], 3);
            $temp['judgement'] = $sheet->getCell($settings['a_judgement_col'].$row)->getValue();
            $temp['checkpoint_no'] = $checkpoint_no;
            
            $result[$checkpoint_no] = $temp;
        }
        
        return $result;
    }
}