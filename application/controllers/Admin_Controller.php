<?php
/** 
* My Controller Class 
* 
* @package IACT
* @filename My_Controller.php
* @category My_Controller
**/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {

    function __construct($auth = false, $access = '') {
        parent::__construct();
        if($auth) {
            $this->is_logged();
        }

        if($access === 'Admin') {
            $this->is_admin_user();
        } else if($access === 'Dashboard') {
            $this->is_dashboard_user();
        } else if($access === 'Audit') {
            $this->is_audit_user();
        }


        $this->id = $this->session->userdata('id');
        $this->name = $this->session->userdata('name');
        $this->username = $this->session->userdata('username');
        $this->email = $this->session->userdata('email');
        $this->user_type = $this->session->userdata('user_type');
        $this->product_id = $this->session->userdata('product_id');

    }

    /**
     * @method: is_logged()
     * @access: public
     * @category : My_Controller
     * Desc : this method is used to check if user is logged in or not
     */
    function is_logged() {
        if(!$this->session->userdata('is_logged_in')) {
            redirect(base_url().'login');
        }
    }

    /**
     * @method: is_admin_user()
     * @access: public
     * @category : My_Controller
     * Desc : this method is used to check if user has Admin access
     */
    function is_admin_user() {

        if($this->session->userdata('user_type') !== 'Admin') {
            $this->session->set_flashdata('error', 'Access Denied');
            redirect(base_url());
        }

    }

    /**
     * @method: is_employee()
     * @access: public
     * @category : My_Controller
     * Desc : this method is used to check if user is employee or not
     */
    function is_dashboard_user() {

        if($this->session->userdata('user_type') !== 'Dashboard') {
            $this->session->set_flashdata('error', 'Access Denied');
            redirect(base_url());
        }

    }
    
    /**
     * @method: is_employee()
     * @access: public
     * @category : My_Controller
     * Desc : this method is used to check if user is employee or not
     */
    function is_audit_user() {

        if($this->session->userdata('user_type') !== 'Audit') {
            $this->session->set_flashdata('error', 'Access Denied');
            redirect(base_url());
        }

    }

    function pr($var) {
        echo "<pre>";
        print_r($var);
        exit;
    }
    
    function upload_file($file_field, $file_name, $upload_path, $file_types = 'xls|xlsx') {
        if(!is_dir($upload_path)) {
            mkdir($upload_path);
        }
        
        //echo $file_types;exit;
            
        $config['upload_path'] = $upload_path;
        if($file_field !== 'sampling_excel' && $file_field !== 'automate_excel') {
            $config['file_name'] = $file_name.'-'.random_string('alnum', 6);
        } else {
            $config['file_name'] = $file_name;
        }
        
        $config['allowed_types'] = $file_types;
        $config['overwrite'] = True;

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload($file_field)) {

            if(!$this->upload->is_allowed_filetype()) {
                $error = "The file type you are attempting to upload is not allowed.";
            } else {
                $error = $this->upload->display_errors();
            }

            $result = array(
                'status' => 'error',
                'error' => $error
            );

        } else {
            $upload_data = $this->upload->data();
            $result = array(
                'status' => 'success',
                'file' => $upload_path.$upload_data['file_name']
            );
        }
        
        return $result;
    }
    
    function upload_photo($field, $upload_path, $filename) {
        $response = array('status' => 'error', 'error' => 'Invalid parameters');

        if(!empty($_FILES[$field]['name']) && !empty($upload_path)) {
            //upload wallpaper.

            if(!is_dir($upload_path)) {
                mkdir($upload_path);
            }

            $config['upload_path'] = $upload_path;
            if(!empty($filename)) {
                $config['file_name'] = $filename;
            }
            $config['allowed_types'] = 'png|jpg|JPG|jpeg|';
            $config['overwrite'] = True;

            $this->load->library('upload', $config);

            if(!$this->upload->do_upload($field)) {
                $response['status'] = 'error';

                if(!$this->upload->is_allowed_filetype()) {
                    $response['error'] = "The file type you are attempting to upload is not allowed.";
                } else {
                    $response['error'] = $this->upload->display_errors();
                }

            } else {
                $upload_data = $this->upload->data();
                $response = array(
                    'status' => 'success',
                    'file' => $upload_path.$upload_data['file_name']
                );
            }

        }

        return $response;
    }
    
    function sendMail($to, $subject, $message, $bcc = '', $attachment = '', $cc = '') {
        $this->load->library('email');
        $this->email->clear(TRUE);
        
        $this->email->from('noreply@lge.com', 'LG OQIS');
        $this->email->to($to);
        $this->email->subject($subject);
        
        if(!empty($bcc)) {
            $this->email->bcc($bcc);
        }
        
        if(!empty($cc)) {
            $this->email->cc($cc);
        }
        
        if(!empty($attachment)) {
            $this->email->attach($attachment);
        }

        $this->email->message($message);

        return $this->email->send();
        //echo $this->email->print_debugger();
    }
    
    function display_sampling_plan($plan_date, $only_interval = false) {
        $this->load->model('Sampling_model');
        
        $dimensions = $this->Sampling_model->get_sampling_plan_dimensions($plan_date);
        $inspections = $this->Sampling_model->get_all_inspections_for_sampling_plan($plan_date, $only_interval);
        if(empty($inspections)) {
            return array('sampling' => array(), 'ids' => array());
        }
        
        //echo "<pre>";print_r($inspections);exit;
        $sampling_plan = array();
        $headers = array('Model.Suffix', 'Tool', 'Line', 'Lot Size', 'Group Lot');
        
        $tool_lot = 0;
        $groups = array();
        $ids = array();
        foreach($dimensions as $key => $dimension) {
            $group_count = $dimension['group_count'];
            $groups[] = $group_count;
            
            $temp       = array();
            $temp[]     = $dimension['model_suffix'];
            
            if($group_count == 1) {
                $temp[]     = $dimension['tool'];
                $temp[]     = $dimension['line'];
            } else if($group_count > 0) {
                $temp[]     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$dimension['tool'].'</td>';
                $temp[]     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$dimension['line'].'</td>';
            } else {
                $temp[]     = 'skip';
                $temp[]     = 'skip';
            }
            
            $lot_size_str = '<span style="font-size:15px;">'.$dimension['lot_size'].'</span>';
            if($dimension['lot_size'] != $dimension['original_lot_size']) {
                $lot_size_str .= ' <small style="text-decoration:line-through;">'.$dimension['original_lot_size'].'</small>';
            }
            $temp[]     = $lot_size_str;
            
            $temp[]     = $dimension['lot_size'];
            
            $temp_ids = array();
            foreach($inspections as $inspection) {
                if($key === 0) {
                    $headers[] = $inspection['inspection_name'];
                }
                
                $no_of_samples = $this->Sampling_model->get_no_of_samples_for_sampling_plan($plan_date, $inspection['inspection_id'], $dimension['model_suffix'], $dimension['line']);
                
                $temp_ids[] = $no_of_samples['id'];

                $no_of_samples = $no_of_samples['no_of_samples'] === null && $no_of_samples['skipped'] ? 'NA' : $no_of_samples['no_of_samples'];
                
                if($inspection['config_type'] == 'Tool') {
                    
                    if($group_count == 1) {
                        $no_of_samples     = $no_of_samples;
                    } else if($group_count > 0) {
                        $no_of_samples     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$no_of_samples.'</td>';
                    } else {
                        $no_of_samples     = 'skip';
                    }
                }
                
                $temp[] = $no_of_samples;
            }
            
            $ids[] = $temp_ids;
            $sampling_plan[] = $temp;
        }
        
        $final = array();
        foreach($sampling_plan as $key => $plan) {
            $iteration = $groups[$key];
            
            if($iteration == 1) {
                $final[] = $plan;
            } else if($iteration > 0) {
                $tool_sum = 0;
                for($i = 0; $i < $iteration; $i++) {
                    $tool_sum += $sampling_plan[$key+$i][4];
                }
                $plan[4] = '<td rowspan="'.$iteration.'" class="merged-cell">'.$tool_sum.'</td>';
                
                $final[] = $plan;
            } else {
                $plan[4] = 'skip';
                $final[] = $plan;
            }
        }
        
        if(empty($final)) {
            return array();
        } else {
            return array('sampling' => array_merge(array($headers), $final), 'ids' => $ids);
        }
    }
    
    function display_day_progress($plan_date) {
        $this->load->model('Sampling_model');
        
        $dimensions = $this->Sampling_model->get_sampling_plan_dimensions($plan_date);
        $inspections = $this->Sampling_model->get_all_inspections_for_sampling_plan($plan_date);

        $result = array();
        $model_insp_status = array();
        $tool_insp_status = array();
        
        $line = null;
        
        foreach($dimensions as $key => $dimension) {
            if(!isset($lot_key)) {
                $lot_key = $dimension['tool'].$dimension['line'];
            }
            
            if(!isset($line)) {
                $line = $dimension['line'];
            }

            if($line != '' && $line != $dimension['line']) {
                $line = $dimension['line'];
            }
            
            $model_key = $dimension['model_suffix'].$line;
            if(!isset($model_insp_status[$model_key])) {
                $model_insp_status[$model_key] = array();
            }
            
            $iter_key = $dimension['tool'].$dimension['line'];
            if(!isset($tool_insp_status[$iter_key])) {
                $tool_insp_status[$iter_key] = array('group_lot' => 0);
            }
            
            if($lot_key == $iter_key) {
                $tool_insp_status[$iter_key]['group_lot'] += $dimension['lot_size'];
            } else {
                $tool_insp_status[$iter_key]['group_lot'] = $dimension['lot_size'];
                $lot_key = $dimension['tool'].$dimension['line'];
            }
            
            $status = 'Complete';
            $ng = false;
            foreach($inspections as $i_key => $inspection) {
                $progress = $this->Sampling_model->get_progress_for_sampling_plan($plan_date, 
                    $inspection['inspection_id'], $dimension['model_suffix'], $dimension['line']);
                    
                if(empty($progress)) {
                    $progress = array('no_of_samples' => '','completed' => '','in_progess' => '', 'ng_count' => '');
                }
                
                if($inspection['config_type'] == 'Tool') {
                    if(!isset($tool_insp_status[$lot_key][$i_key])) {
                        $tool_insp_status[$lot_key][$i_key] = array('planned' => 0, 'completed' => 0, 'in_progess' => 0, 'ng_count' => 0);
                    }
                    $tool_insp_status[$lot_key][$i_key]['planned']      += $progress['no_of_samples'];
                    $tool_insp_status[$lot_key][$i_key]['completed']    += $progress['completed'];
                    $tool_insp_status[$lot_key][$i_key]['in_progess']   += $progress['in_progess'];
                    $tool_insp_status[$lot_key][$i_key]['ng_count']     += $progress['ng_count'];
                    
                } else {
                    if(!isset($model_insp_status[$model_key][$i_key])) {
                        $model_insp_status[$model_key][$i_key] = array('planned' => 0, 'completed' => 0, 'in_progess' => 0);
                    }
                    
                    $model_insp_status[$model_key][$i_key]['planned']    = $progress['no_of_samples'];
                    $model_insp_status[$model_key][$i_key]['completed']  = $progress['completed'];
                    $model_insp_status[$model_key][$i_key]['in_progess'] = $progress['in_progess'];
                    
                    if($progress['no_of_samples'] > $progress['completed']) {
                        $status = 'Pending';
                    }
                    
                    if($progress['ng_count']) {
                        $ng = true;
                    }
                }
            }
            
            $model_insp_status[$model_key]['Status'] = $status;
            $model_insp_status[$model_key]['NG'] = $ng;
        }
        
        foreach($tool_insp_status as $tis_key => $tis_val) {
            $status = 'Complete';
            $ng = false;
            foreach($tis_val as $tis_val_key => $tis_val_val) {
                if($tis_val_key == 'group_lot' || $tis_val_key == 'Status') {
                    continue;
                }
                
                if($tis_val_val['planned'] > $tis_val_val['completed']) {
                    $status = 'Pending';
                }
                
                if($tis_val_val['ng_count']) {
                    $ng = true;
                }
            }
            
            $tool_insp_status[$tis_key]['Status'] = $status;
            $tool_insp_status[$tis_key]['NG'] = $ng;
        }
        
        $sampling_plan = array();
        $inspt_count = count($inspections);
        $headers = array('Model.Suffix', 'Tool', 'Line', 'Lot Size', 'Group Lot', 'Status');
        
        $line = null;
        foreach($dimensions as $key => $dimension) {
            $group_count = $dimension['group_count'];
            $iter_key = $dimension['tool'].$dimension['line'];
            
            if(!isset($line)) {
                $line = $dimension['line'];
            }

            if($line != '' && $line != $dimension['line']) {
                $line = $dimension['line'];
            }
            
            $model_key = $dimension['model_suffix'].$line;

            $temp       = array();
            $temp[]     = $dimension['model_suffix'];
            
            if($group_count == 1) {
                $temp[]     = $dimension['tool'];
                $temp[]     = $dimension['line'];
                $temp[]     = $dimension['lot_size'];
                $temp[]     = $dimension['lot_size'];
            } else if($group_count > 0) {
                $temp[]     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$dimension['tool'].'</td>';
                $temp[]     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$dimension['line'].'</td>';
                $temp[]     = $dimension['lot_size'];
                $temp[]     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$tool_insp_status[$iter_key]['group_lot'].'</td>';
            } else {
                $temp[]     = 'skip';
                $temp[]     = 'skip';
                $temp[]     = $dimension['lot_size'];
                $temp[]     = 'skip';
            }
            
            if($model_insp_status[$model_key]['Status'] == 'Complete' && $tool_insp_status[$iter_key]['Status'] == 'Complete') {
                
                if($model_insp_status[$model_key]['NG'] || $tool_insp_status[$iter_key]['NG']) {
                    $temp[]     = '<td class="text-center merged-cell"><i class="fa fa-remove" style="color:#d30e43;"></i></td>';
                } else {
                    $temp[]     = '<td class="text-center merged-cell"><i class="fa fa-check" style="color:#26c281;"></i></td>';
                }
                
            } else {
                $temp[]     = '<td class="text-center merged-cell"><i class="fa fa-refresh"></i></td>';
            }
            
            
            for($i = 0; $i < $inspt_count; $i++) {
                $progress = array();
                if($key === 0) {
                    $headers[] = $inspections[$i]['inspection_name'].'<br /><br />'.nl2br($inspections[$i]['insp_text']);
                }
                
                $type = $inspections[$i]['config_type'];

                if($type == 'Model.Suffix') {
                    $progress = $model_insp_status[$model_key][$i];
                    
                    $p = $progress['planned'];
                    $c = $progress['completed'];
                    $ip = $progress['in_progess'];
                    
                    $color = '';
                    if($p > 0) {
                        if($p <= ($c+$ip)) {
                            $color = '#26C281';
                        } else if(($c+$ip) > 0) {
                            $color = '#F3C200';
                        } else {
                            $color = '#E35B5A';
                        }
                    }
                    
                    $prog = array();
                    $prog[] = '<td class="text-center merged-cell" style="background-color:'.$color.'; border-right-color: '.$color.';">'.$p.'</td>';
                    $prog[] = '<td class="text-center merged-cell" style="background-color:'.$color.'; border-right-color: '.$color.';">'.$c.'</td>';
                    $prog[] = '<td class="text-center merged-cell" style="background-color:'.$color.'">'.$ip.'</td>';
                    
                    //echo "<pre>";print_r($progress);exit;
                    $temp = array_merge($temp, array_values($prog));
                } else {
                    
                    $p = $tool_insp_status[$iter_key][$i]['planned'];
                    $c = $tool_insp_status[$iter_key][$i]['completed'];
                    $ip = $tool_insp_status[$iter_key][$i]['in_progess'];
                    
                    $color = '';
                    if($p > 0) {
                        if($p <= ($c+$ip)) {
                            $color = '#26C281';
                        } else if(($c+$ip) > 0) {
                            $color = '#F3C200';
                        } else {
                            $color = '#E35B5A';
                        }
                    }
                    
                    if($group_count == 1) {
                        $progress[]     = '<td class="text-center" style="background-color:'.$color.'; border-right-color: '.$color.';">'.$tool_insp_status[$iter_key][$i]['planned'].'</td>';
                        $progress[]     = '<td class="text-center" style="background-color:'.$color.'; border-right-color: '.$color.';">'.$tool_insp_status[$iter_key][$i]['completed'].'</td>';
                        $progress[]     = '<td class="text-center" style="background-color:'.$color.'">'.$tool_insp_status[$iter_key][$i]['in_progess'].'</td>';

                    } else if($group_count > 0) {
                        $progress[]     = '<td style="background-color:'.$color.'; border-right-color: '.$color.';" rowspan="'.$group_count.'" class="text-center merged-cell">'.$tool_insp_status[$iter_key][$i]['planned'].'</td>';
                        
                        $progress[]     = '<td style="background-color:'.$color.'; border-right-color: '.$color.';" rowspan="'.$group_count.'" class="text-center merged-cell">'.$tool_insp_status[$iter_key][$i]['completed'].'</td>';
                        
                        $progress[]     = '<td style="background-color:'.$color.'" rowspan="'.$group_count.'" class="text-center merged-cell">'.$tool_insp_status[$iter_key][$i]['in_progess'].'</td>';
                    } else {
                        $progress = array('skip', 'skip', 'skip');
                    }
                    
                    $temp = array_merge($temp, array_values($progress));
                }
            }

            $sampling_plan[] = $temp;
        }
        
        /* echo "<pre>";
        print_r($sampling_plan);
        exit; */

        if(empty($sampling_plan)) {
            return array();
        } else {
            return array_merge(array($headers), $sampling_plan);
        }
    }
    
    function display_day_progress3($plan_date) {
        $this->load->model('Sampling_model');
        
        $dimensions = $this->Sampling_model->get_sampling_plan_dimensions($plan_date);
        $inspections = $this->Sampling_model->get_all_inspections_for_sampling_plan($plan_date);
        
        $sampling_plan = array();
        $headers = array('Model.Suffix', 'Tool', 'Line', 'Lot Size', 'Group Lot');

        $tool_lot = 0;
        $groups = array();
        $group_audit_c = array();
        foreach($dimensions as $key => $dimension) {
            $group_count = $dimension['group_count'];
            $groups[] = $group_count;
            
            $temp       = array();
            $temp[]     = $dimension['model_suffix'];
            
            if($group_count == 1) {
                $temp[]     = $dimension['tool'];
                $temp[]     = $dimension['line'];
            } else if($group_count > 0) {
                $temp[]     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$dimension['tool'].'</td>';
                $temp[]     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$dimension['line'].'</td>';
            } else {
                $temp[]     = 'skip';
                $temp[]     = 'skip';
            }
            
            $temp[]     = $dimension['lot_size'];
            $temp[]     = $dimension['lot_size'];
            
            $i_status = array();
            foreach($inspections as $i_key => $inspection) {
                if($key === 0) {
                    $headers[] = $inspection['inspection_name'].'<br /><br />'.nl2br($inspection['insp_text']);
                }
                
                $progress = $this->Sampling_model->get_progress_for_sampling_plan($plan_date, 
                    $inspection['inspection_id'], $dimension['model_suffix'], $dimension['line']);

                if(empty($progress)) {
                    $progress = array('no_of_samples' => '','','');
                }

                
                if($inspection['config_type'] == 'Tool') {
                    $group_audit_c[3*$i_key+6]          = 0;
                    $group_audit_c[3*$i_key+7]          = 0;
                    
                    if($group_count == 1) {
                        $progress['no_of_samples']     = $progress['no_of_samples'];
                    } else if($group_count > 0) {
                        $progress['no_of_samples']     = '<td rowspan="'.$group_count.'" class="merged-cell">'.$progress['no_of_samples'].'</td>';
                    } else {
                        $progress['no_of_samples']     = 'skip';
                    }
                }
                
                //$temp['config_type'] = $inspection['config_type'];
                $temp = array_merge($temp, array_values($progress));
            }

            $sampling_plan[] = $temp;
        }

        $final = array();
        foreach($sampling_plan as $key => $plan) {
            $iteration = $groups[$key];
            
            if($iteration == 1) {
                $final[] = $plan;
            } else if($iteration > 0) {
                $tool_sum = 0;
                
                $keys = array_keys($group_audit_c);
                $group_audit_c = array_fill_keys($keys, 0);
                for($i = 0; $i < $iteration; $i++) {
                    $tool_sum += $sampling_plan[$key+$i][4];
                    
                    foreach($group_audit_c as $c_key => $c_sum) {
                        $group_audit_c[$c_key] += $sampling_plan[$key+$i][$c_key];
                    }
                }
                
                $plan[4] = '<td rowspan="'.$iteration.'" class="merged-cell">'.$tool_sum.'</td>';
                foreach($group_audit_c as $c_key => $c_sum) {
                    $plan[$c_key] = '<td rowspan="'.$iteration.'" class="merged-cell">'.$c_sum.'</td>';
                }
                
                $final[] = $plan;
            } else {
                $plan[4] = 'skip';
                foreach($group_audit_c as $c_key => $c_sum) {
                    $plan[$c_key] = 'skip';
                }
                
                $final[] = $plan;
            }
        }
        
        if(empty($final)) {
            return array();
        } else {
            return array_merge(array($headers), $final);
        }
    }

    public function send_sms($to, $sms) {
        $user = 'Lgelectronic';
        $password = 'Sid2014!';
        $sender = "LGEILP";
        $message = urlencode($sms);

        /* //Prepare you post parameters
        $postData = array(
            'user' => 'Lgelectronic',
            'password' => 'Sid2014!',
            'sender' => $senderId,
            'SMSText' => $message,
            'GSM' => $to
        ); */

        //API URL
        $url="http://193.105.74.58/api/v3/sendsms/plain?user=".$user."&password=".$password."&sender=".$sender."&SMSText=".$message."&GSM=".$to;
        //echo $url;
        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            //,CURLOPT_FOLLOWLOCATION => true
        ));
        //Ignore SSL certificate verification
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = curl_exec($ch);

        $flag = true;
        //Print error if any
        if(curl_errno($ch))
        {
            $flag = false;
        }
        //echo $flag;exit;
        curl_close($ch);
        return $flag;
    }
}